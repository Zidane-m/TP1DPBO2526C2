import java.util.ArrayList;
import java.util.Scanner;

public class Main {
    public static void main(String[] args) {
        ArrayList<Film> daftarFilm = new ArrayList<>();
        Scanner scanner = new Scanner(System.in);
        int pilihan = 0;

        do {
            System.out.println("\n==================================");
            System.out.println("       PROGRAM DATA BIOSKOP       ");
            System.out.println("==================================");
            System.out.println("1. Tambah Data");
            System.out.println("2. Tampilkan Data");
            System.out.println("3. Update Data");
            System.out.println("4. Hapus Data");
            System.out.println("5. Cari Data");
            System.out.println("6. Keluar");
            System.out.println("==================================");
            System.out.print("Pilih menu : ");

            if (!scanner.hasNextInt()) {
                System.out.println("Pilihan menu tidak valid! Silakan masukkan angka 1-6.");
                scanner.next(); // Clear invalid token
                continue;
            }

            pilihan = scanner.nextInt();
            scanner.nextLine(); // Consume newline

            // 1. TAMBAH DATA
            if (pilihan == 1) {
                System.out.println("\n--- Tambah Data Film ---");
                System.out.print("Masukkan ID       : ");
                if (!scanner.hasNextInt()) {
                    System.out.println("ID harus berupa angka!");
                    scanner.next();
                    continue;
                }
                int id = scanner.nextInt();
                scanner.nextLine(); // Consume newline

                boolean idSudahAda = false;
                for (int i = 0; i < daftarFilm.size(); i++) {
                    if (daftarFilm.get(i).getId() == id) {
                        idSudahAda = true;
                    }
                }

                if (idSudahAda) {
                    System.out.println("ID sudah digunakan!");
                } else {
                    System.out.print("Masukkan Judul    : ");
                    String judul = scanner.nextLine().trim();

                    System.out.print("Masukkan Genre    : ");
                    String genre = scanner.nextLine().trim();

                    System.out.print("Masukkan Durasi   : ");
                    if (!scanner.hasNextInt()) {
                        System.out.println("Durasi harus berupa angka!");
                        scanner.next();
                        continue;
                    }
                    int durasi = scanner.nextInt();
                    scanner.nextLine(); // Consume newline

                    if (durasi <= 0) {
                        System.out.println("Durasi harus lebih dari 0!");
                    } else {
                        System.out.print("Masukkan Path Gambar : ");
                        String gambar = scanner.nextLine().trim();

                        if (judul.isEmpty() || genre.isEmpty() || gambar.isEmpty()) {
                            System.out.println("Judul, genre, dan gambar tidak boleh kosong!");
                        } else {
                            Film filmBaru = new Film(id, judul, genre, durasi, gambar);
                            daftarFilm.add(filmBaru);
                            System.out.println("Data film berhasil ditambahkan!");
                        }
                    }
                }
            }
            // 2. TAMPILKAN DATA
            else if (pilihan == 2) {
                System.out.println("\n--- Data Film ---");
                if (daftarFilm.isEmpty()) {
                    System.out.println("Belum ada data film.");
                } else {
                    for (int i = 0; i < daftarFilm.size(); i++) {
                        System.out.println("\nFilm ke-" + (i + 1));
                        System.out.println("--------------------------");
                        daftarFilm.get(i).tampilkanData();
                    }
                }
                System.out.print("\nTekan Enter untuk kembali ke menu...");
                scanner.nextLine();
            }
            // 3. UPDATE DATA
            else if (pilihan == 3) {
                System.out.println("\n--- Update Data Film ---");
                System.out.print("Masukkan ID film yang ingin diubah : ");
                if (!scanner.hasNextInt()) {
                    System.out.println("ID harus berupa angka!");
                    scanner.next();
                    continue;
                }
                int idCari = scanner.nextInt();
                scanner.nextLine(); // Consume newline

                int posisi = -1;
                for (int i = 0; i < daftarFilm.size(); i++) {
                    if (daftarFilm.get(i).getId() == idCari) {
                        posisi = i;
                    }
                }

                if (posisi == -1) {
                    System.out.println("Data film tidak ditemukan!");
                } else {
                    System.out.print("Masukkan Judul Baru    : ");
                    String judulBaru = scanner.nextLine().trim();

                    System.out.print("Masukkan Genre Baru    : ");
                    String genreBaru = scanner.nextLine().trim();

                    System.out.print("Masukkan Durasi Baru   : ");
                    if (!scanner.hasNextInt()) {
                        System.out.println("Durasi harus berupa angka!");
                        scanner.next();
                        continue;
                    }
                    int durasiBaru = scanner.nextInt();
                    scanner.nextLine(); // Consume newline

                    if (durasiBaru <= 0) {
                        System.out.println("Durasi harus lebih dari 0!");
                    } else {
                        System.out.print("Masukkan Path Gambar Baru : ");
                        String gambarBaru = scanner.nextLine().trim();

                        if (judulBaru.isEmpty() || genreBaru.isEmpty() || gambarBaru.isEmpty()) {
                            System.out.println("Semua data tidak boleh kosong!");
                        } else {
                            Film target = daftarFilm.get(posisi);
                            target.setJudul(judulBaru);
                            target.setGenre(genreBaru);
                            target.setDurasi(durasiBaru);
                            target.setGambar(gambarBaru);
                            System.out.println("Data film berhasil diubah!");
                        }
                    }
                }
            }
            // 4. HAPUS DATA
            else if (pilihan == 4) {
                System.out.println("\n--- Hapus Data Film ---");
                System.out.print("Masukkan ID film yang ingin dihapus : ");
                if (!scanner.hasNextInt()) {
                    System.out.println("ID harus berupa angka!");
                    scanner.next();
                    continue;
                }
                int idHapus = scanner.nextInt();
                scanner.nextLine(); // Consume newline

                int posisi = -1;
                for (int i = 0; i < daftarFilm.size(); i++) {
                    if (daftarFilm.get(i).getId() == idHapus) {
                        posisi = i;
                    }
                }

                if (posisi == -1) {
                    System.out.println("Data film tidak ditemukan!");
                } else {
                    daftarFilm.remove(posisi);
                    System.out.println("Data film berhasil dihapus!");
                }
            }
            // 5. CARI DATA
            else if (pilihan == 5) {
                System.out.println("\n--- Cari Data Film ---");
                System.out.print("Masukkan ID film : ");
                if (!scanner.hasNextInt()) {
                    System.out.println("ID harus berupa angka!");
                    scanner.next();
                    continue;
                }
                int idCari = scanner.nextInt();
                scanner.nextLine(); // Consume newline

                int posisi = -1;
                for (int i = 0; i < daftarFilm.size(); i++) {
                    if (daftarFilm.get(i).getId() == idCari) {
                        posisi = i;
                    }
                }

                if (posisi == -1) {
                    System.out.println("Data film tidak ditemukan!");
                } else {
                    System.out.println("\nData film ditemukan!");
                    System.out.println("--------------------------");
                    daftarFilm.get(posisi).tampilkanData();
                }

                System.out.print("\nTekan Enter untuk kembali ke menu...");
                scanner.nextLine();
            }
            // 6. KELUAR
            else if (pilihan == 6) {
                System.out.println("\nProgram selesai.");
            } else {
                System.out.println("Pilihan menu tidak valid! Silakan pilih menu 1 sampai 6.");
            }

        } while (pilihan != 6);

        scanner.close();
    }
}
