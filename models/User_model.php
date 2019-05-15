<?php

class User_model extends CI_Model{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get($data){
        $this->db->select('id, username, password, registertime, online, coins, latitude, longitude');
        $this->db->from('users');
        //var_dump($data);
        if(isset($data['username']) && (!is_numeric($data['username']))) $this->db->where('users.username', $data['username']);
        if(isset($data['userid']) && (is_numeric($data['userid']))) $this->db->where('users.id', $data['userid']);
        $q=$this->db->get();
        if($q->num_rows() > 0){
            return $q->result_array()[0];
        }else{
            return false;
        }
    }

    public function update($data){
        if(isset($data['newusername'])) $this->db->set('users.username', $data['newusername']);
        if(isset($data['password'])) $this->db->set('users.password', $data['password']);
        if(isset($data['coins'])) $this->db->set('users.coins', $data['coins']);
        if(isset($data['latitude'])) $this->db->set('users.latitude', $data['latitude']);
        if(isset($data['longitude'])) $this->db->set('users.longitude', $data['longitude']);
        if(isset($data['online'])) $this->db->set('users.online', $data['online']);
        if(isset($data['id'])) $this->db->where('users.id', $data['id']);
        if(isset($data['username'])) $this->db->where('users.username', $data['username']);

        $this->db->update('users');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function insert($data){
            $this->db->set('username', $data['username']);
            $this->db->set('password', $data['password']);
            $this->db->set('email', $data['email']);
            $this->db->set('registertime', time());
            $this->db->insert('users');
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }

    }

    public function password_check($username, $password){
        $this->db->select('id, password');
        $this->db->from('users');
        $this->db->where('username', $username);
        //$this->db->where('password', $password);
        $q=$this->db->get();
        if($q->num_rows() > 0){
            if(password_verify($password, $q->result_array()[0]['password'])){
                return $q->result_array()[0]['id'];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function get_near_users($data){
        $user = $this->get($data);
        $longitude = $user['longitude'];
        $latitude = $user['latitude'];

        $this->db->select('id, username, online, coins, latitude, longitude');
        $this->db->from('users');
        $this->db->where('latitude>', $latitude-0.001);
        $this->db->where('latitude<', $latitude+0.001);
        $this->db->where('longitude>', $longitude-0.001);
        $this->db->where('longitude<', $longitude+0.001);
        $czas = (time() - 60*30);
        $this->db->where('online>', $czas);
        $q = $this->db->get();
        if($q->num_rows() >0){
            return $q->result_array();
        }else{
            return false;
        }
    }
}