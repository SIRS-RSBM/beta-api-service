<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hasilpa_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        // Memuat database default (master)
        $this->load->database('default');
        // Memuat database default1 (pendaftaran)
        $this->db2 = $this->load->database('default1', TRUE); // Koneksi kedua untuk database pendaftaran
        // Memuat database default2 (layanan)
        $this->db3 = $this->load->database('default2', TRUE); // Koneksi ketiga untuk database layanan
    }

    public function get_data_with_join($norm, $ruangan = null) {
        // Query untuk tabel pasien di database default (master)
        $this->db->where('NORM', $norm);
        $query1 = $this->db->get('pasien');
        $data_pasien = $query1->row_array();

        if ($data_pasien) {
            // Query untuk seluruh data pendaftaran di database default1
            $this->db2->select('pendaftaran.*, kunjungan.nomor AS KUNJUNGAN_NOMOR, kunjungan.ruangan');
            $this->db2->from('pendaftaran');
            $this->db2->join('kunjungan', 'kunjungan.nopen = pendaftaran.nomor', 'left'); // Join dengan tabel kunjungan
            $this->db2->where('pendaftaran.NORM', $norm);
            
            if ($ruangan) {
                $this->db2->where('kunjungan.ruangan', $ruangan);
            }
            
            $query2 = $this->db2->get();
            $data_pendaftaran = $query2->result_array(); // Mengambil semua data pendaftaran yang sesuai

            // Gabungkan hasil dari tabel pasien dengan data pendaftaran
            if ($data_pendaftaran) {
                $data_pasien['pendaftaran'] = $data_pendaftaran;

                // Ambil daftar nomor kunjungan untuk query berikutnya
                $kunjungan_nomor_list = array_column($data_pendaftaran, 'KUNJUNGAN_NOMOR');
                if (!empty($kunjungan_nomor_list)) {
                    // Query untuk tabel hasil_pa di database default2
                    $this->db3->select('hasil_pa.*');
                    $this->db3->from('hasil_pa');
                    $this->db3->where_in('hasil_pa.kunjungan', $kunjungan_nomor_list); // Gunakan kolom KUNJUNGAN dari tabel hasil_pa
                    $query3 = $this->db3->get();
                    $data_hasil_pa = $query3->result_array(); // Mengambil semua data hasil_pa yang sesuai

                    // Gabungkan hasil dari tabel hasil_pa
                    if ($data_hasil_pa) {
                        $data_pasien['HASIL_PA'] = $data_hasil_pa;
                    } else {
                        $data_pasien['HASIL_PA'] = [];
                    }
                } else {
                    $data_pasien['HASIL_PA'] = [];
                }
            } else {
                $data_pasien['PENDAFTARAN'] = [];
                $data_pasien['HASIL_PA'] = [];
            }

            return $data_pasien;
        } else {
            return null;
        }
    }
}
?>
