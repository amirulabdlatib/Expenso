<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
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
    public function index(Request $request)
    {
        $accounts = Account::where('user_id', Auth::id())
            ->select(['id', 'name', 'current_balance', 'currency', 'type', 'icon', 'is_active'])
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($request->filter && $request->filter !== 'all', function ($query) use ($request) {
                if ($request->filter == 'active') {
                    return $query->where('is_active', true);
                } elseif ($request->filter == 'inactive') {
                    return $query->where('is_active', false);
                } else {
                    return $query;
                }
            })
            ->get();

        $activeAccounts = Account::where('user_id', Auth::id())
            ->where('is_active', true)
            ->count();

        $inactiveAccounts = Account::where('user_id', Auth::id())
            ->where('is_active', true)
            ->count();

        $totalBalance = Account::where('user_id', Auth::id())
            ->sum('current_balance');

        $activeAccountsBalance = Account::where('user_id', Auth::id())
            ->where('is_active', true)->sum('current_balance');


        return response()->json([
            'accounts' => $accounts,
            'totalBalance' => $totalBalance,
            'active_accounts' => $activeAccounts,
            'activeAccountsBalance' => $activeAccountsBalance,
            'inactiveAccounts' => $inactiveAccounts,
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
