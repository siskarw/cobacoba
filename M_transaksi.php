<?php

class M_transaksi extends CI_Model
{
    public function get_transaksi() {
        $this->db->select('id_trans, no_hp_cus, id_karyawan, trans_masuk, trans_ambil, pembayaran, total_harga');
        $this->db->from('transaksi');
        $this->db->order_by('id_trans', 'ASC');
        return $this->db->get();
    }

    public function insert_transaksi($data)
    {
        if (!$this->db->insert('transaksi', $data)) {
            log_message('error', 'Failed to insert transaction: ' . $this->db->last_query());
            return false;
        }
        return true;
    }

    public function get_data_by_id($id_trans)
    {
        $this->db->select('id_trans, no_hp_cus, id_karyawan, trans_masuk, trans_ambil, pembayaran, total_harga');
        $this->db->from('transaksi');
        $this->db->where('id_trans', $id_trans);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query;
        } else {
            log_message('error', 'Transaction with ID ' . $id_trans . ' not found.');
            return false;
        }
    }

    public function update_transaksi($data, $id_trans)
    {
        $this->db->where('id_trans', $id_trans);
        if (!$this->db->update('transaksi', $data)) {
            log_message('error', 'Failed to update transaction: ' . $this->db->last_query());
            return false;
        }
        return true;
    }

    public function hapus_transaksi($id_trans)
    {
        $this->db->where('id_trans', $id_trans);
        if (!$this->db->delete('transaksi')) {
            log_message('error', 'Failed to delete transaction: ' . $this->db->last_query());
            return false;
        }
        return true;
    }
}