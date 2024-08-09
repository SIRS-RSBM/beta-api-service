<?php
class Hasilklinis_model extends CI_Model {
    private $db_master;
    private $db_pendaftaran;
    private $db_layanan;

    public function __construct() {
        parent::__construct();
        $this->db_master = $this->load->database('default', TRUE);
        $this->db_pendaftaran = $this->load->database('default1', TRUE);
        $this->db_layanan = $this->load->database('default2', TRUE);
    }

    public function get_pasien_by_norm($norm) {
        $this->db_master->select('NORM');
        $this->db_master->from('pasien');
        $this->db_master->where('NORM', $norm);
        $query = $this->db_master->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return NULL;
        }
    }

    public function get_pendaftaran_by_norm_and_ruangan($norm, $ruangan) {
        $this->db_pendaftaran->select('*');
        $this->db_pendaftaran->from('pendaftaran');
        $this->db_pendaftaran->join('kunjungan', 'pendaftaran.NOMOR = kunjungan.NOPEN', 'left');
        $this->db_pendaftaran->where('pendaftaran.NORM', $norm);
        $this->db_pendaftaran->where('kunjungan.RUANGAN', $ruangan);
        $query = $this->db_pendaftaran->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return NULL;
        }
    }

    public function get_kunjungan_by_nomor($nomor) {
        $this->db_pendaftaran->select('NOMOR, NOPEN, RUANGAN');
        $this->db_pendaftaran->from('kunjungan');
        $this->db_pendaftaran->where('NOMOR', $nomor);
        $query = $this->db_pendaftaran->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return NULL;
        }
    }

public function get_tindakan_medis_by_kunjungan($nomor) {
    // Mengambil data dari tabel tindakan_medis di database layanan
    $this->db_layanan->select('*');
    $this->db_layanan->from('tindakan_medis');
    $this->db_layanan->where('kunjungan', $nomor); // Menggunakan NOMOR dari tabel kunjungan
    $query = $this->db_layanan->get();

    // Log query dan parameter untuk debugging
    log_message('debug', 'Query: ' . $this->db_layanan->last_query());
    log_message('debug', 'Parameter NOMOR: ' . $nomor);

    if ($query->num_rows() > 0) {
        return $query->result_array();
    } else {
        return [];
    }
}

public function get_tindakan_medis_with_nama($nomor) {
    // Mengambil data dari tabel tindakan_medis di database layanan
    $this->db_layanan->select('tindakan_medis.*, tindakan.NAMA');
    $this->db_layanan->from('tindakan_medis');
    $this->db_layanan->join('master.tindakan', 'tindakan_medis.tindakan = tindakan.ID', 'left');
    $this->db_layanan->where('tindakan_medis.kunjungan', $nomor);
    $query = $this->db_layanan->get();

    // Log query dan parameter untuk debugging
    log_message('debug', 'Query: ' . $this->db_layanan->last_query());
    log_message('debug', 'Parameter NOMOR: ' . $nomor);

    if ($query->num_rows() > 0) {
        return $query->result_array();
    } else {
        return [];
    }
}

public function get_tindakan_medis_with_hasil_lab($nomor) {
    // Mengambil data dari tabel tindakan_medis di database layanan
    $this->db_layanan->select('tindakan_medis.*, hasil_lab.*, tindakan.NAMA as nama_tindakan, parameter_tindakan_lab.parameter as nama_parameter');
    $this->db_layanan->from('tindakan_medis');
    $this->db_layanan->join('master.tindakan', 'tindakan_medis.tindakan = tindakan.id', 'left');
    $this->db_layanan->join('hasil_lab', 'tindakan_medis.ID = hasil_lab.tindakan_medis', 'left');
    $this->db_layanan->join('master.parameter_tindakan_lab', 'hasil_lab.parameter_tindakan = parameter_tindakan_lab.ID', 'left');
    $this->db_layanan->where('tindakan_medis.kunjungan', $nomor);
    $query = $this->db_layanan->get();

    // Log query dan parameter untuk debugging
    log_message('debug', 'Query: ' . $this->db_layanan->last_query());
    log_message('debug', 'Parameter NOMOR: ' . $nomor);

    if ($query->num_rows() > 0) {
        return $query->result_array();
    } else {
        return [];
    }
}



}

