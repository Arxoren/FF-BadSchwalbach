<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
    <div class="admin_lightbox_mainform">
        <div class="admin_headrow">    
            <h1>Link Eingaben</h1>
        </div>
    </div>
    <div class="edit_form editor">
        <div>
            <label>Link (Ziel)</label>
            <input type="text" class="js_admin_linkeditor_link" value="<?php echo $_POST['link_url']; ?>" />
        </div>
        <div>
            <label>Anzeigetext</label>
            <input type="text" class="js_admin_linkeditor_text" />
        </div>
        <div>
            <label>Link öffnen</label>
            <select class="js_admin_linkeditor_target">
            <?php    
                $select_b = $select_s = '';
                if($_POST['link_target']=="_blank") { $select_b=" selected"; }
                if($_POST['link_target']=="_self") { $select_s=" selected"; }
            ?>
                <option value="_blank"<?php echo $select_b; ?>>In einem neuen Fenster</option> 
                <option value="_self"<?php echo $select_s; ?>>Im gleichen Fenster</option>
            </select>
        </div>
    </div>
    <div class="admin_savebutton">
        <a href="#" id="js_admin_linkeditor_save" class="admin_button" data-moduleID="">Link einfügen</a>
        <hr class="clear" />
    </div>
    <div class="admin_trenner"></div>
    <div class="admin_savebutton">
        <a href="#" id="js_admin_linkeditor_removelink" class="admin_black" data-moduleID="">Link entfernen</a>
        <hr class="clear" />
    </div>
</div>

