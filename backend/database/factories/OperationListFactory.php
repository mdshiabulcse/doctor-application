<?php

namespace Database\Factories;

use App\Models\dashboard\ot\OperationCategory;
use App\Models\dashboard\ot\OperationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OperationListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'operation_name'=>fake()->word(),
            'operation_sub_head'=>fake()->name(),
            'operation_procedure_details'=>fake()->word(),
            'operation_type_id' => function () {
            return factory(OperationType::class)->create()->id;
        },
            'operation_category_id' => function () {
            return factory(OperationCategory::class)->create()->id;
        },
            'status'=>1,
        ];
    }
}
