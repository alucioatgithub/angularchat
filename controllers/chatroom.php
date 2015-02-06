<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Chatroom extends Portal_Controller
{	
	var $models = array(
                        'chat',
                        'user/user' => 'user',
                        'chat_files',
                        'chat_session'
                        );

	public function __construct()
	{
		parent::__construct();
		/**if(!_can('chat'))
		{
			set_notify('danger', 'Access denied');
            redirect('account');
		}**/		  
	}

    public function chat()
    {
        $this->template->title('Test Chat Room')->build('test_chat', $this->data);
    }

	public function index()
	{
		$this->chatroom();
	}

	public function chatroom() 
	{
        if(!_can('chat'))
        {
            set_notify('danger', 'Access denied');
            redirect('account');
        }

        $this->data['active_users'] = $this->chat->get_active_users();
		$this->data['chat'] = $this->chat->get_all();
        //$this->data['userchat'] = $this->chat->get_many_by(array('user_id' => 160));
		// $this->template->title('Chat Room')->build('chatroom', $this->data);
        $this->template->title('Chat Room')->build('ng_chatroom', $this->data);
	}

	function save_message() {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('chat_message', 'Message', 'trim|required|xss_clean');
            if ($this->form_validation->run() == TRUE) {
                $user_id = $this->current_user->user_id;

                $chat_messsage = array(
                    'user_id' => $user_id,
                    'message' => $this->input->post('chat_message'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'is_view' => 1,
                    'is_reply' => 0);

                $this->chat->insert($chat_messsage);                
                $chat_messsage['created_at'] = get_chat_log_time($chat_messsage['created_at']);
                // $chat_messsage['created_at'] = date(DATE_FORMAT . " h:i A", strtotime($chat_messsage['created_at']));

                echo json_encode(array('status' => 'true', 'chat_messsage' => $chat_messsage));
                die();
            } else
                echo json_encode(array('status' => 'false', 'error_msg' => form_error('chat_message')));
        }
    }

    function get_messages() 
    {
        // $messages = $this->chat->get_many_by(array('user_id' => $this->current_user->user_id));
        // var_dump($this->input->post('unread'));die();
        $messages = $this->chat->get_chat_logs($this->current_user->user_id, NULL, 0, array(), $this->input->post('unread'));

        // $messages = $this->chat->get_chat_logs($this->current_user->user_id);

        $fetched_chat_ids = pluck($messages, 'chat_id');

        $this->chat->mark_seen($fetched_chat_ids);        

        $new_message = array();

        $this->update_session();
        $page = '';
        if (!empty($messages)) {
            
            foreach ($messages as $k => $res) {
                $med['chat'] = $res;
                
                $file = $this->chat_files->get($res->file);
                //to show recently uploaded media files
                header("Content-Type: text/plain");
                $med['file'] = $file;
                $section = $this->load->view('footer_chat', $med, TRUE);
                $page = $page.$section;
            }
            
        }
        echo json_encode(array('status' => 'true', 'page' => $page ));
    }

    function update_session() {
        $user_id = $this->current_user->user_id;

        $session = $this->chat_session->get($user_id);

        if(empty($session)) {
            $this->chat_session->insert(array('user_id' => $user_id, 'updated_at' => date('Y-m-d H:i:s')));
        }else {
            $this->chat_session->update($user_id, array('updated_at' => date('Y-m-d H:i:s')));
        }

    }


    public function upload_files()
    {
        $dir = "./uploads/chat";
        $this->create_dir($dir);

        $upload_data = $this->upload_file();

        if ($upload_data['status'] == 'False') {
            echo json_encode(array('status' => 'false', 'error' => $upload_data['error']));
            exit;
        }

        //add file
        $file_id = $this->_save_file($upload_data);

        //add to database
        $chat = $this->_save_record($upload_data, $file_id, 1);

        //to show recently uploaded media files
        header("Content-Type: text/plain");
        $file = $this->chat_files->get($file_id);

        $med['is_user'] = ($this->input->post('user_id') == 'user')?'user':'admin';
        $med['file'] = $file;

        header("Content-Type: text/plain");
        $med['chat'] =$chat;
        $med['file'] = $file;

        if($this->input->post('user_id') == 'user')
            $page = $this->load->view('footer_chat', $med, TRUE);
        else
            $page = $this->load->view('view_file', $med, TRUE);
        echo json_encode(array('status' => 'success', 'page' => $page));
    }

    public function ng_upload_files()
    {
        //sleep(30);
        $dir = "./uploads/chat";
        $this->create_dir($dir);

        $upload_data = $this->upload_file();

        if ($upload_data['status'] == 'False') {
            echo json_encode(array('status' => 'false', 'error' => $upload_data['error']));
            exit;
        }

        //add file
        $file_id = $this->_save_file($upload_data);

        //add to database
        $chat = $this->_save_record($upload_data, $file_id, 0);
        $file = $this->chat_files->get($file_id);

        $chat->file_name = $file->file;
        $chat->mime_type = $file->mime_type;

        echo json_encode(array('status' => 'success', 'file' => $chat));
        die();
                

    }


    function upload_file() {
        //sleep(300);

        if (!empty($_FILES)) {

            $file_ext = end(explode(".", $this->input->post('qqfilename'))); 

            $path = './uploads/chat/';

            $config['upload_path'] = $path;
            // $config['allowed_types'] = 'jpeg|gif|jpg|png|avi|mp3|mp4|txt';
            $config['allowed_types'] = '*';
            $config['max_size'] = '204800'; // 200 kB = 200 * 1024 bytes
            $config['file_name'] = $this->input->post('qqfilename');

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('qqfile')) {
                return array('status' => 'False', 'error' => $this->upload->display_errors());

            } else {
                $upload_data = $this->upload->data();
                return array(
                    'status' => 'True',
                    'file_name' => $upload_data['file_name'],
                    'file_path' => base_url('uploads/chat/' . $upload_data['file_name'])
                );
            }
        } else {
            return array('status' => 'False', 'error' => 'Something went wrong!');
        }
    }

    public function _save_file($upload_data = array())
    {
        $file_ext = end(explode(".", $this->upload->file_name));
        $upload_data = $this->upload->data();

        $data = array(
                        'file' => $upload_data['file_name'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'owner_id' => $this->current_user->user_id,
                        'mime_type' => $upload_data['is_image'] ? CHAT_IMAGE : 1
                    );

        $insert_id = $this->chat_files->insert($data);
        return $insert_id;
    }

    public function _save_record($upload_data='', $file_id='', $is_view = 1)
    {
        $user_id = $this->input->post('user_id');
        $chat_messsage = array(
                    'user_id' => ($user_id != 'user')? $user_id : $this->current_user->user_id,
                    'message' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'is_reply' => ($user_id != 'user')?1 : 0,
                    'file' => $file_id,
                    'is_view' => $is_view
        );

        $chat_id = $this->chat->insert($chat_messsage);  
        return $this->chat->get($chat_id);
    }

    public function create_dir($dir='')
    {
        //check if exists
        if (!file_exists($dir) && !is_dir($dir)) {
            //create dir
            mkdir($dir, 0777);      
        }
    }


    function download_file($name) {
        $name = str_replace(array('&#40;', '&#41;'), array('(', ')'), $name);
        $this->load->helper('download');
        $data = file_get_contents(base_url() . 'uploads/chat/' . $name); // Read the file's contents
        echo force_download($name, $data);
    }


}