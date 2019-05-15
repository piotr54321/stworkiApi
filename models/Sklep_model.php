<?php

class  Sklep_model extends CI_Model{
    public function __construct()
    {
        $this->load->database();
        $this->load->model('user_model');
    }


    function plecak($dane){
        $this->db->select('plecak.id_uzytkownika as user_id, plecak.id_przedmiotu, plecak.ilosc, przedmioty.nazwa,przedmioty.fotografia, przedmioty.cena, przedmioty_kategorie.nazwa_kategorii');
        $this->db->from('plecak');
        $this->db->join('przedmioty', 'plecak.id_przedmiotu = przedmioty.id_przedmiotu');
        $this->db->join('przedmioty_kategorie', 'przedmioty_kategorie.id_kategorii = przedmioty.id_kategoria');
        if(isset($dane['user_id'])) $this->db->where('plecak.id_uzytkownika', $dane['user_id']);
        if(isset($dane['id_przedmiotu'])) $this->db->where('plecak.id_przedmiotu', $dane['id_przedmiotu']);
        if(isset($dane['ilosc'])) $this->db->where('plecak.ilosc >', $dane['ilosc']);

        //var_dump($dane);

        $q = $this->db->get();
        if($q->num_rows() > 0){
            return $q->result_array();
        }else{
            return false;
        }
    }

    function zmien_plecak($dane){
        $user = $this->user_model->get(['userid' => $dane['user_id']]);
        $przedmiot = $this->przedmioty_sklep($dane)[0];
        if($user['coins'] < $przedmiot['cena']){
            return false;
        }else {
            $this->db->set('coins', ($user['coins']-$przedmiot['cena']));
            $this->db->where('id', $dane['user_id']);
            $this->db->update('users');
            if (!$this->plecak(array('user_id' => $dane['user_id'], 'id_przedmiotu' => $dane['id_przedmiotu'], 'ilosc' => $dane['ilosc']))) {
                $this->db->set('id_uzytkownika', $dane['user_id']);
                $this->db->set('id_przedmiotu', $dane['id_przedmiotu']);
                $this->db->set('ilosc', $dane['ilosc']);
                $this->db->insert('plecak');
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $this->db->set('id_uzytkownika', $dane['user_id']);
                $this->db->set('id_przedmiotu', $dane['id_przedmiotu']);
                $this->db->set('ilosc', $dane['ilosc']);
                $this->db->update('plecak');
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    function przedmioty_sklep($dane = array()){
         $this->db->select('przedmioty.id_przedmiotu,przedmioty.nazwa, przedmioty.fotografia, przedmioty.cena, przedmioty_kategorie.nazwa_kategorii');
         $this->db->from('przedmioty');
         $this->db->join('przedmioty_kategorie', 'przedmioty.id_kategoria =przedmioty_kategorie.id_kategorii');
         if(isset($dane['id_przedmiotu'])) $this->db->where('przedmioty.id_przedmiotu', $dane['id_przedmiotu']);

         $q = $this->db->get();
        if($q->num_rows() > 0){
            return $q->result_array();
        }else{
            return false;
        }
    }
}