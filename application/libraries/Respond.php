<?php

class Respond{

    public function __construct(){
        $this->ci = &get_instance();
    }

    public function send_response($Result_data)
    {
        $this->ci->response($Result_data, REST_Controller::HTTP_OK);
    }
}