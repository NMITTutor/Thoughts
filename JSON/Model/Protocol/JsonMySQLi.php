<?php
// Experiments in storing and retrieving JSON into MySQL
// -- uses at least PHP 5.4 , or perhaps PHP 7.0

define("MYSQL_CONN_ERROR", "Unable to connect to database."); 

// Ensure reporting is setup correctly 
mysqli_report(MYSQLI_REPORT_STRICT); 



class JsonMySQLi extends mysqli{
    
    public $_token = ""; // Should it be publlic??
    
    function __construct( )
    {
        try {
            
            // Construct the parent first
            // Since __construct has a number of optional arguments, aka parameters
            // here it gets the "args" as an array, then uses call_user_func_array to pass any of the "arguments"
            $args = func_get_args();
            
            // first arg is a token
            $this->_token = $args[0];
            $lcParentArgs = array_slice($args,1);
            
            // Construct MySQLi (the parent)    
            call_user_func_array(array($this, 'parent::__construct'), $lcParentArgs );
            
            // If the database does not exist create the database using the token as the name
            $res = parent::query("create database if not exists `$this->_token`;");
            
            // Select the database (for use)
            if(! parent::select_db("$this->_token")){
              $e = new mysqli_sql_exception("Can not select database ".$this->_token) ;
               throw($e);
            };
            
        }
        catch(mysqli_sql_exception $e){
            
            throw $e;
        }
    }
    
    // Would be nice to have inner objects in php
    /* private function contructInnerObject($pInnerClass)
    {
        // mostly from  https://stackoverflow.com/a/34598986/7571029
        $class = new ReflectionClass($pInnerClass);
        $constructor = $class->getConstructor();
        $constructor->setAccessible(true);
        $innerObject = $class->newInstanceWithoutConstructor(); // This method appeared in PHP 5.4
        $constructor->invoke($innerObject, $this, $name);
        return $innerObject;
    }
    */
    
    public function Table(){
        try{
            $args = func_get_args();
            $jsnTable = null;
            $argCount = count($args);

            if($argCount == 1){
                $strTableName = $args[0];
                return new JsonTable($this,$strTableName);  
            }else if($argCount == 2){
                $strTableName = $args[0];
                $objObject = $args[1];
                return new JsonTable($this,$strTableName,$objObject);
            }
            else{
                $e = new mysqli_sql_exception("Can not select database ".$this->_token) ;
                //echo "Can not select database ".$this->_token;
                throw($e);
                return null;
            }
        }
        catch(mysqli_sql_exception $e) {
            throw $e;
        }
   }
        
    
    
    // CLASS Statics 
    
    // For generating a token
     /**
     * Universally Unique Identifier v4
     *
     * @param  int   $b
     * @return UUID, if $b returns binary(16)
     * from https://github.com/nbari/DALMP
     */
    private static  function UUID($b = null)
    {
        
        if (function_exists('uuid_create')) {
            $uuid = uuid_create();
        } else {
            $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xfff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
        }
        return $b ? pack('H*', str_replace('-', '', $uuid)) : $uuid;
    }
    
    public static function makeToken(){
        $token = self::UUID();
        
        return $token;
    }
    
    public static function getAToken($pUserName){
     try{
        $mySQL = new MySQLi("localhost","root","ten9eight");
        $token = "";
        
        // See if we have this username in the Json database
             if(! $mySQL->select_db("Json")){
              $e = new mysqli_sql_exception("Can not select database Json") ;
               throw($e);
            };
            $name = $pUserName;
           
            $res = $mySQL->query("SELECT Token FROM tblUser WHERE Name = '$name';");
            if( $row = $res->fetch_assoc())
                $token = $row['Token'];
            else{
                $token = self::makeToken();
                
                $mySQL->query("INSERT INTO tblUser(Name,Token) VALUES ('$name','$token')");
            }
         
         return $token;
        }
        
        
        catch(mysqli_sql_exception $e){

            throw $e;
        }
           
    }// GetAToken
    
    public static function isAToken($pToken){
      try{
        $result = false;
          
        $mySQL = new MySQLi("localhost","root","ten9eight");
        $token = "";
        
        // See if we have this username in the Json database
             if(! $mySQL->select_db("Json")){
              $e = new mysqli_sql_exception("Can not select database Json") ;
               throw($e);
            };
            $token = $pToken;
           
            $res = $mySQL->query("SELECT Token FROM tblUser WHERE Token = '$token';");
            if( $row = $res->fetch_assoc())
                $result = true;
            else{
                $result = false;
            }
         
         return $result;
        }
        
        
        catch(mysqli_sql_exception $e){

            throw $e;
        }
    }// IsAToken
    

}

