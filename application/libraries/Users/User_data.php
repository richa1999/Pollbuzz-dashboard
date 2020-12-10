<?php

class User_data
{
    public $temp_array = array();
    public $counter = 0;
    public $doc;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->library('fetch_doc');
        $this->ci->load->library('auth');
        $this->connect_firestore = $this->ci->auth->firestore_auth();
        $this->doc = $this->ci->fetch_doc->fetch_document('Users');
    }

    public function users()
    {
        foreach($this->doc as $document) 
        {
            array_push($this->temp_array, $document->data());
        }
        return($this->temp_array);
    }

    public function users_count()
    {
        foreach($this->doc as $user) 
        {
            $this->counter++;
        }
        return($this->counter);
    }

    public function users_created_polls()
    {
        $Colref = $this->connect_firestore->collection('Users')->listDocuments();
        foreach($Colref as $document) 
        {
            $snapshot = $document->collection('Created')->listDocuments();
            foreach($snapshot as $collection) 
            {
                array_push($this->temp_array, $collection->id());
            }
        }
        return($this->temp_array);
    }
}
?>