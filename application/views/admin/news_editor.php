<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php //print_r($content); ?>


    <div id="admin_contentbox">
        <form name="einsatzedit" id="admin_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="op" value="news_save" />
        <input type="hidden" name="target" value="news_edit" />
        <input type="hidden" name="newsID" value="<?php echo $content["newsdata"]["newsID"]; ?>" />

        <input type="hidden" name="module_reihe" class="js_module_reihe" value="" />

        <div id="admin_siteeditbar">

            <div class="admin_back"><a href="<?php echo base_url(); ?>admin/?op=news_liste"><img src="<?php echo base_url(); ?>backend/images/button_back.png" /></a></div>
            <ul>
                
                <?php

                    if($content["newsdata"]['online']==0) {
                		$class_online='offline'; 
                	} else {
                		$class_online='online'; 
                	}

                ?>
                
                <li><a href="#" class="admin_<?php echo $class_online; ?>">Live</a></li>
                <li><a href="#" class="js_admin_metamenue_open">Metadaten</a></li>
                <li><a href="#" class="admin_button js_admin_pagelayoutsave">Speichern</a></li>
            </ul>
            <ul class="admin_mobile">
                <li><a href="#" class="js_admin_metamenue_open">Metadaten</a></li>
                <li><a href="#" class="admin_button js_admin_pagelayoutsave">Speichern</a></li>
            </ul>              
            <div class="admin_pagename">
                <h2>&nbsp;</h2>
                <h1 class="admin">News bearbeiten</h1>
            </div>
      
        </div>
        <div id="admin_pageheadline_placeholder" class="hide"></div>

        <hr class="clear" />

        <div id="admin_edit_area">