<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class OperationTypeTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\dashboard\ot\OperationType::create([
            'name_of_type' => 'Appendectomy',
            'details_of_type' => 'Surgery to relieve pressure on the spinal nerves in the lower back.',
            'status' => 1,
        ]);
    }
}
