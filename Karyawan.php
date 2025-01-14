<?php

class Karyawan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_karyawan');
    }

    public function index()
    {
        $data['karyawan'] = $this->M_karyawan->get_all_karyawan();
        $data['jumlah_karyawan'] = $this->M_karyawan->hitung_karyawan();
    
        $this->load->view('karyawan/index', $data);
    }
    
    public function tambah()
    {
        if ($this->input->post()) {
            $data = [
                'id_karyawan' => $this->input->post('id_karyawan'),
                'nama_karyawan' => $this->input->post('nama_karyawan'),
            ];

            $this->M_karyawan->insert_karyawan($data);
            $this->session->set_flashdata('data', 'tambah');
            redirect('karyawan');
        } else {
            $this->load->view('karyawan/tambah');
        }
    }

    public function edit($id)
    {
        $data['karyawan'] = $this->M_karyawan->get_karyawan_by_id($id);

        if (!$data['karyawan']) {
            redirect('karyawan');
        }

        if ($this->input->post()) {
            $data_update = [
                'nama_karyawan' => $this->input->post('nama_karyawan'),
            ];

            $this->M_karyawan->update_karyawan($id, $data_update);
            $this->session->set_flashdata('data', 'edit');
            redirect('karyawan');
        }

        $this->load->view('karyawan/edit', $data);
    }

    public function hapus($id)
    {
        $karyawan = $this->M_karyawan->get_karyawan_by_id($id);

        if ($karyawan) {
            $this->M_karyawan->delete_karyawan($id);
            $this->session->set_flashdata('data', 'hapus');
        } else {
            $this->session->set_flashdata('error', 'Data karyawan tidak ditemukan.');
        }
        
        redirect('karyawan');
    }
}
?>
