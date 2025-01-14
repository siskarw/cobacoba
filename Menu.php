<?php

class Menu extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_menu');
    }

    public function index()
    {
        $data['menu'] = $this->M_menu->get_menu();
        $this->load->view('menu/index', $data);
    }

    public function tambah()
    {
        $this->load->view('menu/tambah');
    }

    public function edit($id_menu)
    {
        $menu = $this->M_menu->get_data_by_id($id_menu);

        if (!$menu) {
            show_404();
        }

        $data['menu'] = $menu;
        $this->load->view('menu/edit', $data);
    }

    public function aksi_edit()
    {
        $id_menu = $this->input->post('id_menu');
        $data = [
            'nama_menu' => $this->input->post('nama'),
            'jenis_menu' => $this->input->post('jenis'),
            'waktu' => $this->input->post('waktu'),
            'harga' => $this->input->post('harga'),
        ];

        if (!empty($_FILES['berkas']['name'])) {
            $upload = $this->upload_foto();
            if ($upload['status'] == 'success') {
                $data['foto'] = $upload['file_name'];
            }
        }

        $this->M_menu->update_menu($id_menu, $data);

        redirect('menu');
    }

    public function aksi_tambah()
    {
        $data = [
            'id_menu' => $this->input->post('id_menu'),
            'nama_menu' => $this->input->post('nama'),
            'jenis_menu' => $this->input->post('jenis'),
            'waktu' => $this->input->post('waktu'),
            'harga' => $this->input->post('harga'),
        ];

        $this->M_menu->insert_menu($data);

        redirect('menu');
    }

    public function hapus($id_menu)
    {
        if ($this->M_menu->hapus_menu($id_menu)) {
            $this->session->set_userdata('data', 'hapus');
            redirect('menu');
        } else {
            echo "Data gagal dihapus";
        }
    }
}
?>