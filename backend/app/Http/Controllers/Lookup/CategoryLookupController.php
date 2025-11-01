<?php

namespace App\Http\Controllers\Lookup;

use App\Models\Category;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;


class CategoryLookupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $categories = Category::getCategories();

        return response()->json([
            'categories' => $categories,
        ], Response::HTTP_OK);
    }
}
