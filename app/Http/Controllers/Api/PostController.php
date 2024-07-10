<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Storage;

use Illuminate\Http\Request;
use App\Http\Requests\Post\PutRequest;
use App\Http\Requests\Post\StoreRequest;
use App\Models\Post;

class PostController extends Controller
{

    public function all()
    {

        // if (cache()->has('post_index')) {
        //     return response()->json(cache()->get('post_index'));
        // } else {
        //     $posts = Post::get();
        //     cache()->put('post_index', $posts);
        //     return response()->json($posts);
        // }
        return Post::all();
        return response()->json(Cache::remember('posts_index', now()->addMinutes(10), function () {
            return Post::all();
        }));

    }

    public function index()
    {
        return response()->json(Post::with('category')->paginate(2));
    }

    public function store(StoreRequest $request)
    {
        return response()->json(Post::create($request->validated()));
    }

    public function show(Post $post)
    {
        return response()->json($post);
    }

    public function slug(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        return response()->json($post);
    }

    public function update(PutRequest $request, Post $post)
    {
        $post->update($request->validated());
        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json('ok');
    }


    function upload(Request $request, Post $post)
    {

        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,gif|max:10240'
        ]);

        Storage::disk('public_upload')->delete("image/" . $post->image);

        $data['image'] = $filename = time() . '.' . $request->image->extension();

        $request->image->move(public_path('image'), $filename);

        $post->update($data);

        return response()->json($post);
    }

}
