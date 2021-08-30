<?php

namespace App\Http\Requests;


class CreateTaskRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            "description" => ["required"],
            "user_id" => ["sometimes", "exists:users,id"],
            "project_id" => ["required", "exists:projects,id"],
            "completed" => ["sometimes", "boolean"]
        ];
    }
}
