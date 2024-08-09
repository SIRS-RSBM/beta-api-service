<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reservasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Memuat model Reservasi_model
        $this->load->model('Reservasi_model');
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
                ->set_output(json_encode(['error' => 'Anda belum login']));
            return;
        }

        // Mengambil parameter NORM dari query string
        $norm = $this->input->get('NORM');

        if (empty($norm)) {
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Parameter NORM diperlukan']));
            return;
        }

        // Mengambil data dari model berdasarkan NORM
        $data['reservasi'] = $this->Reservasi_model->get_reservasi_by_norm($norm);

        // Mengatur header untuk format JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data['reservasi']));
    }
}
?>
