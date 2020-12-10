<?php
require APPPATH . "libraries/REST_Controller.php";

class Login extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('admin/login_user');
        $this->load->library('respond');
    }

    public function index_get()
    {
        $result = $this->login_user->token();
        $response = $this->respond->send_response($result);
    }

}