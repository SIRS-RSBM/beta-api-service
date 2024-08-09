<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subspesialis extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Subspesialis_model');
        $this->load->helper('url');
    }

    public function index() {
        $data = $this->Subspesialis_model->get_all_dokter(); // Mengambil semua data dokter tanpa pendobelan
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
