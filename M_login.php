<?php
class M_login extends CI_Model
{
    public function cek_karyawan($nama_karyawan, $id_karyawan)
    {
        return $this->db->get_where('karyawan', array(
            'nama_karyawan' => $nama_karyawan,
            'id_karyawan' => $id_karyawan
        ));
    }
}
