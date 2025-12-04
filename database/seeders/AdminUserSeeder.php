<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon; // Used for modern date/time handling
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the current timestamp for created_at and updated_at
        $now = Carbon::now();

        // 1. Insert the specific admin user data
        DB::table('user')->updateOrInsert(
            ['user_id' => 'admin001'],
            [
                'first_name' => 'Isuru',
                'last_name' => 'Perera',
                'designation' => 'IT Administrator',
                'nic_number' => '200104592348',
                'email' => 'johndeo@stu.vau.ac.lk',
                'contact_number' => '0720000000',
                'role' => 'admin',
                'passcode'=> Hash::make('Abc@3210'),
                'created_datetime' => $now,
                'modified_datatime' => $now,
            ]
        );

        // 2. Insert permissions for the first admin user
        DB::table('user_permissions')->updateOrInsert(
            ['user_id' => 'admin001'],
            [
                'view_officers' => 1, 'view_officer_details' => 1, 'view_halls' => 1,
                'view_hall_details' => 1, 'view_quarters' => 1, 'view_quarter_details' => 1,
                'view_audit_log' => 1, 'administrative_officer_approval' => 1,
                'additional_government_agent_approval' => 1, 'government_agent_approval' => 1,
                'form_history' => 1, 'account_setting' => 1,
                'created_at' => $now, 'updated_at' => $now,
            ]
        );
    }
}