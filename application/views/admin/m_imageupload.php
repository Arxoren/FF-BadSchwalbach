<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
    <div class="admin_lightbox_mainform">
        <div class="admin_headrow">    
            <h1>Neues Bild hochladen</h1>
        </div>
        <form name="imageupload" id="js_admin_savesingleimage_ajax" method="post" enctype="multipart/form-data">
            <input type="hidden" name="media_type" value="image">
            <input type="hidden" name="img_name" value="<?php echo $_POST["img_name"]; ?>" />
            <input type="hidden" name="folder" id="js_admin_uploadtarget" value="images_cms/<?php echo $_POST["folder"]; ?>" />
            <input type="hidden" name="imgtagtarget" value="<?php echo $_POST["imgtag"]; ?>" />

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
                <input type="submit" value="Bild hochladen" id="js_admin_savesingleimage_ajax" />
            </div>
        </form>
    </div>
</div> 

