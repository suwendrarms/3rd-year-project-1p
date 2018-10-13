<?php

use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('employees')->insert([
            'photo' => '',
            'code' => '000001',
           
            'name' => 'suwendra',
            'gender' => '1',
            'date_of_birth' => '',
            'date_of_joining' => '',
            'number' => '0719999999',
            'qualification' => '',
            'emergency_number' => '',
           
            'father_name' => '',
            'current_address' => '',
            'permanent_address' => '',
          
            'probation_period' => '',
            'date_of_confirmation' => '',
            'department' => '',
            'salary' => '',
            'account_number' => '',
            'bank_name' => '',
          
            'date_of_resignation' => '',
            'notice_period' => '',
           
            'full_final' => '',
            'user_id' => '1'
        ]);
    }
}
