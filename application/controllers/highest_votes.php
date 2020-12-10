<?php
require APPPATH . "libraries/REST_Controller.php";

class Highest_votes extends REST_Controller
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
            $result = $this->polls_data->rank_poll_by_votes();
            $response = $this->respond->send_response($result);
        }
        else
        {
            $response = $this->respond->send_response($verify);
        }
    }
}
?>