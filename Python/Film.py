# Class Film untuk menyimpan data film di bioskop
class Film:
    def __init__(self, id: int = 0, judul: str = "", genre: str = "", durasi: int = 0, gambar: str = ""):
        # Atribut private menggunakan __ (encapsulation)
        self.__id = id
        self.__judul = judul
        self.__genre = genre
        self.__durasi = durasi
        self.__gambar = gambar

    # Getter dan Setter
    def getId(self) -> int:
        return self.__id

    def setId(self, id: int) -> None:
        self.__id = id

    def getJudul(self) -> str:
        return self.__judul

    def setJudul(self, judul: str) -> None:
        self.__judul = judul

    def getGenre(self) -> str:
        return self.__genre

    def setGenre(self, genre: str) -> None:
        self.__genre = genre

    def getDurasi(self) -> int:
        return self.__durasi

    def setDurasi(self, durasi: int) -> None:
        self.__durasi = durasi

    def getGambar(self) -> str:
        return self.__gambar

    def setGambar(self, gambar: str) -> None:
        self.__gambar = gambar

    # Method untuk menampilkan data film
    def tampilkanData(self) -> None:
        print(f"ID       : {self.getId()}")
        print(f"Judul    : {self.getJudul()}")
        print(f"Genre    : {self.getGenre()}")
        print(f"Durasi   : {self.getDurasi()} menit")
        print(f"Gambar   : {self.getGambar()}")
