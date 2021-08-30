<?php

namespace App\Http\Requests;

class UpdateTaskRequest extends BaseApiRequest
{
    public function rules()
    {
        return
            [
                "completed" => ["sometimes", "boolean"],
                "description" => ["sometimes", "string"],
                "user_id" => ["sometimes", "exists:users,id"]
            ];
    }
}
