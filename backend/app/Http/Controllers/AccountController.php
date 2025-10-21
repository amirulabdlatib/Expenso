<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            'accounts' => $accounts,
            'totalBalance' => $accounts->sum('balance'),
            'active_accounts' => $activeAccounts->count(),
            'activeAccountsBalance' => $activeAccounts->sum('balance'),
            'inactiveAccounts' => $inactiveAccounts->count(),
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        Account::create($data);

        return response()->json([
            'message' => 'Account created successfully.',
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        return response()->json([
            'account' => $account,
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $account->update($data);

        return response()->json([
            "account"=> $account,
        ],  Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();
        return response()->noContent();
    }

    public function getActiveAccountCount(Account $account)
    {
        return $account->where('user_id',Auth::id())
                        ->where('is_active',true)
                        ->count();
    }
}