// A JsonTable is an innner class, of JsonMySQLi

class JsonTable{
    public  $Name;
    private $objJsnSQL;
    private $strJSON;
    private $aryCurrentRow;
    private $blnHaveTable;
    private $sqlResult;
    
    private function ToSQLType($pValue)
    {
        try{
            $blnHasException = false;
            $strExceptionMessage = "";
            $strType = gettype($pValue);

            switch ($strType){
              case "boolean": return "BOOLEAN"; break;// TINYINT(1)
              case  "integer": return "INT"; break;
              case  "double": return "DOUBLE"; break; // (for historical reasons "double" is returned in case of a float, and not simply "float")
              case   "string": return "VARCHAR(".strlen($pValue).")"; break; // first time is the MAX
              case   "array":
                     
                    $blnHasException = true;
                    
                    $strExceptionMessage = "JsnTable says no support for arrays as column values at present ";
                    //return "SKIP";
                    break;
              case    "object":
                    $blnHasException = true;
                    $strExceptionMessage = "JsnTable says no support for objects as column values at present ";
                    break;
                default : 
                    $blnHasException = true;
                    $strExceptionMessage = "JsnTable says no support for ".gettype($pValue)." as column values at present ";
                    break;
            }
            if($blnHasException){
                $e =  new mysqli_sql_exception( $strExceptionMessage ) ;
                throw($e);
            }
            return "";
        }
        catch(mysqli_sql_exception $e){

            throw $e;
        }
        
    }
    
    private function TableExists(){
        try{
           $result = false;
           $tok = $this->objJsnSQL->_token;
           $name = $this->Name;

           $SQL = "SELECT Count(*) > 0 AS HaveTable  FROM information_schema.tables WHERE table_schema = '$tok' AND table_name = '$name' LIMIT 1;";

           $this->sqlResult = $this->objJsnSQL->query($SQL);

           $row = $this->sqlResult->fetch_assoc();

           $result = $row['HaveTable'] == 1;

           return $result;
        }
        catch(mysqli_sql_exception $e){

            throw $e;
        }
       
    }
    
    private function CreateTable(){
        // echo "</br> Create Table JSON is : ".$this->strJSON;
        $ary =  (array)(json_decode($this->strJSON));
        
        // Make the column specs
        $strColumns = "(";
        $blnFirst = true;
        $aryPrimaryKeys = array();
         
        foreach($ary as $ColNameSpec => $ColValue ){
            $ColType = $this->ToSQLType($ColValue);
           
            if($ColType == ""){
                // May not need to do this because ToSQLType should have already thrown an exception?
                $e =  new mysqli_sql_exception( "jsnTable says Table $this->Name for column $ColNameSpec can not have an unknown type ") ;
                throw($e);
            }// if
            else if($ColType != "SKIP")
            {
                // Remove spaces from ColName ( is this safe?)
                $aryColumn = explode(" ",$ColNameSpec);
                $ColName = $aryColumn[0];
                
                // Check, Is this a PRIMARY KEY and/or an AUTO_INCREMENT?
                $blnIsPrimary = false;
                $blnIsAuto = false;  
                
                foreach($aryColumn as $colPart){
                  if( strtolower($colPart) == "pk"){
                    $blnIsPrimary = true;
                    //echo "found PK ";
                  } 
                  if( strtolower($colPart)  == "auto"){
                    $blnIsAuto = true;  
                  }
                }// CHECKING COL PK AND AUTO
                //== 
                
                if($blnIsPrimary){
                    $nextIndex = count($aryPrimaryKeys) ;
                    $aryPrimaryKeys[$nextIndex] = $ColName;
                    //echo "Adding ColName $ColName";
                }
                
                
                // Add a column
                $Column = "$ColName $ColType ";
                if($blnIsAuto){
                   $Column .= " NOT NULL AUTO_INCREMENT " ;
                }// NOT NULL ADDED TO MAKE THIS A BIT MORE ROBUST in JsnDrop
                if($blnFirst){
                    $blnFirst = false;
                    $strColumns .= $Column;
                }
                else
                 $strColumns  .= ", $Column";
                
            }// else
        }//foreach
       

       // if have primary keys add them 
       if(count($aryPrimaryKeys ) > 0){
       
           $strColumns.=" , PRIMARY KEY( ";
           $blnFirstKey = true;
           
           foreach($aryPrimaryKeys as $pkName){
               if($blnFirstKey){
                   $blnFirstKey = false;
                   $strColumns.= " $pkName ";
               }
               else {
                   $strColumns .= ", $pkName ";
               }
               
           }
           $strColumns.=" )";
       }

       // Finish columns
       $strColumns.=" )";
       try{
           // Make SQL
           $Name = $this->Name;
           $SQL = "CREATE TABLE $Name $strColumns";
           //echo " </br> SORRY IN DEBUG MODE Create SQL : $SQL ";
           // Run the query
           $this->sqlResult = $this->objJsnSQL->query($SQL);
           if(!$this->sqlResult ){
                $e =  new mysqli_sql_exception( $this->objJsnSQL->error ) ;
                throw($e);
           }
           
       }
       catch(mysqli_sql_exception $e  ){
           throw($e);
       }
         
    }// CreateTable
    
