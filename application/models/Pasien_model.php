<?php
class Pasien_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Mengambil data pasien dengan filter NORM, TANGGAL_LAHIR, dan NO_KTP
     *
     * @param string $norm NORM pasien
     * @param string $tanggal_lahir Tanggal Lahir pasien
     * @param string $no_ktp NO_KTP pasien dari tabel kartu_identitas_pasien
     * @return array Hasil query
     */
    public function get_Pasien($norm = '', $tanggal_lahir = '', $no_ktp = '') {
        $this->db->select('
            pasien.*,
            kartu_asuransi_pasien.nomor AS NO_BPJS,
            kartu_identitas_pasien.nomor AS NO_KTP
        ');
        $this->db->from('pasien');
        
        // Join dengan tabel kartu_asuransi_pasien berdasarkan NORM
        $this->db->join('kartu_asuransi_pasien', 'pasien.NORM = kartu_asuransi_pasien.NORM', 'left');
        
        // Join dengan tabel kartu_identitas_pasien berdasarkan NORM
        $this->db->join('kartu_identitas_pasien', 'pasien.NORM = kartu_identitas_pasien.NORM', 'left');

        // Jika parameter NORM disediakan, tambahkan ke filter
        if (!empty($norm)) {
            $this->db->where('pasien.NORM', $norm);
        }

        // Jika parameter NO_KTP disediakan, tambahkan ke filter
        if (!empty($no_ktp)) {
            $this->db->where('kartu_identitas_pasien.nomor', $no_ktp);
        }

        // Jika parameter TANGGAL_LAHIR disediakan, tambahkan ke filter
        if (!empty($tanggal_lahir)) {
            $this->db->where('pasien.TANGGAL_LAHIR', $tanggal_lahir);
        }

        // Eksekusi query
        $query = $this->db->get();

        // Kembalikan hasil sebagai array
        return $query->result_array();
    }
}
?>
