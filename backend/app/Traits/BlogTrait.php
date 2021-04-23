<?php

namespace App\Traits;

use App\Models\BlogTag;
use App\Models\Tag;

trait BlogTrait
{

    /**
     * @param $blog
     * @param $tags
     * Attach tags to a blog
     */
    public function attachTags($blog, $tags)
    {
        $data = [];

        foreach ($tags as $name) {
            $tag = Tag::firstOrCreate(['tag_name' => $name]);
            $data[] = $tag->id;
        }

        return $blog->tags()->sync($data);
    }


    /**
     * @param $request
     * Save blog properties
     */
    public function saveBlog($request, $blog)
    {
        $blog->title = $request->title;
        $blog->body = $request->body;
        $blog->user_id = $blog->user_id ?: auth('sanctum')->id();
        $blog->save();

        return $blog;
    }




}
