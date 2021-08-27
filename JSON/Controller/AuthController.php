<?php

// Quick sketch
require_once "Model/AuthModel.php";
require_once "Model/JsnModel.php";
class ClsAuthController {
    public $AuthModel;
    public $JsnModel;
    public $JsnDropResponse;
    
    function __construct(){
        $lcNextView = "AuthView.php";
        // All roads lead to Authorisation
        if(!empty($_REQUEST)){
             
            if(!empty($_REQUEST['a_username']) && !empty($_REQUEST['a_password']) ){
                $this->AuthModel = new AuthModel();
                if($this->AuthModel->LogIn($_REQUEST['a_username'],$_REQUEST['a_password']))
                   $lcNextView = "TokView.php" ;
            }
            if(!empty($_REQUEST['tok'])){
                $this->JsnModel = new JsnModel();
                $this->JsnDropResponse = $this->JsnModel->processJSN($_REQUEST['tok']);
                $lcNextView = "JsnView.php";
            }

        }
        
        require_once "View/".$lcNextView;
    }

}

$aClsAuth = new ClsAuthController()

?>
