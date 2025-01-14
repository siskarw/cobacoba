<?php
class M_menu extends CI_Model
{
    public function get_menu()
    {
        return $this->db->get('menu')->result_array();
    }

    public function get_all_menu() {
        $query = $this->db->get('menu');
        return $query->result_array();
    }

    public function get_data_by_id($id_menu)
    {
        $query = $this->db->get_where('menu', array('id_menu' => $id_menu));
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return null;
    }

    public function insert_menu($data)
    {
        return $this->db->insert('menu', $data);
    }

    public function update_menu($id_menu, $data)
    {
        $this->db->where('id_menu', $id_menu);
        return $this->db->update('menu', $data);
    }

    public function hapus_menu($id_menu)
    {
        $this->db->where('id_menu', $id_menu);
        return $this->db->delete('menu');
    }
}