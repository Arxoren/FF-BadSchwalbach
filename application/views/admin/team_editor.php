<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($content["member"]["memberID"]=="") { $modus="new"; } else { $modus="edit"; } ?>

<div id="admin_contentbox">
    <form name="einsatzedit" id="admin_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="op" value="team_save" />
        <input type="hidden" name="target" value="team_showlist" />
        <input type="hidden" name="editID" value="<?php echo $content["member"]["memberID"]; ?>" />

        <input type="hidden" name="media_type" value="image">
        <input type="hidden" name="folder" id="js_admin_uploadtarget" value="images_cms/mannschaft" />


	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['page_headline']; ?></h1>
		<input type="button" class="admin_button" value="Speichern" id="js-send-form" /> 
		<hr class="clear" />

	</div>
    <div id="admin_pageheadline_placeholder" class="hide"></div>

	<div class="edit_form">
        <p>
            <label for="vorname">Vorname</lable>
            <input type="text" name="vorname" value="<?php if($modus!="new") { echo $content["member"]["vorname"]; } ?>" />
        </p>
        <p>
            <label for="nachname">Nachname</lable>
            <input type="text" name="nachname" value="<?php if($modus!="new") { echo $content["member"]["nachname"]; } ?>" />
        </p>
        <p>
            <?php 
                $check_m= $check_w="";
                if($modus!="new") {
                    if($content["member"]["geschlecht"]=="m") { 
                        $check_m=" checked"; 
                    }
                    if($content["member"]["geschlecht"]=="w") { 
                        $check_w=" checked"; 
                    }
                }
                echo '<label for="geschlecht">Geschlecht</lable><br/>';
                echo '<input type="radio" name="geschlecht" value="m" class="admin_tab" '.$check_m.'/> Männlich<br/>';
                echo '<input type="radio" name="geschlecht" value="w" class="admin_tab" '.$check_w.'/> Weiblich';
            ?>
        </p>
    </div>
    <div class="edit_form formblock">
        <p>
            <label for="gebday">Geburtsdatum <span class="helptext">- (optional)</span></lable>
            <input type="date" name="gebday" value="<?php if($modus!="new") { echo $content["member"]["gebday"]; } ?>">        
        </p>
        <p>
            <label for="beruf">Beruf <span class="helptext">- (optional)</span></lable>
            <input type="text" name="beruf" value="<?php if($modus!="new") { echo $content["member"]["beruf"]; } ?>" />
        </p>
    </div>
    <div class="edit_form formline">
        <p>
            <label for="wehrID">Feuerwehr</lable>
            <select name="wehrID">
                <option value="novalue">- Bitte wählen -</option>
                <?php
                foreach($content['feuerwehren'] as $wehren) {    
                    if($wehren['wehrID']==$content['member']['wehrID']) { $check = " selected"; } else { $check = ""; }
                    echo '<option value="'.$wehren['wehrID'].'"'.$check.'>FFW '.$wehren['ort'].'</option>';
                }
                ?>
            </select>
        </p>
        <p>
            <label for="rang">Rang</lable>
            <select name="rang">
                <option value="novalue">- Bitte wählen -</option>
                <?php
                $x=0;
                foreach($content['rang_m'] as $rang) {    
                    if($x==$content['member']['rang']) { $check = " selected"; } else { $check = ""; }
                    echo'<option value="'.$x.'"'.$check.'>'.$rang.'</option>';
                    $x++;
                }
                ?>
            </select>
        </p>
        <p>
            <label for="position">Position</lable>
            <select name="position">
                <?php
                $x=0;
                foreach($content['position'] as $pos) {    
                    if($x==$content['member']['position']) { $check = " selected"; } else { $check = ""; }
                    echo'<option value="'.$x.'"'.$check.'>'.$pos.'</option>';
                    $x++;
                }
                ?>
            </select>
        </p>
    </div>

    

    <div class="edit_form">
        
        <p class="admin_label">Bild hinzufügen <span class="helptext"></span></p>
        <?php 
        if($modus!="new") {
            if($content["member"]["bild"]!="") {
                echo '<p>
                        <img src="'.base_url().'frontend/images_cms/mannschaft/'.$content["member"]["bild"].'" />
                        <a href="'.base_url().'admin/?op=team_image_delete&memberID='.$content["member"]["memberID"].'&target=team_edit">Bild löschen</a>
                    </p>';
            } else {
                echo '<p><span class="helptext">Es ist noch kein Bild hinterlegt</span></p>';
            }
        } else {
            echo '<p><span class="helptext">Laden Sie ein Bild hoch</span></p>';
        }
        ?>
        <div class="js_adminimageupload_box admin_image_upload">
            <div class="admin_uploadcontainer admin_uploadcontainer_1">
                <p><input type="file" name="media_file[]" class="js_media_file_1 js_meda_choosefile" data-uploadnumber="1" /></p>
                <p class="imagePreview_container">
                    <div class="admin_imagePreview" id="js_adminimage_preview_1" data-uploadnumber="1">
                        <div class="admin_upload_advice"><strong>hier klicken</strong><br>um ein Bild hoch zu laden</div>
                     </div>
                </p>
            </div>
        </div>

    </div>
    </form>

    <div id="admin_footer"></div>
</div>

