# Skema Database & Relasi Model (Laravel Eloquent)

Dokumen ini mendefinisikan struktur tabel basis data untuk aplikasi **Jurnal Keuangan Pribadi**, lengkap dengan tipe data, indeks, dan relasi Eloquent di Laravel.

---

## 1. Tabel Fase 1 (Core MVP)

### A. Tabel `accounts` (Master Bank & Dompet)
Menyimpan daftar rekening bank, dompet digital, atau uang tunai yang dimiliki.

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED (PK)` | Auto increment primary key |
| `name` | `VARCHAR(100)` | Nama akun (contoh: BCA Utama, Mandiri Tabungan, GoPay, Tunai) |
| `type` | `ENUM('bank', 'e-wallet', 'cash')` | Jenis akun |
| `account_number` | `VARCHAR(50)` | Nomor rekening / ID akun (opsional/nullable) |
| `initial_balance` | `DECIMAL(15, 2)` | Saldo awal saat akun pertama kali didaftarkan |
| `current_balance` | `DECIMAL(15, 2)` | Saldo berjalan saat ini (terkalkulasi dari transaksi) |
| `is_active` | `BOOLEAN` | Default `true`, untuk soft-archive akun yang tidak dipakai |
| `notes` | `TEXT` | Catatan tambahan (opsional/nullable) |
| `created_at` | `TIMESTAMP` | Waktu dibuat |
| `updated_at` | `TIMESTAMP` | Waktu diperbarui |

### B. Tabel `categories` (Kategori Transaksi)
Menyimpan pos-pos kategori pemasukan dan pengeluaran.

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED (PK)` | Auto increment primary key |
| `name` | `VARCHAR(100)` | Nama kategori (contoh: Makan & Minum, Gaji, Transport) |
| `type` | `ENUM('income', 'expense')` | Golongan kategori: Pemasukan atau Pengeluaran |
| `icon` | `VARCHAR(50)` | Nama icon (opsional/nullable, misal icon Heroicons) |
| `color` | `VARCHAR(20)` | Kode warna badge (opsional/nullable, misal `#10B981`) |
| `created_at` | `TIMESTAMP` | Waktu dibuat |
| `updated_at` | `TIMESTAMP` | Waktu diperbarui |

### C. Tabel `transactions` (Pencatatan Transaksi)
Menyimpan setiap mutasi dana masuk atau keluar.

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED (PK)` | Auto increment primary key |
| `account_id` | `BIGINT UNSIGNED (FK)` | Relasi ke `accounts.id` (onDelete: restrict) |
| `category_id` | `BIGINT UNSIGNED (FK)` | Relasi ke `categories.id` (onDelete: restrict) |
| `type` | `ENUM('income', 'expense')` | Jenis transaksi |
| `amount` | `DECIMAL(15, 2)` | Nominal mutasi (selalu bernilai positif > 0) |
| `transaction_date` | `DATE` | Tanggal terjadinya transaksi |
| `notes` | `TEXT` | Keterangan transaksi (opsional/nullable) |
| `created_at` | `TIMESTAMP` | Waktu pencatatan sistem |
| `updated_at` | `TIMESTAMP` | Waktu pembaruan data |

---

## 2. Tabel Fase 2 (Fitur Spesial & Hutang Piutang)

### D. Tabel `allocations` (Alokasi Tabungan / Pos Dana)
Untuk mencatat alokasi dana simpanan yang terkadang dipinjam untuk kebutuhan harian.

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED (PK)` | Auto increment primary key |
| `name` | `VARCHAR(100)` | Nama alokasi (contoh: Dana Darurat, Dana Liburan) |
| `target_amount` | `DECIMAL(15, 2)` | Target dana yang ingin dikumpulkan (opsional) |
| `allocated_balance` | `DECIMAL(15, 2)` | Saldo riil yang tersimpan di alokasi ini |
| `borrowed_amount` | `DECIMAL(15, 2)` | Total dana yang saat ini sedang dipinjam ke operasional |
| `created_at` | `TIMESTAMP` | Waktu dibuat |
| `updated_at` | `TIMESTAMP` | Waktu diperbarui |

### E. Tabel `allocation_mutations` (Riwayat Pinjam & Pelunasan Alokasi)
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED (PK)` | Auto increment primary key |
| `allocation_id` | `BIGINT UNSIGNED (FK)` | Relasi ke `allocations.id` |
| `account_id` | `BIGINT UNSIGNED (FK)` | Akun bank yang menerima pinjaman / sumber pelunasan |
| `type` | `ENUM('deposit', 'borrow', 'repay')` | `deposit` (isi alokasi), `borrow` (pinjam ke akun), `repay` (kembalikan saat gajian) |
| `amount` | `DECIMAL(15, 2)` | Nominal mutasi |
| `date` | `DATE` | Tanggal mutasi |
| `notes` | `TEXT` | Keterangan |
| `created_at` | `TIMESTAMP` | Waktu dibuat |

### F. Tabel `debts` (Hutang & Piutang Eksternal)
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED (PK)` | Auto increment primary key |
| `contact_name` | `VARCHAR(100)` | Nama pihak yang meminjamkan / dipinjamkan |
| `type` | `ENUM('payable', 'receivable')` | `payable` = Hutang kita, `receivable` = Piutang kita |
| `total_amount` | `DECIMAL(15, 2)` | Total hutang/piutang |
| `remaining_amount`| `DECIMAL(15, 2)` | Sisa yang belum dilunasi |
| `due_date` | `DATE` | Jatuh tempo (opsional/nullable) |
| `status` | `ENUM('unpaid', 'partially_paid', 'paid')` | Status pelunasan |
| `notes` | `TEXT` | Catatan |
| `created_at` | `TIMESTAMP` | Waktu dibuat |
| `updated_at` | `TIMESTAMP` | Waktu diperbarui |

---

## 3. Relasi Model Eloquent Laravel

### `Account` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = [
        'name', 'type', 'account_number', 'initial_balance', 'current_balance', 'is_active', 'notes'
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
```

### `Category` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'type', 'icon', 'color'];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
```

### `Transaction` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'account_id', 'category_id', 'type', 'amount', 'transaction_date', 'notes'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
```

