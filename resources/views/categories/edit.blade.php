@extends('layouts.app')

@section('title', 'Edit Kategori Transaksi')

@section('content')
<div class="max-w-xl mx-auto">

    <!-- Back Button & Header -->
    <div class="mb-6">
        <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 mb-2">
            &larr; Kembali ke Daftar Kategori
        </a>
        <h2 class="text-2xl font-bold text-slate-900">Edit Kategori</h2>
        <p class="text-sm text-slate-500">Perbarui rincian nama atau ikon kategori.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Nama Kategori -->
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">
                    Nama Kategori <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none transition">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Kategori -->
            <div>
                <label for="type" class="block text-sm font-semibold text-slate-700 mb-1">
                    Jenis Kategori <span class="text-rose-500">*</span>
                </label>
                <select name="type" id="type" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none transition bg-white">
                    <option value="expense" {{ old('type', $category->type) == 'expense' ? 'selected' : '' }}>📉 Pengeluaran (Uang Keluar)</option>
                    <option value="income" {{ old('type', $category->type) == 'income' ? 'selected' : '' }}>📈 Pemasukan (Uang Masuk)</option>
                </select>
                @error('type')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ikon / Emoji -->
            <div>
                <label for="icon" class="block text-sm font-semibold text-slate-700 mb-1">
                    Ikon / Emoji <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                </label>
                <input type="text" name="icon" id="icon" value="{{ old('icon', $category->icon) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none transition text-center text-xl">
                
                <!-- Quick Emoji Picker -->
                <div class="mt-2.5 flex flex-wrap gap-2 text-xl">
                    <span class="text-xs text-slate-400 self-center mr-1">Pilih Cepat:</span>
                    @foreach(['🍔', '☕', '🚗', '🛒', '💡', '🎬', '💊', '📚', '💼', '🎁', '💻', '📈', '🏠', '✈️', '🤲'] as $emoji)
                        <button type="button" onclick="document.getElementById('icon').value='{{ $emoji }}'"
                                class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-200 border border-slate-200 flex items-center justify-center transition">
                            {{ $emoji }}
                        </button>
                    @endforeach
                </div>
                @error('icon')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('categories.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 font-semibold text-sm transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-sm transition">
                    Perbarui Kategori
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

