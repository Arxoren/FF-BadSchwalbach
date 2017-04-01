<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
    <div class="admin_lightbox_mainform">
        <div class="admin_headrow">    
            <h1>Video einfügen</h1>
        </div>

        <div>
            <ul class="tabbar">
                <li><a href="#" class="js_admin_tabbar" data-drawer="youtube">YouTube</a></li>
                <li><a href="#" class="js_admin_tabbar" data-drawer="vimeo">Vimeo</a></li>
                <li><a href="#" class="js_admin_tabbar" data-drawer="file">Eigene Datei</a></li>
            </ul>
        </div>        

        <div class="js_admin_tabbarcontent">
            <div class="js_admin_opendrawer_youtube admin_hide" data-type="youtube">
                <p>Bitte kopieren Sie den youtube link, den Sie erhalten wenn Sie auf "teilen" klicken, hier hinein:</p>
                <input type="text" name="link_1" class="js_videocontent" value="">
            </div>
            <div class="js_admin_opendrawer_vimeo admin_hide" data-type="vimeo">
                <p>Bitte kopieren Sie den vimeo link, den Sie erhalten wenn Sie auf "teilen" klicken, hier hinein:</p>
                <input type="text" name="link_2" class="js_videocontent" value="">
            </div>
            <div class="js_admin_opendrawer_file admin_hide" data-type="file">
                <label for="link">Wählen Sie eine Datei</lable>
                <select name="link_3">
                <?php
                    foreach($files as $video) {
                        echo'<option value="'.$video["filename"].'">'.$video["name"].'</option>';
                    }
                ?>
                </select>
            </div>
        </div>

        <div class="admin_savebutton">
            <a href="#" id="js_admin_moduleedit_video_update" class="admin_button" data-moduleID="<?php echo $_POST['moduleID']; ?>">Interface aktuallisieren</a>
            <hr class="clear" />
        </div>

    </div>
</div> 

