<?php

namespace App\Http\Requests;

class CreateUserRequest extends BaseApiRequest
{

    public function rules()
    {
        return [
            "name" => "required",
            "email" => "required",
            "password" => "required",
        ];
    }
}
