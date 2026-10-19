@extends('layouts.app')

@section('title', 'Master Akun Bank & Dompet')

@section('content')
<div class="space-y-6">

    <!-- Top Summary Banner -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Card Total Saldo -->
        <div class="md:col-span-2 bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-emerald-400 uppercase tracking-wider mb-1">Total Saldo Bersih</p>
                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                        Rp {{ number_format($totalBalance, 0, ',', '.') }}
                    </h2>
                    <p class="text-xs text-slate-400 mt-2">Akumulasi seluruh saldo dari akun bank & dompet yang aktif</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-3xl">
                    💳
                </div>
            </div>
        </div>

        <!-- Card Total Akun -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Akun Terdaftar</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $accounts->where('is_active', true)->count() }}</span>
                    <span class="text-xs text-slate-500 font-medium">Akun Aktif</span>
                </div>
            </div>
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">{{ $accounts->where('is_active', false)->count() }} Akun Non-aktif</span>
                <a href="{{ route('accounts.create') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                    + Tambah Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Section Header & Add Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Daftar Akun Bank & Dompet</h3>
            <p class="text-sm text-slate-500">Kelola rekening bank, dompet digital, dan kas tunai agar dana tidak tercampur.</p>
        </div>
        <a href="{{ route('accounts.create') }}" 
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Akun Bank
        </a>
    </div>

    <!-- Account List Grid -->
    @if ($accounts->isEmpty())
        <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl mb-4">
                🏦
            </div>
            <h4 class="text-base font-bold text-slate-900 mb-1">Belum Ada Akun Bank</h4>
            <p class="text-sm text-slate-500 max-w-md mx-auto mb-6">
                Mulai dengan mendaftarkan rekening bank (BCA, Mandiri, dll.), e-wallet (GoPay, OVO), atau kas fisik beserta saldo awalnya.
            </p>
            <a href="{{ route('accounts.create') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-sm transition">
                + Tambah Akun Pertama Anda
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($accounts as $account)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:shadow-md transition flex flex-col justify-between relative group">
                    <div>
                        <!-- Header Card -->
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl shrink-0 
                                    {{ $account->type === 'bank' ? 'bg-blue-50 text-blue-600' : ($account->type === 'e-wallet' ? 'bg-purple-50 text-purple-600' : 'bg-amber-50 text-amber-600') }}">
                                    @if($account->type === 'bank') 🏛️ @elseif($account->type === 'e-wallet') 📱 @else 💵 @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-base leading-tight">{{ $account->name }}</h4>
                                    <span class="inline-block text-[11px] font-medium text-slate-500 mt-0.5">
                                        {{ $account->type_label }} &bull; {{ $account->account_number ?? 'Tanpa No. Rek' }}
                                    </span>
                                </div>
                            </div>
                            
                            @if (!$account->is_active)
                                <span class="text-[10px] bg-slate-100 text-slate-500 font-semibold px-2 py-0.5 rounded-md">
                                    Nonaktif
                                </span>
                            @endif
                        </div>

                        <!-- Current Balance -->
                        <div class="my-4 p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[11px] text-slate-500 font-medium block mb-0.5">Saldo Berjalan:</span>
                            <div class="text-2xl font-extrabold text-slate-900 tracking-tight">
                                Rp {{ number_format($account->current_balance, 0, ',', '.') }}
                            </div>
                            <span class="text-[11px] text-slate-400 block mt-1">
                                Saldo Awal: Rp {{ number_format($account->initial_balance, 0, ',', '.') }}
                            </span>
                        </div>

                        @if ($account->notes)
                            <p class="text-xs text-slate-500 line-clamp-2 mb-3 italic">
                                "{{ $account->notes }}"
                            </p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2 text-xs">
                        <a href="{{ route('accounts.edit', $account) }}" 
                           class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium transition">
                            ✏️ Edit
                        </a>
                        <form action="{{ route('accounts.destroy', $account) }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $account->name }}?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-3 py-1.5 rounded-lg text-rose-600 hover:text-rose-700 hover:bg-rose-50 font-medium transition">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection

