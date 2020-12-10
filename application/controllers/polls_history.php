<?php
require APPPATH . "libraries/REST_Controller.php";

class Polls_history extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('polls/polls_data');
        $this->load->library('respond');
    }

    public function index_get()
    {
        $verify = $this->auth->validateToken();
        if($this->auth->verification($verify))
        {
            $result = $this->polls_data->polls_created_in_timespan();
            $response = $this->respond->send_response($result);
        }
        else
        {
            $response = $this->respond->send_response($verify);
        }
    }
}
?>