<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php //print_r($content); ?>


    <div class="insert_new_contentmodule_list">
        <ul>
        <?php 
            foreach($modulelist as $list) {
                
                echo'<li><a href="#" class="js_new_contentmodule" data-modulelist-id="'.$list["contentmoduleID"].'"><img src="'.base_url().'backend/images/contentmodule/'.$list["icon"].'" /><br/>'.$list["name"].'</a></li>';

            }
        ?> 
        </ul>
        <p><a href="#" class="js_contentmodules_close">Abbrechen</a></p>
    </div>
