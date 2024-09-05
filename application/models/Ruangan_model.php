<?php
class Ruangan_model extends CI_Model {
    private $regonline;
    private $master;

    public function __construct() {
        parent::__construct();
        // Load database connections
        $this->regonline = $this->load->database('default3', TRUE);  // Koneksi ke database 'regonline'
        $this->master = $this->load->database('default', TRUE);     // Koneksi ke database 'master'
    }

    public function get_ruangan_list() {
        // Melakukan join dengan database master, tabel penjamin_ruangan
        $this->regonline->select('link_ruangan.*, penjamin_ruangan.ruangan_penjamin AS POLI_BPJS');
        $this->regonline->from('link_ruangan');
        $this->regonline->join('master.penjamin_ruangan', 'penjamin_ruangan.Ruangan_RS = link_ruangan.ID', 'left'); 
        $this->regonline->where('link_ruangan.antrian', 'A');
        $query = $this->regonline->get();

        return $query->result_array();
    }
}
