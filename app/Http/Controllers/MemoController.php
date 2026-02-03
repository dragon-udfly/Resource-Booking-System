<?php

namespace App\Http\Controllers;

use App\Models\Memo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class MemoController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Inbox: Memos received by the current user
        $receivedMemos = Memo::with('sender')
            ->where('receiver_id', $userId)
            ->where('receiver_cleared', 0)
            ->orderBy('date_created', 'desc')
            ->get();

        // Outbox: Memos sent by the current user
        $sentMemos = Memo::with('receiver')
            ->where('sender_id', $userId)
            ->where('sender_cleared', 0)
            ->orderBy('date_created', 'desc')
            ->get();

        // Get active users for the "To" dropdown (excluding self)
        // Formatting: Designation - Full Name
        $users = User::where('user_id', '!=', $userId)
            ->where('role', '!=', 'inactive') // Assuming inactive role check
            ->get()
            ->map(function ($user) {
                $displayName = $user->designation . ' - ' . $user->first_name . ' ' . $user->last_name;
                return [
                    'id' => $user->user_id,
                    'name' => $displayName
                ];
            });

        // Determine Layout based on role
        // Assuming 'admin' role uses the admin layout, others use user layout
        $layout = (Auth::user()->role === 'admin') ? 'layouts.admin_body_layout' : 'layouts.user_body_layout';

        return view('internalmemo', compact('receivedMemos', 'sentMemos', 'users', 'layout'));
    }

    public function fetchInbox()
    {
        $userId = Auth::id();
        $receivedMemos = Memo::with('sender')
            ->where('receiver_id', $userId)
            ->where('receiver_cleared', 0)
            ->orderBy('date_created', 'desc')
            ->get();

        return view('partials.memo_inbox_rows', compact('receivedMemos'))->render();
    }

    public function fetchOutbox()
    {
        $userId = Auth::id();
        $sentMemos = Memo::with('receiver')
            ->where('sender_id', $userId)
            ->where('sender_cleared', 0)
            ->orderBy('date_created', 'desc')
            ->get();

        return view('partials.memo_sent_rows', compact('sentMemos'))->render();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|exists:user,user_id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fill in all required fields.');
        }

        try {
            Memo::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $request->receiver_id,
                'subject' => $request->subject, // Model handles encryption
                'body' => $request->body,       // Model handles encryption
                'status' => 2,                  // 2 = Pending
            ]);

            return redirect()->route('memo.index')->with('success', 'Memo sent successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send memo: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $memo = Memo::with('sender')->findOrFail($id);

        // Security check: Only sender or receiver can view
        if ($memo->sender_id !== Auth::id() && $memo->receiver_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'id' => $memo->id,
            'subject' => $memo->subject, // Accessor decrypts automatically
            'body' => $memo->body,       // Accessor decrypts automatically
            'sender' => $memo->sender->designation . ' - ' . $memo->sender->first_name . ' ' . $memo->sender->last_name,
            'date' => Carbon::parse($memo->date_created)->format('Y-m-d'),
            'status' => $memo->status,
            // Allow response only if current user is receiver AND status is Pending (2)
            'can_respond' => ($memo->receiver_id === Auth::id() && $memo->status == 2)
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:1,0', // 1=Yes, 0=No
        ]);

        $memo = Memo::findOrFail($id);

        if ($memo->receiver_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $memo->status = $request->status;
        $memo->save();

        $action = $request->status == 1 ? 'Approved/Agreed' : 'Rejected/Disagreed';

        return response()->json(['success' => true, 'message' => "Memo marked as {$action}."]);
    }

    public function clearRead()
    {
        $userId = Auth::id();

        // Renamed to Clear Inbox essentially, as is_read is removed.
        // It clears ALL messages from inbox view EXCEPT Pending (2).
        $count = Memo::where('receiver_id', $userId)
            ->where('receiver_cleared', 0)
            ->where('status', '!=', 2) // Protect Pending Memos
            ->update(['receiver_cleared' => 1]);

        // Log the action (System Log)
        \Log::info("User {$userId} cleared {$count} memos from inbox.");

        return response()->json(['success' => true, 'message' => "Cleared {$count} resolved memos."]);
    }

    public function clearSent()
    {
        $userId = Auth::id();

        // Clear Sent Memos EXCEPT Pending (2)
        $count = Memo::where('sender_id', $userId)
            ->where('sender_cleared', 0)
            ->where('status', '!=', 2) // Protect Pending Memos
            ->update(['sender_cleared' => 1]);

        // Log the action (System Log)
        \Log::info("User {$userId} cleared {$count} sent memos from outbox.");

        return response()->json(['success' => true, 'message' => "Cleared {$count} resolved sent memos."]);
    }
}