    public function Drop(){
        try {
            $lcName = $this->Name; 
            $SQL = "DROP TABLE ".$lcName;
            //echo "</br> ".$SQL;
            
            // Run the query
            $this->sqlResult =  $this->objJsnSQL->query($SQL);
            
        }
        catch(mysqli_sql_exception $e){

            throw $e;
        }
    } 
    
    public function Row($pColumn = "")
    {
       /* To be implemented - return the column in the current row */
       echo "Row : To be implemented - return the column in the current row";
    }
    
    private function  JsnToWhere($pJsn){
    // Thinking about better Where parsing 
    // This is dangerous and open to SQL injection.
        
        $aryTop = (array) json_decode($pJsn);
        
        $whereCondition = $aryTop["WHERE"];// $this->objJsnSQL->real_escape_string($aryTop["WHERE"]); // NEED TO DOUBLE CHECK THIS DOES PROTECT
        
        return $whereCondition;
        
    }
    
    private function FixSQLType($pRow){
        // FROM https://stackoverflow.com/a/28261996/7571029

        // Fix the types    
        $fixed = array();
        $i = 0;
        foreach ($pRow as $key => $value) {
            $info = $this->sqlResult->fetch_field_direct($i);
            $i++;
            
            // unescape value too?
            
            if (in_array($info->type, array(
                    MYSQLI_TYPE_TINY, MYSQLI_TYPE_SHORT, MYSQLI_TYPE_INT24,    
                    MYSQLI_TYPE_LONG, MYSQLI_TYPE_LONGLONG,
                    MYSQLI_TYPE_DECIMAL, 
                    MYSQLI_TYPE_FLOAT, MYSQLI_TYPE_DOUBLE
            ))) {
                $fixed[$key] = 0 + $value;
            } else {
                
                $fixed[$key] = $value;
            }

        }
        
        return $fixed;
    }
    public function Where($pJsnWhere){
        try{ 
             $lcWhere = $this->JsnToWhere($pJsnWhere);
             
             $lcName = $this->Name;
             $lcAllRows = array();
             $SQL = "SELECT * FROM $lcName WHERE $lcWhere";
             //echo "</br> Where SQL : $SQL ";
             
             // Run the query
             $this->sqlResult =  $this->objJsnSQL->query($SQL);
             if(!$this->sqlResult){
	         $e =  new mysqli_sql_exception( $this->objJsnSQL->error ) ;
                 throw($e);
	     }
             while( $row = $this->sqlResult->fetch_assoc()){
                 $lcCount = count($lcAllRows) ;
                 $fixedRow = $this->FixSQLType($row);
                 $lcAllRows[$lcCount]= $fixedRow;
             }
             if( count($lcAllRows) == 0)                                                                                {                                                                                                              $e =  new mysqli_sql_exception( "Nothing selected from ".$this->Name ) ;                                        throw($e);                                                                                             } 
             $jsnWhere = json_encode($lcAllRows);
             return $jsnWhere;
              
            
            
        }
        catch(mysqli_sql_exception $e){

            throw $e;
        }
    }
    
