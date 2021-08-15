<?php

namespace App\Http\Requests;

class LoginRequest extends BaseApiRequest
{

    public function rules()
    {
        return [
            "username" => "required",
            "password" => "required",
        ];
    }
}
