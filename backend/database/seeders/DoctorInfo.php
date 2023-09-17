<?php

namespace Database\Seeders;

use App\Models\Auth\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DoctorInfo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\dashboard\DoctorInfo::create([
            'doctor_name' => 'admin',
            'doctor_id' => 'admin@gmail.cpm',
            'doctor_fees' => 500.00,
            'hospital_name' => 'Test Hospital',
            'doctor_details' => 'mbbs,bds',
            'user_id' => '1',
            'ip_address' => '192.168.0.1',
            'status' => 1,
        ]);
    }
}
