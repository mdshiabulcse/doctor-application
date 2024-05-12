<?php

namespace Database\Factories;

use App\Models\dashboard\ot\OperationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OperationTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model=OperationType::class;
    public function definition()
    {
        return [
            'name_of_type'=>$this->faker->unique()->word,
        ];
    }
}
