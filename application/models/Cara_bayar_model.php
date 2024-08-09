<?php
class Cara_bayar_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        // Load database connection for 'regonline'
        $this->load->database('default4', TRUE);
    }

    public function get_cara_bayar_list() {
         $this->db->query("USE regonline");
        $query = $this->db->get('cara_bayar');
        return $query->result_array();
    }
}
