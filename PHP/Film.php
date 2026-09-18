<?php
// Class Film untuk menyimpan data film di bioskop
class Film {
    // Atribut dibuat private untuk menerapkan enkapsulasi
    private $id;
    private $judul;
    private $genre;
    private $durasi;
    private $gambar;

    // Constructor
    public function __construct($id = 0, $judul = "", $genre = "", $durasi = 0, $gambar = "") {
        $this->id = (int)$id;
        $this->judul = $judul;
        $this->genre = $genre;
        $this->durasi = (int)$durasi;
        $this->gambar = $gambar;
    }

    // Getter dan Setter
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = (int)$id;
    }

    public function getJudul() {
        return $this->judul;
    }

    public function setJudul($judul) {
        $this->judul = $judul;
    }

    public function getGenre() {
        return $this->genre;
    }

    public function setGenre($genre) {
        $this->genre = $genre;
    }

    public function getDurasi() {
        return $this->durasi;
    }

    public function setDurasi($durasi) {
        $this->durasi = (int)$durasi;
    }

    public function getGambar() {
        return $this->gambar;
    }

    public function setGambar($gambar) {
        $this->gambar = $gambar;
    }
}
?>
