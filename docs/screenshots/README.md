# Template Screenshot Aplikasi

Folder ini berisi **placeholder** untuk screenshot fitur Kopi AJ. Ganti file PNG di sini dengan screenshot asli aplikasi, lalu README utama akan otomatis menampilkan gambar terbaru.

## Cara mengganti

1. Jalankan aplikasi secara lokal.
2. Ambil screenshot sesuai daftar di bawah.
3. Timpa file placeholder dengan nama file yang **sama persis**.
4. Commit dan push:

   ```bash
   git add docs/screenshots
   git commit -m "docs: perbarui screenshot aplikasi"
   git push
   ```

> Tidak perlu mengubah README selama nama file tetap sama.

## Daftar file

### Tampilan pelanggan (mobile)

| File | Fitur | Rasio yang disarankan |
| --- | --- | --- |
| `01-customer-menu.png` | Menu & katalog + kategori | 9:16 (720 x 1280) |
| `02-customer-cart.png` | Keranjang, catatan, rincian pembayaran | 9:16 (720 x 1280) |
| `03-customer-payment.png` | Pilihan pembayaran online / cash | 9:16 (720 x 1280) |
| `04-customer-cash-qr.png` | QR pembayaran cash | 9:16 (720 x 1280) |
| `05-customer-order-status.png` | Status / ringkasan pesanan | 9:16 (720 x 1280) |

### Panel admin & kasir (desktop)

| File | Fitur | Rasio yang disarankan |
| --- | --- | --- |
| `06-admin-login.png` | Halaman login | 16:10 (1600 x 1000) |
| `07-admin-dashboard.png` | Dashboard analitik | 16:10 (1600 x 1000) |
| `08-admin-orders.png` | Pesanan masuk | 16:10 (1600 x 1000) |
| `09-admin-order-detail.png` | Detail & konfirmasi pesanan | 16:10 (1600 x 1000) |
| `10-admin-scan-qr.png` | Scan QR pembayaran | 16:10 (1600 x 1000) |
| `11-admin-menus.png` | Kelola menu | 16:10 (1600 x 1000) |
| `12-admin-receipt.png` | Struk pembayaran | 16:10 (1600 x 1000) |

## Tips pengambilan screenshot

- Gunakan data contoh yang rapi dan tidak mengandung data pribadi asli.
- Sembunyikan token, API key, nomor telepon, email, dan informasi sensitif.
- Gunakan mode terang untuk tampilan pelanggan dan konsisten untuk panel admin.
- Ambil screenshot pada lebar viewport yang sama agar galeri terlihat serasi.
- Kompres gambar (mis. [Squoosh](https://squoosh.app/)) agar repository tetap ringan. Target di bawah 300 KB per gambar.
- Format PNG atau JPG didukung. Jika memakai JPG, sesuaikan ekstensi pada README.

## Preview placeholder

Placeholder dibuat dengan warna brand Kopi AJ (oranye dan cokelat) agar README tetap terlihat rapi sebelum screenshot asli tersedia.
