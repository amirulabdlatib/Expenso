<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AccountController extends Controller
{

    use AuthorizesRequests;

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
            'totalBalance' => $accounts->sum('current_balance'),
            'active_accounts' => $activeAccounts->count(),
            'activeAccountsBalance' => $activeAccounts->sum('current_balance'),
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
        $data['current_balance'] = $data['initial_balance'];

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
        $this->authorize('view', $account);

        return response()->json([
            'account' => $account,
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        $this->authorize('update', $account);

        $data = $request->validated();

        if ($data['initial_balance'] !== $account->initial_balance) {
            $difference = $request->initial_balance - $account->initial_balance;
            $data['current_balance'] = $account->current_balance + $difference;
        }

        $account->update($data);

        return response()->json([
            "account" => $account,
        ],  Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $this->authorize('delete', $account);

        $account->delete();
        return response()->noContent();
    }

    public function getActiveAccountCount()
    {
        return Account::where('user_id', Auth::id())
            ->where('is_active', true)
            ->count();
    }
}
