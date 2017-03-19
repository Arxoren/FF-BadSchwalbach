<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
         
<div id="admin_contentbox">
    <div class="admin_lightbox_mainform">
        <div class="admin_headrow">    
            <h1>Bildgallerie bearbeiten</h1>
        </div>
    </div>
    <div class="edit_form">
        <div class="admin_table">    
            <?php $ds=explode(" ", $news_details["date"]); ?>
            <div class="rowlabel">
                Einsatzbeginn
            </div>
            <div>
                <label for="title">Datum</lable>
                <input type="date" name="datum" class="js_einsatzstart_date" value="<?php echo $ds[0]; ?>">
            </div>
            <div>    
                <label for="title">Uhrzeit</lable>
                <input type="time" name="zeit" class="js_einsatzstart_time" value="<?php echo $ds[1]; ?>">
            </div>
            <hr class="clear" />
        </div> 
        <div class="admin_table">    
            <div class="rowlabel">
                Einsatzbeginn
            </div>
            <div>
	            <label for="wehr">&nbsp;</lable>
                <select name="wehr">
					<?php
                    if($news_details['wehrID']==0) { $select=' selected'; } else { $select=''; }
                    echo'<option value="0"'.$select.'>Allgemein</option>';

                    foreach($news_details['wehren'] as $wehr) { 
                        if($wehr['wehrID']==$news_details['wehrID']) { $select=' selected'; } else { $select=''; }
                        echo'<option value="'.$wehr['wehrID'].'"'.$select.'>FFW '.$wehr['ort'].'</option>';
                    }
                    ?>
				</select>
			</div>
            <hr class="clear" />
		</div>              
    </div>
    <div class="admin_savebutton">
        <a href="#" id="js_admin_newsdetails_update" class="admin_button" data-moduleID="<?php echo $_POST['moduleID']; ?>">Übernehmen</a>
        <hr class="clear" />
    </div>
</div>

