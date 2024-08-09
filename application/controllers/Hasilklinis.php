
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hasilklinis extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Hasilklinis_model');
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

        // Mendapatkan parameter NORM dan RUANGAN dari query string
        $norm = $this->input->get('NORM');
        $ruangan = $this->input->get('RUANGAN');

        if (!$norm || !$ruangan) {
            $response = array('status' => 'error', 'message' => 'Parameter NORM atau RUANGAN tidak diberikan.');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        // Mengambil data pasien dari model
        $data_pasien = $this->Hasilklinis_model->get_pasien_by_norm($norm);

        if ($data_pasien) {
            // Mengambil semua data pendaftaran berdasarkan NORM dan RUANGAN
            $data_pendaftaran = $this->Hasilklinis_model->get_pendaftaran_by_norm_and_ruangan($norm, $ruangan);

            if ($data_pendaftaran) {
                // Mengambil data kunjungan dan tindakan_medis untuk setiap pendaftaran
                foreach ($data_pendaftaran as &$pendaftaran) {
                    $nomor = $pendaftaran['NOMOR'];
                    $pendaftaran['kunjungan'] = $this->Hasilklinis_model->get_kunjungan_by_nomor($nomor);

                    // Mengambil data tindakan_medis berdasarkan NOMOR dari kunjungan
                    if ($pendaftaran['kunjungan']) {
                        $nomor_kunjungan = $pendaftaran['kunjungan']['NOMOR']; // Ambil NOMOR dari hasil kunjungan
                        $pendaftaran['tindakan_medis'] = $this->Hasilklinis_model->get_tindakan_medis_with_hasil_lab($nomor_kunjungan);
                    } else {
                        $pendaftaran['tindakan_medis'] = [];
                    }
                }
                $response = array('status' => 'success', 'data_pasien' => $data_pasien, 'data_pendaftaran' => $data_pendaftaran);
            } else {
                $response = array('status' => 'success', 'data_pasien' => $data_pasien, 'data_pendaftaran' => []);
            }
        } else {
            $response = array('status' => 'error', 'message' => 'Data pasien tidak ditemukan.');
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
?>
