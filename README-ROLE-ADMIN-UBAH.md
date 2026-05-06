# Menjalankan Migration & Mengubah Role User Menjadi Admin

## 1. Jalankan Migration

Pastikan kolom `role` sudah ditambahkan ke tabel `users`:

```bash
php artisan migrate
```

---

## 2. Ubah User Menjadi Admin

Gunakan **Laravel Tinker** untuk mengubah role user:

```bash
php artisan tinker
```

Lalu jalankan perintah berikut:

```php
$user = App\Models\User::first();
$user->update(['role' => 'admin']);
```

Keluar dari tinker:

```bash
exit
```

---

## Catatan

- Pastikan method `isAdmin()` tersedia di model `User`:

```php
public function isAdmin()
{
    return $this->role === 'admin';
}
```

- Jika ingin mengubah user tertentu (bukan user pertama), gunakan:

```php
$user = App\Models\User::where('email', 'email@example.com')->first();
$user->update(['role' => 'admin']);
```

- Perubahan ini langsung tersimpan ke database tanpa perlu restart aplikasi.

---

## Opsional: Mengembalikan Role ke User

```bash
php artisan tinker
```

```php
$user = App\Models\User::first();
$user->update(['role' => 'user']);
```
