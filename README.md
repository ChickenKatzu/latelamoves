# Movie App - Laravel 11 Technical Test

## 📋 Deskripsi
Aplikasi web pencarian film menggunakan OMDb API dengan fitur autentikasi, multi-language, infinite scroll, dan manajemen favorite movie.

## 🚀 Fitur Utama
- **Autentikasi** - Login dengan credential yang ditentukan
- **Pencarian Film** - Pencarian dengan multiple parameter
- **Infinite Scroll** - Load data film secara otomatis
- **Lazy Load** - Optimasi loading gambar
- **Multi Language** - Support EN/ID
- **Favorite Movies** - Tambah/hapus film favorit
- **Responsive Design** - Tampilan mobile-friendly

## 🛠 Library & Teknologi

### Backend
- **Laravel 11** - PHP Framework
- **Guzzle HTTP** - HTTP Client untuk OMDb API
- **Laravel UI** - Authentication scaffolding

### Frontend
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Axios** - HTTP Client
- **Vite** - Build tool

### Database
- **SQLite/MySQL** - Database untuk menyimpan favorites

## 🏗 Architecture

### MVC Pattern
- **Models** - FavoriteMovie model untuk interaksi database
- **Views** - Blade templates dengan layout system
- **Controllers** - Auth, Movie, Language controllers

### Repository Pattern (Implisit)
- API calls dipisahkan di controller
- Database queries menggunakan Eloquent ORM

### Middleware Pattern
- Authentication middleware untuk proteksi route
- Localization middleware untuk multi-language

## 📸 Screenshots

### Halaman Login
![Halaman Login](screenshots/login.png)
*Halaman login dengan credential default*

### Halaman List Movie
![List Movie](screenshots/movie-list.png)
*Grid film dengan infinite scroll dan lazy load*

### Detail Movie
![Detail Movie](screenshots/movie-detail.png)
*Informasi lengkap film dengan tombol favorite*

### Halaman Favorites
![Favorites](screenshots/favorites.png)
*Daftar film favorit dengan opsi hapus*

### Multi Language
![Multi Language](screenshots/multi-language.png)
*Switch bahasa Inggris/Indonesia*

### Empty State
![Empty State](screenshots/empty-state.png)
*Tampilan ketika data kosong*

## 💻 Instalasi

1. **Clone repository**
```bash
git clone [repository-url]
cd movie-app
