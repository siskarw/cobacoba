<?php
ob_start();
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_login');
    }

    public function index()
    {
        $this->load->view('login/index');
    }

    public function aksi_login()
    {
        $nama_karyawan = $this->input->post('nama');
        $id_karyawan = $this->input->post('id');

        $cek = $this->M_login->cek_karyawan($nama_karyawan, $id_karyawan)->num_rows();

        if ($cek > 0) {
            $data_session = array(
                'nama' => $nama_karyawan,
                'status' => "Login"
            );
            $this->session->set_userdata('login', $data_session);
            $this->session->set_userdata('data', 'welcome');
            redirect('dashboard');
        } else {
            $this->session->set_userdata('gagal', 'gagal');
            redirect('login');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('login');
        $this->session->set_flashdata('message_display', 'Successfully Logout');
        redirect('login');
    }
}
?>