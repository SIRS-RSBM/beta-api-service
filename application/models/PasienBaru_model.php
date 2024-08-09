<?php
class PasienBaru_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database('regonline');
    }

    public function insert_reservasi($data) {
        // Mulai transaksi
        $this->db->trans_start();

        // Panggil fungsi generatorReservasi untuk mendapatkan ID
        $tanggal = $data['TANGGALKUNJUNGAN'];
        $query = $this->db->query("SELECT generatorReservasi(?) AS reservasi_id", array($tanggal));
        $result = $query->row();
        $data['ID'] = $result->reservasi_id; // Tambahkan ID ke data

        // Panggil fungsi getNomorAntrian untuk mendapatkan nomor antrian
        $pos = $data['POS_ANTRIAN'];
        $cara_bayar = $data['CARABAYAR'];
        $query = $this->db->query("SELECT getNomorAntrian(?, ?, ?) AS nomor_antrian", array($pos, $tanggal, $cara_bayar));
        $result = $query->row();
        $data['NO'] = $result->nomor_antrian; // Tambahkan nomor antrian ke data

        // Panggil fungsi generateNoAntrianPoli untuk mendapatkan nomor antrian poli
        $poli = $data['POLI'];
        $query = $this->db->query("SELECT generateNoAntrianPoli(?, ?) AS nomor_antrian_poli", array($poli, $tanggal));
        $result = $query->row();
        $data['ANTRIAN_POLI'] = $result->nomor_antrian_poli; // Tambahkan nomor antrian poli ke data

        // Masukkan data ke tabel reservasi
        $this->db->insert('reservasi', $data);

        // Selesai transaksi
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // Jika transaksi gagal
            return false;
        } else {
            // Mengembalikan ID yang dihasilkan
            return $data['ID'];
        }
    }
}
