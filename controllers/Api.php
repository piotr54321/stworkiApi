<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 17.11.2018
 * Time: 15:50
 */

use Restserver\Libraries\REST_Controller;
require(APPPATH . 'libraries/REST_Controller.php');

class Api extends REST_Controller{
    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('user_model');
        $this->load->model('users_model');
        $this->load->model('creatures_model');
        $this->load->model('sklep_model');
    }

    function user_get(){
        if (null === $this->get('username') && null === $this->get('userid')){
            $this->response(array('code'=>'400'));
        }else {
            $dane = array();
            if (null !== $this->get('username')) $dane['username'] = $this->get('username');
            if (null !== $this->get('userid')) $dane['userid'] = $this->get('userid');
            if ($this->user_model->get($dane)) {
                $this->response($this->user_model->get($dane));
            } else {
                $this->response(array('code'=>'400'));
            }
        }
    }

    function user_post(){
        //var_dump($_REQUEST);
        //$this->db->set('text', json_encode($_REQUEST))->insert('logs');
        if(null == $this->post('username') && null == $this->post('id')){
            $this->response(array('code'=>'400'));
        }else{
            $dane = array();
            if(null !== $this->post('username')) $dane['username'] = $this->post('username');
            if(null !== $this->post('newusername')) $dane['newusername'] = $this->post('newusername');
            if(null !== $this->post('id')) $dane['id'] = $this->post('id');
            if(null !== $this->post('password')) $dane['password'] = $this->post('password');
            if(null !== $this->post('online')) $dane['online'] = time();
            if(null !== $this->post('coins')) $dane['coins'] = $this->post('coins');
            if(null !== $this->post('latitude')) $dane['latitude'] = $this->post('latitude');
            if(null !== $this->post('longitude')) $dane['longitude'] = $this->post('longitude');
            if($this->user_model->update($dane)){
                $this->response(array('code'=>'200'));
            }else{
                $this->response(array('code'=>'400'));
            }
        }
    }

    function user_put(){

        //var_dump($this->put('username'));
        //$this->db->set('text', json_encode($this->put('username')))->insert('logs');
        if(null == $this->put('username') || null == $this->put('password') || null == $this->put('email')){
            $this->response(array('code'=>'400'));
        }else{
            $dane = array();
            $dane['username'] = $this->put('username');
            $dane['password'] = $this->put('password');
            $dane['email'] = $this->put('email');
            if($this->user_model->insert($dane)){
                $this->response(array('code'=>'200'));
            }else{
                $this->response(array('code'=>'404'));
            }
        }
    }

    function passwordcheck_get(){
        if(null !== $this->get('username') && null !== $this->get('password')) {
            if ($this->user_model->password_check($this->get('username'), $this->get('password'))) {
                $this->response(array('code'=>'200', 'id'=>$this->user_model->password_check($this->get('username'), $this->get('password'))));
            }else{
                $this->response(array('code'=>'400'));
            }
        }else{
            $this->response(array('code'=>'400'));
        }
    }

    function users_get(){
        $dane = array();
        if(null !== $this->get('id')) $dane['id'] = $this->get('id');
        if(null !== $this->get('username')) $dane['username'] = $this->get('username');
        if(null !== $this->get('registertime')) $dane['registertime'] = $this->get('registertime');
        if(null !== $this->get('online')) $dane['online'] = $this->get('online');
        if(null !== $this->get('coins')) $dane['coins'] = $this->get('coins');
        if(null !== $this->get('email')) $dane['email'] = $this->get('email');
        if(null !== $this->get('longitude')) $dane['longitude'] = $this->get('longitude');
        if(null !== $this->get('latitude')) $dane['latitude'] = $this->get('latitude');
        $result = $this->users_model->usersget($dane);
        if($result){
            $this->response($result);
        }else{
            $this->response(array('code'=>'400'));
        }
    }

    function userscreatures_get()
    {
        if (null != $this->get('users_id')) {
            $dane = array();
            if (null != $this->get('id')) $dane['id'] = $this->get('id');
            if (null != $this->get('users_id')) $dane['users_id'] = $this->get('users_id');
            $result = $this->creatures_model->user_creatures($dane);
            if ($result) {
                $this->response($result);
            } else {
                $this->response(array(array('code'=>'400')));
            }
        } else {
            $this->response(array(array('code'=>'400')));
        }
    }

    function creature_get(){
        if(null != $this->get('id')){
            $dane = array();
            $dane['id'] = $this->get('id');
            $result = $this->creatures_model->creature_info($dane);
            if($result){
                $this->response($result);
            }else{
                $this->response(array(array('code'=>'400')));
            }
        }else{
            //$this->response(array(array('code'=>'400')));
            $result = $this->creatures_model->creature_info();
            $this->response($result);
        }
    }

    function creature_put(){
        if(null != $this->put('creatures_id') && null != $this->put('users_id') && null != $this->put('name')){
            $dane = array();
            $dane['creatures_id'] = $this->put('creatures_id');
            $dane['users_id'] = $this->put('users_id');
            $dane['name'] = $this->put('name');
            $result = $this->creatures_model->new_creature($dane);
            if($result){
                $this->response(array('code'=>'200'));
            }else{
                $this->response(array('code'=>'400'));
            }
        }else{
            $this->response(array('code'=>'400'));
        }
    }

    function creature_post(){
        if(null != $this->post('id')){
            $dane = array();
            if(null != $this->post('name')) $dane['name'] = $this->post('name');
            if(null != $this->post('health')) $dane['health'] = $this->post('health');
            if(null != $this->post('happy')) $dane['happy'] = $this->post('happy');
            if(null != $this->post('hungry')) $dane['hungry'] = $this->post('hungry');
            if(null != $this->post('current')) $dane['current'] = $this->post('current');
            $result = $this->creatures_model->update_creature($dane);
            if($result){
                $this->response(array('code'=>'200'));
            }else{
                $this->response(array('code'=>'400'));
            }
        }else{
            $this->response(array('code'=>'400'));
        }
    }

    function plecak_get(){
        $dane = array();
        //var_dump($this->get());

        if(null != $this->get('user_id')) $dane['user_id'] = $this->get('user_id');
        if(null != $this->get('id_przedmiotu')) $dane['id_przedmiotu'] = $this->get('id_przedmiotu');
        if(null != $this->get('ilosc')) $dane['ilosc'] = $this->get('ilosc');

        $result = $this->sklep_model->plecak($dane);
        if(!$result){
            $this->response(array('code'=>'400'));
        }else{
            $this->response($result);
        }
    }

    function przedmioty_get(){
        $dane=array();
        if(null != $this->get('id_przedmiotu')) $dane['id_przedmiotu'] = $this->get('id_przedmiotu');
        $result = $this->sklep_model->przedmioty_sklep($dane);
        if(!$result){
            $this->response(array('code'=>'400'));
        }else{
            $this->response($result);
        }
    }

    function plecak_post(){
        $dane=array();
        if((null != $this->post('user_id')) && (null != $this->post('id_przedmiotu')) && (null != $this->post('ilosc'))){

            $dane['user_id'] = $this->post('user_id');
            $dane['id_przedmiotu'] = $this->post('id_przedmiotu');
            $dane['ilosc'] = $this->post('ilosc');

            $result = $this->sklep_model->zmien_plecak($dane);
            if($result){
                $this->response(array('code'=>'200'));
            }else{
                $this->response(array('code'=>'400'));
            }
        }else{
            $this->response(array('code'=>'400'));
        }
    }

    function usersnear_get(){
        if (null === $this->get('username') && null === $this->get('userid')){
            $this->response(array('code'=>'400'));
        }else {
            $dane = array();
            if (null !== $this->get('username')) $dane['username'] = $this->get('username');
            if (null !== $this->get('userid')) $dane['userid'] = $this->get('userid');
            $near_users = $this->user_model->get_near_users($dane);
            if ($near_users) {
                $this->response($near_users);
            } else {
                $this->response(array('code'=>'400'));
            }
        }
    }
}