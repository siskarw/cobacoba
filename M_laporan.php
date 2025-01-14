<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_laporan extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_laporan($start_date = null, $end_date = null, $status = null, $karyawan = null) {
        $this->db->select('t.id_trans, t.trans_masuk, t.trans_ambil, t.total_harga, t.pembayaran, k.nama_karyawan, c.nama, c.point_cust');
        $this->db->from('transaksi t');
        $this->db->join('karyawan k', 'k.id_karyawan = t.id_karyawan', 'left');
        $this->db->join('customer c', 'c.no_hp_cus = t.no_hp_cus', 'left');

        if ($start_date && $end_date) {
            $this->db->where('t.trans_masuk >=', $start_date);
            $this->db->where('t.trans_ambil <=', $end_date);
        }

        if ($status) {
            $this->db->where('t.pembayaran', $status);
        }

        if ($karyawan) {
            $this->db->like('k.nama_karyawan', $karyawan);
        }

        $this->db->order_by('t.id_trans', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    public function get_poin_summary() {
        $this->db->select('MAX(point_cust) as poin_tertinggi, MIN(point_cust) as poin_terendah');
        $this->db->from('customer');
        $query = $this->db->get();
        return $query->row();
    }
}
?>