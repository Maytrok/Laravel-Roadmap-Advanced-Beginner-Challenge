<?php

namespace Database\Factories;

use App\Models\Project;
use Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $days = rand(-10, 30);
        $deadline = $days < 0 ? now()->subDays(abs($days)) : now()->addDays($days);
        return [
            "name" => $this->faker->sentence(4),
            "user_id" => $this->faker->numberBetween(1, 9),
            "client_id" => $this->faker->numberBetween(1, 20),
            "deadline" => $deadline,
            "status" => Arr::random(Project::STATUS),
        ];
    }
}
