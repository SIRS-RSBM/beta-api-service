<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eresep extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load model
        $this->load->model('Eresep_model');
    }

    /**
     * Metode untuk memeriksa otentikasi berdasarkan header X-Username dan X-Password
     */
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
                ->set_output(json_encode(['error' => 'Anda belum login']));
            return;
        }

        // Mendapatkan parameter NORM dan RUANGAN dari query string
        $norm = $this->input->get('NORM');
        $ruangan = $this->input->get('RUANGAN');

        // Validasi parameter NORM
        if (!$norm) {
            $this->output
                ->set_status_header(400)
                ->set_output(json_encode(['error' => 'Parameter NORM tidak ditemukan']));
            return;
        }

        // Validasi parameter RUANGAN
        if (!$ruangan) {
            $this->output
                ->set_status_header(400)
                ->set_output(json_encode(['error' => 'Parameter RUANGAN belum dimasukkan']));
            return;
        }

        // Ambil data dari model
        $data = $this->Eresep_model->get_data($norm, $ruangan);

        // Cek apakah data ditemukan
        if ($data) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        } else {
            $this->output
                ->set_status_header(404)
                ->set_output(json_encode(['error' => 'Data tidak ditemukan']));
        }
    }
}
?>
