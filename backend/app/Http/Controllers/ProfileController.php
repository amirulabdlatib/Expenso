<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function index()
    {
        $total_transactions = Transaction::forCurrentUser()
            ->count();

        $total_accounts = Account::forCurrentUser()
            ->count();

        return response()->json([
            'total_transactions' => $total_transactions,
            'total_accounts' => $total_accounts,
            'total_income' => Transaction::totalIncome(),
            'total_expenses' => Transaction::totalExpenses(),
            'net_balance' => Transaction::totalIncome() - Transaction::totalExpenses(),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = User::find(Auth::id());
        $validated = $request->validated();

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated.'
        ], Response::HTTP_OK);
    }

    public function deleteData() {}

    public function destroy() {}
}
