<?php

namespace App\Http\Resources;

use App\System\Roles;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request)
    {
        $isAdmin = $request->user()->hasRole(Roles::SUPER_ADMIN);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "created_at" => $this->created_at,
            $this->mergeWhen($isAdmin, [
                "deleted_at" => $this->deleted_at
            ]),
            $this->mergeWhen($isAdmin || $request->user()->id == $this->id, [
                "roles"  => RoleResource::collection($this->roles),
                "permissions" => $this->permissions
            ])

        ];
    }
}
