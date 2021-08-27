<?php


?>
<html>
    <title>
        JsnDrop - who are you?
    </title>
    <link href='https://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="View/formstyles.css">
    <body>
        <!-- 
        <form method="post" action="?">
            <fieldset>
            <label for="Name">Name</label>
            <input type="text" id="Name" name="Name" Value="" required >
            <label for="Password">Password</label>
            <input type="password" id="Password" name="Password" Value="" required>     <input type="submit" id="LogIn" value="Log in">     
            </fieldset>
        </form>
        -->
        <div class="form-style-10">
        <h1>I'm JsnDrop! Who are you?<span>Sign in to receive a JsnDrop token.
            <?=isset($this->AuthModel) && $this->AuthModel->Msg == "AUTH_ERROR"?"WOOPS?":""
            ?>
            </span></h1>
        <form method="post" action="#">
            <div class="inner-wrap">
                <label><input type="text" name="a_username" id="a_username" required placeholder="Enter your NewSimland username"/></label>
            </div>
            <div class="inner-wrap">
                <label><input type="password" name="a_password" id="a_password" required placeholder="Enter your NewSimland password"/></label>
            </div> 
            <div class="button-section">
             <input type="submit" value="Sign In!"/>
             <input type="hidden" name="ctr" value="AuthController">
            </div>
        </form>
        </div>
    </body>
    <!-- <script type="text/javascript" src="View/js/validoverride.js"></script> -->
</html>
