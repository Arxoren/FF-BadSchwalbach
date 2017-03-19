<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($content["termine"]["termineID"]=="") { $modus="new"; } else { $modus="edit"; } ?>

<div id="admin_contentbox">
    <form name="einsatzedit" id="admin_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="op" value="termine_save" />
        <input type="hidden" name="target" value="termine_overview" />
        <input type="hidden" name="editID" value="<?php echo $content["termine"]["termineID"]; ?>" />


	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['page_headline']; ?></h1>
		<input type="button" class="admin_button" value="Speichern" id="js-send-form" /> 
		<hr class="clear" />

	</div>
    <div id="admin_pageheadline_placeholder" class="hide"></div>

	<div class="edit_form formblock">
        <p>
            <label for="title">Name der Veranstaltung</lable>
            <input type="text" name="headline" value="<?php if($modus!="new") { echo $content["termine"]["headline"]; } ?>" />
        </p>
        <p>
            <label for="stichwort">Ort</lable>
            <input type="text" name="ort" value="<?php if($modus!="new") { echo $content["termine"]["ort"]; } ?>" />
        </p>
        <p>
            <label for="wehrID">Feuerwehr</lable>
            <select name="wehrID">
                <option value="0">Alle Wehren</option>
                <?php
                foreach($content['feuerwehren'] as $wehren) {    
                    if($wehren['wehrID']==$content['termine']['wehrID']) { $check = " selected"; } else { $check = ""; }
                    echo '<option value="'.$wehren['wehrID'].'"'.$check.'>FFW '.$wehren['ort'].'</option>';
                }
                ?>
            </select>
        </p>
    </div>
    <div class="edit_form">
        <div class="admin_table">    
            <?php
                if($modus!="new") {
                    $ds=explode(" ", $content["termine"]["date_anfang"]);
                }
            ?>
            <div class="rowlabel">
                Veranstaltungsbeginn
            </div>
            <div>
                <label for="title"><span class="helptext">Datum</span></lable>
                <input type="date" name="terminstart_date" class="js_einsatzstart_date" value="<?php if($modus!="new") { echo $ds[0]; } ?>">
            </div>
            <div>    
                <label for="title"><span class="helptext">Uhrzeit</span></lable>
                <input type="time" name="terminstart_time" class="js_einsatzstart_time" value="<?php if($modus!="new") { echo $ds[1]; } ?>">
            </div>
            <hr class="clear" />
        </div>
        <div>
            <div class="admin_table">    
            <?php
                if($modus!="new") {
                    $de=explode(" ", $content["termine"]["date_ende"]);
                }
            ?>
            <div class="rowlabel">
                Veranstaltungsende
            </div>
            <div>
                <label for="title"><span class="helptext">Datum</span></lable>
                <input type="date" name="terminende_date" class="js_einsatzstart_date" value="<?php if($modus!="new") { echo $de[0]; } ?>">
            </div>
            <div>    
                <label for="title"><span class="helptext">Uhrzeit</span></lable>
                <input type="time" name="terminende_time" class="js_einsatzstart_time" value="<?php if($modus!="new") { echo $de[1]; } ?>">
            </div>
            <hr class="clear" />
        </div>    
    </div>
    <div class="edit_form formblock">
        <p>
            <label for="einsatzbericht">Einsatzbericht <span class="helptext">- Die vollständige Version</span></lable>
            <textarea class="text_long js_textoptions" name="text_long" id="einsatzbericht"><?php if($modus!="new") { echo $content["termine"]["text"]; } ?></textarea>
        </p>
    </div>
    </form>

    <div id="admin_footer"></div>
</div>

