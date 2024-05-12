<?php

namespace Database\Factories;

use App\Models\dashboard\ot\OperationCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OperationCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = OperationCategory::class;
    public function definition()
    {
        return [
            'operation_category_name'=>$this->faker->unique()->word,
        ];
    }
}
