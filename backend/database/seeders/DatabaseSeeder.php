<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\dashboard\ot\OperationCategory;
use App\Models\dashboard\ot\OperationList;
use App\Models\dashboard\ot\OperationType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         \App\Models\dashboard\DoctorInfo::factory(10)->create();


        OperationList::factory('10')->create();
        OperationType::factory('5')->create();
        OperationCategory::factory('5')->create();

        $this->call([
//        DoctorInfo::class
//        OperationList::factory('10')->create(),
//        OperationType::factory('5')->create(),
//        OperationCategory::factory('5')->create(),
        ]);
    }
}
