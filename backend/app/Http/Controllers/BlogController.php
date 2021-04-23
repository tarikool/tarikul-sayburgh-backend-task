<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blog\CommentRequest;
use App\Http\Requests\Blog\StoreRequest;
use App\Http\Requests\Blog\UpdateRequest;
use App\Http\Resources\BlogResource;
use App\Http\Resources\CommentResource;
use App\Models\Blog;
use App\Models\Comment;
use App\Traits\BlogTrait;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use BlogTrait;

    public function index()
    {
        $blogs = Blog::with('comments', 'tags', 'user')
            ->paginate(15);

        return response()->json([
            'status' => 1,
            'data' => BlogResource::collection($blogs)
        ]);
    }

    /**
     * @param StoreRequest $request
     * @param Blog $blog
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request, Blog $blog)
    {
        $tags = (array)$request->tag;

        $blog = $this->saveBlog($request, $blog);
        $this->attachTags($blog, $tags);

        return response()->json([
            'status' => 1,
            'data' => new BlogResource($blog)
        ]);

    }

    /**
     * @param Blog $blog
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Blog $blog)
    {
        return response()->json([
            'status' => 1,
            'data' => new BlogResource($blog)
        ]);
    }

    /**
     * @param UpdateRequest $request
     * @param Blog $blog
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Blog $blog)
    {
        $tags = (array)$request->tag;

        if ($blog->user_id == auth('sanctum')->id()){
            $blog = $this->saveBlog($request, $blog);
            $this->attachTags($blog, $tags);

            return response()->json([
                'status' => 1,
                'data' => new BlogResource($blog)
            ]);
        }


        return response()->json([
            'status' => 0,
            'errors' => 'Unauthorized action'
        ]);
    }

    /**
     * @param Blog $blog
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Blog $blog)
    {
        if ($blog->user_id == auth('sanctum')->id()){
            $blog->delete();
            return response()->json(['status' => 1]);
        }

        return response()->json([
            'status' => 0,
            'errors' => 'Unauthorized action'
        ]);

    }


    /**
     * Get all comments for a blog
     * @param Blog $blog
     * @return \Illuminate\Http\JsonResponse
     */
    public function blogComments(Blog $blog)
    {
        $comments = Comment::with('user')
            ->where('blog_id', $blog->id)
            ->paginate(15);

        return response()->json([
            'status' => 1,
            'data' => CommentResource::collection($comments)
        ]);
    }


    /**
     * Post Comment on a Blog
     * @param CommentRequest $request
     * @param Blog $blog
     */
    public function postComment(CommentRequest $request, Blog $blog)
    {
        $comment = new Comment();
        $comment->body = $request->body;
        $comment->blog_id = $blog->id;
        $comment->user_id = auth('sanctum')->id();
        $comment->save();

        return response()->json([
            'status' => 1,
            'data' => new CommentResource($comment)
        ]);

    }


    /**
     * @param CommentRequest $request
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateComment(CommentRequest $request, Comment $comment)
    {
        if ($comment->user_id == auth('sanctum')->id()){
            $comment->body = $request->body;
            $comment->save();

            return response()->json([
                'status' => 1,
                'data' => new CommentResource($comment)
            ]);
        }

        return response()->json([
            'status' => 0,
            'errors' => 'Unauthorized action'
        ]);
    }


    /**
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment(Comment $comment)
    {
        if ($comment->user_id == auth('sanctum')->id()){
            $comment->delete();
            return response()->json(['status' => 1]);
        }

        return response()->json([
            'status' => 0,
            'errors' => 'Unauthorized action'
        ]);
    }



}
