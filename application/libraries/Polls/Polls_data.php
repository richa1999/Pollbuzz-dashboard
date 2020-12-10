<?php

class Polls_data
{
    public $temp_array = array();
    public $counter = 0;
    public $doc;
    public $sub_doc;
    public $connect_firestore;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->library('fetch_doc');
        $this->ci->load->library('auth');
        $this->doc = $this->ci->fetch_doc->fetch_document('Polls');
        $this->connect_firestore = $this->ci->auth->firestore_auth();
    }

    public function polls()
    {
        foreach($this->doc as $document) 
        {
            array_push($this->temp_array, $document->data());
        }
        return ($this->temp_array);
    }

    public function polls_count()
    {
        foreach($this->doc as $polls) 
        {
            $this->counter++;
        }
        return($this->counter);
    }

    public function polls_created_today()
    {
        $today = date_create();
        foreach($this->doc as $document)
        {
            if(date('d/m/Y',$document['timestamp']) == date_format($today,'d/m/Y'))
            {
                return( $document->id());
            }
        }
    }

    public function polls_created_in_timespan()
    {
        $from = date_create('31 april,2020');
        $to = date_create('5 june,2020');
        foreach($this->doc as $document)
        {
            if(date('d/m/Y',$document['timestamp']) >= date_format($from,'d/m/Y') && date('d/m/Y',$document['timestamp']) >= date_format($to,'d/m/Y'))
            {
                array_push($this->temp_array, $document->id());
            }
        }
        return($this->temp_array);
    }
    
     public function votes_count()
    {
        $collection = $this->connect_firestore->collection('Polls')->listDocuments();
        foreach($collection as $document) 
        {
            $snapshot = $document->collection('Response')->listDocuments();
            foreach($snapshot as $sub_document) 
            {
                $this->counter++;
            }
            $this->temp_array[$document->id()] = $this->counter;
            $this->counter=0;
        }
        return($this->temp_array);
    }

     public function rank_poll_by_votes()
    {
        $collection = $this->connect_firestore->collection('Polls')->listDocuments();
        foreach($collection as $document){
            $snapshot = $document->collection('Response')->listDocuments();
            foreach($snapshot as $sub_document) 
            {
                $this->counter++;
            }
            $this->temp_array[$document->id()] = $this->counter;
            arsort($this->temp_array);
            $this->counter=0;
        }
        return($this->temp_array);
    }    

    public function polls_options_images()
    {
        foreach($this->doc as $document) 
        {
            $map = $document->get('map');
            $keys = array_keys($map);
            foreach($keys as $map_key) 
            {
                if(preg_match("/(https:)/", $map_key)) 
                {
                    if(!in_array($document->id(), $this->temp_array)) 
                    {
                        $this->temp_array[$document->id()] = $keys;
                    }
                }
            }
        }
        return($this->temp_array);
    }
    
}
?>