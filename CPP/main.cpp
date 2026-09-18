#include <iostream>
#include <string>
#include "Film.cpp"
using namespace std;

int main(){
    // Array untuk menyimpan object-object Film
    Film daftarFilm[100];

    // Menyimpan jumlah data film yang digunakan
    int jumlahFilm = 0;

    // Variabel untuk menyimpan pilihan menu
    int pilihan;

    // Program akan terus berjalan selama
    // pengguna belum memilih menu keluar
    do{
        cout << "\n==================================" << endl;
        cout << "       PROGRAM DATA BIOSKOP       " << endl;
        cout << "==================================" << endl;
        cout << "1. Tambah Data" << endl;
        cout << "2. Tampilkan Data" << endl;
        cout << "3. Update Data" << endl;
        cout << "4. Hapus Data" << endl;
        cout << "5. Cari Data" << endl;
        cout << "6. Keluar" << endl;
        cout << "==================================" << endl;
        cout << "Pilih menu : ";
        cin >> pilihan;

        // 1. TAMBAH DATA
        if (pilihan == 1){
            // Mengecek apakah array masih memiliki tempat
            if (jumlahFilm >= 100){
                cout << "Data film sudah penuh!" << endl;
            }else{
                int id;
                string judul;
                string genre;
                int durasi;
                string gambar;

                bool idSudahAda = false;

                cout << "\n--- Tambah Data Film ---" << endl;

                // Memasukkan ID
                cout << "Masukkan ID       : ";
                cin >> id;

                // Mengecek apakah ID sudah digunakan
                for (int i = 0; i < jumlahFilm; i++){
                    if (daftarFilm[i].getId() == id){
                        idSudahAda = true;
                    }
                }

                // Jika ID sudah ada, data tidak ditambahkan
                if (idSudahAda == true){
                    cout << "ID sudah digunakan!" << endl;
                }
                else{
                    // Membersihkan enter dari input sebelumnya
                    cin.ignore();

                    // Memasukkan judul
                    cout << "Masukkan Judul    : ";
                    getline(cin, judul);

                    // Memasukkan genre
                    cout << "Masukkan Genre    : ";
                    getline(cin, genre);

                    // Memasukkan durasi
                    cout << "Masukkan Durasi   : ";
                    cin >> durasi;

                    // Mengecek apakah durasi valid
                    if (durasi <= 0){
                        cout << "Durasi harus lebih dari 0!" << endl;
                    }else{
                        // Membersihkan enter dari input sebelumnya
                        cin.ignore();

                        // Memasukkan path gambar
                        cout << "Masukkan Path Gambar : ";
                        getline(cin, gambar);

                        // Mengecek input yang kosong
                        if (judul == ""){
                            cout << "Judul tidak boleh kosong!" << endl;
                        }else if (genre == ""){
                            cout << "Genre tidak boleh kosong!" << endl;
                        }else if (gambar == ""){
                            cout << "Path gambar tidak boleh kosong!" << endl;
                        }else{
                            // Mengisi object menggunakan setter
                            daftarFilm[jumlahFilm].setId(id);
                            daftarFilm[jumlahFilm].setJudul(judul);
                            daftarFilm[jumlahFilm].setGenre(genre);
                            daftarFilm[jumlahFilm].setDurasi(durasi);
                            daftarFilm[jumlahFilm].setGambar(gambar);

                            // Menambah jumlah data
                            jumlahFilm++;

                            cout << "Data film berhasil ditambahkan!" << endl;
                        }
                    }
                }
            }
        }
        // 2. TAMPILKAN DATA
        else if (pilihan == 2){
            cout << "\n--- Data Film ---" << endl;

            // Mengecek apakah belum ada data
            if (jumlahFilm == 0)
            {
                cout << "Belum ada data film." << endl;
            }
            else
            {
                // Menampilkan seluruh data film
                for (int i = 0; i < jumlahFilm; i++)
                {
                    cout << "\nFilm ke-" << i + 1 << endl;
                    cout << "--------------------------" << endl;

                    // Memanggil method untuk menampilkan data
                    daftarFilm[i].tampilkanData();
                }
            }

            cout << "\nTekan Enter untuk kembali ke menu...";
            cin.ignore();
            cin.get();
        }


    
        // 3. UPDATE DATA
    

        else if (pilihan == 3)
        {
            int idCari;

            cout << "\n--- Update Data Film ---" << endl;
            cout << "Masukkan ID film yang ingin diubah : ";
            cin >> idCari;

            // Menyimpan posisi data yang ditemukan
            int posisi = -1;

            // Mencari film berdasarkan ID
            for (int i = 0; i < jumlahFilm; i++)
            {
                if (daftarFilm[i].getId() == idCari)
                {
                    posisi = i;
                }
            }

            // Jika posisi masih -1, berarti data tidak ditemukan
            if (posisi == -1)
            {
                cout << "Data film tidak ditemukan!" << endl;
            }
            else
            {
                string judulBaru;
                string genreBaru;
                int durasiBaru;
                string gambarBaru;

                cin.ignore();

                // Memasukkan data baru
                cout << "Masukkan Judul Baru    : ";
                getline(cin, judulBaru);

                cout << "Masukkan Genre Baru    : ";
                getline(cin, genreBaru);

                cout << "Masukkan Durasi Baru   : ";
                cin >> durasiBaru;

                // Mengecek durasi baru
                if (durasiBaru <= 0)
                {
                    cout << "Durasi harus lebih dari 0!" << endl;
                }
                else
                {
                    cin.ignore();

                    cout << "Masukkan Path Gambar Baru : ";
                    getline(cin, gambarBaru);

                    // Mengecek input yang kosong
                    if (judulBaru == "")
                    {
                        cout << "Judul tidak boleh kosong!" << endl;
                    }
                    else if (genreBaru == "")
                    {
                        cout << "Genre tidak boleh kosong!" << endl;
                    }
                    else if (gambarBaru == "")
                    {
                        cout << "Path gambar tidak boleh kosong!" << endl;
                    }
                    else
                    {
                        // Mengubah data menggunakan setter
                        daftarFilm[posisi].setJudul(judulBaru);
                        daftarFilm[posisi].setGenre(genreBaru);
                        daftarFilm[posisi].setDurasi(durasiBaru);
                        daftarFilm[posisi].setGambar(gambarBaru);

                        cout << "Data film berhasil diubah!" << endl;
                    }
                }
            }
        }
        // 4. HAPUS DATA
        else if (pilihan == 4)
        {
            int idHapus;

            cout << "\n--- Hapus Data Film ---" << endl;
            cout << "Masukkan ID film yang ingin dihapus : ";
            cin >> idHapus;

            // Menyimpan posisi data yang ditemukan
            int posisi = -1;

            // Mencari film berdasarkan ID
            for (int i = 0; i < jumlahFilm; i++)
            {
                if (daftarFilm[i].getId() == idHapus)
                {
                    posisi = i;
                }
            }

            // Jika data tidak ditemukan
            if (posisi == -1)
            {
                cout << "Data film tidak ditemukan!" << endl;
            }
            else
            {
                // Menggeser data setelah data yang dihapus
                // satu posisi ke kiri
                for (int i = posisi; i < jumlahFilm - 1; i++)
                {
                    daftarFilm[i] = daftarFilm[i + 1];
                }

                // Mengurangi jumlah data
                jumlahFilm--;

                cout << "Data film berhasil dihapus!" << endl;
            }
        }


    
        // 5. CARI DATA
    

        else if (pilihan == 5)
        {
            int idCari;

            cout << "\n--- Cari Data Film ---" << endl;
            cout << "Masukkan ID film : ";
            cin >> idCari;

            // Menyimpan posisi data yang ditemukan
            int posisi = -1;

            // Mencari data berdasarkan ID
            for (int i = 0; i < jumlahFilm; i++)
            {
                if (daftarFilm[i].getId() == idCari)
                {
                    posisi = i;
                }
            }

            // Mengecek apakah data ditemukan
            if (posisi == -1)
            {
                cout << "Data film tidak ditemukan!" << endl;
            }
            else
            {
                cout << "\nData film ditemukan!" << endl;
                cout << "--------------------------" << endl;

                // Menampilkan data film
                daftarFilm[posisi].tampilkanData();
            }

            cout << "\nTekan Enter untuk kembali ke menu...";
            cin.ignore();
            cin.get();
        }


    
        // 6. KELUAR
    

        else if (pilihan == 6)
        {
            cout << "\nProgram selesai." << endl;
        }


    
        // PILIHAN TIDAK VALID
    

        else
        {
            cout << "Pilihan menu tidak valid!" << endl;
            cout << "Silakan pilih menu 1 sampai 6." << endl;
        }

    } while (pilihan != 6);

    return 0;
}