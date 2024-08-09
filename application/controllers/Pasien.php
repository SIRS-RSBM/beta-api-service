<?php
class Pasien extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pasien_model');
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

    /**
     * Endpoint untuk mengambil data pasien berdasarkan NORM, TANGGAL_LAHIR, atau NO_KTP
     */
    public function index() {
        // Memeriksa otentikasi
        if (!$this->authenticate()) {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'Anda belum login']));
            return;
        }

        // Mendapatkan parameter pencarian dari query string
        $norm = $this->input->get('norm');
        $tanggal_lahir = $this->input->get('tanggal_lahir');
        $no_ktp = $this->input->get('no_ktp');

        // Memanggil model dengan parameter pencarian
        $data = $this->Pasien_model->get_Pasien($norm, $tanggal_lahir, $no_ktp);

        // Mengembalikan data sebagai JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
?>
