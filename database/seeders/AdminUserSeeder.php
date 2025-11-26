<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon; // Used for modern date/time handling

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
        DB::table('users')->insert([
            'user_id' => 'admin001',
            'first_name' => 'Isuru',
            'last_name' => 'Perera',
            'designation' => 'IT Administrator',
            'nic_number' => '200104592348',
            'email' => 'johndeo@stu.vau.ac.lk',
            'contact_number' => '0720000000',
            'role' => 'admin',
            'passcode'=>'Abc@3210',
            
            // 2. Automatically populate the timestamps 
            // (Assuming your migration uses created_at and updated_at, 
            // as Laravel converts these to DATETIME types.)
            'created_at' => $now,
            'updated_at' => $now,
            
            // NOTE: If you are strictly using 'created_datetime' and 
            // 'modified_datatime', you would need to use those column names here instead.
        ]);
    }
}