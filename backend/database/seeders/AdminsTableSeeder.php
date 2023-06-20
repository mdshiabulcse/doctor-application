<?php

namespace Database\Seeders;

use App\Models\Auth\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                        Admin::create([
                     'name' => 'admin',
                     'email' => 'admin@gmail.cpm',
                     'phone' => '01724521239',
                     'email_verified_at' => now(),
                     'password' => Hash::make('12345678'),
                     'remember_token' => Str::random(10),
         ]);
    }
}
