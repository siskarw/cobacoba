<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_customer extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_customer() {
        $query = $this->db->get('customer');
        return $query->result_array();
    }

    public function get_customer_by_no_hp($no_hp_cus) {
        $this->db->where('no_hp_cus', $no_hp_cus);
        $query = $this->db->get('customer');

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            log_message('error', 'Customer with phone number ' . $no_hp_cus . ' not found.');
            return false;
        }
    }

    public function add_customer($data) {
        if (!$this->db->insert('customer', $data)) {
            log_message('error', 'Failed to insert customer: ' . $this->db->last_query());
            return false;
        }
        return true;
    }

    public function update_customer($no_hp_cus, $data) {
        $this->db->where('no_hp_cus', $no_hp_cus);
        if (!$this->db->update('customer', $data)) {
            log_message('error', 'Failed to update customer: ' . $this->db->last_query());
            return false;
        }
        return true;
    }

    public function delete_customer($no_hp_cus) {
        $this->db->where('no_hp_cus', $no_hp_cus);
        if (!$this->db->delete('customer')) {
            log_message('error', 'Failed to delete customer: ' . $this->db->last_query());
            return false;
        }
        return true;
    }

    public function get_customer() {
        $query = $this->db->get('customer');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            log_message('error', 'No customers found in the database.');
            return [];
        }
    }
}