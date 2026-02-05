<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\AuditLog;
use App\Models\HallBooking;
use App\Models\QuarterApplication;
use App\Models\QuarterAllocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\GradeSalarySetting;
use App\Services\MarkingCalculatorService;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nic_number' => ['required'],
            'passcode' => ['required'],
        ]);

        $user = User::where('nic_number', $credentials['nic_number'])->first();

        if ($user && Hash::check($credentials['passcode'], $user->passcode)) {
            Auth::login($user);
            $request->session()->regenerate();

            AuditLog::create([
                'log_title' => $user->user_id . ' logged into the system',
                'performed_by' => $user->user_id,
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            if ($user->role == 'admin') {
                return redirect()->intended('admin');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'nic_number' => 'The provided credentials do not match our records.',
        ])->onlyInput('nic_number');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showDashboard()
    {
        $user = Auth::user()->load('permissions');
        $quarterApplications = collect(); // Initialize as an empty collection

        if ($user->hasPermissionTo('requester') || $user->hasPermissionTo('government_agent_approval') || $user->hasPermissionTo('additional_government_agent_approval') || $user->hasPermissionTo('administrative_officer_approval')) {
            $bookings = HallBooking::with('hall')
                ->where('final_approval', 'pending')
                ->orderBy('date_created', 'desc')
                ->get();

            // Fetch Scheduled Quarter Applications
            $scheduledQuarterApplications = QuarterApplication::with('quarterAllocation')
                ->whereHas('quarterAllocation', function ($query) {
                    $query->where('allocation_status', 'pending');
                })
                ->where('quarter_type', 'Scheduled')
                ->orderBy('date_created', 'desc')
                ->get();

            // Fetch Family Quarter Applications
            $familyQuarterApplications = QuarterApplication::with(['quarterAllocation', 'familyQuarterApplication.markingFamilyQuarter'])
                ->whereHas('quarterAllocation', function ($query) {
                    $query->where('allocation_status', 'pending');
                })
                ->where('quarter_type', 'Family')
                ->get();

            // Calculate marks and sort Family Applications
            $markingCalculator = new MarkingCalculatorService();
            foreach ($familyQuarterApplications as $app) {
                if ($app->familyQuarterApplication) {
                    $breakdown = $markingCalculator->calculateDynamicScore($app->familyQuarterApplication);
                    $app->total_mark = $breakdown['total'];
                } else {
                    $app->total_mark = 0;
                }
            }

            // Sort by total_mark descending
            $familyQuarterApplications = $familyQuarterApplications->sortByDesc('total_mark');

            return view('dashboard', [
                'user' => $user,
                'bookings' => $bookings,
                'familyQuarterApplications' => $familyQuarterApplications,
                'scheduledQuarterApplications' => $scheduledQuarterApplications,
            ]);
        } else {
            return view('dashboard', [
                'user' => $user,
                'bookings' => collect(),
                'familyQuarterApplications' => collect(),
                'scheduledQuarterApplications' => collect(),
            ]);
        }
    }

    public function index()
    {
        $users = User::where('role', 'user')->get();
        return view('officers', ['users' => $users]);
    }

    public function seeOfficers()
    {
        if (Auth::check() && Auth::user()->hasPermissionTo('view_officers')) {
            $users = User::all(); // Fetch all users, including admins

            // Fetch grade salary settings for display
            $gradeSalarySettings = GradeSalarySetting::all()->keyBy('grade');
            $grades = $gradeSalarySettings->keys()->sort()->all();

            // Add default grades if missing (for consistency with other views)
            $defaultGrades = ['1 (G I)', '2 (G II)', '3 (G III)', '4 (G IV)', '5 (G V)', '5A'];
            foreach ($defaultGrades as $defaultGrade) {
                if (!in_array($defaultGrade, $grades)) {
                    $grades[] = $defaultGrade;
                }
            }
            sort($grades);

            return view('seeofficers', ['users' => $users, 'gradeSalarySettings' => $gradeSalarySettings, 'grades' => $grades]);
        }
        return redirect()->back()->with('error', 'You do not have permission to view officers.');
    }

    public function create()
    {
        return view('createaccount');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:200',
            'last_name' => 'required|string|max:200',
            'nic_number' => 'required|string|max:50|unique:user',
            'passcode' => 'required|string|min:4|max:255',
            'email' => 'required|string|email|max:200|unique:user',
            'contact_number' => 'required|string|max:10|unique:user',
            'designation' => 'nullable|string|max:200',
            'permissions' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
            }
            return redirect()->route('createaccount')->withErrors($validator)->withInput();
        }

        try {
            $lastUser = User::orderBy('user_id', 'desc')->first();
            $nextUserIdNumber = 1;
            if ($lastUser) {
                $lastUserIdNumber = (int) Str::after($lastUser->user_id, 'user');
                $nextUserIdNumber = $lastUserIdNumber + 1;
            }
            $newUserId = 'user' . str_pad($nextUserIdNumber, 3, '0', STR_PAD_LEFT);

            $user = User::create([
                'user_id' => $newUserId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'nic_number' => $request->nic_number,
                'passcode' => Hash::make($request->passcode),
                'role' => 'user',
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'designation' => $request->designation,
                'created_datetime' => Carbon::now(),
            ]);

            $permissions = [
                'view_officers' => 0,
                'view_officer_details' => 0,
                'view_halls' => 0,
                'view_hall_details' => 0,
                'view_quarters' => 0,
                'view_quarter_details' => 0,
                'view_audit_log' => 0,
                'administrative_officer_approval' => 0,
                'additional_government_agent_approval' => 0,
                'government_agent_approval' => 0,
                'form_history' => 0,
                'account_setting' => 0,
                'requester' => 0,
            ];

            if ($request->has('permissions')) {
                foreach ($request->permissions as $permission) {
                    if (array_key_exists($permission, $permissions)) {
                        $permissions[$permission] = 1;
                    }
                }
            }

            UserPermission::create(array_merge(['user_id' => $user->user_id], $permissions));

            AuditLog::create([
                'log_title' => 'Created new account ' . $newUserId,
                'performed_by' => Auth::id(),
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            $successMessage = 'User account created successfully with ID ' . $newUserId . '.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => $successMessage]);
            }
            return redirect()->route('officers.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('User creation failed: ' . $e->getMessage());
            $errorMessage = 'An unexpected error occurred while creating the account.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 500);
            }
            return redirect()->route('createaccount')->with('error', $errorMessage)->withInput();
        }
    }

    public function edit(User $user)
    {
        return view('modifyaccount', ['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:200',
            'last_name' => 'required|string|max:200',
            'designation' => 'nullable|string|max:200',
            'email' => 'required|string|email|max:200|unique:user,email,' . $user->user_id . ',user_id',
            'contact_number' => 'required|string|max:10|unique:user,contact_number,' . $user->user_id . ',user_id',
            'passcode' => 'nullable|string|min:4|confirmed',
            'permissions' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $updateData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'designation' => $request->designation,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'modified_datetime' => Carbon::now(),
            ];

            if ($request->filled('passcode')) {
                $updateData['passcode'] = Hash::make($request->passcode);
            }

            $user->update($updateData);

            $allPermissions = [
                'view_officers' => 0,
                'view_officer_details' => 0,
                'view_halls' => 0,
                'view_hall_details' => 0,
                'view_quarters' => 0,
                'view_quarter_details' => 0,
                'view_audit_log' => 0,
                'administrative_officer_approval' => 0,
                'additional_government_agent_approval' => 0,
                'government_agent_approval' => 0,
                'form_history' => 0,
                'account_setting' => 0,
                'requester' => 0,
            ];

            if ($request->has('permissions')) {
                foreach ($request->permissions as $permission) {
                    if (array_key_exists($permission, $allPermissions)) {
                        $allPermissions[$permission] = 1;
                    }
                }
            }

            if ($user->permissions) {
                $user->permissions()->update($allPermissions);
            } else {
                $user->permissions()->create($allPermissions);
            }

            AuditLog::create([
                'log_title' => 'Modified account ' . $user->user_id,
                'performed_by' => Auth::id(),
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            $successMessage = 'Officer account updated successfully!';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => $successMessage]);
            }
            return redirect()->route('officers.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('User update failed: ' . $e->getMessage());
            $errorMessage = 'An unexpected error occurred while updating the account.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 500);
            }
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    public function destroy(Request $request, User $user)
    {
        try {
            $userId = $user->user_id;
            $user->delete();

            AuditLog::create([
                'log_title' => 'Deleted account ' . $userId,
                'performed_by' => Auth::id(),
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            $successMessage = 'Officer account ' . $userId . ' deleted successfully!';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => $successMessage]);
            }
            return redirect()->route('officers.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('User deletion failed: ' . $e->getMessage());
            $errorMessage = 'An unexpected error occurred while deleting the account.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 500);
            }
            return redirect()->route('officers.index')->with('error', $errorMessage);
        }
    }

    public function updateAdminProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:200',
            'last_name' => 'required|string|max:200',
            'nic_number' => 'required|string|max:50|unique:user,nic_number,' . $user->user_id . ',user_id',
            'email' => 'required|string|email|max:200|unique:user,email,' . $user->user_id . ',user_id',
            'contact_number' => 'required|string|max:10|unique:user,contact_number,' . $user->user_id . ',user_id',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'nic_number' => $request->nic_number,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'modified_datetime' => Carbon::now(),
            ]);

            AuditLog::create([
                'log_title' => 'Admin ' . $user->user_id . ' updated their profile details',
                'performed_by' => $user->user_id,
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            $successMessage = 'Profile details updated successfully.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => $successMessage]);
            }
            return back()->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Profile update failed for user ' . $user->user_id . ': ' . $e->getMessage());
            $errorMessage = 'An unexpected error occurred while updating profile.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 500);
            }
            return back()->with('error', $errorMessage);
        }
    }

    public function updateUserProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:200|unique:user,email,' . $user->user_id . ',user_id',
            'contact_number' => 'required|string|max:10|unique:user,contact_number,' . $user->user_id . ',user_id',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user->update([
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'modified_datetime' => Carbon::now(),
            ]);

            AuditLog::create([
                'log_title' => 'User ' . $user->user_id . ' updated their profile details',
                'performed_by' => $user->user_id,
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            $successMessage = 'Profile details updated successfully.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => $successMessage]);
            }
            return back()->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Profile update failed for user ' . $user->user_id . ': ' . $e->getMessage());
            $errorMessage = 'An unexpected error occurred while updating profile.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 500);
            }
            return back()->with('error', $errorMessage);
        }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_passcode' => 'required|string|min:4|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator);
        }

        try {
            $user = Auth::user();
            $user->passcode = Hash::make($request->new_passcode);
            $user->modified_datetime = Carbon::now();
            $user->save();

            AuditLog::create([
                'log_title' => 'User ' . $user->user_id . ' changed their passcode',
                'performed_by' => $user->user_id,
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            $successMessage = 'Passcode changed successfully.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => $successMessage]);
            }
            return back()->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Passcode change failed for user ' . Auth::id() . ': ' . $e->getMessage());
            $errorMessage = 'An unexpected error occurred while changing the passcode.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 500);
            }
            return back()->with('error', $errorMessage);
        }
    }

    public function showAuditLog()
    {
        $auditLogs = AuditLog::orderBy('audit_log_id', 'desc')->get();
        return view('auditlog', ['auditLogs' => $auditLogs]);
    }

    public function seeAuditLog()
    {
        if (Auth::check() && Auth::user()->hasPermissionTo('view_audit_log')) {
            $auditLogs = AuditLog::orderBy('audit_log_id', 'desc')->get();
            return view('seeaudtilog', ['auditLogs' => $auditLogs]);
        }
        return redirect()->back()->with('error', 'You do not have permission to view the audit log.');
    }

    public function clearAuditLog(Request $request)
    {
        try {
            AuditLog::truncate();

            AuditLog::create([
                'log_title' => 'Audit log records deleted',
                'performed_by' => Auth::id(),
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            $successMessage = 'Audit log has been cleared successfully.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => $successMessage]);
            }
            return redirect()->route('auditlog')->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Audit log clearing failed: ' . $e->getMessage());
            $errorMessage = 'An unexpected error occurred while clearing the audit log.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 500);
            }
            return redirect()->route('auditlog')->with('error', $errorMessage);
        }
    }

    public function clearUsers()
    {
        // Get all non-admin users
        $usersToDelete = User::where('role', '!=', 'admin')->get();

        foreach ($usersToDelete as $user) {
            // Permissions are deleted via cascade if set up in DB, but we can manually ensure it
            // Assuming cascading deletes or manual deletion if needed. 
            // If UserPermission has user_id FK cascading on delete, just deleting User is enough.
            // If not, we should delete permissions first.
            // Let's assume standard Laravel relationship delete or manual.
            if ($user->permissions) {
                $user->permissions()->delete();
            }
            $user->delete();
        }

        AuditLog::create([
            'log_title' => 'All non-admin user records deleted',
            'performed_by' => Auth::id(),
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

        return redirect()->route('systemsetting')->with('success', 'All user records (except admins) have been cleared successfully.');
    }

    public function showGradeSalary()
    {
        // Fetch existing settings to populate the form
        $gradeSalarySettings = GradeSalarySetting::all()->keyBy('grade');
        // Dynamically get all unique grades from the database, or fall back to a default list if none exist
        $grades = $gradeSalarySettings->keys()->sort()->all();

        // If no grades exist in the DB, or if some standard grades are missing, provide a default set
        $defaultGrades = ['1 (G I)', '2 (G II)', '3 (G III)', '4 (G IV)', '5 (G V)', '5A'];
        foreach ($defaultGrades as $defaultGrade) {
            if (!in_array($defaultGrade, $grades)) {
                $grades[] = $defaultGrade;
            }
        }
        sort($grades); // Sort the grades for consistent display

        return view('gradesalary', compact('gradeSalarySettings', 'grades'));
    }

    public function updateGradeSalary(Request $request)
    {
        $rules = [];
        $grades = ['1 (G I)', '2 (G II)', '3 (G III)', '4 (G IV)', '5 (G V)'];

        foreach ($grades as $grade) {
            $key = str_replace([' ', '(', ')', '-'], '_', $grade);
            $rules["grade_{$key}_min"] = 'required|integer|min:0';
            $rules["grade_{$key}_max"] = 'required|integer|min:0|gte:grade_' . $key . '_min';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
            }
            return redirect()->route('gradesalary.index')->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            foreach ($grades as $grade) {
                $key = str_replace([' ', '(', ')', '-'], '_', $grade);
                GradeSalarySetting::updateOrCreate(
                    ['grade' => $grade],
                    [
                        'min_salary' => $request->{"grade_{$key}_min"},
                        'max_salary' => $request->{"grade_{$key}_max"},
                    ]
                );
            }

            AuditLog::create([
                'log_title' => 'Grade Salary Settings Updated',
                'performed_by' => Auth::id(),
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            DB::commit();

            $successMessage = 'Grade salary settings updated successfully!';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => $successMessage]);
            }
            return redirect()->route('gradesalary.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Grade Salary Settings Update Failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $errorMessage = 'An unexpected error occurred. Failed to update settings.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 500);
            }
            return redirect()->route('gradesalary.index')->with('error', $errorMessage);
        }
    }
}