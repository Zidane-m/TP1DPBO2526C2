# Tugas Praktikum 1 DPBO 2526 (TP1DPBO2526C2)
Sistem Manajemen Data Bioskop dalam 4 Bahasa Pemrograman (C++, Java, Python, PHP)

---

## 📌 Janji

> Saya [Isi Nama Anda] NIM [Isi NIM Anda] mengerjakan Tugas Praktikum 1 dalam mata kuliah Desain dan Pemrograman Berbasis Objek untuk keberkahan-Nya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

---

## 🏗️ Penjelasan Desain dan Kode (Flow Kode)

### 1. Desain Class & Enkapsulasi (Encapsulation)
Aplikasi ini menggunakan konsep **Object-Oriented Programming (OOP)** dengan pembungkusan data (*Encapsulation*) pada sebuah kelas bernama `Film`.

- **Atribut (Private)**:
  - `id` (integer) : Identitas unik film.
  - `judul` (string) : Judul film bioskop.
  - `genre` (string) : Genre dari film (misal: Action, Sci-Fi).
  - `durasi` (integer) : Durasi pemutaran film (dalam menit).
  - `gambar` (string) : Path lokasi file gambar lokal.

- **Method (Public)**:
  - **Constructor** : Menginisialisasi objek film baru.
  - **Getter & Setter** : Mengakses dan mengubah nilai atribut secara terenkapsulasi (`getId()`, `setId()`, `getJudul()`, `setJudul()`, dst.).
  - **tampilkanData()** : Memformat dan mencetak detail informasi objek film.

---

### 2. Arsitektur File Projek
Setiap bahasa dipisahkan secara tegas antara kode **Class** dan kode **Main**:
- **C++**: `CPP/Film.cpp` (Class) & `CPP/main.cpp` (Main CLI)
- **Java**: `Java/Film.java` (Class) & `Java/Main.java` (Main CLI)
- **Python**: `Python/Film.py` (Class) & `Python/main.py` (Main CLI)
- **PHP**: `PHP/Film.php` (Class) & `PHP/index.php` (Web Interface)

---

### 3. Alur Eksekusi Program (Flow Code)
Program mengelola sekumpulan objek `Film` menggunakan struktur *Array / List of Objects*. Alur logika berjalan sebagai berikut:

1. **Inisialisasi Data**: Program menyiapkan wadah penampung (*list/array of object*).
2. **Menu Utama (Looping Menu)**:
   - **1. Tambah Data**: Meminta input data film baru, melakukan validasi unik pada `id`, lalu membuat dan menambahkan objek `Film` ke daftar.
   - **2. Tampilkan Data**: Menelusuri seluruh daftar objek film dan memanggil method `tampilkanData()` (dilengkapi jeda layar *Enter* untuk kenyamanan CLI).
   - **3. Update Data**: Mencari film berdasarkan ID menggunakan perulangan tanpa `break`, lalu memperbarui data dengan setter jika ditemukan.
   - **4. Hapus Data**: Mencari posisi film berdasarkan ID tanpa `break` lalu menghapus objek tersebut dari daftar.
   - **5. Cari Data**: Mencari objek film berdasarkan ID tanpa `break` dan menampilkan detailnya jika ditemukan.
   - **6. Keluar**: Menutup dan mengakhiri eksekusi program.

---

## 📷 Dokumentasi Program (Hasil Output)

*Ruang (space) untuk menambahkan gambar screenshot / screenrecord bahwa program berhasil berjalan pada ke-4 bahasa pemrograman:*

### 1. C++ (Terminal CLI)
![Output C++](Dokumentasi/cpp_output.png)
*(Tempatkan screenshot hasil eksekusi program C++ di folder `Dokumentasi/cpp_output.png`)*

---

### 2. Java (Terminal CLI)
![Output Java](Dokumentasi/java_output.png)
*(Tempatkan screenshot hasil eksekusi program Java di folder `Dokumentasi/java_output.png`)*

---

### 3. Python (Terminal CLI)
![Output Python](Dokumentasi/python_output.png)
*(Tempatkan screenshot hasil eksekusi program Python di folder `Dokumentasi/python_output.png`)*

---

### 4. PHP (Web Interface)
![Output PHP](Dokumentasi/php_output.png)
*(Tempatkan screenshot hasil eksekusi halaman web PHP di folder `Dokumentasi/php_output.png`)*