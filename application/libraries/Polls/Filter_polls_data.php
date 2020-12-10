<?php

class Filter_polls_data
{
    public $temp_array = array();
    public $doc;
    public $connect_firestore;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->library('fetch_doc');
        $this->ci->load->library('auth');
        $this->connect_firestore = $this->ci->auth->firestore_auth();
        $this->doc = $this->ci->fetch_doc->fetch_document('Polls');
    }

    public function filter_polls()
    {
        foreach($this->doc as $document) 
        {
            $ques = $document->get('question');
            if(preg_match("/[.,!@#$%^\s]|[z]/", $ques)) 
            {
                if(!in_array($document->id(), $this->temp_array)) 
                {
                    array_push($this->temp_array, $document->id());
                }
            }
        }
        return($this->temp_array);
    }

    public function filter_polls_option()
    {
        foreach($this->doc as $document) 
        {
            $map = $document->get('map');
            $keys = array_keys($map);
            foreach($keys as $map_key) 
            {
                if(preg_match("/[.,!@#$%^\s]|[jf]/", $map_key)) 
                {
                    if(!in_array($document->id(), $this->temp_array)) 
                    {
                        array_push($this->temp_array, $document->id());
                    }
                }
            }
        }
        return($this->temp_array);
    }

    public function filter_poll_spam()
    {
        $id_array = array();
        $ques_array = array();
        $Colref = $this->connect_firestore->collection('Users')->listDocuments();
        foreach($Colref as $document) 
        {
            $snapshot = $document->collection('Created')->documents();
            foreach($snapshot as $collection)
            {
                array_push($id_array , $collection['pollId']);
            }

            foreach($id_array as $id)
            {
                $docref = $this->connect_firestore->collection('Polls');
                $snapshot = $docref->document($id)->snapshot();
                if ($snapshot->exists()) 
                {
                   array_push($ques_array , $snapshot['question']);
                }
               
            }
            $this->temp_array[$document->id()] = $ques_array;
            $ques_array=array();
        }
        return($this->temp_array);
    }

}
?>