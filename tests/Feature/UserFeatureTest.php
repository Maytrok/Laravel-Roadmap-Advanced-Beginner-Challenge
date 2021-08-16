<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserFeatureTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;

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

        Sanctum::actingAs(User::find(1));

        $response = $this->get("/api/users/1");
        $response->assertOk();
    }

    /**
     * @test
     */
    public function only_admins_can_see_roles_and_permissions_for_all_users()
    {
        Sanctum::actingAs(User::find(1));

        $response = $this->get("/api/users/2");
        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll($this->rolesAndPermission)
                ->etc()
        );

        Sanctum::actingAs(User::find(2));

        $response = $this->get("/api/users/1");
        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->missingAll($this->rolesAndPermission)
                ->etc()
        );

        $response = $this->get("/api/users/2");
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
        Sanctum::actingAs(User::find(2));

        $response = $this->get("api/users");

        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has(11)
                ->first(
                    fn (AssertableJson $j) => $j->hasAll(["id", "name", "email", "created_at"]),
                )
                ->has(1, fn (AssertableJson $j) => $j->hasAll($this->rolesAndPermission)->etc())
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
        Sanctum::actingAs(User::find(2));
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
            "email" => "example@admin.com"
        ];

        Sanctum::actingAs(User::find(2));
        $response = $this->patch("api/users/9", $data);
        $response->assertStatus(Response::HTTP_FORBIDDEN);


        $response = $this->patch("api/users/2", ["email" => "me@aol.com"]);
        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->where("email", "me@aol.com")->etc()
        );

        Sanctum::actingAs(User::find(1));
        $response = $this->patch("api/users/9", $data);
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
        Sanctum::actingAs(User::find(2));
        $response = $this->delete("api/users/2");
        $response->assertStatus(Response::HTTP_FORBIDDEN);

        Sanctum::actingAs(User::find(1));
        $response = $this->delete("api/users/2");
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertCount(10, User::all());
    }
}
