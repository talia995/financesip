# Mini Project: Jurnal Keuangan Pribadi (FinanceSIP)

Aplikasi pencatatan jurnal keuangan pribadi untuk mengelola multi-akun bank, melacak arus kas (pemasukan & pengeluaran), serta mengelola pos alokasi dana tabungan yang dapat dipinjam secara terkontrol.

---

## 📚 Dokumentasi Teknis

Seluruh dokumentasi arsitektur dan aturan bisnis tersimpan rapi di dalam repositori ini:

- 🗄️ **[Skema Database & Relasi Eloquent](docs/database.md)**: Rancangan tabel `accounts`, `categories`, `transactions`, `allocations`, dan `debts`.
- ⚙️ **[Aturan Bisnis & Logika Perhitungan](docs/business_rules.md)**: Logika kalkulasi saldo, mekanisme pinjam tabungan & pelunasan gajian, serta validasi request.

---

## 🎯 Fitur & Tahapan Rilis

### Fase 1: Core MVP (Fokus Utama)
- [ ] **Master Akun Bank/Dompet:** CRUD akun bank (BCA, Mandiri, GoPay, Tunai) dengan pencatatan saldo awal.
- [ ] **Master Kategori:** Kategori pemasukan dan pengeluaran.
- [ ] **Transaksi Keuangan:** Tambah, ubah, dan hapus mutasi masuk/keluar terhubung ke akun bank terkait.
- [ ] **Otomatisasi Saldo:** Saldo akun bank bertambah/berkurang secara real-time.
- [ ] **Dashboard Ringkasan:** Total saldo keseluruhan, saldo per rekening, dan ringkasan bulanan.

### Fase 2: Fitur Lanjutan
- [ ] **Pos Alokasi Dana (Envelopes / Sinking Funds):** Pisahkan pos tabungan darurat atau liburan.
- [ ] **Fitur Pinjam Tabungan:** Ambil dana tabungan saat saldo operasional menipis dengan catatan kewajiban ganti.
- [ ] **Pelunasan Saat Gajian:** Kembalikan dana pinjaman ke pos tabungan secara otomatis.
- [ ] **Hutang & Piutang Eksternal:** Catatan pinjaman ke/dari pihak ketiga beserta status pelunasannya.

---

## 🛠️ Tech Stack Rekomendasi
- **Framework:** Laravel 11 / 12
- **Frontend / UI:** Blade + Tailwind CSS (atau Laravel Breeze / Livewire)
- **Database:** SQLite (lokal & tanpa konfigurasi server) atau MySQL
- **Architecture:** Controller + Service Layer (`TransactionService`)
