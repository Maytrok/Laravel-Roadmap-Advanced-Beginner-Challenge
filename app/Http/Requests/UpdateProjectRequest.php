<?php

namespace App\Http\Requests;

class UpdateProjectRequest extends BaseApiRequest
{
    public function rules()
    {
        return array_merge($this->getPatchRulesFromCreateRequest(new CreateProjectRequest()), ["status" => ["sometimes"]]);
    }
}
