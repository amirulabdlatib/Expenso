<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $categories = Category::where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->select(['id', 'name', 'type', 'icon', 'color', 'parent_id'])
            ->with('children:id,name,parent_id')
            ->get();

        return response()->json([
            'categories' => $categories,
        ], Response::HTTP_OK);
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
        ], Response::HTTP_CREATED);
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);
        $category->load(['parent', 'children']);

        return response()->json([
            'category' => $category,
        ], Response::HTTP_OK);
    }

    public function edit(Category $category)
    {
        $this->authorize('edit', $category);

        return response()->json([
            'category' => $category,
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);
        $validated_data = $request->validated();

        if ($category->children()->exists() && $request['type'] !== $category['type']) {
            $category->children()->update([
                'type' => $validated_data['type'],
            ]);
        }

        $category->update($validated_data);
        return response()->json([
            'message' => 'Category updated successfully.'
        ]);
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        if ($category->transactions()->exists()) {
            return response()->json([
                'message' => 'Cannot delete category because it has associated transactions.',
                'error' => 'This category is currently in use.'
            ], 422);
        }
        $category->delete();
        return response()->noContent();
    }
}
