<?php
require_once "Model/Protocol/JsonMySQLi.php";

class JsnModel{
    private $JsnSQL;
    
    function __construct(){
        
    }
    
    function processJSN($pJsnTok){
    /*
    
       {"tok":"token uuid","cmd":[{"NAME OF COMMAND":{JSON VALUE}]}}
    */ 
     try{ 
        $jsnSQLResult = "{\"JsnMsg\":\"AUTH_ERROR\",\"Msg\":\"Command not executed\"}";
        $lcAryTok = (array)json_decode($pJsnTok);
         
        if($lcAryTok == NULL){
            $e = new Exception("JSON decode failed");
        }
        // else
        //    echo "processJSN tok ".$lcAryTok['tok'];
         
        if( ! isset($lcAryTok['tok'])){
            $e = new Exception("JsnDrop no token");
            throw($e);
        } else{
            
             $lcTokValue = $lcAryTok['tok'];
             //echo "</br> Process JSN TOK = $lcTokValue";
             if( !JsonMySQLi::isAToken($lcTokValue)) {
                $e = new Exception("JsnDrop invalid token");
                throw($e) ;
             } 
             else {
                 if(! isset($lcAryTok['cmd'])){
                     $e = new Exception("JsnDrop no command");
                     throw($e); 
                 }
                 else {
                     $lcTokCmd = $lcAryTok['cmd'];
                     
                     if(!is_array($lcTokCmd)){
                        $lcTokCmd = array($lcTokCmd); 
                     }
                     //$this->JsnSQL = new JsonMySQLi()
                     $this->JsnSQL = new JsonMySQLi($lcTokValue,"localhost","root","ten9eight"); // DANGER
                     
                     $blnFirst = true;
                     foreach($lcTokCmd as $tokType => $tokValue){
                         
                        $aryValue = (array) $tokValue;
                        $state = "START";
                        $table = "";
                        $jsn = "";
                        foreach($aryValue as $cmdType => $cmdTbl){
                           
                            if($state == "START"){
                                $state = $cmdType;
                            }
                            //echo "</br> State is $state , Command is $cmdType";
                            switch($state){
                                case ("STORE"):
                                    
                                    if($cmdType == "STORE"){
                                        
                                        $table = $cmdTbl;
                                        
                                    } 
                                    else if( $cmdType == "VALUE"){
                                         
                                         $jsn = json_encode($cmdTbl);
                                         $this->JsnSQL->Table($table)->Store($jsn);
                                         $state = "START";
                                         $jsnSQLResult = "{\"JsnMsg\":\"SUCCESS.STORE\",\"Msg\":\"STORE $table executed\"}";
                                    } 
                                    else 
                                        $jsnSQLResult = "{\"JsnMsg\":\"ERROR.STORE\",\"Msg\":\"STORE $table is expecting an VALUE clause\"}";
                                    break;  
                                 case ("CREATE"):
                                    
                                    if($cmdType == "CREATE"){
                                        //echo "</br>Got to CREATE ";
                                        $table = $cmdTbl;
                                        
                                    } 
                                    else if( $cmdType == "EXAMPLE"){
                                        // echo "</br>Got to EXAMPLE";
                                        $jsn = json_encode($cmdTbl);
                                         $this->JsnSQL->Table($table,$jsn);
                                         $state = "START";
                                         $jsnSQLResult = "{\"JsnMsg\":\"SUCCESS.CREATE\",\"Msg\":\"create $table executed\"}";
                                    } 
                                    else 
                                        $jsnSQLResult = "{\"JsnMsg\":\"ERROR.CREATE\",\"Msg\":\"Create $table is expecting an EXAMPLE clause\"}";
                                    break;
                                case ("ALL"):
                                    //echo "</br>Got to ALL ";
                                    $table = $cmdTbl;
                                    $jsnQueryResult = $this->JsnSQL->Table($table)->All();
                                    $jsnSQLResult = "{\"JsnMsg\":\"SUCCESS.ALL\",\"Msg\":$jsnQueryResult}";
                                    $state = "START";
                                    break;
                                case ("DROP"):
                                    //echo "</br>Got to DROP ";
                                    
                                    $this->JsnSQL->Table($cmdTbl)->Drop();
                                    $jsnSQLResult = "{\"JsnMsg\":\"SUCCESS.DROP\",\"Msg\":\"Drop $table executed\"}";
                                    $state = "START";
                                
                                    break;
                                case ("DELETE"):
                                    //echo "</br>Got a Delete ";
                                    if($cmdType == "DELETE"){
                                        //echo "</br>Got to DELETE ";
                                        $table = $cmdTbl;
                                    } 
                                    else if( $cmdType == "WHERE"){
                                        $obj = (object)array('WHERE' => $cmdTbl);
                                        $jsn = json_encode($obj);
                                        
                                        $this->JsnSQL->Table($table)->Delete($jsn);
                                        $state = "START";
                                        $jsnSQLResult = "{\"JsnMsg\":\"SUCCESS.DELETE\",\"Msg\":\"Delete from $table executed\"}";
                                    }
                                    else 
                                        $jsnSQLResult = "{\"JsnMsg\":\"ERROR.DELETE\",\"Msg\":\"Delete from $table is expecting a WHERE clause\"}";
                                    break;
                                case ("SELECT"):
                                     
                                    if($cmdType == "SELECT"){
                                        //echo "</br>Got to Select";
                                        $table = $cmdTbl;
                                    } 
                                    else if( $cmdType == "WHERE"){
                                        //echo "</br>Got to SELECT WHERE";
                                        $obj = (object)array('WHERE' => $cmdTbl);
                                        $jsn = json_encode($obj);
                                        //echo "jsn = $jsn </br>";
                                        $jsnSelectResult = $this->JsnSQL->Table($table)->Where($jsn);
                                        $state = "START";
                                        $jsnSQLResult = "{\"JsnMsg\":\"SUCCESS.SELECT\",\"Msg\":$jsnSelectResult}";
                                    }
                                    else 
                                        $jsnSQLResult = "{\"JsnMsg\":\"ERROR.SELECT\",\"Msg\":\"Select from $table is expecting a WHERE clause\"}";
                                    break;
                                default: ;
                                   
                            } 
                        }
                        
                     }
                     return  $jsnSQLResult;
                 }// else have a cmd
             }// else have valid token
      }// else have a token
     }// try
     catch (mysqli_sql_exception $e){ 
             return "{\"JsnMsg\":\"ERROR.DATA_ERROR\",\"Msg\":\"Data error. ". $e->getMessage()."\"}";
     }
     catch(Exception $e){
            return "{\"JsnMsg\":\"ERROR.AUTH_ERROR\",\"Msg\":\"Invalid token or cmd.". $e->getMessage()."\"}";
     }
    }// function process Jsn
    
    
}// Class



?>