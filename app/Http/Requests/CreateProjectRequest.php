<?php

namespace App\Http\Requests;

class CreateProjectRequest extends BaseApiRequest
{

    public function rules()
    {
        return [
            "name" => ["required"],
            "user_id" => ["required", "exists:users,id"],
            "client_id" => ["required", "exists:clients,id"],
            "deadline" => ["required", "date"]
        ];
    }
}
