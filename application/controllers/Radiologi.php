<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Radiologi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Hasilradiologi_model');
    }

    private function authenticate() {
        // Mendapatkan header kustom
        $headers = $this->input->request_headers();
        $username = isset($headers['X-Username']) ? $headers['X-Username'] : '';
        $password = isset($headers['X-Password']) ? $headers['X-Password'] : '';

        // Hardcoded username dan password
        $valid_username = '8888';
        $valid_password = 'QBCJC53PGR';

        // Periksa username dan password
        if ($username == $valid_username && $password == $valid_password) {
            return true;
        }

        return false;
    }

    // Fungsi untuk mendapatkan data
    public function get_data() {
        // Memeriksa otentikasi
        if (!$this->authenticate()) {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Anda belum login']));
            return;
        }

        $norm = $this->input->get('NORM'); // Ambil parameter NORM dari query string
        $ruangan = $this->input->get('RUANGAN'); // Ambil parameter RUANGAN dari query string

        if (empty($norm) || empty($ruangan)) {
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Parameter NORM dan RUANGAN diperlukan']));
            return;
        }

        $data = $this->Hasilradiologi_model->get_data_by_norm_and_ruangan($norm, $ruangan);

        if (empty($data)) {
            $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Data tidak ditemukan']));
            return;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
?>
