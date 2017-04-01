<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
    <div class="admin_lightbox_mainform">
        <div class="admin_headrow">    
            <h1>Download Dateien</h1>
            <a href="#" id="js_admin_moduleedit_opensubform2" class="admin_icon_button admin_icon_folder" data-moduleID="<?php echo $_POST['moduleID']; ?>">&nbsp;</a>
            <a href="#" id="js_admin_moduleedit_opensubform" class="admin_icon_button admin_icon_upload" data-moduleID="<?php echo $_POST['moduleID']; ?>">&nbsp;</a>
            <!--<a href="#" id="js_admin_modulesettings" class="admin_icon_button admin_icon_settings" data-moduleID="<?php //echo $_POST['moduleID']; ?>">&nbsp;</a> -->
        </div>
        <div class="admin_scrollcontent">
            <ul id="admin_moduledit_imggal">
            <?php
                /*
                $file_id_list = explode(':', $module_data);
                foreach($file_id_list as $file) {

                }
                */
                $i = 0;
                if(isset($downloadfiles) && $downloadfiles!="") {
                    foreach($downloadfiles as $file) {
                        


                        echo '<li class="file" data-fileid="'.$file["fileID"].'" data-name="'.$file['filename'].'" data-format="'.$file['format'].'" data-size="'.$file['size'].'" data-icon="'.base_url().'backend/images/'.basic_get_fileicon($file['format']).'">
                            <img src="'.base_url().'backend/images/'.basic_get_fileicon($file['format']).'" />
                            <p><strong>'.$file['filename'].'</strong></p><p>'.$file['format'].' - '.$file['size'].'</p>
                            <div class="editpanel">
                                <a href="#" class="js_admin_moduleedit_filedelete">delete</a>
                            </div>
                            <hr class="clear" />
                        </li>';
                        $i++;
                    }
                }
            ?>
            </ul>
        </div>
        <div class="admin_savebutton">
            <a href="#" id="js_admin_moduleedit_downloadfile_update" class="admin_button" data-moduleID="<?php echo $_POST['moduleID']; ?>">Interface aktuallisieren</a>
            <hr class="clear" />
        </div>
    </div>
    <div class="admin_lightbox_subform2 admin_hide">
        <div class="admin_headrow">    
            <h1>Datei hinzufügen</h1>
            <a href="#" id="js_admin_moduleedit_closesubform" class="admin_icon_button admin_left admin_icon_back">&nbsp;</a>
        </div>
        <div class="admin_scrollcontent">
            <?php
                echo'<form name="get_folderlist" id="js_admin_loadfolder_ajax" method="post" enctype="multipart/form-data">';
                echo'<input type="hidden" name="media_type" value="file">';
                echo'<div class="admin_onerow_form"><p>
                    <label for="folder"><span class="helptext">Ordner</span></lable>
                    <select name="folder">';

                    foreach($filelist["folder"] as $file) {
                        $folder='files_cms/'.$file;
                        if($folder==$_GET["path"]) { $check=' selected'; } else { $check=''; }
                        echo'<option value="'.$file.'"'.$check.'>files_cms/'.$file.'</option>';
                    }

                echo'</select></p><p><input type="submit" value="Ordner laden" id="js_admin_loadfolder_ajax" /></p>
                </div></form>';
            ?>            
            <ul class="js_admin_editorfolderlist">
            <?php
                $i=0;
                foreach($files as $file) {
                    echo '<li class="file js_admin_moduleedit_addfile" data-fileid="'.$file["fileID"].'" data-name="'.$file['filename'].'" data-format="'.$file['format'].'" data-size="'.$file['size'].'" data-icon="'.base_url().'backend/images/'.basic_get_fileicon($file['format']).'">
                        <img src="'.base_url().'backend/images/'.basic_get_fileicon($file['format']).'" />
                        <p><strong>'.$file['filename'].'</strong></p><p>'.$file['format'].' - '.$file['size'].'</p>
                        <hr class="clear" />
                        </li>';
                    $i++;
                }
            ?>
            </ul>
        </div>
    </div>
    <div class="admin_lightbox_subform admin_hide">
        <div class="admin_headrow">    
            <h1>Neue Datei hochladen</h1>
            <a href="#" id="js_admin_moduleedit_closesubform" class="admin_icon_button admin_left admin_icon_back">&nbsp;</a>
        </div>
        <form name="imageupload" id="js_admin_saveimage_ajax" method="post" enctype="multipart/form-data">
                        
            <input type="hidden" name="op" value="media_upload">
            <input type="hidden" name="media_type" value="file">
            <input type="hidden" name="target" value="media_folder_list">

            <div class="js_adminimageupload_box admin_image_upload">
                <div class="admin_uploadcontainer admin_uploadcontainer_1">
                    <p>
                        <label for="folder2"><span class="helptext">Ordner</span></lable>
                        <select name="folder" id="js_admin_uploadtarget">';
                        <?php
                        foreach($filelist["folder"] as $file) {
                            $folder='files_cms/'.$file;
                            if($folder==$_GET["path"]) { $check=' selected'; } else { $check=''; }
                            echo'<option value="files_cms/'.$file.'"'.$check.'>files_cms/'.$file.'</option>';
                        }
                        ?>
                        </select>
                    </p>
                    <p>&nbsp;</p>
                    <p><input type="file" name="media_file[]" class="" data-uploadnumber="1" /></p>
                    <p>&nbsp;</p>
                    <p>
                        <label for="displayname"><span class="helptext">Anzeige Name</span></lable>
                        <input type="text" name="displayname" />
                    </p>
                    <p>&nbsp;</p>
                    <p>
                        <label for="description"><span class="helptext">Beschreibung</span></lable>
                        <input type="text" name="description" />
                    </p>
                </div>
            </div>
            <div class="admin_uploadbutton">
                <input type="submit" value="Bild hochladen" id="js_admin_savefile_ajax" />
            </div>
        </form>
    </div>

</div> 

