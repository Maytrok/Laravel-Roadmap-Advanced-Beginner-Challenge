<?php

namespace App\Http\Requests;

class UpdateClientRequest extends BaseApiRequest
{

    public function rules()
    {
        return $this->getPatchRulesFromCreateRequest(new CreateClientRequest());
    }
}
