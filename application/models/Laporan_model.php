<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model {

    // public function getUserByEmail($email){
    //     return $this->db->get_where('tb_user', ['email' => $email])->row_array();
    // }

    // public function insertUser($data) {
    //     return $this->db->insert('tb_user', $data);
    // }
    
    public function insertLaporan($data) {
        return $this->db->insert('tb_laporan', $data);
    }

    public function getLaporan($tipe, $param = NULL){
        if ($tipe == 'all'){
            return $this->db->get('tb_laporan')->result_array();
        }

        if ($tipe == 'id_laporan'){
            return $this->db->get_where('tb_laporan', ['id_laporan' => $param])->row_array();
        }

        if ($tipe == 'id_user'){
            return $this->db->get_where('tb_laporan', ['id_user' => $param])->row_array();
        }
    }

    public function deleteLaporan($param){
        return $this->db->delete('tb_laporan', ['id_laporan' => $param]);
    }


}