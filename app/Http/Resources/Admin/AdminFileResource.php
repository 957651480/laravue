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
        $author = $this->author;
        return [
            'file_id'=>(integer)$this->file_id,
            'filename'=>(string)$this->filename,
            'source_filename'=>(string)$this->source_filename,
            'name'=>(string)$this->source_filename,
            'extension'=>(string)$this->extension,
            'size'=>(integer)$this->size,
            'mime_type'=>(string)$this->mime_type,
            'url'=>(string)$this->url,
            'author_id'=>(integer)$this->author_id,
            'author_name'=>(string)optional($author)->name,
            'created_at'=>(string)$this->created_at->toDateTimeString(),
            'updated_at'=>(string)$this->updated_at->toDateTimeString(),
        ];
    }
}
