<?php

class Suratsehat_model extends CI_Model {

    public function __construct() {
        parent::__construct();
         $this->db->query("USE pendaftaran");
        $this->load->database(); // Koneksi default (pendaftaran)
        $this->db_medicalrecord = $this->load->database('default5', TRUE); // Koneksi ke database medicalrecord
    }

    // Mendapatkan semua data dengan LEFT JOIN dan hanya menampilkan data yang ada di surat_sehat
    public function get_all_data_with_join() {
        $this->db->select('
            pendaftaran.*,
            kunjungan.NOMOR AS NOMOR_KUNJUNGAN,
            surat_sehat.*
        '); // Menampilkan kolom dari pendaftaran, NOMOR_KUNJUNGAN dari kunjungan, dan kolom dari surat_sehat
        
        $this->db->from('pendaftaran');
        $this->db->join('kunjungan', 'pendaftaran.nomor = kunjungan.nopen', 'left');
        
        // LEFT JOIN dengan tabel surat_sehat di database medicalrecord
        $this->db->join('medicalrecord.surat_sehat AS surat_sehat', 'kunjungan.NOMOR = surat_sehat.kunjungan', 'inner');
        // Gunakan INNER JOIN untuk memastikan hanya data yang ada di surat_sehat yang ditampilkan
        
        $query = $this->db->get();
        return $query->result_array();
    }

    // Mendapatkan data berdasarkan ID dengan LEFT JOIN dan hanya menampilkan data yang ada di surat_sehat
    public function get_data_by_id($id) {
        $this->db->select('
            pendaftaran.*,
            kunjungan.NOMOR AS NOMOR_KUNJUNGAN,
            surat_sehat.*
        '); // Menampilkan kolom dari pendaftaran, NOMOR_KUNJUNGAN dari kunjungan, dan kolom dari surat_sehat
        
        $this->db->from('pendaftaran');
        $this->db->join('kunjungan', 'pendaftaran.nomor = kunjungan.nopen', 'left');
        $this->db->where('pendaftaran.id', $id);
        
        // LEFT JOIN dengan tabel surat_sehat di database medicalrecord
        $this->db->join('medicalrecord.surat_sehat AS surat_sehat', 'kunjungan.NOMOR = surat_sehat.kunjungan', 'inner');
        // Gunakan INNER JOIN untuk memastikan hanya data yang ada di surat_sehat yang ditampilkan
        
        $query = $this->db->get();
        return $query->row_array();
    }

    // Mendapatkan data berdasarkan NORM dengan LEFT JOIN dan hanya menampilkan data yang ada di surat_sehat
    public function search_by_norm($norm) {
        $this->db->select('
            pendaftaran.*,
            kunjungan.NOMOR AS NOMOR_KUNJUNGAN,
            surat_sehat.*
        '); // Menampilkan kolom dari pendaftaran, NOMOR_KUNJUNGAN dari kunjungan, dan kolom dari surat_sehat
        
        $this->db->from('pendaftaran');
        $this->db->join('kunjungan', 'pendaftaran.nomor = kunjungan.nopen', 'left');
        $this->db->where('pendaftaran.NORM', $norm);
        
        // LEFT JOIN dengan tabel surat_sehat di database medicalrecord
        $this->db->join('medicalrecord.surat_sehat AS surat_sehat', 'kunjungan.NOMOR = surat_sehat.kunjungan', 'inner');
        // Gunakan INNER JOIN untuk memastikan hanya data yang ada di surat_sehat yang ditampilkan
        
        $query = $this->db->get();
        return $query->result_array();
    }
}
?>


