<?php

namespace App\Http\Controllers\Lookup;

use App\Models\Category;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class ParentCategoryLookupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $categories = Category::query()
            ->forCurrentUser()
            ->whereNull('parent_id')
            ->select(['id', 'name', 'type'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'categories' => $categories,
        ], Response::HTTP_OK);
    }
}
