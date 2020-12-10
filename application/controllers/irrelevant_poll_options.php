<?php
require APPPATH . "libraries/REST_Controller.php";

class Irrelevant_poll_options extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('polls/filter_polls_data');
        $this->load->library('respond');
    }

    public function index_get()
    {
        $verify = $this->auth->validateToken();
        if($this->auth->verification($verify))
        {
            $result = $this->filter_polls_data->filter_polls_option();
            $response = $this->respond->send_response($result);
        }
        else
        {
            $response = $this->respond->send_response($verify);
        }
    }
}
?>