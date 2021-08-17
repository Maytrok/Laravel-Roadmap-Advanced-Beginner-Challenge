<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientFeatureTest extends TestCase
{

    use RefreshDatabase;

    private $exampleData = [
        "matchcode" => "my Name",
        "name" => "my company",
        "street" => "firststreet",
        "zip" => "0815",
        "city" => "vice city",
        "email" => "call@me.net",
        "phone" => "05100-5551546",
    ];

    private $clientFields = [
        "matchcode",
        "name",
        "street",
        "zip",
        "city",
        "email",
        "phone",
        "id",
        "updated_at",
        "created_at",
    ];


    protected $seed = true;
    /**
     * @test
     */
    public function clients_can_listed()
    {
        Sanctum::actingAs(User::find(2));
        $response = $this->get('api/clients/');

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has(20)
                ->first(fn (AssertableJson $json) => $json->hasAll(
                    "name",
                    "street",
                    "zip",
                    "city"
                ))
                ->etc()
        );
    }

    /**
     * @test
     */
    public function a_client_can_shown()
    {
        Sanctum::actingAs(User::find(2));
        $response = $this->get('api/clients/2');
        $response->assertOk();

        $response->assertJson(fn (AssertableJson $json) =>
        $json->hasAll($this->clientFields));
    }

    /**
     * @test
     */
    public function creating_clients_require_some_fields()
    {
        Sanctum::actingAs(User::find(2));
        $response = $this->post('api/clients/', []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->where("message", "The given data was invalid.")
                ->has("errors", 7)->etc()
        );
    }

    /**
     * @test
     */
    public function clients_can_be_created()
    {

        Sanctum::actingAs(User::find(2));
        $response = $this->post('api/clients/', $this->exampleData);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(
                $this->clientFields
            )
        );
    }

    /**
     * @test
     */
    public function clients_can_be_updated()
    {
        Sanctum::actingAs(User::find(2));
        $response = $this->patch('api/clients/2', ["name" => "superName"]);
        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(
                $this->clientFields
            )->where("name", "superName")
        );
    }

    /**
     * @test
     */
    public function clients_can_be_soft_deleted()
    {
        Sanctum::actingAs(User::find(2));
        $this->assertCount(20, Client::all());
        $response = $this->delete('api/clients/2');
        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertCount(19, Client::all());
        $this->assertCount(20, Client::withTrashed()->get());
    }
}
