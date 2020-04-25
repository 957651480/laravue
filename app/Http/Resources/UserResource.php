<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nickName' => $this->nickName,
            'email' => $this->email,
            'roles' => [array_map(
                function ($role) {
                    return $role['name'];
                },
                $this->roles->toArray()
            )],
            'permissions' => array_map(
                function ($permission) {
                    return $permission['name'];
                },
                $this->getAllPermissions()->toArray()
            ),
            'open_id'=>$this->open_id,
            'avatar' =>$this->avatarUrl,
            'token'=>$this->token,
            'created_at'=>$this->created_at->toDateTimeString(),
            'updated_at'=>$this->updated_at->toDateTimeString(),
        ];
    }
}
