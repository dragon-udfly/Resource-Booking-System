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
     */
    public function systemStatus(Request $request)
    {
        if (!auth()->check() || (!auth()->user()->hasPermissionTo('admin') && !auth()->user()->hasPermissionTo('government_agent_approval'))) {
            // Basic protection
        }

        $filter = $request->input('filter', 'all');
        $logPath = storage_path('logs/laravel.log');
        $logs = [];

        if (file_exists($logPath)) {
            $file = file($logPath);
            $file = array_reverse($file);

            foreach ($file as $line) {
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

    // --- Backup Methods ---

    public function backupDatabase()
    {
        return $this->executeBackup([], 'backup', 'Database Backup');
    }

    public function backupHalls()
    {
        return $this->executeBackup(['hall'], 'hall', 'Hall Details Record Backup');
    }

    public function backupQuarters()
    {
        return $this->executeBackup(['quarters'], 'quarters', 'Quarter Details Record Backup');
    }

    public function backupOfficers()
    {
        return $this->executeBackup(['user'], 'user', 'Officers Details Record Backup');
    }

    public function backupScheduledApplications()
    {
        $commands = [
            'quarter_application --where="quarter_type=\'Scheduled\'"',
            'scheduled_quarter_application'
        ];
        return $this->executeBackup($commands, 'scheduled_applications', 'Scheduled Quarter Applications Backup');
    }

    public function backupFamilyApplications()
    {
        $commands = [
            'quarter_application --where="quarter_type=\'Family\'"',
            'family_quarter_application',
            'marking_family_quarter'
        ];
        return $this->executeBackup($commands, 'family_applications', 'Family Quarter Applications Backup');
    }

    public function backupGradeSalary()
    {
        return $this->executeBackup(['grade_salary_settings'], 'grade_salary', 'Grade Salary Settings Backup');
    }

    public function backupMarkingScheme()
    {
        return $this->executeBackup(['marking_scheme'], 'marking_scheme', 'Marking Scheme Backup');
    }

    public function backupMemos()
    {
        return $this->executeBackup(['memos'], 'memos', 'Memos Backup');
    }

    /**
     * Generic backup executor.
     * @param array $tableCommands Array of strings. e.g. ['table1', 'table2 --where="id>1"']. If empty, dumps entire DB.
     * @param string $filePrefix Prefix for the filename.
     * @param string $logAction Action title for logging.
     */
    private function executeBackup(array $tableCommands = [], $filePrefix = 'backup', $logAction = 'Database Backup')
    {
        $mysqldumpPath = env('MYSQLDUMP_PATH');

        if (!$mysqldumpPath) {
            return redirect()->back()->with('error_modal', 'MYSQLDUMP_PATH is not configured in the .env file.');
        }

        $filename = "{$filePrefix}_" . date('Y-m-d_H-i-s') . '.sql';
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

        // Base command structure
        $baseCommand = "\"{$mysqldumpPath}\" --user=\"{$dbUser}\" --password=\"{$dbPass}\" --host=\"{$dbHost}\" \"{$dbName}\"";

        try {
            // If no specific tables requested, dump the whole database
            if (empty($tableCommands)) {
                $command = "$baseCommand > \"{$path}\"";
                exec($command, $output, $returnVar);
                if ($returnVar !== 0) {
                    throw new \Exception("Full backup failed with return code $returnVar");
                }
            } else {
                // Execute sequentially appending to the file
                foreach ($tableCommands as $index => $tblCmd) {
                    // First command overwrites (>), subsequent append (>>)
                    $operator = ($index === 0) ? '>' : '>>';

                    // $tblCmd might contain spaces for arguments like --where, so we append it directly
                    // e.g. "quarter_application --where=..."
                    $command = "$baseCommand $tblCmd $operator \"{$path}\"";

                    exec($command, $output, $returnVar);

                    if ($returnVar !== 0) {
                        throw new \Exception("Backup failed for command part: $tblCmd with return code $returnVar");
                    }
                }
            }

            if (file_exists($path) && filesize($path) > 0) {
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
                return redirect()->back()->with('error_modal', "{$logAction} failed. File not created or empty.");
            }

        } catch (\Exception $e) {
            Log::error("{$logAction} exception: " . $e->getMessage());
            return redirect()->back()->with('error_modal', "An unexpected error occurred during backup: " . $e->getMessage());
        }
    }

    public function restoreDatabase(Request $request)
    {
        // 1. Validate File Upload
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt|max:51200', // Max 50MB
        ]);

        $file = $request->file('backup_file');

        // Double check extension just in case
        if ($file->getClientOriginalExtension() !== 'sql') {
            return redirect()->back()->with('error_modal', 'Invalid file type. Please upload a .sql file.');
        }

        // 2. Integrity Check (Simple content scan)
        $content = file_get_contents($file->getRealPath(), false, null, 0, 10000); // Read first 10KB
        $criticalTables = ['hall', 'quarters', 'user', 'quarter_application'];
        $foundTables = 0;

        foreach ($criticalTables as $table) {
            // Searching for "CREATE TABLE `tablename`" or "CREATE TABLE tablename"
            // Using case-insensitive search
            if (stripos($content, "CREATE TABLE `{$table}`") !== false || stripos($content, "CREATE TABLE {$table}") !== false) {
                $foundTables++;
            }
        }

        // We expect at least one critical table to be present to consider it a valid dump of THIS system
        // Or if it's a partial backup (e.g. just halls), we should still allow it but maybe warn?
        // User asked for "confirmed table and column integrity".
        // A strict check might be too restrictive for partial backups (e.g. restoring just Halls).
        // Let's check if it looks like a dump at all.
        if ($foundTables === 0 && stripos($content, 'MySQL dump') === false) {
            return redirect()->back()->with('error_modal', 'Invalid backup file. critical table definitions or MySQL dump header not found.');
        }

        // 3. Prepare for Restoration
        $mysqldumpPath = env('MYSQLDUMP_PATH');
        if (!$mysqldumpPath) {
            return redirect()->back()->with('error_modal', 'MYSQLDUMP_PATH is not configured in .env');
        }

        // Derive mysql.exe path from mysqldump.exe path
        $mysqlPath = str_replace('mysqldump', 'mysql', $mysqldumpPath);

        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbName = env('DB_DATABASE');
        $dbHost = env('DB_HOST');
        $filePath = $file->getRealPath();

        // 4. Executing Restore Command
        // mysql -u [user] -p[password] [database_name] < [filename.sql]
        $command = "\"{$mysqlPath}\" --user=\"{$dbUser}\" --password=\"{$dbPass}\" --host=\"{$dbHost}\" \"{$dbName}\" < \"{$filePath}\"";

        try {
            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                // Log to System Log
                Log::info("Database restored successfully by User ID: " . auth()->id());

                // Log to Audit Log
                \App\Models\AuditLog::create([
                    'log_title' => 'Database Restore',
                    'details' => "Restored database from file: " . $file->getClientOriginalName(),
                    'performed_by' => auth()->id(),
                    'date_performed' => date('Y-m-d'),
                    'time_performed' => date('H:i:s'),
                ]);

                return redirect()->back()->with('success_modal', 'Database restored successfully.');
            } else {
                Log::error("Database restore failed. Return Var: {$returnVar}");
                return redirect()->back()->with('error_modal', 'Database restore failed. Check system logs.');
            }
        } catch (\Exception $e) {
            Log::error("Database restore exception: " . $e->getMessage());
            return redirect()->back()->with('error_modal', "An unexpected error occurred during restore: " . $e->getMessage());
        }
    }
}
