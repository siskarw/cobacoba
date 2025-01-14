<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_laporan');
    }

    public function index() {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $status = $this->input->get('status');
        $karyawan = $this->input->get('karyawan');

        $data['transaksi'] = $this->M_laporan->get_laporan($start_date, $end_date, $status, $karyawan);
        $data['poin_summary'] = $this->M_laporan->get_poin_summary();

        $this->load->view('laporan/index', $data);
    }
}
?>