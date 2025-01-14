<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_customer');
    }

    public function index() {
        $data['customers'] = $this->M_customer->get_all_customer(); 
        $this->load->view('customer/index', $data);
    }

    public function tambah() {
        $this->form_validation->set_rules('no_hp_cus', 'Phone Number', 'required|is_unique[customer.no_hp_cus]');
        $this->form_validation->set_rules('nama', 'Name', 'required');
        $this->form_validation->set_rules('jenis_kelamin', 'Gender', 'required|in_list[L,P]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('customer/tambah');
        } else {
            $data = array(
                'no_hp_cus' => $this->input->post('no_hp_cus'),
                'nama' => $this->input->post('nama'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'point_cust' => $this->input->post('point_cust')
            );
            $this->M_customer->add_customer($data);
            redirect('customer');
        }
    }

    public function edit($no_hp_cus = null) {
        if ($no_hp_cus === null) {
            show_error('Parameter no_hp_cus tidak diberikan.', 400);
        }

        $data['customer'] = $this->M_customer->get_customer_by_no_hp($no_hp_cus);
        if (empty($data['customer'])) {
            show_404();
        }

        $this->form_validation->set_rules('nama', 'Name', 'required');
        $this->form_validation->set_rules('jenis_kelamin', 'Gender', 'required|in_list[L,P]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('customer/edit', $data);
        } else {
            $update_data = array(
                'nama' => $this->input->post('nama'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'point_cust' => $this->input->post('point_cust')
            );
            $this->M_customer->update_customer($no_hp_cus, $update_data);
            redirect('customer');
        }
    }

    public function delete($no_hp_cus) {
        $this->M_customer->delete_customer($no_hp_cus);
        redirect('customer');
    }
    
}
