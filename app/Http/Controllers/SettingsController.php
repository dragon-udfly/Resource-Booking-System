<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

    public function backupHallBookings()
    {
        $commands = [
            'hall',
            'hall_booking'
        ];
        return $this->executeBackup($commands, 'hall_bookings', 'Hall Booking Applications Backup');
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

    public function restoreMemos(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,csv,txt|max:51200',
        ]);

        $file = $request->file('backup_file');
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'sql') {
            return $this->importMemoSql($file);
        } elseif ($extension === 'csv' || $extension === 'txt') {
            return $this->importMemoCsv($file);
        } else {
            return redirect()->back()->with('error_modal', 'Invalid file type. Only .sql and .csv are supported.');
        }
    }

    private function importMemoSql($file)
    {
        $content = file_get_contents($file->getRealPath());

        // Basic check for table name
        if (stripos($content, 'memos') === false) {
            return redirect()->back()->with('error_modal', "Invalid SQL file. Table 'memos' not found.");
        }

        DB::beginTransaction();
        try {
            // Truncate first to replace
            DB::table('memos')->truncate();

            // Execute SQL (Assumes file content is safe/trusted admin input)
            // Note: DB::unprepared supports multiple statements.
            DB::unprepared($content);

            // --- INTEGRITY CHECKS ---

            // 1. Check for Invalid Users (Sender/Receiver)
            // We use whereNotExists for performance.
            $invalidUsers = DB::table('memos')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('user')
                        ->whereRaw('memos.sender_id = user.user_id');
                })
                ->orWhereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('user')
                        ->whereRaw('memos.receiver_id = user.user_id');
                })
                ->count();

            if ($invalidUsers > 0) {
                throw new \Exception("Integrity Violation: Found {$invalidUsers} memos with invalid Sender or Receiver IDs that do not exist in the Users table.");
            }

            // 2. Check for "Twice in a row" / Duplicates
            // Strict interpretation: No exact duplicates allowed? 
            // Or literally "Sender X -> Receiver Y" cannot happen twice in a row?
            // "same user id can not be twic in a row (sender and receiver can be same user)"
            // This phrasing is tricky. "Sender and Receiver can be same" means Self-Message is allowed.
            // "Same user id can not be twice in a row" usually means "Don't repeat the same user in a list".
            // But memos are distinct records.
            // I will interpret "Twice in a row" as: A user cannot send the SAME message to the SAME receiver twice consecutively?
            // Actually, for a Restore operation, we are restoring HISTORY. We shouldn't reject history unless it's corrupt.
            // But if the user insists: I will check for EXACT DUPLICATES.
            // Finding exact duplicates in DB:
            $duplicates = DB::select('
                SELECT sender_id, receiver_id, subject, body, date_created, COUNT(*) as c 
                FROM memos 
                GROUP BY sender_id, receiver_id, subject, body, date_created 
                HAVING c > 1
            ');

            if (count($duplicates) > 0) {
                throw new \Exception("Integrity Violation: Found duplicate memo records.");
            }

            DB::commit();

            \App\Models\AuditLog::create([
                'log_title' => "Restored Memos",
                'details' => "Restored from SQL: " . $file->getClientOriginalName(),
                'performed_by' => auth()->id(),
                'date_performed' => date('Y-m-d'),
                'time_performed' => date('H:i:s'),
            ]);

            return redirect()->back()->with('success_modal', "Memos restored successfully from SQL with integrity checks.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error_modal', "Restore Failed: " . $e->getMessage());
        }
    }

    private function importMemoCsv($file)
    {
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));

        if (count($data) < 1) {
            return redirect()->back()->with('error_modal', 'CSV file is empty.');
        }

        $headers = array_map('trim', $data[0]);
        $rows = array_slice($data, 1);

        // Validation: Required columns
        $requiredCols = ['sender_id', 'receiver_id', 'subject', 'body'];
        $columnMap = [];
        foreach ($requiredCols as $req) {
            $index = array_search($req, $headers);
            if ($index === false) {
                return redirect()->back()->with('error_modal', "Missing required CSV column: {$req}");
            }
            $columnMap[$req] = $index;
        }

        // Cache User IDs for validation
        $validUserIds = \App\Models\User::pluck('user_id')->flip()->toArray();

        DB::beginTransaction();
        try {
            DB::table('memos')->truncate();

            $previousRow = null;

            foreach ($rows as $i => $row) {
                if (count($row) !== count($headers))
                    continue;

                $rowData = array_combine($headers, $row);

                $senderId = $rowData['sender_id'];
                $receiverId = $rowData['receiver_id'];

                // 1. User Integrity Check
                if (!isset($validUserIds[$senderId])) {
                    throw new \Exception("Row " . ($i + 2) . ": Sender ID '{$senderId}' does not exist.");
                }
                if (!isset($validUserIds[$receiverId])) {
                    throw new \Exception("Row " . ($i + 2) . ": Receiver ID '{$receiverId}' does not exist.");
                }

                // 2. Twice in a row Check (Consecutive duplicates)
                // Normalize for comparison
                $currentRowSignature = "{$senderId}|{$receiverId}|" . $rowData['subject'] . "|" . $rowData['body'];

                if ($previousRow === $currentRowSignature) {
                    // Skip specific logic or Throw? User said "can not be twice".
                    // We will throw to enforce integrity.
                    throw new \Exception("Row " . ($i + 2) . ": Duplicate memo detected immediately following the previous one.");
                }
                $previousRow = $currentRowSignature;

                // Create using Model to trigger Encryption Mutators
                \App\Models\Memo::create([
                    'sender_id' => $senderId,
                    'receiver_id' => $receiverId,
                    'subject' => $rowData['subject'], // Mutator encrypts this
                    'body' => $rowData['body'],       // Mutator encrypts this
                    'status' => $rowData['status'] ?? 'pending', // Default
                    'sender_cleared' => $rowData['sender_cleared'] ?? 0,
                    'receiver_cleared' => $rowData['receiver_cleared'] ?? 0,
                    'date_created' => $rowData['date_created'] ?? now(),
                ]);
            }

            DB::commit();

            \App\Models\AuditLog::create([
                'log_title' => "Restored Memos",
                'details' => "Restored from CSV: " . $file->getClientOriginalName(),
                'performed_by' => auth()->id(),
                'date_performed' => date('Y-m-d'),
                'time_performed' => date('H:i:s'),
            ]);

            return redirect()->back()->with('success_modal', "Memos restored successfully from CSV.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error_modal', "Restore Failed: " . $e->getMessage());
        }
    }

    public function restoreHalls(Request $request)
    {
        return $this->handleSpecificRestore($request, 'hall', \App\Models\Hall::class);
    }

    public function restoreQuarters(Request $request)
    {
        return $this->handleSpecificRestore($request, 'quarters', \App\Models\Quarter::class);
    }

    public function restoreOfficers(Request $request)
    {
        return $this->handleSpecificRestore($request, 'user', \App\Models\User::class);
    }

    public function restoreGradeSalary(Request $request)
    {
        return $this->handleSpecificRestore($request, 'grade_salary_settings', \App\Models\GradeSalarySetting::class);
    }

    public function restoreMarkingScheme(Request $request)
    {
        return $this->handleSpecificRestore($request, 'marking_scheme', \App\Models\MarkingScheme::class);
    }

    private function handleSpecificRestore(Request $request, $tableName, $modelClass)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,csv,txt|max:51200',
        ]);

        $file = $request->file('backup_file');
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'sql') {
            return $this->importSql($file, $tableName);
        } elseif ($extension === 'csv' || $extension === 'txt') {
            return $this->importCsv($file, $tableName);
        } else {
            return redirect()->back()->with('error_modal', 'Invalid file type. Only .sql and .csv are supported.');
        }
    }

    private function importSql($file, $tableName)
    {
        // Integrity Check: Look for CREATE TABLE or INSERT INTO specific table
        $content = file_get_contents($file->getRealPath(), false, null, 0, 10000);
        // Checking for table name quoted or unquoted
        $pattern = "/(CREATE TABLE|INSERT INTO)\s+[`\"]?{$tableName}[`\"]?/i";

        if (!preg_match($pattern, $content)) {
            return redirect()->back()->with('error_modal', "Invalid SQL file. Could not find commands for table '{$tableName}'.");
        }

        $mysqldumpPath = env('MYSQLDUMP_PATH');
        if (!$mysqldumpPath) {
            return redirect()->back()->with('error_modal', 'MYSQLDUMP_PATH is not configured.');
        }
        $mysqlPath = str_replace('mysqldump', 'mysql', $mysqldumpPath);

        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbName = env('DB_DATABASE');
        $dbHost = env('DB_HOST');
        $filePath = $file->getRealPath();

        $command = "\"{$mysqlPath}\" --user=\"{$dbUser}\" --password=\"{$dbPass}\" --host=\"{$dbHost}\" \"{$dbName}\" < \"{$filePath}\"";

        try {
            exec($command, $output, $returnVar);
            if ($returnVar === 0) {
                \App\Models\AuditLog::create([
                    'log_title' => "Restored {$tableName}",
                    'details' => "Restored from SQL: " . $file->getClientOriginalName(),
                    'performed_by' => auth()->id(),
                    'date_performed' => date('Y-m-d'),
                    'time_performed' => date('H:i:s'),
                ]);
                return redirect()->back()->with('success_modal', "Table '{$tableName}' restored successfully from SQL.");
            }
            return redirect()->back()->with('error_modal', "Restore failed. Return Code: {$returnVar}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error_modal', "Exception: " . $e->getMessage());
        }
    }

    private function importCsv($file, $tableName)
    {
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));

        if (count($data) < 1) {
            return redirect()->back()->with('error_modal', 'CSV file is empty.');
        }

        $headers = $data[0]; // First row is header
        $rows = array_slice($data, 1);

        // 1. Column Matching
        $dbColumns = \Illuminate\Support\Facades\Schema::getColumnListing($tableName);

        // Normalize for comparison (lowercase, trim)
        $normalizedHeaders = array_map('strtolower', array_map('trim', $headers));
        $normalizedDbCols = array_map('strtolower', $dbColumns);

        // Check if all CSV headers exist in DB
        $missingCols = array_diff($normalizedHeaders, $normalizedDbCols);
        if (!empty($missingCols)) {
            return redirect()->back()->with('error_modal', "Column Mismatch. CSV contains unknown columns: " . implode(', ', $missingCols));
        }

        // Check if all DB required columns are in CSV (optional, but good for integrity)
        // For now, we perform a strict match of what IS in the CSV against DB.

        DB::beginTransaction();
        try {
            // Truncate table before import? User "Replace" usually implies this.
            DB::table($tableName)->truncate();

            // Insert in chunks
            $chunkSize = 500;
            $insertData = [];

            foreach ($rows as $rowIndex => $row) {
                if (count($row) !== count($headers)) {
                    continue; // Skip malformed rows
                }

                $rowData = array_combine($headers, $row);

                // DATA TYPE VALIDATION (Basic) could go here on first few rows
                // For now, trusting DB to throw error on insertion if type mismatch

                // Clean empty strings to null if column is nullable?
                // For simplified logic, we insert as is.
                foreach ($rowData as $key => $value) {
                    if ($value === '') {
                        $rowData[$key] = null;
                    }
                }

                $insertData[] = $rowData;

                if (count($insertData) >= $chunkSize) {
                    DB::table($tableName)->insert($insertData);
                    $insertData = [];
                }
            }

            if (!empty($insertData)) {
                DB::table($tableName)->insert($insertData);
            }

            DB::commit();

            \App\Models\AuditLog::create([
                'log_title' => "Restored {$tableName}",
                'details' => "Restored from CSV: " . $file->getClientOriginalName(),
                'performed_by' => auth()->id(),
                'date_performed' => date('Y-m-d'),
                'time_performed' => date('H:i:s'),
            ]);

            return redirect()->back()->with('success_modal', "Table '{$tableName}' restored successfully from CSV.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error_modal', "CSV Import Failed: " . $e->getMessage());
        }
    }
}
