@extends('layouts.app')

@section('title', 'Edit Transaksi Keuangan')

@section('content')
<div class="max-w-2xl mx-auto">

    <!-- Back Button & Header -->
    <div class="mb-6">
        <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 mb-2">
            &larr; Kembali ke Riwayat Transaksi
        </a>
        <h2 class="text-2xl font-bold text-slate-900">Edit Transaksi</h2>
        <p class="text-sm text-slate-500">Koreksi rincian transaksi. Sistem akan mengembalikan saldo lama dan menerapkan nominal baru secara otomatis.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Toggle Jenis Transaksi -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Jenis Transaksi <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex items-center justify-center p-3.5 rounded-xl border-2 cursor-pointer transition text-center
                        {{ old('type', $transaction->type) === 'expense' ? 'border-rose-500 bg-rose-50/50 text-rose-700 font-bold' : 'border-slate-200 hover:bg-slate-50 text-slate-600' }}"
                        id="label-expense">
                        <input type="radio" name="type" value="expense" class="sr-only" 
                               {{ old('type', $transaction->type) === 'expense' ? 'checked' : '' }}
                               onchange="updateType('expense')">
                        <span class="flex items-center gap-2">
                            <span>📉</span>
                            <span>Pengeluaran (Uang Keluar)</span>
                        </span>
                    </label>

                    <label class="relative flex items-center justify-center p-3.5 rounded-xl border-2 cursor-pointer transition text-center
                        {{ old('type', $transaction->type) === 'income' ? 'border-emerald-500 bg-emerald-50/50 text-emerald-700 font-bold' : 'border-slate-200 hover:bg-slate-50 text-slate-600' }}"
                        id="label-income">
                        <input type="radio" name="type" value="income" class="sr-only" 
                               {{ old('type', $transaction->type) === 'income' ? 'checked' : '' }}
                               onchange="updateType('income')">
                        <span class="flex items-center gap-2">
                            <span>📈</span>
                            <span>Pemasukan (Uang Masuk)</span>
                        </span>
                    </label>
                </div>
                @error('type')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nominal Transaksi -->
            <div>
                <label for="amount" class="block text-sm font-semibold text-slate-700 mb-1">
                    Nominal Transaksi (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-sm font-bold text-slate-400">Rp</span>
                    <input type="number" step="any" min="0.01" name="amount" id="amount" 
                           value="{{ old('amount', $transaction->amount) }}" required
                           class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-lg font-bold text-slate-900 outline-none transition">
                </div>
                @error('amount')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Akun Bank & Kategori (2 Kolom) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Pilihan Akun Bank -->
                <div>
                    <label for="account_id" class="block text-sm font-semibold text-slate-700 mb-1">
                        Akun Bank / Dompet <span class="text-rose-500">*</span>
                    </label>
                    <select name="account_id" id="account_id" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none transition bg-white">
                        @foreach ($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ old('account_id', $transaction->account_id) == $acc->id ? 'selected' : '' }}>
                                {{ $acc->name }} (Saldo: Rp {{ number_format($acc->current_balance, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @error('account_id')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pilihan Kategori Transaksi -->
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1">
                        Kategori Transaksi <span class="text-rose-500">*</span>
                    </label>
                    <select name="category_id" id="category_id" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none transition bg-white">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" data-type="{{ $cat->type }}" 
                                    {{ old('category_id', $transaction->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->display_icon }} {{ $cat->name }} ({{ $cat->type_label }})
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tanggal Transaksi -->
            <div>
                <label for="transaction_date" class="block text-sm font-semibold text-slate-700 mb-1">
                    Tanggal Transaksi <span class="text-rose-500">*</span>
                </label>
                <input type="date" name="transaction_date" id="transaction_date" 
                       value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none transition">
                @error('transaction_date')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan / Keterangan -->
            <div>
                <label for="notes" class="block text-sm font-semibold text-slate-700 mb-1">
                    Catatan / Keterangan <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                </label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none transition">{{ old('notes', $transaction->notes) }}</textarea>
                @error('notes')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('transactions.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 font-semibold text-sm transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-sm transition">
                    Perbarui Transaksi
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function updateType(type) {
        const labelExpense = document.getElementById('label-expense');
        const labelIncome = document.getElementById('label-income');
        const categorySelect = document.getElementById('category_id');
        const options = categorySelect.querySelectorAll('option[data-type]');

        if (type === 'expense') {
            labelExpense.className = 'relative flex items-center justify-center p-3.5 rounded-xl border-2 cursor-pointer transition text-center border-rose-500 bg-rose-50/50 text-rose-700 font-bold';
            labelIncome.className = 'relative flex items-center justify-center p-3.5 rounded-xl border-2 cursor-pointer transition text-center border-slate-200 hover:bg-slate-50 text-slate-600';
        } else {
            labelIncome.className = 'relative flex items-center justify-center p-3.5 rounded-xl border-2 cursor-pointer transition text-center border-emerald-500 bg-emerald-50/50 text-emerald-700 font-bold';
            labelExpense.className = 'relative flex items-center justify-center p-3.5 rounded-xl border-2 cursor-pointer transition text-center border-slate-200 hover:bg-slate-50 text-slate-600';
        }

        options.forEach(opt => {
            if (opt.getAttribute('data-type') === type) {
                opt.style.display = 'block';
            } else {
                opt.style.display = 'none';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const currentType = document.querySelector('input[name="type"]:checked')?.value || 'expense';
        updateType(currentType);
    });
</script>
@endsection

