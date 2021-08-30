<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $project = Project::all()->random();


        return [
            "description" => $this->faker->sentence(10),
            "user_id" => $project->user_id,
            "project_id" => $project->id,
            "completed" => random_int(0, 1),
        ];
    }
}
