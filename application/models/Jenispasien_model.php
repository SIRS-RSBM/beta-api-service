<?php
class Jenispasien_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        // Load database connection for 'regonline'
        $this->load->database('default4', TRUE);
    }

    public function get_jenispasien_list() {
         $this->db->query("USE regonline");
        $query = $this->db->get('jenispendaftaran');
        return $query->result_array();
    }
}
