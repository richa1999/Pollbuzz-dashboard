<?php
require APPPATH . "libraries/REST_Controller.php";

class Users_poll extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('users/user_data');
        $this->load->library('respond');

    }

    public function index_get()
    { 
        $verify = $this->auth->validateToken();
        if($this->auth->verification($verify))
        {
            $result = $this->user_data->users_created_polls();
            $response = $this->respond->send_response($result);
        }
        else
        {
            $response = $this->respond->send_response($verify);
        }
    }
}
?>