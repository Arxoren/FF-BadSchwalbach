<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE HTML>
<html lang="de-de">
	
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<meta http-equiv="X-UA-Compatible" content="edge" />
    <title>easyClick 2.0</title>
	

    <!--
    <link rel="stylesheet" type="text/css" href="css/styles.css" media="all" />
   	-->

    <link rel="stylesheet/less" type="text/css" href="<?php echo base_url(); ?>/backend/css/adminstyles.less" media="all" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.5.0/less.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>

</head>

<body> 

<div id="admin_login_panel">
    <div id="admin_login_logo"><img src="<?php echo base_url(); ?>/backend/images/easyclick_logo.png" /></div>
    <div id="admin_login_site"><a href="<?php echo base_url(); ?>" target="_blank">feuerwehr-badschwalbach.de</a></div>
    
    <form action="<?php echo base_url(); ?>admin" method="post">
    <input type="hidden" name="op" value="login" />

    
    <?php 
        if(isset($loginerrormsg)) { 
            $loginerrormsg = explode(":", $loginerrormsg);
            if($loginerrormsg[0]=="success") {
                echo '<div class="success"><p>'.$loginerrormsg[1].'</p></div>'; 
            } else {
                echo '<div class="error"><p>'.$loginerrormsg[1].'</p></div>'; 
            }
        } 
    ?>    
    
    <div id="admin_login_form">
        <div>
            <p>
            <label for="benutzername">Benutzername</lable>
            <input type="text" name="benutzername" value="" />
            </p>
            <p>
            <label>Passwort</lable>
            <input type="password" name="password" value="" />
            </p>
        </div>
        <input type="submit" value="einloggen">
    </div>
    
    </form>
    <div id="admin_login_link"><a href="<?php echo base_url().'admin/?op=resetpassword_form'; ?>">&raquo; Ich habe mein Passwort vergessen und brauche ein neues</a></div>
</div>

</body>