    public function All(){
        try{
             $lcName = $this->Name;              
             $lcAllRows = array();
             $SQL = "SELECT * FROM $lcName";
             // echo "</br> All ".$SQL;
            
             // Run the query
             $this->sqlResult =  $this->objJsnSQL->query($SQL);
             while( $row = $this->sqlResult->fetch_assoc()){
                 $lcCount = count($lcAllRows) ;
                 $fixedRow = $this->FixSQLType($row);
                 $lcAllRows[$lcCount]= $fixedRow;
             }
             if( count($lcAllRows) == 0)
             {
                 $e =  new mysqli_sql_exception( "Nothing selected from ".$this->Name ) ;
                 throw($e); 
             } 
             $jsnAll = json_encode($lcAllRows);
             return $jsnAll;
            
        }
        catch(mysqli_sql_exception $e){

            throw $e;
        }
    }
     
    private function FirstColsValue($ary, &$Columns,&$Values){
            $Columns = "( ";
            $Values = "( ";
            $first = true;
            foreach($ary as $ColSpec => $ColValue)
            {
                $aryColParts = explode(" ", $ColSpec);
                $ColName = $aryColParts[0];
                
                $ColValue = $this->objJsnSQL->real_escape_string($ColValue);
                if(substr_count($this->ToSQLType($ColValue),"CHAR") >0 ){
                    $ColValueTypeOK = "'".$ColValue."'";
                }
                else
                    $ColValueTypeOK = $ColValue;
                
                if($first){
                    $first = false;
                    $Columns .= $ColName;
                    $Values  .= $ColValueTypeOK;
                }
                else {
                    $Columns .= ", ".$ColName;
                    $Values  .= ", ".$ColValueTypeOK;
                }
                
            }
            $Columns .= ")";
            $Values  .= ")";
    }
    
    function AddInsertValue($ary,&$Values){
            $Values .= ",( ";
            //echo "</br>AddInsert </br>";
            $first = true;
            foreach($ary as $ColName => $ColValue)
            {
                
                $ColValue = $this->objJsnSQL->real_escape_string($ColValue);
                if(substr_count($this->ToSQLType($ColValue),"CHAR") >0 ){
                       $ColValueTypeOK = "'".$ColValue."'";  
                }
                else
                    $ColValueTypeOK = $ColValue;
                
                if($first){
                    $first = false;
                    $Values  .= $ColValueTypeOK;
                }
                else {
                    $Values  .= ", ".$ColValueTypeOK;
                }
                
            }
            $Values  .= ")";
    }
    public function Store($pJSON){
     /*
         Actually want to insert unless the record exists, otherwise update 
         
         Two examples.
         INSERT INTO table (id, name, age) VALUES(1, "A", 19) ON DUPLICATE KEY UPDATE name="A", age=19

         REPLACE into table (id, name, age) values(1, "A", 19)
         Using replace because want to make sure the primary key remains
         this option needs delete priviledges
         
         Need  to allow for an array
     */
        try{
            // Check for an array if it is not turn it into an array of one
            if(ltrim($pJSON)[0] != '['){
                $pJSON = "[".$pJSON."]";
            }
            $this->strJSON = $pJSON;
            $allArrays = (array)(json_decode($this->strJSON));
            
            //echo "</br>Store got this array ";
            //print_r($allArrays);
            $first_record = true;
            $Columns = "";
            $Values = "";
            foreach($allArrays as $ary){
                 
               if($first_record){
                  $first_record = false;
                  $this->FirstColsValue($ary,$Columns,$Values);
               }
               else
               {
                   $this->AddInsertValue($ary,$Values); 
               }
              
            }// foreach
            
            // NEED TO CHECK FOR EMPTY STRING IN PRIMARY KEYS
            /*
                    {
                        $e =  new mysqli_sql_exception( "The column ".$ColName." is an empty string, please") ;
                        throw($e); 
                    }
            */
            $lcName = $this->Name; 
            $SQL = "Replace INTO ".$lcName.$Columns." VALUES".$Values;
            //echo "Store SQL is ".$SQL;
            $this->sqlResult =  $this->objJsnSQL->query($SQL);
            if( ! $this->sqlResult){
                $e =  new mysqli_sql_exception( $this->objJsnSQL->error ) ;
                throw($e); 
            }
           
            
        }
        catch(mysqli_sql_exception $e){

            throw $e;
        }
     
    }
    
    public function Delete(){
        try {
            // Args[0] will be a where clause conditional expression BUT for now going to ignore that
            $Args = func_get_args();
            $numArgs = count($Args) ; // Will need to be added if $numArgs = 1
             
            if($numArgs == 1){
                 $conditionalClause = $this->JsnToWhere($Args[0]);
                 $where = "WHERE ".$conditionalClause;
            }
            $lcName = $this->Name; 
            $SQL = "DELETE FROM ".$lcName." ".$where;
            //echo "Delete SQL is ".$SQL;
            $this->sqlResult = $this->objJsnSQL->query($SQL);
        
        }
        catch(mysqli_sql_exception $e){

            throw $e;
        }
        
    }
    
