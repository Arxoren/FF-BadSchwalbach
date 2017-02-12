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
    <div id="admin_login_site"><a href="http://<?php echo base_url(); ?>" target="_blank">feuerwehr-badschwalbach.de</a></div>
    
    <form action="<?php echo base_url(); ?>admin" method="post">
    <input type="hidden" name="op" value="resetpassword" />

    
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

    <div class="advice"><p class="admin_p">Geben Sie Ihre E-Mail Adresse an.<br/>Anschließend wird Ihr Passwort zurückgesetzt und die neuen Zugangsdaten werden Ihnen per E-Mail zugeschickt.</p></div>
    <div id="admin_login_form">
        <div>
            <p>
            <label for="email">E-Mail Adresse</lable>
            <input type="text" name="email" value="" />
            </p>
        </div>
        <input type="submit" value="Passwort zurücksetzen">
    </div>
    
    </form>
    <div id="admin_login_link"><a href="<?php echo base_url().'admin'; ?>">&raquo; Zurück zum Login-Formular</a></div>
</div>

</body>

