<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserFeatureTest extends TestCase
{

    use WithFaker;

    private $rolesAndPermission = ["roles", "permissions"];
    private $jsonAdminUserCanSee = ["id", "name", "email", "created_at", "deleted_at", "roles", "permissions"];


    /**
     * @test
     */
    public function user_controller_is_protected()
    {

        $response = $this->get("/api/users/1");
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function a_user_can_be_shown()
    {

        Sanctum::actingAs(User::factory()->create());
        $user = User::factory()->create();
        $response = $this->get("/api/users/" . $user->id);
        $response->assertOk();
    }

    /**
     * @test
     */
    public function only_admins_can_see_roles_and_permissions_for_all_users()
    {
        Sanctum::actingAs(User::find(1));

        $user = User::factory()->create();
        $user = User::factory()->create();
        $response = $this->get("/api/users/" . $user->id);
        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll($this->rolesAndPermission)
                ->etc()
        );

        Sanctum::actingAs($user);

        $response = $this->get("/api/users/1");
        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->missingAll($this->rolesAndPermission)
                ->etc()
        );

        $response = $this->get("/api/users/" . $user->id);
        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll($this->rolesAndPermission)
                ->etc()
        );
    }

    /**
     * @test
     */
    public function user_index_method()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->get("api/users");
        $count = User::count();
        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has($count)
                ->first(
                    fn (AssertableJson $j) => $j->hasAll(["id", "name", "email", "created_at"]),
                )
                ->has(2, fn (AssertableJson $j) => $j->hasAll($this->rolesAndPermission)->etc())
                ->etc()
        );
    }

    /**
     * @test
     */
    public function creating_an_user_required_fields()
    {
        Sanctum::actingAs(User::find(1));
        $response = $this->post("api/users", []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->where("message", "The given data was invalid.")
                ->has(
                    "errors",
                    fn (AssertableJson $j) =>
                    $j->hasAll(["name", "email", "password"])
                )
        );
    }

    /**
     * @test
     */
    public function only_admins_can_create_new_users()
    {
        $data = [
            "name" => "just random",
            "email" => "42@answers.com",
            "password" => "sup3rG3h3!m"
        ];
        Sanctum::actingAs(User::factory()->create());
        $response = $this->post("api/users", $data);
        $response->assertStatus(Response::HTTP_FORBIDDEN);

        Sanctum::actingAs(User::find(1));

        $response = $this->post("api/users", $data);
        $response->assertCreated();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll($this->jsonAdminUserCanSee)
        );
    }

    /**
     * @test
     */
    public function admin_users_can_update_any_user_an_user_can_be_updated()
    {

        $data = [
            "email" => $this->faker->freeEmail
        ];

        $user = User::factory()->create();


        Sanctum::actingAs(User::factory()->create());
        $response = $this->patch("api/users/" . $user->id, $data);
        $response->assertStatus(Response::HTTP_FORBIDDEN);

        Sanctum::actingAs($user);
        $response = $this->patch("api/users/" . $user->id, ["email" => "me@aol.com"]);
        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->where("email", "me@aol.com")->etc()
        );

        Sanctum::actingAs(User::find(1));
        $response = $this->patch("api/users/" . $user->id, $data);
        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll($this->jsonAdminUserCanSee)
                ->where("email", $data["email"])
        );
    }

    /**
     * @test
     */
    public function only_admin_can_delete_users()
    {

        $user = User::factory()->create();
        Sanctum::actingAs(User::factory()->create());
        $count = User::count();
        $response = $this->delete("api/users/" . $user->id);
        $response->assertStatus(Response::HTTP_FORBIDDEN);

        Sanctum::actingAs(User::find(1));
        $response = $this->delete("api/users/" . $user->id);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertCount(--$count, User::all());
    }
}
