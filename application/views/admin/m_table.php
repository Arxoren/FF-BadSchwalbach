<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
        $hide_first="";
        $hide_name=" admin_hide";
?>

<div id="admin_contentbox">
    <div class="admin_lightbox_mainform<?php echo $hide_first; ?>">
        <div class="admin_headrow">    
            <h1>Tabelle bearbeiten</h1>
            <a href="#" id="js_admin_moduleedit_opensubform2" class="admin_icon_button admin_icon_folder" data-moduleID="<?php echo $_POST['moduleID']; ?>">&nbsp;</a>
        </div>
        <div class="admin_scrollcontent">
            <ul id="admin_moduledit_imggal">
            <?php
                
                $i=0;
                foreach($table as $cell) {
                    echo '<div class="cell">
                    <p>'.$cell["label"].'</p><p><strong>'.$cell["value"].'</strong></p>
                    </div>';
                    $i++;
                }

            ?>
            </ul>
        </div>
        <div class="admin_savebutton">
            <a href="#" id="js_admin_moduleedit_imggal_update" class="admin_button" data-moduleID="<?php echo $_POST['moduleID']; ?>">Interface aktuallisieren</a>
            <hr class="clear" />
        </div>
    </div>
    <div class="admin_lightbox_subform2 admin_hide">
        <div class="admin_headrow">    
            <h1>Bild hinzufügen</h1>
            <a href="#" id="js_admin_moduleedit_closesubform" class="admin_icon_button admin_left admin_icon_back">&nbsp;</a>
        </div>
        <div class="admin_scrollcontent">
            <ul class="js_admin_editorfolderlist">
            <?php
                /*
                $i=0;
                foreach($imagelist as $img) {
                    echo '<li class="js_admin_moduleedit_addimage" id="slideshow_'.$i.'" data-imgid="'.$img["imageID"].'" data-path="'.$img["folder"].$img["name"].'.'.$img["format"].'">
                        <img src="'.base_url().'frontend/images_cms/'.$img["folder"].$img["name"].'.'.$img["format"].'" />
                    </li>';
                    $i++;
                }
                */
            ?>
            </ul>
        </div>
    </div>
</div> 

