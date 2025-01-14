<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashboardModel extends CI_Model
{
    public function getDetailedTransactions()
{
    $this->db->select('
        transaksi.id_trans,
        transaksi.trans_masuk AS tanggal,
        transaksi.trans_ambil,
        transaksi.total_harga,
        transaksi.pembayaran,
        karyawan.nama_karyawan,
        customer.nama AS nama_customer,
        customer.no_hp_cus AS no_hp_customer,
        customer.point_cust,
        detail_transaksi.id_menu,
        detail_transaksi.kuantitas as jumlah
    ');
    $this->db->from('transaksi');
    $this->db->join('karyawan', 'karyawan.id_karyawan = transaksi.id_karyawan', 'left');
    $this->db->join('customer', 'customer.no_hp_cus = transaksi.id_trans', 'left'); 
    $this->db->join('detail_transaksi', 'detail_transaksi.id_trans = transaksi.id_trans', 'left');
    $query = $this->db->get();

    return $query->result_array();
}

    public function getTotalCustomers()
    {
        return $this->db->count_all('customer');
    }

    public function getTotalTransactions()
    {
        return $this->db->count_all('laporan_transaksi');
    }

    public function getTotalIncome()
    {
        $this->db->select_sum('total_harga');
        $query = $this->db->get('laporan_transaksi');
        return $query->row()->total_harga;
    }


    public function getTotalMenus()
    {
        return $this->db->count_all('menu');
    }
    public function getMonthlyEarnings()
    {
        $this->db->select('MONTH(transaksi.trans_masuk) as month, SUM(transaksi.total_harga) as earnings');
        $this->db->group_by('MONTH(transaksi.trans_masuk)');
        $query = $this->db->get('transaksi');
        return $query->result_array();
    }
public function getMenuStatistics()
    {
        $this->db->select('
            menu.nama_menu,
            SUM(detail_transaksi.kuantitas) as jumlah
        ');
        $this->db->join('menu', 'menu.id_menu = detail_transaksi.id_menu', 'left');
        $this->db->group_by('menu.nama_menu');
        $query = $this->db->get('detail_transaksi');
        $total = 0;
        foreach ($query->result_array() as $row) {
            $total += $row['jumlah'];
        }

        $statistics = array_map(function ($row) use ($total) {
            $row['persentase'] = ($row['jumlah'] / $total) * 100;
            return $row;
        }, $query->result_array());

        return $statistics;
    }
}