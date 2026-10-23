@extends('layouts.app')

@section('title', 'Master Kategori Transaksi')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Master Kategori Transaksi</h2>
            <p class="text-sm text-slate-500">Kelompokkan setiap pengeluaran dan pemasukan Anda untuk mempermudah evaluasi arus kas.</p>
        </div>
        <a href="{{ route('categories.create') }}" 
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Kategori Baru
        </a>
    </div>

    <!-- Dua Kolom: Pengeluaran vs Pemasukan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Kolom 1: Kategori Pengeluaran -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-sm">
                            📉
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">Kategori Pengeluaran</h3>
                            <p class="text-xs text-slate-400">Pos pengeluaran harian, bulanan & rutin</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                        {{ $expenseCategories->count() }} Kategori
                    </span>
                </div>

                @if ($expenseCategories->isEmpty())
                    <div class="py-10 text-center text-slate-400 text-sm">
                        <p>Belum ada kategori pengeluaran.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach ($expenseCategories as $cat)
                            <div class="py-3 flex items-center justify-between group hover:bg-slate-50/80 px-2 rounded-xl transition">
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center border border-slate-100">
                                        {{ $cat->display_icon }}
                                    </span>
                                    <div>
                                        <h4 class="font-semibold text-slate-800 text-sm">{{ $cat->name }}</h4>
                                        <span class="text-[11px] text-rose-500 font-medium">Pengeluaran</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('categories.edit', $cat) }}" 
                                       class="px-2.5 py-1.5 text-xs text-slate-500 hover:text-slate-800 hover:bg-slate-200/60 rounded-lg transition">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" 
                                          onsubmit="return confirm('Hapus kategori {{ $cat->name }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-2.5 py-1.5 text-xs text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Kolom 2: Kategori Pemasukan -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                            📈
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">Kategori Pemasukan</h3>
                            <p class="text-xs text-slate-400">Sumber pendapatan & penghasilan</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $incomeCategories->count() }} Kategori
                    </span>
                </div>

                @if ($incomeCategories->isEmpty())
                    <div class="py-10 text-center text-slate-400 text-sm">
                        <p>Belum ada kategori pemasukan.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach ($incomeCategories as $cat)
                            <div class="py-3 flex items-center justify-between group hover:bg-slate-50/80 px-2 rounded-xl transition">
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center border border-slate-100">
                                        {{ $cat->display_icon }}
                                    </span>
                                    <div>
                                        <h4 class="font-semibold text-slate-800 text-sm">{{ $cat->name }}</h4>
                                        <span class="text-[11px] text-emerald-600 font-medium">Pemasukan</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('categories.edit', $cat) }}" 
                                       class="px-2.5 py-1.5 text-xs text-slate-500 hover:text-slate-800 hover:bg-slate-200/60 rounded-lg transition">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" 
                                          onsubmit="return confirm('Hapus kategori {{ $cat->name }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-2.5 py-1.5 text-xs text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection

