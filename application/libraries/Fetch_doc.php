<?php

class Fetch_doc
{
    public $connect_firestore;
    
    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->library('auth');
        $this->connect_firestore = $this->ci->auth->firestore_auth();
    }

    public function fetch_document($collection_name)
    {
        $collection = $this->connect_firestore->collection($collection_name);
        $documents = $collection->documents();
        return $documents;
    }
}