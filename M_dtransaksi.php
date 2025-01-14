<?php
class M_dtransaksi extends CI_Model
{
    public function getAllDetailTransaksi()
    {
        $this->db->select('detail_transaksi.id_detail, detail_transaksi.id_trans, 
                           transaksi.id_trans, menu.nama_menu, karyawan.nama_karyawan, 
                           detail_transaksi.kuantitas, detail_transaksi.harga, 
                           detail_transaksi.subtotal, detail_transaksi.trans_masuk, 
                           detail_transaksi.trans_ambil, detail_transaksi.id_karyawan, 
                           detail_transaksi.no_hp_cus');
        $this->db->from('detail_transaksi');
        $this->db->join('transaksi', 'transaksi.id_trans = detail_transaksi.id_trans');
        $this->db->join('menu', 'menu.id_menu = detail_transaksi.id_menu');
        $this->db->join('karyawan', 'karyawan.id_karyawan = detail_transaksi.id_karyawan');
        $this->db->order_by('detail_transaksi.id_detail', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_all_menu()
    {
        return $this->db->get('menu')->result_array();
    }

    public function get_all_karyawan()
    {
        return $this->db->get('karyawan')->result_array();
    }

    public function insertDetailTransaksi($data)
    {
        if (!$this->db->insert('detail_transaksi', $data)) {
            log_message('error', 'Failed to insert detail transaksi: ' . $this->db->last_query());
            return false;
        }
        return true;
    }

    public function getDetailById($id_detail)
    {
        $this->db->select('detail_transaksi.id_detail, detail_transaksi.id_trans, 
                           transaksi.id_trans, menu.nama_menu, karyawan.nama_karyawan, 
                           detail_transaksi.kuantitas, detail_transaksi.harga, 
                           detail_transaksi.subtotal, detail_transaksi.trans_masuk, 
                           detail_transaksi.trans_ambil, detail_transaksi.id_karyawan, 
                           detail_transaksi.no_hp_cus');
        $this->db->from('detail_transaksi');
        $this->db->join('transaksi', 'transaksi.id_trans = detail_transaksi.id_trans');
        $this->db->join('menu', 'menu.id_menu = detail_transaksi.id_menu');
        $this->db->join('karyawan', 'karyawan.id_karyawan = detail_transaksi.id_karyawan');
        $this->db->where('detail_transaksi.id_detail', $id_detail);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row_array() : false;
    }

    public function updateDetailTransaksi($id_detail, $data)
    {
        if (!$this->checkTransaksiExists($data['id_trans'])) {
            log_message('error', 'ID Transaksi ' . $data['id_trans'] . ' tidak ada di tabel transaksi.');
            return false;
        }

        $this->db->where('id_detail', $id_detail);
        if (!$this->db->update('detail_transaksi', $data)) {
            log_message('error', 'Failed to update detail transaksi: ' . $this->db->last_query());
            return false;
        }
        return true;
    }

    public function checkTransaksiExists($id_trans)
    {
        $this->db->where('id_trans', $id_trans);
        $query = $this->db->get('transaksi');
        return $query->num_rows() > 0;
    }

    public function deleteDetailTransaksi($id_detail)
    {
        if (empty($id_detail) || !is_numeric($id_detail)) {
            log_message('error', 'Invalid id_detail for delete: ' . $id_detail);
            return false;
        }

        $this->db->where('id_detail', $id_detail);
        if (!$this->db->delete('detail_transaksi')) {
            log_message('error', 'Failed to delete detail transaksi: ' . $this->db->last_query());
            return false;
        }

        $this->db->where('id_trans', $id_detail);
        $query = $this->db->get('detail_transaksi');
        if ($query->num_rows() == 0) {
            $this->db->where('id_trans', $id_detail);
            if (!$this->db->delete('transaksi')) {
                log_message('error', 'Failed to delete transaksi: ' . $this->db->last_query());
                return false;
            }
        }

        return true;
    }

    public function getTotalDetailTransaksi()
    {
        $this->db->from('detail_transaksi');
        return $this->db->count_all_results();
    }
}
?>