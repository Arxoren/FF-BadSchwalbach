<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
    if(isset($_POST['image']) && $_POST['image']!="") {
        $preview = $_POST['image'];
    } else {
        $preview = base_url()."/frontend/images_cms/placeholder.jpg";
    }
    if(!isset($_POST['imageID'])) {
        $_POST['imageID'] = "";
    }
   //print_r($_POST);

?>

<div id="admin_contentbox">
    <div class="admin_lightbox_mainform">
        <div class="admin_headrow">    
            <h1>Teaser bearbeiten</h1>
            <a href="#" id="js_admin_moduleedit_opensubform2" class="admin_icon_button admin_icon_folder" data-moduleID="<?php echo $_POST['moduleID']; ?>">&nbsp;</a>  
            <a href="#" id="js_admin_moduleedit_opensubform" class="admin_icon_button admin_icon_upload" data-moduleID="<?php echo $_POST['moduleID']; ?>">&nbsp;</a>
            <!--<a href="#" id="js_admin_modulesettings" class="admin_icon_button admin_icon_settings" data-moduleID="<?php //echo $_POST['moduleID']; ?>">&nbsp;</a> -->
        </div>
        <div class="admin_scrollcontent">
           <input type="hidden" name="teaserID" class="js_teaser_editID" value="<?php echo $_POST['teaserID']; ?>" />
           <input type="hidden" name="imageID" class="js_teaser_imageID" value="<?php echo $_POST['imageID']; ?>" />
           <div class="teaser_form">
                <p><img class="js_admin_aktteaserimg" src="<?php echo $preview; ?>" /></p>
                <p>Subline
                <input type="text" name="subline" class="teaser_formfield js_teaser_sub" value="<?php echo $_POST['subline']; ?>"></p>                
                <p>Headline
                <input type="text" name="headline" class="teaser_formfield js_teaser_head" value="<?php echo $_POST['headline']; ?>"></p>
                <p>Link
                <input type="text" name="link" class="teaser_formfield js_teaser_link" value="<?php echo $_POST['url']; ?>"></p>
            </div>
            <hr class="clear" />
        </div>
        <div class="admin_savebutton">
            <a href="#" id="js_admin_moduleedit_teaser_update" class="admin_button" data-moduleID="<?php echo $_POST['moduleID']; ?>">Interface aktuallisieren</a>
            <hr class="clear" />
        </div>
    </div>   

    <div class="admin_lightbox_subform2 admin_hide">
         <div class="admin_headrow">    
            <h1>Ein neues Bild wählen</h1>
            <a href="#" id="js_admin_moduleedit_closesubform" class="admin_icon_button admin_left admin_icon_back">&nbsp;</a>
        </div>
        <div class="admin_scrollcontent">
            <ul id="admin_moduledit_imggal">
            <?php
                $i=0;
                foreach($imagelist as $img) {
                    echo '<li class="js_admin_changeteaserimage" id="slideshow_'.$i.'" data-moduleID="'.$_POST["moduleID"].'" data-imgid="'.$img["imageID"].'" data-path="'.$img["name"].'.'.$img["format"].'">
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
            <h1>Ein neues Bild hochladen</h1>
            <a href="#" id="js_admin_moduleedit_closesubform" class="admin_icon_button admin_left admin_icon_back">&nbsp;</a>
        </div>
        <form name="imageupload" id="js_admin_saveimage_ajax" method="post" enctype="multipart/form-data">
            <input type="hidden" name="media_type" value="image">
            <input type="hidden" name="js_admin_fastuploadtarget" value="teaser">
            <input type="hidden" name="folder" id="js_admin_uploadtarget" value="images_cms/teaser" />

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
   
</div> 

