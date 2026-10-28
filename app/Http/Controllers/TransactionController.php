<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactionService
    ) {}

    /**
     * Tampilkan daftar transaksi dengan ringkasan pemasukan & pengeluaran.
     */
    public function index(Request $request): View
    {
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $selectedAccount = $request->input('account_id');
        $selectedType = $request->input('type');

        $query = Transaction::query()
            ->with(['account', 'category'])
            ->orderByDesc('transaction_date')
            ->orderByDesc('id');

        if ($selectedMonth) {
            $startDate = Carbon::parse($selectedMonth)->startOfMonth();
            $endDate = Carbon::parse($selectedMonth)->endOfMonth();
            $query->whereBetween('transaction_date', [$startDate, $endDate]);
        }

        if ($selectedAccount) {
            $query->where('account_id', $selectedAccount);
        }

        if ($selectedType) {
            $query->where('type', $selectedType);
        }

        // Kalkulasi ringkasan untuk filter yang dipilih
        $summaryQuery = clone $query;
        $allFiltered = $summaryQuery->get();

        $totalIncome = $allFiltered->where('type', 'income')->sum('amount');
        $totalExpense = $allFiltered->where('type', 'expense')->sum('amount');
        $netCashflow = $totalIncome - $totalExpense;

        $transactions = $query->paginate(15)->withQueryString();
        $accounts = Account::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('transactions.index', [
            'transactions' => $transactions,
            'accounts' => $accounts,
            'categories' => $categories,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netCashflow' => $netCashflow,
            'selectedMonth' => $selectedMonth,
            'selectedAccount' => $selectedAccount,
            'selectedType' => $selectedType,
        ]);
    }

    /**
     * Tampilkan form pencatatan transaksi baru.
     */
    public function create(): View
    {
        $accounts = Account::where('is_active', true)->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('transactions.create', [
            'accounts' => $accounts,
            'categories' => $categories,
        ]);
    }

    /**
     * Simpan transaksi baru dan update saldo rekening.
     */
    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $this->transactionService->createTransaction($request->validated());

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil disimpan dan saldo akun otomatis diperbarui!');
    }

    /**
     * Tampilkan form edit transaksi.
     */
    public function edit(Transaction $transaction): View
    {
        $accounts = Account::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('transactions.edit', [
            'transaction' => $transaction,
            'accounts' => $accounts,
            'categories' => $categories,
        ]);
    }

    /**
     * Perbarui data transaksi dan koreksi mutasi saldo.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $this->transactionService->updateTransaction($transaction, $request->validated());

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui dan saldo rekening telah dikoreksi!');
    }

    /**
     * Hapus transaksi dan kembalikan saldo ke posisi semula.
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        $this->transactionService->deleteTransaction($transaction);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus dan saldo rekening telah dikembalikan!');
    }
}

