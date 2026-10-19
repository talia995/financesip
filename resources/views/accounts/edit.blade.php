@extends('layouts.app')

@section('title', 'Edit Akun Bank / Dompet')

@section('content')
<div class="max-w-2xl mx-auto">

    <!-- Back Button & Header -->
    <div class="mb-6">
        <a href="{{ route('accounts.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 mb-2">
            &larr; Kembali ke Daftar Akun
        </a>
        <h2 class="text-2xl font-bold text-slate-900">Edit Akun: {{ $account->name }}</h2>
        <p class="text-sm text-slate-500">Perbarui rincian informasi rekening atau sesuaikan status keaktifan akun.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        <form action="{{ route('accounts.update', $account) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Nama Akun -->
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">
                    Nama Akun / Bank <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $account->name) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none transition">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Akun & Nomor Rekening (2 Kolom) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="type" class="block text-sm font-semibold text-slate-700 mb-1">
                        Jenis Akun <span class="text-rose-500">*</span>
                    </label>
                    <select name="type" id="type" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none transition bg-white">
                        <option value="bank" {{ old('type', $account->type) == 'bank' ? 'selected' : '' }}>🏛️ Rekening Bank</option>
                        <option value="e-wallet" {{ old('type', $account->type) == 'e-wallet' ? 'selected' : '' }}>📱 E-Wallet / Dompet Digital</option>
                        <option value="cash" {{ old('type', $account->type) == 'cash' ? 'selected' : '' }}>💵 Kas Tunai / Dompet Fisik</option>
                    </select>
                    @error('type')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="account_number" class="block text-sm font-semibold text-slate-700 mb-1">
                        Nomor Rekening / ID <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                    </label>
                    <input type="text" name="account_number" id="account_number" value="{{ old('account_number', $account->account_number) }}"
                           placeholder="Contoh: 1234567890"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none transition">
                    @error('account_number')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info Saldo (Read-only notice) -->
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-slate-500 block">Saldo Berjalan Saat Ini</span>
                    <span class="text-lg font-bold text-slate-900">Rp {{ number_format($account->current_balance, 0, ',', '.') }}</span>
                </div>
                <span class="text-xs text-slate-400 max-w-[200px] text-right">
                    Saldo otomatis terkoreksi saat Anda mencatat transaksi pemasukan/pengeluaran.
                </span>
            </div>

            <!-- Status Keaktifan -->
            <div class="flex items-center gap-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" 
                       {{ old('is_active', $account->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                <label for="is_active" class="text-sm font-medium text-slate-700 cursor-pointer">
                    Akun Aktif (Dihitung dalam total saldo bersih)
                </label>
            </div>

            <!-- Catatan -->
            <div>
                <label for="notes" class="block text-sm font-semibold text-slate-700 mb-1">
                    Catatan <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                </label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none transition">{{ old('notes', $account->notes) }}</textarea>
                @error('notes')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('accounts.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 font-semibold text-sm transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-sm transition">
                    Perbarui Akun
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

