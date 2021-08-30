<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectFeatureTest extends TestCase
{


    public function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::find(2));
    }

    /**
     * @test
     */
    public function a_project_can_viewed()
    {


        $project = Project::factory()->create();
        $response = $this->get("api/projects/" . $project->id);
        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(["name", "user", "client", "deadline", "status"])->etc()
        );
    }


    /**
     * @test
     */
    public function a_project_can_be_created()
    {
        Project::factory()->count(5)->create();
        $client = Client::factory()->create();
        $this->assertEquals(5, Project::count());
        $response = $this->post("api/projects", [
            "name" => "Test Project",
            "user_id" => 1,
            "client_id" => $client->id,
            "deadline" => now()->addMonth()
        ]);
        $response->assertCreated();
        $this->assertEquals(6, Project::count());
    }



    /**
     * @test
     */
    public function a_project_cant_be_created_if_the_client_id_doesnt_exists()
    {

        Project::factory()->count(5)->create();
        $this->assertEquals(5, Project::count());
        $response = $this->post("api/projects", [
            "name" => "Test Project",
            "user_id" => 1,
            "client_id" => 100,
            "deadline" => now()->addMonth()
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(["message", "errors"])
                ->where("errors.client_id.0", "The selected client id is invalid.")
        );
    }

    /**
     * @test
     */
    public function a_project_can_be_updated()
    {
        $project = Project::factory()->create();
        $response = $this->patch("api/projects/" . $project->id, ["status" => "clarification", "name" => "Just testing"]);
        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has("status")
                ->where("status", "clarification")
                ->where("name", "Just testing")
                ->etc()
        );
    }

    /**
     * @test
     */
    public function only_admins_can_delete_a_project()
    {
        $project = Project::factory()->create();
        $response = $this->delete("api/projects/" . $project->id);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertEquals(1, Project::count());

        Sanctum::actingAs(User::find(1));

        $response = $this->delete("api/projects/" . $project->id);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals(0, Project::count());
    }
}
