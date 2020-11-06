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

    public function getLaporan($tipe, $param = NULL, $limit = NULL){
        if ($limit != NULL) {
            $this->db->limit($limit);            
        }
        
        if ($tipe == 'all'){            
            $this->db->order_by('id_laporan', 'DESC');
            return $this->db->get('tb_laporan')->result_array();
        }

        if ($tipe == 'id_laporan'){
            $this->db->select("tb_user.username, tb_laporan.*, tb_user.*, tb_laporan.alamat AS alamat_laporan, tb_laporan.dibuat_pada AS laporan_dibuat");
            $this->db->from('tb_laporan');
            $this->db->join('tb_user', 'tb_user.id_user = tb_laporan.id_user');
            $this->db->where('id_laporan', $param);
            return $this->db->get()->row_array();
        }

        if ($tipe == 'id_user'){
            $this->db->order_by('id_laporan', 'DESC');
            return $this->db->get_where('tb_laporan', ['id_user' => $param])->result_array();
        }
    }

    public function deleteLaporan($param){
        return $this->db->delete('tb_laporan', ['id_laporan' => $param]);
    }


}