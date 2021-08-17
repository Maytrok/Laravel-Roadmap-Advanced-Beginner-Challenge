<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientOverviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "name" => $this->matchcode,
            "street" => $this->street,
            "zip" => $this->zip,
            "city" => $this->city
        ];
    }
}
