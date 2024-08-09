
<?php
class Ruangan_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        // Load database connection for 'regonline'
        $this->load->database('default4', TRUE);
    }

    public function get_ruangan_list() {
        // Tambahkan kondisi WHERE untuk memilih hanya baris dengan kolom 'antrian' = 'A'
         $this->db->query("USE regonline");
        $this->db->where('antrian', 'A');
        $query = $this->db->get('link_ruangan');
        return $query->result_array();
    }
}
