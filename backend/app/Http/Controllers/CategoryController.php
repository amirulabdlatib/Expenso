<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCategoryRequest;

class CategoryController extends Controller
{
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        Category::create($data);

        return response()->json([
            'message' => 'Category created successfully.'
        ],Response::HTTP_CREATED);
    }
}
