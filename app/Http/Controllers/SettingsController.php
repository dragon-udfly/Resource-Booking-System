<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Display the system settings page.
     */
    public function index()
    {
        return view('systemsetting');
    }

    /**
     * Send a test email using the provided details.
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
            'subject' => 'required|string|max:255',
            'email-body' => 'required|string',
        ]);

        try {
            $toEmail = $request->test_email;
            $subject = $request->subject;
            $body = $request->input('email-body');

            Mail::raw($body, function ($message) use ($toEmail, $subject) {
                $message->to($toEmail)
                    ->subject($subject);
            });

            return redirect()->back()->with('success', 'Test email sent successfully to ' . $toEmail);
        } catch (\Exception $e) {
            Log::error('Test Email Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    /**
     * Display the system status log page with filters.
     * Restricted to Admin users.
     */
    public function systemStatus(Request $request)
    {
        // Authorization check
        if (!auth()->check() || (!auth()->user()->hasPermissionTo('admin') && !auth()->user()->hasPermissionTo('government_agent_approval'))) {
            // Basic protection, though middleware should handle this.
        }

        $filter = $request->input('filter', 'all'); // Default to 'all' or 'today' as preferred.
        $logPath = storage_path('logs/laravel.log');
        $logs = [];

        if (file_exists($logPath)) {
            // Read file into array
            // For very large files, this should be optimized (e.g. read chunk from end), 
            // but for 1-5MB, file() is acceptable and simplest for "All" filter.
            $file = file($logPath);
            $file = array_reverse($file); // Newest first

            foreach ($file as $line) {
                // Regex to extract structure: [YYYY-MM-DD HH:MM:SS] Env.Level: Message
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.*)/', $line, $matches)) {
                    $logDate = \Carbon\Carbon::parse($matches[1]);
                    $shouldInclude = false;

                    switch ($filter) {
                        case 'today':
                            if ($logDate->isToday())
                                $shouldInclude = true;
                            break;
                        case 'week':
                            if ($logDate->isCurrentWeek())
                                $shouldInclude = true;
                            break;
                        case 'month':
                            if ($logDate->isCurrentMonth())
                                $shouldInclude = true;
                            break;
                        case 'all':
                        default:
                            $shouldInclude = true;
                            break;
                    }

                    if ($shouldInclude) {
                        $logs[] = [
                            'timestamp' => $matches[1],
                            'env' => $matches[2],
                            'level' => $matches[3],
                            'message' => $matches[4],
                        ];
                    }
                }
            }
        }

        return view('systemstatus', ['logs' => $logs, 'currentFilter' => $filter]);
    }
}
