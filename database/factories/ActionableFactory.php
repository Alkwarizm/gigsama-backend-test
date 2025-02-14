<?php

namespace Database\Factories;

use App\Models\Actionable;
use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ActionableFactory extends Factory
{
    protected $model = Actionable::class;

    public function definition(): array
    {
        return [
            'note_id' => Note::factory(),
            'type' => $this->faker->randomElement(['step', 'schedule']),
            'description' => $this->faker->text(),
            'status' => $this->faker->randomElement(['pending', 'completed']),
            'schedule' => $this->faker->word(),
            'cron_expression' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
