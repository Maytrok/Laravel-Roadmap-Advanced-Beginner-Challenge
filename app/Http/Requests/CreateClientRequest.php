<?php

namespace App\Http\Requests;

class CreateClientRequest extends BaseApiRequest
{

    public function rules()
    {
        return [
            "matchcode" => ["required"],
            "name" => ["required"],
            "street" => ["required"],
            "zip" => ["required"],
            "city" => ["required"],
            "email" => ["required", "email"],
            "phone" => ["required"],
        ];
    }
}
