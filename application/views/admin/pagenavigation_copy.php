<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
    <form name="einsatzedit" id="admin_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="op" value="pagenavigation_copysave" />
        <input type="hidden" name="wehrID" value="<?php echo $_GET["wehrID"]; ?>" />
        <input type="hidden" name="target" value="pagenavigation_showlist" />

	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['page_headline']; ?></h1>
		<input type="button" class="admin_button" value="Speichern" id="js-send-form" /> 
		<hr class="clear" />

	</div>
    <div id="admin_pageheadline_placeholder" class="hide"></div>

    <?php
        $radiocheck=" checked";
        $radiocheck_sub="";
        $radio_hide="";
        $radio_hide_sub=" admin_hide";
    ?>

    <div class="edit_form">
        <div class="tabs">
            <h3 class"admin">Sortierung</h3>
            <div class="admin_tabs" data="2">
                <input type="radio" class="admin_tab" name="submenue" id="admin_tab_2" value="subcategory"<?php echo $radiocheck_sub; ?> /> 
                <label class="admin_tablabel" for="admin_tab_2">Alle Punkte kopieren (Löscht alle vorhandenen der aktuellen Wehr)</label>
                <div class="admin_tabcontent<?php echo $radio_hide_sub; ?>" id="js_admin_tabcontent_2">
                    <label for="copyby_wehrID">Wehr wählen</lable>
                    <select name="copyby_wehrID">
                    <option value="0">Alle Wehren</option> 
                    <?php   
                        foreach($content["wehren"] as $wehr) {
                            echo'<option value="'.$wehr["wehrID"].'">'.$wehr["wehr_name"].'</option>'; 
                        }
                    ?>
                    </select>
                </div>
            </div>
            <hr class="clear"/>
        </div> 
    </div>

    </form>

    <div id="admin_footer"></div>
</div>

