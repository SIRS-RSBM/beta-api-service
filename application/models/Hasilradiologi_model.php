<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hasilradiologi_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database('default1'); // Database pendaftaran
        $this->load->database('default2', TRUE); // Database layanan
         $this->load->database('default', TRUE); // 'default' digunakan untuk database master
    }

    // Fungsi untuk mendapatkan data berdasarkan NORM dan RUANGAN
    public function get_data_by_norm_and_ruangan($norm, $ruangan) {
        // SQL query untuk join tabel dan ambil data yang diperlukan
        $sql = "
            SELECT 
                p.NOMOR,
                k.NOMOR AS NOMOR_KUNJUNGAN,
                p.NORM,
                p.TANGGAL AS TANGGAL_KUNJUNGAN,
                p.DIAGNOSA_MASUK,
                p.RUJUKAN,
                k.RUANGAN AS NOMOR_RUANGAN,
                r.DESKRIPSI AS RUANGAN_RUANGAN,
                tm.ID AS ID_TINDAKAN_MEDIS,
                t.NAMA AS NAMA_TINDAKAN,
                hr.KLINIS,
                hr.KESAN,
                hr.USUL,
                hr.HASIL,
                hr.BTK,
                hr.TANGGAL AS HASIL_EXPERTISE
            FROM 
                pendaftaran.pendaftaran p
            LEFT JOIN 
                pendaftaran.kunjungan k 
            ON 
                p.NOMOR = k.NOPEN
            LEFT JOIN 
                master.ruangan r
            ON 
                k.RUANGAN = r.ID
            LEFT JOIN 
                layanan.tindakan_medis tm
            ON 
                k.NOMOR = tm.KUNJUNGAN
            LEFT JOIN 
                master.tindakan t
            ON 
                tm.TINDAKAN = t.ID
            LEFT JOIN 
                layanan.hasil_rad hr
            ON 
                tm.ID = hr.TINDAKAN_MEDIS
            WHERE 
                p.NORM = ? AND k.RUANGAN = ?
        ";

        // Menjalankan query dan mengembalikan hasil sebagai array
        $query = $this->db->query($sql, array($norm, $ruangan));
        if (!$query) {
            log_message('error', 'Database query failed: ' . $this->db->last_query());
            return array();
        }
        return $query->result_array();
    }
}
?>
