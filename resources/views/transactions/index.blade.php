@extends('layouts.app')

@section('title', 'Riwayat Transaksi Keuangan')

@section('content')
<div class="space-y-6">

    <!-- Top Summary Cashflow Banner -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Pemasukan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Pemasukan</p>
                <h3 class="text-2xl font-extrabold text-emerald-600 tracking-tight">
                    + Rp {{ number_format($totalIncome, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-slate-400">Periode terpilih</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl font-bold">
                📈
            </div>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Pengeluaran</p>
                <h3 class="text-2xl font-extrabold text-rose-600 tracking-tight">
                    - Rp {{ number_format($totalExpense, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-slate-400">Periode terpilih</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl font-bold">
                📉
            </div>
        </div>

        <!-- Selisih / Arus Kas Bersih -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-5 text-white shadow-md flex items-center justify-between relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl"></div>
            <div>
                <p class="text-xs font-semibold text-emerald-400 uppercase tracking-wider mb-1">Arus Kas Bersih (Net)</p>
                <h3 class="text-2xl font-extrabold tracking-tight {{ $netCashflow >= 0 ? 'text-white' : 'text-rose-400' }}">
                    {{ $netCashflow >= 0 ? '+' : '' }} Rp {{ number_format($netCashflow, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-slate-400">{{ $netCashflow >= 0 ? 'Surplus (Pemasukan > Pengeluaran)' : 'Defisit (Pengeluaran > Pemasukan)' }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center text-2xl font-bold">
                💰
            </div>
        </div>
    </div>

    <!-- Filter & Action Header -->
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Riwayat Mutasi Transaksi</h3>
                <p class="text-xs text-slate-500">Filter berdasarkan periode bulan, akun bank, atau jenis mutasi.</p>
            </div>
            <a href="{{ route('transactions.create') }}" 
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Catat Transaksi Baru
            </a>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('transactions.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-3 border-t border-slate-100">
            <div>
                <label for="month" class="block text-xs font-semibold text-slate-500 mb-1">Periode Bulan</label>
                <input type="month" name="month" id="month" value="{{ $selectedMonth }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            <div>
                <label for="account_id" class="block text-xs font-semibold text-slate-500 mb-1">Akun Bank / Dompet</label>
                <select name="account_id" id="account_id"
                        class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 outline-none bg-white">
                    <option value="">Semua Akun Bank</option>
                    @foreach ($accounts as $acc)
                        <option value="{{ $acc->id }}" {{ $selectedAccount == $acc->id ? 'selected' : '' }}>
                            {{ $acc->name }} (Rp {{ number_format($acc->current_balance, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="type" class="block text-xs font-semibold text-slate-500 mb-1">Jenis Transaksi</label>
                <select name="type" id="type"
                        class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 outline-none bg-white">
                    <option value="">Semua Jenis</option>
                    <option value="income" {{ $selectedType === 'income' ? 'selected' : '' }}>📈 Pemasukan Saja</option>
                    <option value="expense" {{ $selectedType === 'expense' ? 'selected' : '' }}>📉 Pengeluaran Saja</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="flex-1 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs transition">
                    Terapkan Filter
                </button>
                <a href="{{ route('transactions.index') }}" 
                   class="px-3 py-2 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 text-xs font-medium transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table of Transactions -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
        @if ($transactions->isEmpty())
            <div class="py-16 text-center">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center text-3xl mb-3">
                    📝
                </div>
                <h4 class="font-bold text-slate-800 text-base mb-1">Belum Ada Transaksi</h4>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mb-5">
                    Tidak ditemukan transaksi untuk filter yang dipilih. Mulai catat pengeluaran atau pemasukan pertama Anda!
                </p>
                <a href="{{ route('transactions.create') }}" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition">
                    + Catat Transaksi Baru
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/80 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Akun Bank</th>
                            <th class="px-5 py-3.5">Kategori</th>
                            <th class="px-5 py-3.5">Catatan</th>
                            <th class="px-5 py-3.5 text-right">Nominal</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($transactions as $tx)
                            <tr class="hover:bg-slate-50/70 transition">
                                <!-- Tanggal -->
                                <td class="px-5 py-4 whitespace-nowrap text-xs font-medium text-slate-700">
                                    {{ $tx->transaction_date->format('d M Y') }}
                                </td>

                                <!-- Akun Bank -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-800">
                                        @if($tx->account->type === 'bank') 🏛️ @elseif($tx->account->type === 'e-wallet') 📱 @else 💵 @endif
                                        {{ $tx->account->name }}
                                    </span>
                                </td>

                                <!-- Kategori -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-800">
                                        <span>{{ $tx->category->display_icon }}</span>
                                        <span>{{ $tx->category->name }}</span>
                                    </span>
                                </td>

                                <!-- Catatan -->
                                <td class="px-5 py-4 text-xs text-slate-500 max-w-xs truncate">
                                    {{ $tx->notes ?: '-' }}
                                </td>

                                <!-- Nominal -->
                                <td class="px-5 py-4 whitespace-nowrap text-right font-bold text-sm {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                </td>

                                <!-- Aksi -->
                                <td class="px-5 py-4 whitespace-nowrap text-center text-xs">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('transactions.edit', $tx) }}" 
                                           class="p-1.5 rounded-lg text-slate-400 hover:text-slate-800 hover:bg-slate-100 transition" title="Edit">
                                            ✏️
                                        </a>
                                        <form action="{{ route('transactions.destroy', $tx) }}" method="POST" 
                                              onsubmit="return confirm('Hapus transaksi ini? Saldo rekening akan dikembalikan otomatis.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($transactions->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $transactions->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection

