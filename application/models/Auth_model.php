<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function getUserByEmail($email){
        return $this->db->get_where('tb_user', ['email' => $email])->row_array();
    }

    public function insertUser($data) {
        return $this->db->insert('tb_user', $data);
    }

    public function insertLaporan($data) {
        return $this->db->insert('tb_user', $data);
    }

    public function getUser($tipe, $param = NULL){
        if ($tipe == 'all'){
            return $this->db->get('tb_user')->result_array();
        }

        if ($tipe == 'id_user'){
            return $this->db->get_where('tb_user', ['id_user' => $param])->row_array();
        }

        if ($tipe == 'level'){
            return $this->db->get_where('tb_user', ['level' => $param])->row_array();
        }
    }

    public function deleteUser($param){
        return $this->db->delete('tb_user', ['id_user' => $param]);
    }


}