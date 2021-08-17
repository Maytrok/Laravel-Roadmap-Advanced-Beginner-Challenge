<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientOverviewResource;
use App\Models\Client;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    public function index()
    {
        return ClientOverviewResource::collection(Client::all());
    }

    public function store(CreateClientRequest $request)
    {
        return Client::create($request->validated());
    }

    public function show(Client $client)
    {
        return $client;
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update($request->validated());
        return $client;
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return response("", Response::HTTP_NO_CONTENT);
    }
}
