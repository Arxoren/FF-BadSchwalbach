<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
    if(isset($name) && $name!="") {
        $hide_first="";
        $hide_name=" admin_hide";
    } else {
        $hide_first=" admin_hide";
        $hide_name="";
        $name="";
    }
?>

<div id="admin_contentbox">
    <div class="admin_lightbox_mainform<?php echo $hide_first; ?>">
        <div class="admin_headrow">    
            <h1>Bildgallerie bearbeiten</h1>
            <a href="#" id="js_admin_moduleedit_opensubform2" class="admin_icon_button admin_icon_folder" data-moduleID="<?php echo $_POST['moduleID']; ?>">&nbsp;</a>
            <a href="#" id="js_admin_moduleedit_opensubform" class="admin_icon_button admin_icon_upload" data-moduleID="<?php echo $_POST['moduleID']; ?>">&nbsp;</a>
            <!--<a href="#" id="js_admin_modulesettings" class="admin_icon_button admin_icon_settings" data-moduleID="<?php //echo $_POST['moduleID']; ?>">&nbsp;</a> -->
        </div>
        <div class="admin_scrollcontent">
            <ul id="admin_moduledit_imggal">
            <?php
                
                //print_r($imagegallery);

                $i=0;
                foreach($imagegallery as $img) {
                    echo '<li class="js_admin_moduleedit_imagedelete" id="slideshow_'.$i.'" data-imgid="'.$img["imageID"].'">
                        <img src="'.base_url().'frontend/images_cms/'.$img["folder"].$img["name"].'.'.$img["format"].'" />
                    </li>';
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
            <?php
                echo'<form name="get_folderlist" id="js_admin_loadfolder_ajax" method="post" enctype="multipart/form-data">';
                echo'<input type="hidden" name="media_type" value="image">';
                echo'<div class="admin_onerow_form"><p>
                    <label for="folder"><span class="helptext">Ordner</span></lable>
                    <select name="folder">';

                    foreach($filelist["folder"] as $file) {
                        $folder='gallerie/'.$file;
                        if($folder==$_GET["path"]) { $check=' selected'; } else { $check=''; }
                        echo'<option value="gallerie/'.$file.'"'.$check.'>gallerie/'.$file.'</option>';
                    }

                echo'</select></p><p><input type="submit" value="Ordner laden" id="js_admin_loadfolder_ajax" /></p>
                </div></form>';
            ?>            
            <ul class="js_admin_editorfolderlist">
            <?php
                $i=0;
                foreach($imagelist as $img) {
                    echo '<li class="js_admin_moduleedit_addimage" id="slideshow_'.$i.'" data-imgid="'.$img["imageID"].'" data-path="'.$img["folder"].$img["name"].'.'.$img["format"].'">
                        <img src="'.base_url().'frontend/images_cms/'.$img["folder"].$img["name"].'.'.$img["format"].'" />
                    </li>';
                    $i++;
                }
            ?>
            </ul>
        </div>
    </div>
    <div class="admin_lightbox_subform admin_hide">
        <div class="admin_headrow">    
            <h1>Neues Bild hochladen</h1>
            <a href="#" id="js_admin_moduleedit_closesubform" class="admin_icon_button admin_left admin_icon_back">&nbsp;</a>
        </div>
        <form name="imageupload" id="js_admin_saveimage_ajax" method="post" enctype="multipart/form-data">
            <input type="hidden" name="media_type" value="image">
            <input type="hidden" name="folder" id="js_admin_uploadtarget" value="images_cms/gallerie/<?php echo $name; ?>" />

            <div class="js_adminimageupload_box admin_image_upload">
                <div class="admin_uploadcontainer admin_uploadcontainer_1">
                    <?php
                    /*
                    echo'
                    <p>
                        <label for="folder2"><span class="helptext">Ordner</span></lable>
                        <select name="folder2">';

                            foreach($filelist["folder"] as $file) {
                                echo'<option value="images_cms/gallerie/'.$file.'">'.$file.'</option>';
                            }
                        echo'
                        </select>
                    </p>';
                    */
                    ?>
                    <p><input type="file" name="media_file" class="js_media_file_1 js_meda_choosefile" data-uploadnumber="1" /></p>
                    <p class="imagePreview_container">
                        <div class="admin_imagePreview" id="js_adminimage_preview_1" data-uploadnumber="1">
                            <div class="admin_upload_advice"><strong>hier klicken</strong><br>um ein Bild hoch zu laden</div>
                         </div>
                    </p>
                    <p>
                        <label for="alt_text"><span class="helptext">ALT-Text</span></lable>
                        <input type="text" name="alt_text" />
                    </p>
                </div>
            </div>
            <div class="admin_uploadbutton">
                <input type="submit" value="Bild hochladen" id="js_admin_saveimage_ajax" />
            </div>
        </form>
    </div>

    <div id="js_admin_modulsettingsform" class="<?php echo $hide_name; ?>">
        <div class="admin_headrow">    
            <h1>Gallerie Namen eingeben</h1>
            <!-- <a href="#" id="js_admin_moduleedit_closesubform" class="admin_icon_button admin_left admin_icon_back">&nbsp;</a> -->
        </div>
        <form name="imageupload" id="js_admin_savesettings_ajax" method="post" enctype="multipart/form-data">
            <input type="hidden" id="js_admin_setting_moduleID" name="moduleID" value="<?php echo $_POST['moduleID']; ?>">

            <div class="js_adminimageupload_box admin_image_upload">
                <div class="admin_uploadcontainer admin_uploadcontainer_1">
                    <?php
                    if($name!="") { $ro=' readonly'; } else { $ro=''; }
                    echo'<p><input type="text" id="js_admin_setting_name" name="name" class="" value="'.$name.'" '.$ro.' /></p>';
                    ?>
                </div>
            </div>
            <div class="admin_uploadbutton">
                <input type="submit" value="Einstellungen speichern" id="js_admin_savesettings_ajax" />
            </div>
        </form>
    </div>

</div> 

