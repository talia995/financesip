<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('db:copy-sqlite', function () {
    $sqlitePath = database_path('database.sqlite');

    if (! file_exists($sqlitePath)) {
        $this->error('File database.sqlite tidak ditemukan.');
        return 1;
    }

    config(['database.connections.sqlite_temp' => [
        'driver' => 'sqlite',
        'database' => $sqlitePath,
        'prefix' => '',
        'foreign_key_constraints' => false,
    ]]);

    try {
        $oldAccounts = DB::connection('sqlite_temp')->table('accounts')->get();
    } catch (\Throwable $e) {
        $this->error('Gagal membaca data dari SQLite: ' . $e->getMessage());
        return 1;
    }

    if ($oldAccounts->isEmpty()) {
        $this->warn('Tabel accounts di SQLite masih kosong (belum ada data tersimpan).');
        return 0;
    }

    $count = 0;
    foreach ($oldAccounts as $acc) {
        DB::table('accounts')->updateOrInsert(
            ['id' => $acc->id],
            (array) $acc
        );
        $count++;
    }

    $this->info("Berhasil menyalin {$count} data akun bank dari SQLite ke MySQL!");
    return 0;
})->purpose('Salin data tabel accounts dari SQLite ke MySQL');

Artisan::command('db:copy-sqlite', function () {
    $sqlitePath = database_path('database.sqlite');

    if (! file_exists($sqlitePath)) {
        $this->error('File database.sqlite tidak ditemukan.');
        return 1;
    }

    config(['database.connections.sqlite_temp' => [
        'driver' => 'sqlite',
        'database' => $sqlitePath,
        'prefix' => '',
        'foreign_key_constraints' => false,
    ]]);

    try {
        $oldAccounts = DB::connection('sqlite_temp')->table('accounts')->get();
    } catch (\Throwable $e) {
        $this->error('Gagal membaca data dari SQLite: ' . $e->getMessage());
        return 1;
    }

    if ($oldAccounts->isEmpty()) {
        $this->warn('Tabel accounts di SQLite masih kosong (belum ada data tersimpan).');
        return 0;
    }

    $count = 0;
    foreach ($oldAccounts as $acc) {
        DB::table('accounts')->updateOrInsert(
            ['id' => $acc->id],
            (array) $acc
        );
        $count++;
    }

    $this->info("Berhasil menyalin {$count} data akun bank dari SQLite ke MySQL!");
    return 0;
})->purpose('Salin data tabel accounts dari SQLite ke MySQL');

