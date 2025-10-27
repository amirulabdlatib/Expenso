<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CategoryLookupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $categories = Category::where('user_id', Auth::id())
            ->select(['id', 'name', 'type'])
            ->get();

        return response()->json([
            'categories' => $categories,
        ], Response::HTTP_OK);
    }
}
