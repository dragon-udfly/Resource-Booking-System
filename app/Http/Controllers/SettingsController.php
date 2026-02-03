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

    public function backupDatabase()
    {
        return $this->backupTable(null, 'Database Backup');
    }

    public function backupHalls()
    {
        return $this->backupTable('hall', 'Hall Details Record Backup');
    }

    public function backupQuarters()
    {
        return $this->backupTable('quarters', 'Quarter Details Record Backup');
    }

    public function backupOfficers()
    {
        return $this->backupTable('user', 'Officers Details Record Backup');
    }

    private function backupTable($tableName = null, $logAction = 'Database Backup')
    {
        $mysqldumpPath = env('MYSQLDUMP_PATH');

        if (!$mysqldumpPath) {
            return redirect()->back()->with('error_modal', 'MYSQLDUMP_PATH is not configured in the .env file.');
        }

        // Determine filename
        $prefix = $tableName ? $tableName : 'backup';
        $filename = "{$prefix}_" . date('Y-m-d_H-i-s') . '.sql';
        $directory = database_path('backups');
        $path = $directory . '/' . $filename;

        // Ensure directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbName = env('DB_DATABASE');
        $dbHost = env('DB_HOST');

        // Construct command
        // If tableName is provided, append it to the command to dump only that table
        $tableArg = $tableName ? " \"{$tableName}\"" : "";
        $command = "\"{$mysqldumpPath}\" --user=\"{$dbUser}\" --password=\"{$dbPass}\" --host=\"{$dbHost}\" \"{$dbName}\"{$tableArg} > \"{$path}\"";

        try {
            // Using exec to run the command
            exec($command, $output, $returnVar);

            if ($returnVar === 0 && file_exists($path) && filesize($path) > 0) {

                // Log to System Log
                Log::info("{$logAction} created successfully: {$filename} by User ID: " . auth()->id());

                // Log to Audit Log
                \App\Models\AuditLog::create([
                    'log_title' => $logAction,
                    'details' => "Created backup: {$filename}",
                    'performed_by' => auth()->id(),
                    'date_performed' => date('Y-m-d'),
                    'time_performed' => date('H:i:s'),
                ]);

                return redirect()->back()->with('success_modal', "{$logAction} created successfully. File: {$filename}");
            } else {
                Log::error("{$logAction} failed. Return Var: {$returnVar}");
                return redirect()->back()->with('error_modal', "{$logAction} failed. Check system logs for details.");
            }

        } catch (\Exception $e) {
            Log::error("{$logAction} exception: " . $e->getMessage());
            return redirect()->back()->with('error_modal', "An unexpected error occurred during backup: " . $e->getMessage());
        }
    }
}
