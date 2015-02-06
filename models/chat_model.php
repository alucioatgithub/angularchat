<?php
if (!defined('BASEPATH'))  exit('No direct script access allowed');

class Chat_model extends MY_Model {

    public $_table = TBL_CHAT;
    public $primary_key = 'chat_id';
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

    public function get_active_users($online_user_ids = NULL, $type = 'online') {
        if(!is_null($online_user_ids)) {
          if($type == 'online')
            $this->db->where_in('user_id', $online_user_ids);
          else 
            $this->db->where_not_in('user_id', $online_user_ids);
        }

        $query = $this->db->select('c.*,f.file as file_name')
                          ->from(TBL_CHAT . ' as c')
                          ->join(TBL_CHAT_FILES . ' as f', 'c.file = f.file_id', 'left')
                          ->where('user_id !=', 0)
                          // ->where('file is null')
                          ->order_by('c.created_at', 'asc')
                          // ->group_by('user_id')                          
                          ->get();
        if($query)
            return $query->result();
        else
            return false;
    }

    public function get_chat_logs($user_id, $perpage = NULL, $offset = NULL, $fetched_chat_ids = array(), $unread = 'false') {

        if(!is_null($perpage))
          $this->db->limit($perpage,$offset);

        if($unread == 'true')
          $this->db->where('is_view', 0);

        if($fetched_chat_ids)
          $this->db->where_not_in('c.chat_id', $fetched_chat_ids);

        $result = $this->db->select('c.*, cf.file as file_name, cf.mime_type')
                           ->from(TBL_CHAT . ' as c')
                           ->join(TBL_USER . ' as u', 'u.user_id = c.user_id')
                           ->join(TBL_CHAT_FILES . ' as cf', 'c.file = cf.file_id', 'left')
                           ->where('c.user_id', $user_id)
                           ->order_by('c.created_at', 'desc')
                           ->get()
                           ->result();

        $result = array_reverse($result);

        return $result;

    }

    function fetch_all_chat_ids() {
      $all_chat_ids = $this->db->select('chat_id')
                               ->from(TBL_CHAT)
                               ->get()
                               ->result();
      return $all_chat_ids;
    }

    function get_unread_msg($user_id, $fetched_chat_ids = array() ) {
        if($fetched_chat_ids)
          $this->db->where_not_in('chat_id', $fetched_chat_ids);

        $result =  $this->db->select('count(*) as count')
                         ->from(TBL_CHAT)
                         // ->where('is_view', 0)
                         ->where('is_reply', 0)
                         ->where('user_id', $user_id)
                         ->get()
                         ->row()->count;
        return $result;
    }

    function mark_seen($fetched_chat_ids) {
        if(empty($fetched_chat_ids))
          return false;

        return $this->db->where_in('chat_id', $fetched_chat_ids)
                        ->update(TBL_CHAT, array('is_view' => 1));
    }

}