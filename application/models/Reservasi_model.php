<?php
class Reservasi_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        // Memuat database default3 (regonline)
        $this->load->database('default3');
    }

    /**
     * Mengambil semua data dari tabel reservasi
     *
     * @return array Hasil query
     */
    public function get_all_reservasi() {
        // Tentukan nama database jika perlu, terutama jika menggunakan beberapa database
        $this->db->query("USE regonline"); // Ganti dengan nama database yang sesuai jika diperlukan

        // Menentukan tabel yang akan di-query
        $this->db->from('reservasi');
        
        // Eksekusi query
        $query = $this->db->get();

        // Kembalikan hasil sebagai array
        return $query->result_array();
    }

    /**
     * Mengambil data reservasi berdasarkan NORM
     *
     * @param string $norm NORM yang digunakan untuk pencarian
     * @return array Hasil query
     */
    public function get_reservasi_by_norm($norm) {
        // Tentukan nama database jika perlu, terutama jika menggunakan beberapa database
        $this->db->query("USE regonline"); // Ganti dengan nama database yang sesuai jika diperlukan

        // Menentukan tabel yang akan di-query dan menambahkan kondisi pencarian
        $this->db->from('reservasi');
        $this->db->where('NORM', $norm);
        
        // Eksekusi query
        $query = $this->db->get();

        // Kembalikan hasil sebagai array
        return $query->result_array();
    }
}
?>
