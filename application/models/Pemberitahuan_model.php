<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pemberitahuan_model extends CI_Model
{

    // public function getUserByEmail($email){
    //     return $this->db->get_where('tb_user', ['email' => $email])->row_array();
    // }

    // public function insertUser($data) {
    //     return $this->db->insert('tb_user', $data);
    // }

    public function insertPemberitahuan($data)
    {
        return $this->db->insert('tb_pemberitahuan', $data);
    }

    public function getPemberitahuan($tipe, $param = NULL)
    {
        $this->db->order_by('id_pemberitahuan', 'DESC');

        if ($tipe == 'all') {
            return $this->db->get('tb_pemberitahuan')->result_array();
        }

        if ($tipe == 'id_pemberitahuan') {
            return $this->db->get_where('tb_pemberitahuan', ['id_pemberitahuan' => $param])->row_array();
        }

        if ($tipe == 'id_user') {
            return $this->db->get_where('tb_pemberitahuan', ['id_user' => $param])->result_array();
        }
    }

    public function deletePemberitahuan($param)
    {
        return $this->db->delete('tb_pemberitahuan', ['id_pemberitahuan' => $param]);
    }
}
