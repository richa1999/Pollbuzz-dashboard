<?php

class Delete_doc_by_id
{
    public $connect_firestore;
    
    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->library('auth');
        $this->connect_firestore = $this->ci->auth->firestore_auth();
    }
    
    public function delete_document_by_id($collection_name,$id)
    {
        $snapshot = $this->connect_firestore->collection($collection_name)->document($id)->snapshot();
        $delete_doc = $snapshot->delete();  
    }
}