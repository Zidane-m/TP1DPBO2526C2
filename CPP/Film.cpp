#include <iostream>
#include <string>
using namespace std;

// Class Film digunakan untuk menyimpan data film
class Film
{
private:
    // Atribut dibuat private untuk menerapkan encapsulation
    int id;
    string judul;
    string genre;
    int durasi;
    string gambar;

    public:
        Film(){
        }
        // Mengambil nilai ID
        int getId(){
            return id;
        }
        // Mengambil nilai judul
        string getJudul(){
            return judul;
        }
        // Mengambil nilai genre
        string getGenre(){
            return genre;
        }
        // Mengambil nilai durasi
        int getDurasi(){
            return durasi;
        }
        // Mengambil nilai path gambar
        string getGambar(){
            return gambar;
        }

        // Mengubah nilai ID
        void setId(int idBaru){
            id = idBaru;
        }
        // Mengubah nilai judul
        void setJudul(string judulBaru){
            judul = judulBaru;
        }
        // Mengubah nilai genre
        void setGenre(string genreBaru){
            genre = genreBaru;
        }
        // Mengubah nilai durasi
        void setDurasi(int durasiBaru){
            durasi = durasiBaru;
        }
        // Mengubah nilai path gambar
        void setGambar(string gambarBaru){
            gambar = gambarBaru;
        }
        // Menampilkan seluruh data dari sebuah object Film
        void tampilkanData(){
            cout << "ID       : " << getId() << endl;
            cout << "Judul    : " << getJudul() << endl;
            cout << "Genre    : " << getGenre() << endl;
            cout << "Durasi   : " << getDurasi() << " menit" << endl;
            cout << "Gambar   : " << getGambar() << endl;
        }

        ~Film(){
        }
};
