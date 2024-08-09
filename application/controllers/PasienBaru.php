<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PasienBaru extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('PasienBaru_model');
    }

    public function post_reservasi() {
        $this->db->query("USE regonline");
        $input_data = json_decode($this->input->raw_input_stream, true);

        if ($input_data === null) {
            $response = array('status' => 'error', 'message' => 'Invalid JSON');
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        } else {
            $data = array(
                'TANGGALKUNJUNGAN' => $input_data['TANGGALKUNJUNGAN'],
                'TANGGAL_REF' => $input_data['TANGGAL_REF'],
                'NORM' => 0,
                'NIK' => $input_data['NIK'],
                'NAMA' => $input_data['NAMA'],
                'TEMPAT_LAHIR' => $input_data['TEMPAT_LAHIR'],
                'TANGGAL_LAHIR' => $input_data['TANGGAL_LAHIR'],
                'POLI' => $input_data['POLI'],
                'DOKTER' => $input_data['DOKTER'],
                'CARABAYAR' => $input_data['CARABAYAR'],
                'JENIS_KUNJUNGAN' => $input_data['JENIS_KUNJUNGAN'],
                'CONTACT' => $input_data['CONTACT'],
                'TGL_DAFTAR' => $input_data['TGL_DAFTAR'],
                'JAM' => $input_data['JAM'],
                'JAM_PELAYANAN' => $input_data['JAM_PELAYANAN'],
                'ESTIMASI_PELAYANAN' => $input_data['ESTIMASI_PELAYANAN'],
                'POS_ANTRIAN' => $input_data['POS_ANTRIAN'],
                'JENIS' => $input_data['JENIS'],
                'JENIS_APLIKASI' => $input_data['JENIS_APLIKASI'],
                'POLI_BPJS' => $input_data['POLI_BPJS']
            );

            $insert_id = $this->PasienBaru_model->insert_reservasi($data);

            if ($insert_id) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Data inserted successfully',
                    'ID' => $insert_id
                );
                $this->output
                    ->set_status_header(201)
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
            } else {
                $response = array('status' => 'error', 'message' => 'Failed to insert data');
                $this->output
                    ->set_status_header(500)
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
            }
        }
    }
}
