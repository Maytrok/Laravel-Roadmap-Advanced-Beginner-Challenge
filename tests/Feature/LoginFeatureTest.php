<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LoginFeatureTest extends TestCase
{

    use RefreshDatabase;

    private $seed = true;
    /**
     * @test
     */
    public function login_requests_needs_a_username_and_password_body()
    {
        $response = $this->post('/api/login');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.username.0', 'The username field is required.');
        $response->assertJsonPath('errors.password.0', 'The password field is required.');
    }

    /**
     * @test
     */
    public function login_credentials_has_to_be_correct()
    {
        $response = $this->post('/api/login', [
            "username" => "admin",
            "password" => "amifake"
        ]);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function successful_login_returns_a_token()
    {
        $response = $this->post('/api/login', [
            "username" => "admin",
            "password" => "password"
        ]);
        $response->assertOk();
        $tokens = User::find(1)->tokens->toArray();
        $this->assertCount(1, $tokens);
    }

    /**
     * @test
     */
    public function email_address_can_use_to_login()
    {
        $response = $this->post('/api/login', [
            "username" => "admin@admin.com",
            "password" => "password"
        ]);
        $response->assertOk();
        $tokens = User::find(1)->tokens->toArray();
        $this->assertCount(1, $tokens);
    }

    /**
     * @test
     */
    public function logout_route_needs_a_valid_token()
    {
        $response = $this->get("api/logout");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function logout_can_be_performed()
    {

        Sanctum::actingAs(
            User::factory()->create()
        );

        $response = $this->get("api/logout");
        $response->assertOk();
    }

    /**
     * @test
     */
    public function logout_removes_all_tokens()
    {
        $user = User::find(1);
        $user->createToken("myFirst");
        $token = $user->createToken("justAnOtherOne")->plainTextToken;

        $this->assertCount(2, $user->tokens);

        $response = $this->withHeaders(["Authorization" => "Bearer " . $token])->get("api/logout");
        $response->assertOk();
        $user->load("tokens");
        $this->assertCount(0, $user->tokens);
    }
}
