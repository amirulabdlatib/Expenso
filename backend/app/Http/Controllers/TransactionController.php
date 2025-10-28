<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Account;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with([
                'accounts:name,icon',
                'categories:name,icon,color,type'
            ])->get();

        $total_income = Transaction::totalIncome();
        $total_expenses = Transaction::totalExpenses();
        $total_transaction_this_month = Transaction::where('user_id', Auth::id())
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        return response()->json([
            'transactions' => $transactions,
            'total_income' => $total_income,
            'total_expenses' => $total_expenses,
            'total_transaction_this_month' => $total_transaction_this_month,
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $amount = $data['amount'];
        $type = $data['type'];

        $account_id = $data['account_id'];
        $account = Account::find($account_id);

        unset($data['amount']);
        unset($data['type']);

        if ($type == TransactionType::Income->value) {
            $data['credit'] = $amount;
            $account->balance = $account->balance + $amount;
            $account->save();
        } elseif ($type == TransactionType::Expense->value) {
            $data['debit'] = $amount;
            $account->balance = $account->balance - $amount;
            $account->save();
        } elseif ($type == TransactionType::Transfer->value) {
            return response()->noContent();
        } else {
            return response()->noContent();
        }

        Transaction::create($data);

        return response()->json([
            'message' => ' Transaction created.'
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
