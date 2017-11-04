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
            <input type="text" class="js_admin_linkeditor_link" />
        </div>
        <div>
            <label>Anzeigetext</label>
            <input type="text" class="js_admin_linkeditor_text" />
        </div>
        <div>
            <label>Link öffnen</label>
            <select class="js_admin_linkeditor_target">
                <option value="_blank">In einem neuen Fenster</option>
                <option value="_self">Im gleichen Fenster</option>
            </select>
        </div>
    </div>
    <div class="admin_savebutton">
        <a href="#" id="js_admin_linkeditor_save" class="admin_button" data-moduleID="">Interface aktuallisieren</a>
        <hr class="clear" />
    </div>
</div>

