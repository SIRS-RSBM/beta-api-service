


<?php
class Subspesialis_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->db->query("USE regonline");
        $this->load->database('defaul5'); // Gunakan database 'regonline'
        $this->load->database('default', TRUE); // Load database 'master'
    }

     // Mengambil semua data dokter dengan join ke tabel jadwal_dokter_hfis, penjamin_ruangan, dan ruangan
    public function get_all_dokter() {
        $this->db->select('dokter.*, 
                           jadwal_dokter_hfis.KD_SUB_SPESIALIS, 
                           penjamin_ruangan.RUANGAN_RS, 
                           ruangan.DESKRIPSI'); // Menyertakan kolom 'deskripsi' dari tabel 'ruangan'
        $this->db->from('dokter');
        $this->db->join('jadwal_dokter_hfis', 'dokter.KODE = jadwal_dokter_hfis.KD_DOKTER', 'left');
        $this->db->join('master.penjamin_ruangan', 'jadwal_dokter_hfis.KD_SUB_SPESIALIS = penjamin_ruangan.ruangan_penjamin', 'left'); // Join dengan penjamin_ruangan
        $this->db->join('master.ruangan', 'penjamin_ruangan.RUANGAN_RS = ruangan.ID', 'left'); // Join dengan ruangan
        $this->db->group_by('dokter.KODE'); // Mengelompokkan hasil berdasarkan kolom KODE dari tabel dokter
        $query = $this->db->get();
        return $query->result_array(); // Menggunakan result_array() untuk mengambil semua baris
    }
}
