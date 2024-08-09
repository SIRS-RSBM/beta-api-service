<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hasilpa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Hasilpa_model');
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

    public function index() {
        // Memeriksa otentikasi
        if (!$this->authenticate()) {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Anda belum login']));
            return;
        }

        // Mengambil parameter NORM dan RUANGAN dari URL
        $norm = $this->input->get('NORM');
        $ruangan = $this->input->get('RUANGAN');
        
        if ($norm) {
            // Mengambil data dari model dengan join
            $data = $this->Hasilpa_model->get_data_with_join($norm, $ruangan);

            if ($data) {
                // Mengembalikan data dalam format JSON
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($data));
            } else {
                // Mengembalikan respon jika data tidak ditemukan
                $this->output
                    ->set_status_header(404)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['message' => 'Data not found']));
            }
        } else {
            // Mengembalikan respon jika parameter NORM tidak disertakan
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'Parameter NORM is required']));
        }
    }
}
?>
