<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suratsehat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Suratsehat_model');
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

    // Mendapatkan semua data dengan LEFT JOIN
    public function index() {
        if (!$this->authenticate()) {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Anda harus login terlebih dahulu']));
            return;
        }

        $data = $this->Suratsehat_model->get_all_data_with_join();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    // Menambahkan data
    public function add() {
        if (!$this->authenticate()) {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Anda harus login terlebih dahulu']));
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $result = $this->Suratsehat_model->add_data($input);
        if ($result) {
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success']));
        } else {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error']));
        }
    }

    // Mendapatkan data berdasarkan ID dengan LEFT JOIN
    public function get($id) {
        if (!$this->authenticate()) {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Anda harus login terlebih dahulu']));
            return;
        }

        $data = $this->Suratsehat_model->get_data_by_id($id);
        if ($data) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        } else {
            $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'not found']));
        }
    }

    // Mencari data berdasarkan NORM dari parameter query string
    public function search() {
        if (!$this->authenticate()) {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Anda harus login terlebih dahulu']));
            return;
        }

        $norm = $this->input->get('NORM'); // Mengambil parameter 'NORM' dari query string
        if ($norm) {
            $data = $this->Suratsehat_model->search_by_norm($norm);
            if ($data) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($data));
            } else {
                $this->output
                    ->set_status_header(404)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'not found']));
            }
        } else {
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'bad request', 'message' => 'Parameter NORM is required']));
        }
    }
}
?>
