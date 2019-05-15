<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 22.11.2018
 * Time: 18:38
 */

class Users_model extends  CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function usersget($data = array()){
        $this->db->select('id, username, registertime, online, coins, email, longitude, latitude');
        $this->db->from('users');
        if(isset($data['id'])) $this->db->where('id', $data['id']);
        if(isset($data['username'])) $this->db->where('username', $data['username']);
        if(isset($data['registertime'])) $this->db->where('registertime', $data['registertime']);
        if(isset($data['online'])) $this->db->where('online', $data['online']);
        if(isset($data['coins'])) $this->db->where('coins', $data['coins']);
        if(isset($data['email'])) $this->db->where('email', $data['email']);
        if(isset($data['latitude'])) $this->db->where('latitude', $data['latitude']);
        if(isset($data['longitude'])) $this->db->where('longitude', $data['longitude']);
        $q = $this->db->get();
        if($q->num_rows() > 0){
            return $q->result_array();
        }else{
            return false;
        }
    }



}