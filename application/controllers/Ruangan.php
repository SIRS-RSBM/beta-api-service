<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ruangan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Ruangan_model');
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

        // Mengambil data dari model
        $data = $this->Ruangan_model->get_ruangan_list();

        // Mengatur header untuk format JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
?>
