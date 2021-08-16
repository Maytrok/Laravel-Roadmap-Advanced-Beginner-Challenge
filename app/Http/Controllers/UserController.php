<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\System\Roles;
use Hash;
use Illuminate\Http\Response;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware("role:" . Roles::SUPER_ADMIN)->only(["store", "destroy"]);
    }
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function store(CreateUserRequest $request)
    {
        $user = User::create(
            array_merge($request->validated(), ["password" => Hash::make($request->get("password"))])
        );
        return new UserResource($user);
    }
    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {

        abort_if(!$request->user()->hasRole(Roles::SUPER_ADMIN) && $request->user()->id != $user->id, Response::HTTP_FORBIDDEN, "You are not allowed to update this user");

        abort_if(!$user->update($request->validated()), 500, "User update failed");
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response("", Response::HTTP_NO_CONTENT);
    }
}
