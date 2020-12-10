
<?php
require APPPATH . "libraries/REST_Controller.php";

class Polls extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->load->library('polls/polls_data');
        $this->load->library('delete_doc_by_id');
        $this->load->library('respond');
    }

    public function index_get()
    {
        $verify = $this->auth->validateToken();
        if($this->auth->verification($verify))
        {
            $result = $this->polls_data->polls();
            $response = $this->respond->send_response($result);
        }
        else
        {
            $response = $this->respond->send_response($verify);
        }
    }

    public function index_delete($id)
    {
        $verify = $this->auth->validateToken();
        if($this->auth->verification($verify))
        {
            $result = $this->delete_doc_by_id->delete_document_by_id('Polls',$id);
            $response = $this->respond->send_response($result);
        }
        else
        {
            $response = $this->respond->send_response($verify);
        }
    }
}
?>