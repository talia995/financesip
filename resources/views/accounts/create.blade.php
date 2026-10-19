@extends('layouts.app')

@section('title', 'Tambah Akun Bank / Dompet')

@section('content')
<div class="max-w-2xl mx-auto">

    <!-- Back Button & Header -->
    <div class="mb-6">
        <a href="{{ route('accounts.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 mb-2">
            &larr; Kembali ke Daftar Akun
        </a>
        <h2 class="text-2xl font-bold text-slate-900">Tambah Akun Bank / Dompet</h2>
        <p class="text-sm text-slate-500">Daftarkan rekening atau e-wallet baru beserta saldo awal yang dimiliki.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        <form action="{{ route('accounts.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Nama Akun -->
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">
                    Nama Akun / Bank <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       placeholder="Contoh: BCA Utama, Mandiri Tabungan, GoPay, Dompet Tunai"
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
                        <option value="bank" {{ old('type') == 'bank' ? 'selected' : '' }}>🏛️ Rekening Bank</option>
                        <option value="e-wallet" {{ old('type') == 'e-wallet' ? 'selected' : '' }}>📱 E-Wallet / Dompet Digital</option>
                        <option value="cash" {{ old('type') == 'cash' ? 'selected' : '' }}>💵 Kas Tunai / Dompet Fisik</option>
                    </select>
                    @error('type')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="account_number" class="block text-sm font-semibold text-slate-700 mb-1">
                        Nomor Rekening / ID <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                    </label>
                    <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}"
                           placeholder="Contoh: 1234567890 / 08123456xxx"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none transition">
                    @error('account_number')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Saldo Awal -->
            <div>
                <label for="initial_balance" class="block text-sm font-semibold text-slate-700 mb-1">
                    Saldo Awal (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-sm font-bold text-slate-400">Rp</span>
                    <input type="number" step="any" min="0" name="initial_balance" id="initial_balance" 
                           value="{{ old('initial_balance', 0) }}" required
                           placeholder="0"
                           class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm font-semibold text-slate-800 outline-none transition">
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Saldo yang saat ini ada di rekening saat pertama kali dicatat.</p>
                @error('initial_balance')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan -->
            <div>
                <label for="notes" class="block text-sm font-semibold text-slate-700 mb-1">
                    Catatan <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                </label>
                <textarea name="notes" id="notes" rows="3"
                          placeholder="Contoh: Rekening khusus operasional belanja harian atau dana cadangan"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none transition">{{ old('notes') }}</textarea>
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
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

