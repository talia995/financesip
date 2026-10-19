<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Display a listing of accounts with total balance summary.
     */
    public function index(): View
    {
        $accounts = Account::query()
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();

        $totalBalance = $accounts->where('is_active', true)->sum('current_balance');

        return view('accounts.index', [
            'accounts' => $accounts,
            'totalBalance' => $totalBalance,
        ]);
    }

    /**
     * Show the form for creating a new account.
     */
    public function create(): View
    {
        return view('accounts.create');
    }

    /**
     * Store a newly created account in storage.
     */
    public function store(StoreAccountRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['current_balance'] = $validated['initial_balance'];
        $validated['is_active'] = true;

        Account::create($validated);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Akun bank/dompet berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified account.
     */
    public function edit(Account $account): View
    {
        return view('accounts.edit', [
            'account' => $account,
        ]);
    }

    /**
     * Update the specified account in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account): RedirectResponse
    {
        $account->update($request->validated());

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Akun bank/dompet berhasil diperbarui!');
    }

    /**
     * Remove the specified account from storage.
     */
    public function destroy(Account $account): RedirectResponse
    {
        $account->delete();

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Akun bank/dompet berhasil dihapus!');
    }
}

