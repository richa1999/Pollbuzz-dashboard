<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once ('../vendor/autoload.php');

use \Firebase\JWT\JWT;
use Google\Cloud\Firestore\FirestoreClient;

class Auth
{
    protected $projectID;
    protected $keyFilePath;
    protected $token_key;
    protected $token_algorithm;
    protected $token_header;
    protected $token_expire_time;

    public function __construct()
    {   
        $this->ci = &get_instance();
        $this->ci->load->config('jwt');
        $this->ci->load->config('firestore_auth');
        $this->token_key = $this->ci->config->item('jwt_key');
        $this->token_algorithm = $this->ci->config->item('jwt_algorithm');
        $this->token_header = $this->ci->config->item('token_header');
        $this->projectID = $this->ci->config->item('project_ID');
        $this->keyFilePath = $this->ci->config->item('keyFilePath');
    }

    public function firestore_auth()
    {
        $db_connect = new FirestoreClient([
            'projectId' => $this->projectID,
            'keyFilePath' => $this->keyFilePath
        ]);
        return $db_connect;
    }

    public function generateToken($data = null)
    {
        if($data && is_array($data))
        {
           try
           {
               return JWT::encode($data, $this->token_key, $this->token_algorithm, $this->token_expire_time);
           }
           catch(Exception $e)
           {
               return 'Message: ' .$e->getMessage();
           }
        }
        else
        {
            return "Token Data Undefined!";
        }
    }
    
    public function validateToken()
    {
        try
        {
            $headers = $this->ci->input->request_headers();
            $token_data = $this->tokenExist($headers);
            if($token_data['status'] === TRUE)
            {
                $token_decode = JWT::decode($token_data['token'], $this->token_key, array($this->token_algorithm));
                $time_difference = strtotime('now') - $token_decode->iat;
                $token_expire_time = $token_decode->exp;
                if($time_difference >= $token_expire_time)
                {
                    return['status' => FALSE, 'message' => 'Token Time Expired'];
                }
                else
                {
                    return['status' => TRUE, 'data' => $token_decode];
                }
            }
            else
            {
                return['status' => FALSE, 'message' => $token_data['message']];
            }
        }
        catch(Exception $e)
        {
            return['status'=> FALSE, 'message' => $e->getMessage()];
        }
    }

    private function tokenExist($headers)
    {
        if(!empty($headers))
        {
            foreach($headers as $header_name => $header_value)
            {
                if(strtolower(trim($header_name)) == strtolower(trim($this->token_header)))
                    return['status' => TRUE, 'token' => $header_value];
            }
        }
        return['status' => FALSE, 'message' => 'Token is not defined.'];
    }

    public function verification($status)
    {
        if($status['status'] == TRUE)
        {
            return TRUE;
        }
        else 
        {
            return FALSE;
        }
    }
}



