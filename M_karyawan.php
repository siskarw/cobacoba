<?php
class M_karyawan extends CI_Model
{
    public function get_all_karyawan()
    {
        return $this->db->get('karyawan')->result_array();
    }

    public function get_karyawan_by_id($id)
    {
        return $this->db->get_where('karyawan', ['id_karyawan' => $id])->row_array();
    }

    public function insert_karyawan($data)
    {
        $this->db->insert('karyawan', $data);
    }

    public function hitung_karyawan()
    {
    return $this->db->count_all('karyawan');
    }


    public function update_karyawan($id, $data)
    {
        $this->db->where('id_karyawan', $id);
        $this->db->update('karyawan', $data);
    }

    public function delete_karyawan($id)
    {
        $this->db->where('id_karyawan', $id);
        $this->db->delete('karyawan');
    }
}