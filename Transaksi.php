<?php

class Transaksi extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('M_customer');
        $this->load->model('M_transaksi');
        $this->load->model('M_menu');
        $this->load->library('form_validation');
    }

    public function index() {
        $query = $this->M_transaksi->get_transaksi();
    
        if ($query instanceof CI_DB_result) {
            $data['transaksi'] = $query->result_array();
        } else {
            $data['transaksi'] = [];
            log_message('error', 'get_transaksi() did not return a valid query result.');
        }
    
        $data['total_transaksi'] = $this->db->count_all('transaksi');
    
        $this->load->view('transaksi/index', $data);
    }

    public function tambah() {
        $data['cus'] = $this->M_customer->get_customer();
        $data['menu'] = $this->M_menu->get_all_menu();
        
        $this->load->view('transaksi/tambah', $data);
    }

    public function aksi_tambah() {
        $this->form_validation->set_rules('id_trans', 'ID Transaksi', 'required');
        $this->form_validation->set_rules('no_hp_customer', 'Customer', 'required');
        $this->form_validation->set_rules('id_karyawan', 'ID Karyawan', 'required');
        $this->form_validation->set_rules('transaksi_masuk', 'Transaksi Masuk', 'required');
        $this->form_validation->set_rules('transaksi_ambil', 'Transaksi Ambil', 'required');
        $this->form_validation->set_rules('pembayaran', 'Pembayaran', 'required');
        $this->form_validation->set_rules('total_harga', 'Total Harga', 'required|numeric');
    
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('transaksi/tambah');
        } else {
            $data = array(
                'id_trans' => $this->input->post('id_trans'),
                'no_hp_cus' => $this->input->post('no_hp_customer'),
                'id_karyawan' => $this->input->post('id_karyawan'),
                'trans_masuk' => $this->input->post('transaksi_masuk'),
                'trans_ambil' => $this->input->post('transaksi_ambil'),
                'pembayaran' => $this->input->post('pembayaran'),
                'total_harga' => $this->input->post('total_harga')
            );
    
            $inserted = $this->M_transaksi->insert_transaksi($data);
    
            if ($inserted) {
                $this->session->set_flashdata('message', 'Transaksi berhasil ditambahkan.');
                redirect('transaksi');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan transaksi.');
                redirect('transaksi/tambah');
            }
        }
    }

    public function edit($id_trans) {
        $data['trans'] = $this->M_transaksi->get_data_by_id($id_trans);

        if (!$data['trans']) {
            show_404();
        }

        $this->load->view('edit', $data);
    }

    public function aksi_edit() {
        $this->form_validation->set_rules('id_trans', 'ID Transaksi', 'required');
        $this->form_validation->set_rules('namacus', 'Customer', 'required');
        $this->form_validation->set_rules('transaksi_masuk', 'Tanggal Transaksi Masuk', 'required');
        $this->form_validation->set_rules('transaksi_ambil', 'Tanggal Transaksi Ambil', 'required');
        $this->form_validation->set_rules('pembayaran', 'Pembayaran', 'required');
        $this->form_validation->set_rules('total_harga', 'Total Harga', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('transaksi/edit/' . $this->input->post('id_trans'));
        } else {
            $data = array(
                'id_trans' => $this->input->post('id_trans'),
                'no_hp_cus' => $this->input->post('namacus'),
                'trans_masuk' => $this->input->post('transaksi_masuk'),
                'trans_ambil' => $this->input->post('transaksi_ambil'),
                'pembayaran' => $this->input->post('pembayaran'),
                'total_harga' => $this->input->post('total_harga'),
            );

            $this->M_transaksi->update_transaksi($data, $this->input->post('id_trans'));

            $this->session->set_flashdata('message', 'Data berhasil diperbarui.');
            redirect('transaksi');
        }
    }

    public function hapus($id_trans) {
        $this->M_transaksi->hapus_transaksi($id_trans);

        if ($this->db->affected_rows()) {
            $this->session->set_flashdata('message', 'Data berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Data gagal dihapus.');
        }
        redirect('transaksi');
    }
}
