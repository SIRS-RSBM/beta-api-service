<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eresep_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        // Load database untuk pendaftaran, layanan, dan inventory
        $this->load->database('default1', TRUE); // Database pendaftaran
        $this->load->database('default2', TRUE); // Database layanan
        $this->load->database('default4', TRUE); // Database inventory
    }

    // Method untuk mencari data berdasarkan NORM dan RUANGAN
    public function get_data($norm = null, $ruangan = null) {
        // Koneksi ke database pendaftaran
        $this->db->query("USE pendaftaran");

        // Query join
        $this->db->select('pendaftaran.*, kunjungan.ruangan, kunjungan.NOMOR, kunjungan.NOPEN, 
                           layanan.order_resep.*, layanan.order_detil_resep.*, barang.nama AS nama_barang');
        $this->db->from('pendaftaran');
        $this->db->join('kunjungan', 'pendaftaran.NOMOR = kunjungan.NOPEN', 'left');
        $this->db->join('layanan.order_resep', 'kunjungan.REF = layanan.order_resep.NOMOR', 'left');
        $this->db->join('layanan.order_detil_resep', 'layanan.order_resep.NOMOR = layanan.order_detil_resep.ORDER_ID', 'left');
        
        // Join dengan tabel barang dari database inventory
        $this->db->join('inventory.barang', 'layanan.order_detil_resep.farmasi = inventory.barang.ID', 'left');

        // Filter berdasarkan parameter jika ada
        if ($norm) {
            $this->db->where('pendaftaran.NORM', $norm);
        }
        if ($ruangan) {
            $this->db->where('kunjungan.ruangan', $ruangan);
        }

        // Tidak perlu melakukan group_by, tampilkan semua data
        // $this->db->group_by('kunjungan.NOMOR');
        
        $query = $this->db->get();
        
        // Debugging: Menampilkan query yang dijalankan
        $sql = $this->db->last_query();
        log_message('debug', 'SQL Query: ' . $sql);
        
        // Menampilkan hasil query untuk debugging
        $result = $query->result_array();
        log_message('debug', 'Query Result: ' . print_r($result, true));
        
        return $result; // Mengembalikan semua baris data sebagai array
    }
}
?>
