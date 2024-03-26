<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Category\PutRequest;
use App\Http\Requests\Category\StoreRequest;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function all()
    {
        return response()->json(Category::get());
    }

    public function index()
    {
        return response()->json(Category::paginate(10));
    }

    public function store(StoreRequest $request)
    {
        return response()->json(Category::create($request->validated()));
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }
    // public function slug(string $slug)
    public function slug(Category $category)
    {
        // $category = Category::where('slug', $slug)->firstOrFail();
        return response()->json($category);
    }

    public function update(PutRequest $request, Category $category)
    {
        $category->update($request->validated());
        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json('ok');
    }
}
