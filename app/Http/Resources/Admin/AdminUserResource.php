<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $permissions = $this->getAllPermissions();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nickName' => $this->nickName,
            'email' => $this->email,
            'roles' => $this->roles,
            'permissions' => $permissions,
            'open_id'=>$this->open_id,
            'avatar' =>$this->avatarUrl,
            'token'=>$this->token,
            'created_at'=>(string)optional($this->created_at)->toDateTimeString(),
            'updated_at'=>(string)optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
