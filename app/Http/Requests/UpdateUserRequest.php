<?php

namespace App\Http\Requests;

class UpdateUserRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            "name" => "sometimes",
            "email" => "sometimes",
        ];
    }
}
