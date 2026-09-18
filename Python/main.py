from Film import Film

def main():
    daftarFilm = []
    pilihan = 0

    while pilihan != 6:
        print("\n==================================")
        print("       PROGRAM DATA BIOSKOP       ")
        print("==================================")
        print("1. Tambah Data")
        print("2. Tampilkan Data")
        print("3. Update Data")
        print("4. Hapus Data")
        print("5. Cari Data")
        print("6. Keluar")
        print("==================================")
        
        try:
            pilihan = int(input("Pilih menu : "))
        except ValueError:
            print("Pilihan menu tidak valid! Silakan pilih menu 1 sampai 6.")
            continue

        # 1. TAMBAH DATA
        if pilihan == 1:
            print("\n--- Tambah Data Film ---")
            try:
                id_film = int(input("Masukkan ID       : "))
            except ValueError:
                print("ID harus berupa angka!")
                continue

            idSudahAda = False
            for f in daftarFilm:
                if f.getId() == id_film:
                    idSudahAda = True

            if idSudahAda:
                print("ID sudah digunakan!")
            else:
                judul = input("Masukkan Judul    : ").strip()
                genre = input("Masukkan Genre    : ").strip()
                
                try:
                    durasi = int(input("Masukkan Durasi   : "))
                except ValueError:
                    print("Durasi harus berupa angka!")
                    continue

                if durasi <= 0:
                    print("Durasi harus lebih dari 0!")
                else:
                    gambar = input("Masukkan Path Gambar : ").strip()

                    if not judul or not genre or not gambar:
                        print("Judul, genre, dan path gambar tidak boleh kosong!")
                    else:
                        film_baru = Film(id_film, judul, genre, durasi, gambar)
                        daftarFilm.append(film_baru)
                        print("Data film berhasil ditambahkan!")

        # 2. TAMPILKAN DATA
        elif pilihan == 2:
            print("\n--- Data Film ---")
            if len(daftarFilm) == 0:
                print("Belum ada data film.")
            else:
                for i in range(len(daftarFilm)):
                    print(f"\nFilm ke-{i + 1}")
                    print("--------------------------")
                    daftarFilm[i].tampilkanData()
            input("\nTekan Enter untuk kembali ke menu...")

        # 3. UPDATE DATA
        elif pilihan == 3:
            print("\n--- Update Data Film ---")
            try:
                id_cari = int(input("Masukkan ID film yang ingin diubah : "))
            except ValueError:
                print("ID harus berupa angka!")
                continue

            posisi = -1
            for i in range(len(daftarFilm)):
                if daftarFilm[i].getId() == id_cari:
                    posisi = i

            if posisi == -1:
                print("Data film tidak ditemukan!")
            else:
                judul_baru = input("Masukkan Judul Baru    : ").strip()
                genre_baru = input("Masukkan Genre Baru    : ").strip()
                try:
                    durasi_baru = int(input("Masukkan Durasi Baru   : "))
                except ValueError:
                    print("Durasi harus berupa angka!")
                    continue

                if durasi_baru <= 0:
                    print("Durasi harus lebih dari 0!")
                else:
                    gambar_baru = input("Masukkan Path Gambar Baru : ").strip()

                    if not judul_baru or not genre_baru or not gambar_baru:
                        print("Semua data tidak boleh kosong!")
                    else:
                        daftarFilm[posisi].setJudul(judul_baru)
                        daftarFilm[posisi].setGenre(genre_baru)
                        daftarFilm[posisi].setDurasi(durasi_baru)
                        daftarFilm[posisi].setGambar(gambar_baru)
                        print("Data film berhasil diubah!")

        # 4. HAPUS DATA
        elif pilihan == 4:
            print("\n--- Hapus Data Film ---")
            try:
                id_hapus = int(input("Masukkan ID film yang ingin dihapus : "))
            except ValueError:
                print("ID harus berupa angka!")
                continue

            posisi = -1
            for i in range(len(daftarFilm)):
                if daftarFilm[i].getId() == id_hapus:
                    posisi = i

            if posisi == -1:
                print("Data film tidak ditemukan!")
            else:
                daftarFilm.pop(posisi)
                print("Data film berhasil dihapus!")

        # 5. CARI DATA
        elif pilihan == 5:
            print("\n--- Cari Data Film ---")
            try:
                id_cari = int(input("Masukkan ID film : "))
            except ValueError:
                print("ID harus berupa angka!")
                continue

            posisi = -1
            for i in range(len(daftarFilm)):
                if daftarFilm[i].getId() == id_cari:
                    posisi = i

            if posisi == -1:
                print("Data film tidak ditemukan!")
            else:
                print("\nData film ditemukan!")
                print("--------------------------")
                daftarFilm[posisi].tampilkanData()

            input("\nTekan Enter untuk kembali ke menu...")

        # 6. KELUAR
        elif pilihan == 6:
            print("\nProgram selesai.")

        else:
            print("Pilihan menu tidak valid! Silakan pilih menu 1 sampai 6.")

if __name__ == "__main__":
    main()
