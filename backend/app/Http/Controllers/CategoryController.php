<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id',Auth::id())
                                ->whereNull('parent_id')
                                ->select(['id', 'name', 'type', 'icon', 'color', 'parent_id'])
                                ->with('children:id,name,parent_id')
                                ->get();

        return response()->json([
            'categories' => $categories,
        ],Response::HTTP_OK);
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        if (!empty($data['parent_id'])) {

        $parent = Category::find($data['parent_id']);
        if ($parent && $parent->parent_id !== null) {
                return response()->json([
                    'message' => 'Cannot assign a child to another child category.'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        Category::create($data);

        return response()->json([
            'message' => 'Category created successfully.'
        ],Response::HTTP_CREATED);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->noContent();
    }
}
