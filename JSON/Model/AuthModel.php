<?php
require_once "Model/Protocol/JsonMySQLi.php";

class AuthModel{
    private $JsonSQL;
    public $JsonMsg;
    public $Msg;
    public $Name;
    public $Token;
    
    public function LogIn($pUserName,$pPassword){
        $result = false;
        // Danger Will Robinson??
	$cleanCMD = escapeshellcmd ("./pamperl.pl ".$pUserName." ".$pPassword);
        
        $loginJSN = exec($cleanCMD);
       
        $this->JsonMsg = rtrim($loginJSN,'0'); 
        $this->Msg = (json_decode($this->JsonMsg ))->JsnMsg;
        if( $this->Msg != "AUTH_ERROR"){
            $this->Name = $pUserName;
            $this->Token  = $this->GetAToken($pUserName);// Makes a token or retrieves it based on username
           
            $result = true;
        }
        else {
            $result = false;
        }
        return $result;
    }
    
    function GetAToken($pUserName){
        // JsnDrop wil only create one if this is a new user, otherwise it uses the token associated with the user
        $token= JsonMySQLi::getAToken($pUserName);
        return $token;
    }
    
    function __construct(){
    }
}



?>
