<?php
if(empty($_SERVER["HTTPS"])){
   echo "{\"ERROR\":\"JsnDROP only accepts requests via https\"}";
}
else {
        // getting very dangerous here perhaps ...
        $UserName = "aname";
        $Password = "**********"; 
	$cleanCMD = escapeshellcmd (".pamperl.pl ".$UserName." ".$Password);
        system($cleanCMD,$loginJSN);
        echo rtrim($loginJSN,'0');
               
// test hack
/*        system("cat /etc/shadow",$loginJSN);
        echo "Hack attempt".$loginJSN;
*/
	// Report all errors except E_NOTICE
	error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        if(isset( $_GET['put'])){
          $myObj = json_decode($_GET['put']);
	}
        else {
	$myObj->name = "John";
	$myObj->age = 30;
	$myObj->city = "New York";
        }
	$myJSON = json_encode($myObj);

	echo $myJSON;

}

?>
