<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreBudgetRequest;

class BudgetController extends Controller
{

    public function index() {}

    public function show() {}

    public function store(StoreBudgetRequest $request)
    {
        $validated_data = $request->validated();
        $validated_data['user_id'] = Auth::id();

        Budget::create($validated_data);

        return response()->json([
            'message' => 'Budget created.'
        ]);
    }

    public function update() {}

    public function destroy() {}
}
