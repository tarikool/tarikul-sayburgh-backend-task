<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'total_comments' => $this->comments->count(),
            'tags' => TagResource::collection($this->tags),
            'user' => $this->getUserInfo(),
        ];
    }



    public function getUserInfo()
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
        ];
    }
}
