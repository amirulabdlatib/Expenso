<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Enums\TransactionType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTransactionRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->select([
                'id',
                'account_id',
                'category_id',
                'name',
                'description',
                'debit',
                'credit',
                'transaction_date',
            ])
            ->with([
                'account:id,name,icon',
                'category:id,name,icon,color,type'
            ])
            ->latest()
            ->get();

        $total_income = Transaction::totalIncome();
        $total_expenses = Transaction::totalExpenses();
        $total_transaction = Transaction::where('user_id', Auth::id())
            ->count();

        return response()->json([
            'transactions' => $transactions,
            'total_income' => $total_income,
            'total_expenses' => $total_expenses,
            'total_transaction_this_month' => $total_transaction,
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
            $account->current_balance = $account->current_balance + $amount;
            $account->save();
            Transaction::create($data);
        } elseif ($type == TransactionType::Expense->value) {
            $data['debit'] = $amount;
            $account->current_balance = $account->current_balance - $amount;
            $account->save();
            Transaction::create($data);
        } elseif ($type == TransactionType::Transfer->value) {

            $transferPairId = Str::ulid();

            $sentTransaction = Transaction::create([
                'user_id' => Auth::id(),
                'account_id' => $account_id,
                'category_id' => $data['category_id'],
                'related_account_id' => $data['related_account_id'],
                'name' => $data['name'],
                'description' => "Transfer out: {$transferPairId} " . $data['description'],
                'debit' => $amount,
                'credit' => null,
                'transaction_date' => $data['transaction_date'],
                'transfer_pair_id' => $transferPairId,
            ]);

            $receivedTransaction = Transaction::create([
                'user_id' => Auth::id(),
                'account_id' => $data['related_account_id'],
                'category_id' => $data['category_id'],
                'related_account_id' => $account_id,
                'name' => $data['name'],
                'description' => "Transfer in: {$transferPairId} " . $data['description'],
                'debit' => null,
                'credit' => $amount,
                'transaction_date' => $data['transaction_date'],
                'transfer_pair_id' => $transferPairId,
            ]);

            $sentAccount = Account::find($sentTransaction->account_id);
            $receivedAccount = Account::find($receivedTransaction->account_id);

            $sentAccount->current_balance = $sentAccount->current_balance - $amount;
            $receivedAccount->current_balance = $receivedAccount->current_balance + $amount;

            $sentAccount->save();
            $receivedAccount->save();
        } else {
            return response()->noContent();
        }


        return response()->json([
            'message' => ' Transaction created.'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {

        $type = $this->getTransactionType($transaction);

        $amount = $transaction->debit ?? $transaction->credit;

        $transaction->load([
            'account:id,name,icon,currency',
            'category:id,name,icon,type',
            'relatedAccount:id,name,icon,currency'
        ]);

        return response()->json([
            'id' => $transaction->id,
            'type' => $type,
            'name' => $transaction->name,
            'description' => $transaction->description,
            'amount' => $amount,
            'transaction_date' => $transaction->transaction_date,
            'account' => $transaction->account,
            'category' => $transaction->category,
            'related_account' => $transaction->relatedAccount,
        ], Response::HTTP_OK);
    }

    public function edit(Transaction $transaction)
    {
        $type = $this->getTransactionType($transaction);

        if ($type == TransactionType::Transfer->value) {
            throw new HttpException(
                Response::HTTP_FORBIDDEN,
                'Transfer transactions cannot be edited.'
            );
        }

        $accounts = Account::getActiveAccounts();
        $categories = Category::getCategories();

        $transaction['amount'] = $transaction->credit ?? $transaction->debit;

        unset($transaction['credit']);
        unset($transaction['debit']);
        unset($transaction['created_at']);
        unset($transaction['updated_at']);

        $transaction->load([
            'account:id,name',
            'category:id,name,type',
        ]);

        return response()->json([
            'accounts' => $accounts,
            'categories' => $categories,
            'transaction' => $transaction,
        ], Response::HTTP_OK);
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
    public function destroy(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            $this->revertTransaction($transaction);

            if ($transaction->transfer_pair_id != null) {
                $relatedTransaction = $transaction->relatedTransaction;
                if ($relatedTransaction) {
                    $this->revertTransaction($relatedTransaction);
                }
                $relatedTransaction->delete();
            }

            $transaction->delete();
        });


        return response()->noContent();
    }

    private function revertTransaction(Transaction $transaction)
    {
        if ($transaction->debit != null) {
            $transaction->account->current_balance += $transaction->debit;
            $transaction->account->save();
        }
        if ($transaction->credit != null) {
            $transaction->account->current_balance -= $transaction->credit;
            $transaction->account->save();
        }
    }

    private function getTransactionType(Transaction $transaction): string
    {
        if ($transaction->transfer_pair_id) {
            return 'transfer';
        }

        if ($transaction->debit) {
            return 'expense';
        }

        return 'income';
    }
}
