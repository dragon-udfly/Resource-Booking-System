<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

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

    public function index()
    {
        $users = User::where('role', 'user')->get();
        return view('officers', ['users' => $users]);
    }

    public function create()
    {
        return view('createaccount');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:200',
            'last_name' => 'required|string|max:200',
            'nic_number' => 'required|string|max:50|unique:user',
            'passcode' => 'required|string|min:4|max:255',
            'email' => 'required|string|email|max:200|unique:user',
            'contact_number' => 'required|string|max:10|unique:user',
            'designation' => 'nullable|string|max:200',
            'permissions' => 'nullable|array'
        ]);

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

        return redirect()->route('officers')->with('success', 'User created successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'new_passcode' => 'required|string|min:4|confirmed',
        ]);

        $user = Auth::user();
        $user->passcode = Hash::make($request->new_passcode);
        $user->modified_datatime = Carbon::now();
        $user->save();

        AuditLog::create([
            'log_title' => 'Modified account ' . $user->user_id,
            'performed_by' => $user->user_id,
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

        return back()->with('success', 'Passcode changed successfully.');
    }
}

