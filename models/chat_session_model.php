<?php
if (!defined('BASEPATH'))  exit('No direct script access allowed');

class Chat_session_model extends MY_Model {

    public $_table = TBL_CHAT_SESSION;
    public $primary_key = 'user_id';
    public $before_create = array();
    public $after_create = array();
    public $before_update = array();
    public $after_update = array();
    public $before_get = array();
    public $after_get = array();
    public $before_delete = array();
    public $after_delete = array();

    public function __construct() {
        parent::__construct();
    }



}