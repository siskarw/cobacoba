<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detail_transaksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_dtransaksi');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['detail_transaksi'] = $this->M_dtransaksi->getAllDetailTransaksi();
        $data['total_detail_transaksi'] = $this->M_dtransaksi->getTotalDetailTransaksi();
        $this->load->view('detail_transaksi/index', $data);
    }

    public function tambah()
    {
        $this->loadViewForAddOrEdit();
    }

    public function aksi_tambah()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('id_detail', 'ID Detail', 'required');
            $this->form_validation->set_rules('id_trans', 'ID Transaksi', 'required');
            $this->form_validation->set_rules('id_menu', 'Menu', 'required');
            $this->form_validation->set_rules('kuantitas', 'Kuantitas', 'required|numeric');
            $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
            $this->form_validation->set_rules('trans_masuk', 'Tanggal Masuk', 'required');
            $this->form_validation->set_rules('trans_ambil', 'Tanggal Ambil', 'required');
            $this->form_validation->set_rules('no_hp_cus', 'No HP Customer', 'required|numeric');

            if ($this->form_validation->run() == TRUE) {
                $subtotal = $this->input->post('kuantitas') * $this->input->post('harga');

                $data = [
                    'id_detail' => $this->input->post('id_detail'),
                    'id_trans' => $this->input->post('id_trans'),
                    'id_menu' => $this->input->post('id_menu'),
                    'kuantitas' => $this->input->post('kuantitas'),
                    'harga' => $this->input->post('harga'),
                    'subtotal' => $subtotal,
                    'trans_masuk' => $this->input->post('trans_masuk'),
                    'trans_ambil' => $this->input->post('trans_ambil'),
                    'id_karyawan' => $this->input->post('id_karyawan'),
                    'no_hp_cus' => $this->input->post('no_hp_cus'),
                ];

                $insert_success = $this->M_dtransaksi->insertDetailTransaksi($data);

                if ($insert_success) {
                    redirect('detail_transaksi');
                } else {
                    $data['error'] = 'Gagal menambahkan detail transaksi.';
                    $this->loadViewForAddOrEdit($data);
                }
            } else {
                $this->loadViewForAddOrEdit();
            }
        } else {
            $this->loadViewForAddOrEdit();
        }
    }

    public function edit($id_detail)
    {
        $data['detail'] = $this->M_dtransaksi->getDetailById($id_detail);

        if (empty($data['detail'])) {
            show_404();
        }

        $this->loadViewForAddOrEdit($data);
    }

    public function aksi_edit()
    {
        $id_detail = $this->input->post('id_detail');

        $this->form_validation->set_rules('id_trans', 'ID Transaksi', 'required');
        $this->form_validation->set_rules('id_menu', 'Menu', 'required');
        $this->form_validation->set_rules('kuantitas', 'Kuantitas', 'required|numeric');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('trans_masuk', 'Tanggal Masuk', 'required');
        $this->form_validation->set_rules('trans_ambil', 'Tanggal Ambil', 'required');
        $this->form_validation->set_rules('no_hp_cus', 'No HP Customer', 'required|numeric');

        if ($this->form_validation->run() == TRUE) {
            $subtotal = $this->input->post('kuantitas') * $this->input->post('harga');

            $data = [
                'id_trans' => $this->input->post('id_trans'),
                'id_menu' => $this->input->post('id_menu'),
                'kuantitas' => $this->input->post('kuantitas'),
                'harga' => $this->input->post('harga'),
                'subtotal' => $subtotal,
                'trans_masuk' => $this->input->post('trans_masuk'),
                'trans_ambil' => $this->input->post('trans_ambil'),
                'id_karyawan' => $this->input->post('id_karyawan'),
                'no_hp_cus' => $this->input->post('no_hp_cus'),
            ];

            if ($this->M_dtransaksi->updateDetailTransaksi($id_detail, $data)) {
                redirect('detail_transaksi');
            } else {
                $data['error'] = 'Gagal memperbarui detail transaksi.';
                $this->loadViewForAddOrEdit($data);
            }
        } else {
            $data['detail'] = $this->M_dtransaksi->getDetailById($id_detail);
            $this->loadViewForAddOrEdit($data);
        }
    }

    public function delete($id_detail)
    {
        if ($this->M_dtransaksi->deleteDetailTransaksi($id_detail)) {
            redirect('detail_transaksi');
        } else {
            log_message('error', 'Failed to delete detail transaksi with id_detail: ' . $id_detail);
            show_error('Failed to delete detail transaksi');
        }
    }

    private function loadViewForAddOrEdit($data = [])
    {
        $data['menu'] = $this->M_dtransaksi->get_all_menu();
        $data['karyawan'] = $this->M_dtransaksi->get_all_karyawan();
        $this->load->view('detail_transaksi/' . (empty($data['detail']) ? 'tambah' : 'edit'), $data);
    }
}
?>
