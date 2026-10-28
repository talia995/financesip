<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * Simpan transaksi baru dan sesuaikan saldo akun bank terkait.
     */
    public function createTransaction(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $transaction = Transaction::create($data);
            $account = Account::findOrFail($data['account_id']);

            if ($data['type'] === 'income') {
                $account->increment('current_balance', $data['amount']);
            } else {
                $account->decrement('current_balance', $data['amount']);
            }

            return $transaction;
        });
    }

    /**
     * Perbarui transaksi yang ada dan koreksi saldo akun lama serta akun baru.
     */
    public function updateTransaction(Transaction $transaction, array $newData): Transaction
    {
        return DB::transaction(function () use ($transaction, $newData) {
            // 1. Revert pengaruh transaksi lama dari akun lama
            $oldAccount = Account::findOrFail($transaction->account_id);
            if ($transaction->type === 'income') {
                $oldAccount->decrement('current_balance', $transaction->amount);
            } else {
                $oldAccount->increment('current_balance', $transaction->amount);
            }

            // 2. Terapkan data baru ke transaksi
            $transaction->update($newData);

            // 3. Apply pengaruh transaksi baru ke akun baru (bisa akun yang sama atau berbeda)
            $newAccount = Account::findOrFail($newData['account_id']);
            if ($newData['type'] === 'income') {
                $newAccount->increment('current_balance', $newData['amount']);
            } else {
                $newAccount->decrement('current_balance', $newData['amount']);
            }

            return $transaction;
        });
    }

    /**
     * Hapus transaksi dan kembalikan saldo akun seperti semula.
     */
    public function deleteTransaction(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $account = Account::findOrFail($transaction->account_id);

            // Kembalikan saldo sebelum transaksi dihapus
            if ($transaction->type === 'income') {
                $account->decrement('current_balance', $transaction->amount);
            } else {
                $account->increment('current_balance', $transaction->amount);
            }

            $transaction->delete();
        });
    }
}

