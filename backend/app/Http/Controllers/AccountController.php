<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $accounts = $user->accounts;

        $activeAccounts = $accounts->where('is_active', true);
        $inactiveAccounts = $accounts->where('is_active', false);

        return response()->json([
            'accounts' => $accounts->count(),
            'active_accounts' => $activeAccounts->count(),
            'activeAccountsBalance' => $activeAccounts->sum('balance'),
            'inactiveAccounts' => $inactiveAccounts->count(),
            'cashBalance' => $activeAccounts->sum('balance'),
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'icon' => 'required|string',
            'balance' => 'required|numeric',
            'currency' => 'required|string|max:7',
            'is_active' => 'required|boolean',
        ]);

        $validated_data['user_id'] = Auth::id();

        Account::create($validated_data);

        return response()->json([
            'message' => 'Account created successfully.'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
