<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use URL;

class ProjectFeatureTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;

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


        $response = $this->get("api/projects/10");
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

        $this->assertEquals(40, Project::count());
        $response = $this->post("api/projects", [
            "name" => "Test Project",
            "user_id" => 1,
            "client_id" => 1,
            "deadline" => now()->addMonth()
        ]);
        $response->assertCreated();
        $this->assertEquals(41, Project::count());
    }



    /**
     * @test
     */
    public function a_project_cant_be_created_if_the_client_id_doesnt_exists()
    {

        $this->assertEquals(40, Project::count());
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
        $response = $this->patch("api/projects/1", ["status" => "clarification", "name" => "Just testing"]);
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
        $response = $this->delete("api/projects/1");
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertEquals(40, Project::count());

        Sanctum::actingAs(User::find(1));

        $response = $this->delete("api/projects/1");
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals(39, Project::count());
    }
}
