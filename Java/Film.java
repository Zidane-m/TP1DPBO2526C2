// Class Film untuk menyimpan data film di bioskop
public class Film {
    private int id;
    private String judul;
    private String genre;
    private int durasi;
    private String gambar;

    // Constructor kosong
    public Film() {
        this.id = 0;
        this.judul = "";
        this.genre = "";
        this.durasi = 0;
        this.gambar = "";
    }

    // Constructor berparameter
    public Film(int id, String judul, String genre, int durasi, String gambar) {
        this.id = id;
        this.judul = judul;
        this.genre = genre;
        this.durasi = durasi;
        this.gambar = gambar;
    }

    // Getter dan Setter
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getJudul() {
        return judul;
    }

    public void setJudul(String judul) {
        this.judul = judul;
    }

    public String getGenre() {
        return genre;
    }

    public void setGenre(String genre) {
        this.genre = genre;
    }

    public int getDurasi() {
        return durasi;
    }

    public void setDurasi(int durasi) {
        this.durasi = durasi;
    }

    public String getGambar() {
        return gambar;
    }

    public void setGambar(String gambar) {
        this.gambar = gambar;
    }

    // Method untuk menampilkan data
    public void tampilkanData() {
        System.out.println("ID       : " + getId());
        System.out.println("Judul    : " + getJudul());
        System.out.println("Genre    : " + getGenre());
        System.out.println("Durasi   : " + getDurasi() + " menit");
        System.out.println("Gambar   : " + getGambar());
    }
}
