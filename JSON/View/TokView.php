<?php


?>
<html>
    <title>
        JsnDrop - Token?
    </title>
    <link href='https://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="View/formstyles.css">
    <body>

        <div class="form-style-10">
        <form method="post" action="#">
        <h1>Hi <?=$this->AuthModel->Name?> <span>Your JsnDrop token is: 
            </span></h1>
    
            <div class="inner-wrap">
                <label><?=$this->AuthModel->Token?></label>
            </div>
            <!-- thinking about putting a link to the code example in GitHUB
            <div class="inner-wrap">
                <label></label>
            </div>
             -->
            <div class="button-section">
            <input type="submit" value="Back"/>
            <input type="hidden" name="ctr" value="AuthController">
            </div>
            </form>
        </div>
    </body>
    
</html>
