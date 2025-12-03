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
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpKernel\Exception\HttpException;


class TransactionController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 50;

        $transactions = Transaction::where('user_id', Auth::id())
            ->select([
                'id',
                'account_id',
                'category_id',
                'name',
                'debit',
                'credit',
                'transaction_date',
            ])
            ->with([
                'account:id,name,icon',
                'category:id,name,icon,color,type'
            ])
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($request->type && $request->type !== 'all', function ($query) use ($request) {
                if ($request->type === 'transfer') {
                    return $query->whereNull('category_id');
                }
                return $query->whereHas('category', function ($q) use ($request) {
                    $q->where('type', $request->type);
                });
            })
            ->when($request->categoryName && $request->categoryName !== 'all', function ($query) use ($request) {
                return $query->whereHas('category', function ($q) use ($request) {
                    $q->where('name', $request->categoryName);
                });
            })
            ->when($request->accountName && $request->accountName !== 'all', function ($query) use ($request) {
                return $query->whereHas('account', function ($q) use ($request) {
                    $q->where('name', $request->accountName);
                });
            })
            ->when($request->filter && in_array($request->filter, ['today', 'week', 'month']), function ($q) use ($request) {
                $now = now();
                return match ($request->filter) {
                    'today' => $q->whereDate('transaction_date', $now->toDateString()),
                    'week' => $q->whereBetween('transaction_date', [
                        $now->copy()->startOfWeek()->toDateString(),
                        $now->copy()->endOfWeek()->toDateString()
                    ]),
                    'month' => $q->whereBetween('transaction_date', [
                        $now->copy()->startOfMonth()->toDateString(),
                        $now->copy()->endOfMonth()->toDateString()
                    ]),
                    default => $q
                };
            })
            ->when($request->sort, function ($query) use ($request) {
                return match ($request->sort) {
                    'date-asc' => $query->oldest('transaction_date'),
                    'date-desc' => $query->latest('transaction_date'),
                    'amount-asc' => $query->orderByRaw('COALESCE(debit, credit) ASC'),
                    'amount-desc' => $query->orderByRaw('COALESCE(debit, credit) DESC'),
                    default => $query->latest('transaction_date')
                };
            }, function ($query) {
                return $query->latest('transaction_date');
            })
            ->paginate($perPage);

        $categories = Category::whereHas('transactions', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->select('name')
            ->distinct()
            ->orderBy('name')
            ->get();

        $accounts = Account::query()
            ->forCurrentUser()
            ->select(['id', 'name'])
            ->get();

        return response()->json([
            'transactions' => $transactions->items(),
            'pagination' => [
                'total' => $transactions->total(),
                'per_page' => $transactions->perPage(),
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'from' => $transactions->firstItem(),
                'to' => $transactions->lastItem(),
            ],
            'categories' => $categories,
            'accounts' => $accounts,
            'total_income' => Transaction::totalIncome(),
            'total_expenses' => Transaction::totalExpenses(),
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

        unset($data['amount']);
        unset($data['type']);

        return DB::transaction(function () use ($request, $type, $amount, $account_id, $data) {
            $account = Account::findOrFail($account_id);

            if ($request->hasFile('receipt_file')) {
                $data['receipt'] = $this->handleReceiptUpload($request->file('receipt_file'));
            }

            if ($type == TransactionType::Income->value) {
                $data['credit'] = $amount;
                $data['debit'] = null;
                $account->current_balance = $account->current_balance + $amount;
                $account->save();
                Transaction::create($data);
            } elseif ($type == TransactionType::Expense->value) {
                $data['debit'] = $amount;
                $data['credit'] = null;
                $account->current_balance = $account->current_balance - $amount;
                $account->save();
                Transaction::create($data);
            } elseif ($type == TransactionType::Transfer->value) {
                $transferPairId = Str::ulid();

                $sentTransaction = Transaction::create([
                    'user_id' => Auth::id(),
                    'account_id' => $account_id,
                    'category_id' => null,
                    'related_account_id' => $data['related_account_id'],
                    'name' => $data['name'],
                    'description' => "Transfer out: {$transferPairId} " . ($data['description'] ?? ''),
                    'debit' => $amount,
                    'credit' => null,
                    'transaction_date' => $data['transaction_date'],
                    'transfer_pair_id' => $transferPairId,
                    'receipt' => $data['receipt'] ?? null,
                ]);

                $receivedTransaction = Transaction::create([
                    'user_id' => Auth::id(),
                    'account_id' => $data['related_account_id'],
                    'category_id' => null,
                    'related_account_id' => $account_id,
                    'name' => $data['name'],
                    'description' => "Transfer in: {$transferPairId} " . ($data['description'] ?? ''),
                    'debit' => null,
                    'credit' => $amount,
                    'transaction_date' => $data['transaction_date'],
                    'transfer_pair_id' => $transferPairId,
                    'receipt' => $data['receipt'] ?? null,
                ]);

                $sentAccount = Account::findOrFail($sentTransaction->account_id);
                $receivedAccount = Account::findOrFail($receivedTransaction->account_id);

                $sentAccount->current_balance = $sentAccount->current_balance - $amount;
                $receivedAccount->current_balance = $receivedAccount->current_balance + $amount;

                $sentAccount->save();
                $receivedAccount->save();
            } else {
                throw new HttpException(Response::HTTP_BAD_REQUEST, 'Invalid transaction type.');
            }

            return response()->json([
                'message' => 'Transaction created.'
            ], Response::HTTP_CREATED);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

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
            'receipt' =>  $transaction->receipt,
        ], Response::HTTP_OK);
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('edit', $transaction);

        $type = $this->getTransactionType($transaction);

        if ($type == TransactionType::Transfer->value || $type == TransactionType::Loan->value) {
            throw new HttpException(
                Response::HTTP_FORBIDDEN,
                "{$type} transactions cannot be edited."
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
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        return DB::transaction(function () use ($request, $transaction) {
            $this->revertTransaction($transaction);
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            $amount = $data['amount'];
            $type = $data['type'];

            $account = Account::find($request->account_id);

            if ($type == TransactionType::Income->value) {
                $data['credit'] = $amount;
                $data['debit'] = null;
                $account->current_balance += $amount;
            } elseif ($type == TransactionType::Expense->value) {
                $data['debit'] = $amount;
                $data['credit'] = null;
                $account->current_balance -= $amount;
            }

            $account->save();

            $oldReceiptPath = $transaction->receipt;
            $shouldRemoveReceipt = $request->has('remove_receipt') && $request->remove_receipt == '1';
            $newReceiptFile = $request->file('receipt_file');

            // If user wants to remove receipt
            if ($shouldRemoveReceipt) {
                $data['receipt'] = null;
            }
            // If user uploads a new receipt
            elseif ($newReceiptFile) {
                $data['receipt'] = $this->handleReceiptUpload($newReceiptFile);
            }

            unset($data['amount']);
            unset($data['type']);
            unset($data['remove_receipt']);

            $updated = $transaction->update($data);

            if ($updated) {
                if (($shouldRemoveReceipt || $newReceiptFile) && $oldReceiptPath) {
                    Storage::disk('private')->delete($oldReceiptPath);
                }
            }

            return response()->json([
                'message' => 'Transaction updated successfully.'
            ], Response::HTTP_OK);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        DB::transaction(function () use ($transaction) {
            $this->revertTransaction($transaction);

            if ($transaction->receipt) {
                Storage::disk('private')->delete($transaction->receipt);
            }

            if ($transaction->transfer_pair_id != null) {
                $relatedTransaction = $transaction->relatedTransaction;
                if ($relatedTransaction) {
                    $this->revertTransaction($relatedTransaction);
                    $relatedTransaction->delete();
                }
            }

            $transaction->delete();
        });


        return response()->noContent();
    }

    public function getReceipt(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        if (!$transaction->receipt) {
            abort(404, 'Receipt not found');
        }

        $path = Storage::disk('private')->path($transaction->receipt);

        if (!file_exists($path)) {
            abort(404, 'Receipt file not found');
        }

        return response()->file($path);
    }

    public function getReceiptInfo(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        if (!$transaction->receipt) {
            abort(404, 'Receipt not found');
        }

        return response()->json([
            'filename' => basename($transaction->receipt),
            'path' => $transaction->receipt,
        ]);
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

        if ($transaction->loan_id) {
            return 'loan';
        }

        return 'income';
    }

    private function handleReceiptUpload($file): string
    {
        $user = Auth::user();
        $folderName = $user->id . '-' . Str::slug($user->name);

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $customFileName = Str::slug($originalName) . '-' . $timestamp . '.' . $extension;

        return $file->storeAs(
            'receipts/' . $folderName,
            $customFileName,
            'private'
        );
    }
}