    function __construct(){
        try{
            $args = func_get_args();
            $numArgs = count($args) ;
            
            if($numArgs < 2){
                $e = new Exception("JsonTable::__construct (JsonMySQLi, strTableName[, strJSON] )");
                throw($e);
            }
            
            // At this point we have at least two parameters
            
            $this->objJsnSQL = $args[0];
            $this->Name = $args[1];
            //$this->strJSON = $arg[2];
            
            if($numArgs == 2){
                // retrieve the table ? 
                // Not Yet, only run the query when it is ready.
                // using Where or All
                // At least check if the table exists
                
                if(!$this->TableExists()){
                    $lcName =  $this->Name ;
                    $e = new mysqli_sql_exception("JsnTable '$lcName' does not exist");
                    throw($e);
                }
            }
            else if ($numArgs == 3){
               // echo "Args ".$args[1]." ".$args[2]." </br>";
               $this->strJSON = $args[2];
                
               // Create the table when?
               // now is good
               if(!$this->TableExists()){
                   $this->CreateTable(); // COLUMNS marked with PK are PRIMARY KEY COLUMNS
               }
            
               // Have the table so 
               // Just INSERT or UPDATE this record
               $this->blnHaveTable = true;
                
            }

            
        }
        catch(mysqli_sql_exception $e){

            throw $e;
        }
    }
}

// Test Code
//============
/*
class tblPerson{
    
    public $ID;
    public $Name;
    public $Password;
    
    function __construct($pID,$pName,$Password){
      $this->ID = $pID;
      $this->Name = $pName;
      $this->Password = $Password;
    }
}


// TEST JsonMySQLi   
    
  
try{

    $aToken = "6c420424-62ad-4218-8b1f-d6cf2115facd";//JsonMySQLi::makeToken();
    $aJSNMySQLi = new JsonMySQLi($aToken,"localhost","root","ten9eight");
    
    
    //Testing table creation from an example
    //needs to include some JSON
    //  '{"ID PK":1,"Name":"Hellow","Password":"ABC123"}' 
  
     
    $jsnPerson = '{"ID PK":1,"Name":"Hellow","Password":"ABC123"}' ;
    echo "</br>jsn Person is ".$jsnPerson;
    $aJSNMySQLi->Table("tblPerson",$jsnPerson);
    
       
    //Testing Store

    // Store One
    $aPerson = new tblPerson(3,"Fellow","ABQ123");
    $jsnPerson =json_encode ($aPerson);
    echo "</br>jsn Person is ".$jsnPerson;
    echo "</br>".$aJSNMySQLi->Table("tblPerson")->Store($jsnPerson);
    
    // Store Many
    $aryPerson = array(new tblPerson(2,"Tellow","ZBC9923"),
                       new tblPerson(5,"Yellow","ZYC123"),
    
    );

    $jsnAryPerson =json_encode ($aryPerson);
    echo "</br>jsn Ary of Person is ".$jsnAryPerson;
    echo "</br>".$aJSNMySQLi->Table("tblPerson")->Store($jsnAryPerson);
   
    
    //Testing All
    echo "</br>".$aJSNMySQLi->Table("tblPerson")->All();
    
     
    //Testing Where
    
    $jsnWhere = "{\"Where\":\"ID > 2\"}";
    echo "</br>".$aJSNMySQLi->Table("tblPerson")->Where($jsnWhere);
    
    // Insert and Update
    $aryPerson = array(new tblPerson(1,"Hellow","ABC123"),
                       new tblPerson(5,"Yellowz","ZYCN23"),
    
    );
    $jsnAryPerson =json_encode ($aryPerson);
    echo "</br>".$aJSNMySQLi->Table("tblPerson")->Store($jsnAryPerson);
   
    echo "</br>".$aJSNMySQLi->Table("tblPerson")->All();
    
     
    //Testing Drop
     
    //$aJSNMySQLi->Table("tblPerson")->Drop();
      

     
}
catch (Exception $e)
{
    
    //echo $aJSNMySQLi->errno."->".$aJSNMySQLi->error;
    echo "{\"ERROR\":\"".$e->getMessage()."\" }"; 
}
*/

?>
