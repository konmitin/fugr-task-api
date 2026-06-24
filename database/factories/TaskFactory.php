<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake('ru_RU')->sentence(random_int(1, 5)),
            'description' => fake('ru_RU')->sentence(random_int(0, 20)),
            'due_date' => fake('ru_RU')->dateTime(),
            'created_at' => now(),
            'updated_at' => now(),
            'status_id' => random_int(1, 2),
            'priority_id' => random_int(1, 3),
            'category_id' => random_int(1, 3),
        ];
    }
}
