# Acara 5 - Routing, .htaccess, Request dan Response

## Struktur
```
public/.htaccess      -> teruskan semua request ke index.php
public/index.php      -> front controller + pencocokan rute
routes/web.php        -> array rute (GET/POST)
config/app.php        -> helper base_path(), url(), redirect(), e()
app/Controllers/      -> HomeController, MahasiswaController, AuthController
```

## URL yang bisa diuji
/  |  /mahasiswa  |  /mahasiswa?q=budi  |  /mahasiswa/create
/mahasiswa/5 (tugas mandiri)  |  /mahasiswa/5/edit  |  /login
URL yang tidak ada -> 404

Buka: http://localhost/<folder-project>/public/
