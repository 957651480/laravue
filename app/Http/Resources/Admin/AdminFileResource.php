<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'file_id'=>(integer)$this->file_id,
            'filename'=>(string)$this->filename,
            'source_filename'=>(string)$this->source_filename,
            'name'=>(string)$this->source_filename,
            'extension'=>(string)$this->extension,
            'size'=>(integer)$this->size,
            'mime_type'=>(string)$this->mime_type,
            'created_at'=>(string)$this->created_at,
            'updated_at'=>(string)$this->updated_at,
        ];
    }
}
