<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskFeatureTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Project::truncate();
        Task::truncate();
        Sanctum::actingAs(User::factory()->create());
    }

    /**
     * @test
     */
    public function all_tasks_for_an_project_can_be_viewed()
    {
        Project::factory()->hasTasks(3)->create();
        $response = $this->get("api/projects/1/tasks");
        $response->assertOk();
        $this->assertIsArray($response->json());
        $this->assertCount(3, $response->json());
    }

    /**
     * @test
     */
    public function a_single_task_can_viewed()
    {
        Project::factory()->hasTasks(1)->create();
        $taskid = Task::first()->id;

        $response = $this->get("api/tasks/" . $taskid);
        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll("description", "user_id", "project_id", "id", "completed")->etc()
        );
    }

    /**
     * @test
     */
    public function creating_a_task_requires_fields()
    {
        $response = $this->post("api/tasks");
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has("message")
                ->has(
                    "errors",
                    fn (AssertableJson $json) =>
                    $json->hasAll(["description", "project_id"])->missing("completed")
                )

        );
    }

    /**
     * @test
     */
    public function tasks_can_be_created()
    {

        $project = Project::factory()->create();
        $description  = $this->faker->sentence(4);
        $response = $this->post("api/tasks", [
            "description" => $description,
            "project_id" => $project->id
        ]);

        $response->assertCreated();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(["description", "completed", "id"])->etc()
        );
    }

    /**
     * @test
     */
    public function a_user_can_assigned_to_an_task()
    {

        $project = Project::factory()->create();
        $description  = $this->faker->sentence(4);
        $response = $this->post("api/tasks", [
            "description" => $description,
            "project_id" => $project->id,
            "user_id" => 1
        ]);

        $response->assertCreated();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(["description", "completed", "id"])->where("user_id", 1)->etc()
        );
    }

    /**
     * @test
     */
    public function a_task_can_updated()
    {

        Project::factory()->hasTasks(1)->create();
        $task = Task::first();
        $description  = $this->faker->sentence(4);

        $response = $this->patch("api/tasks/" . $task->id, [
            "completed" => true,
            "description" =>  $description
        ]);
        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->where("description",  $description)
                ->where("completed", true)
                ->etc()
        );
    }


    /**
     * @test
     */
    public function task_can_be_deleted()
    {
        Project::factory()->hasTasks(1)->create();
        $tasks = Task::all();
        $this->assertCount(1, $tasks);
        $response = $this->delete("api/tasks/" . $tasks->first()->id);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertCount(0, Task::all());
    }
}
