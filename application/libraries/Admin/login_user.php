<?php

class Login_user
{
    public $doc;
    
    public function __construct(){
        $this->ci = &get_instance();
        $this->ci->load->library('auth');
        $this->ci->load->library('fetch_doc');
        $this->doc = $this->ci->fetch_doc->fetch_document('Admin');
    }

    public function token()
    {
        $data = json_decode(file_get_contents("php://input"));
        $docsRef = $this->doc;
        foreach($docsRef as $document)
        {
            if($document['username'] == $data->username && $document['password'] == $data->password)
            {
                $iat = time();
                $exp = $iat +600;
                $admin_data = array(
                    "username" => $document['username'],
                    "password" => $document['password']
                );

                $payload = array(
                    "iat" => $iat,
                    "exp" => $exp,
                    "data" => $admin_data
                );
                $token = $this->ci->auth->generateToken($payload);
                return($token);
            }
        }
    }
}