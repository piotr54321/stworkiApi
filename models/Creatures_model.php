<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 22.11.2018
 * Time: 18:38
 */

class Creatures_model extends CI_Model{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function user_creatures($data = array()){
        $this->db->select('users_creatures.users_id, users_creatures.creatures_id, users_creatures.name, users_creatures.createtime, users_creatures.health, users_creatures.happy, users_creatures.hungry, users_creatures.current');
        $this->db->from('users_creatures');
        $this->db->join('creatures', 'users_creatures.creatures_id = creatures.id');
        $this->db->where('users_creatures.users_id', $data['users_id']);
        if(isset($data['id'])) $this->db->where('users_creatures.id', $data['id']);
        $q = $this->db->get();
        if($q->num_rows() > 0){
            return $q->result_array();
        }else{
            return false;
        }
    }

    function creature_info($data = array()){
        $this->db->select('id, name, image, sex');
        $this->db->from('creatures');
        if(isset($data['id'])) $this->db->where('id', $data['id']);
        $q = $this->db->get();
        if($q->num_rows() > 0){
            return $q->result_array();
        }else{
            return false;
        }
    }

    function new_creature($data = array()){
        if(empty($data)){
            return false;
        }
        $this->db->set('creatures_id', $data['creatures_id']);
        $this->db->set('users_id', $data['users_id']);
        $this->db->set('name', $data['name']);
        $this->db->set('current', 1);
        $this->db->set('createtime', time());
        $this->db->insert('users_creatures');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    function update_creature($data = array()){
        if(empty($data)){
            return false;
        }
        if(isset($data['name'])) $this->db->set('name', $data['name']);
        if(isset($data['health'])) $this->db->set('health', $data['health']);
        if(isset($data['happy'])) $this->db->set('happy', $data['happy']);
        if(isset($data['hungry'])) $this->db->set('hungry', $data['hungry']);
        if(isset($data['current'])) $this->db->set('current', $data['current']);
        $this->db->where('id', $data['id']);
        //$this->db->where('creatures_id', $data['creatures_id']);
        //$this->db->where('users_id', $data['users_id']);
        $this->db->update('users_creatures');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    function drop_creature($data){

    }
}