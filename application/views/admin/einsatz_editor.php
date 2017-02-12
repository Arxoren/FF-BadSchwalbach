<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
if($content["einsaetze"]["einsatzID"]=="") { $modus="new"; } else { $modus="edit"; } 
?>

<div id="admin_contentbox">
    <form name="einsatzedit" id="admin_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="op" value="einsatz_save" />
        <input type="hidden" name="target" value="einsatz_liste" />
        <input type="hidden" name="editID" value="<?php echo $content["einsaetze"]["einsatzID"]; ?>" />

        <input type="hidden" name="media_type" value="image">
        <input type="hidden" name="folder" id="js_admin_uploadtarget" value="images_cms/einsatz" />


	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['page_headline']; ?></h1>
		<input type="button" class="admin_button" value="Speichern" id="js-send-form" /> 
		<hr class="clear" />

	</div>
    <div id="admin_pageheadline_placeholder" class="hide"></div>

	<div class="edit_form">
        <p>
            <label for="title">Titel</lable>
            <input type="text" name="title" value="<?php if($modus!="new") { echo $content["einsaetze"]["title"]; } ?>" />
        </p>
        <p>
            <label for="stichwort">Alarmstichwort</lable>
            <input type="text" name="stichwort" value="<?php if($modus!="new") { echo $content["einsaetze"]["stichwort"]; } ?>" />
            <!--
            <select name="stichwort">
            	<option value="1">hallo Welt</option>
            </select>
            -->
        </p>
        <p>
            <label for="ort">Einsatzort</lable>
            <input type="text" name="ort" id="ort" value="<?php if($modus!="new") { echo $content["einsaetze"]["ort"]; } ?>" />
        </p>        
        <p>
            <label for="stichwort">Introtext <span class="helptext">- Ein kurzer Text für den Ticker</span></lable>
            <textarea class="text_intro js_countsigns" name="text_intro" data-maxsign="255" onkeyup="countChar(this)"><?php if($modus!="new") { echo $content["einsaetze"]["text_short"]; } ?></textarea>
        </p>
        <p class="signcount"><span class="js_charNum">255</span> Zeichen verfügbar (insgesammt 255)</p>
        <p>
            <label>Einsatztyp</label>
            <div class="optiongroup"> 

            <?php

                $radio_class=array("help", "fire", "toxic", "false");
                $radio_label=array("Hilfeleistung", "Brandeinsatz", "Gefahrengut", "Fehlalarm");
                $radio_value=array("Hilfeleistung", "Brandeinsatz", "Gefahrenguteinsatz", "Fehlalarm");

                for($i=0; $i<count($radio_label); $i++) {
                    if($modus!="new") {
                        if($radio_value[$i]==$content["einsaetze"]["type"]) {
                            $radio_check=" checked";
                        } else {
                            $radio_check="";
                        }
                    } else {
                        $radio_check="";
                    }
                    echo'<input type="radio" name="type" id="'.$radio_class[$i].'" class="box radio_icon_'.$radio_class[$i].'" value="'.$radio_value[$i].'"'.$radio_check.'><label for="'.$radio_class[$i].'" class="radiolabel"> '.$radio_label[$i].'</label>';
                }

            ?>
            </div>
            <?php if($modus!="new") { if($content["einsaetze"]["ueberoertlich"]==1) { $radio_check="checked"; } } ?>
            <input type="checkbox" id="ueberoertlich" name="ueberoertlich" value="1" <?php echo $radio_check; ?>/><label for="ueberoertlich" class="inlinelabel">Überörtlicher Einsatz</label>
        </p>
    </div>
    <div class="edit_form formblock">
        <div class="admin_table">    
            <?php
                if($modus!="new") {
                    $ds=explode(" ", $content["einsaetze"]["date_start"]);
                    $de=explode(" ", $content["einsaetze"]["date_ende"]);
                }
            ?>
            <div class="rowlabel">
                Einsatzbeginn
            </div>
            <div>
                <label for="title"><span class="helptext">Datum</span></lable>
                <input type="date" name="einsatzstart_date" class="js_einsatzstart_date" value="<?php if($modus!="new") { echo $ds[0]; } ?>">
            </div>
            <div>    
                <label for="title"><span class="helptext">Uhrzeit</span></lable>
                <input type="time" name="einsatzstart_time" class="js_einsatzstart_time" value="<?php if($modus!="new") { echo $ds[1]; } ?>">
            </div>
            <hr class="clear" />
        </div>
        <div class="admin_table">    
            <div class="rowlabel">
                Einsatzende
            </div>
            <div>
                <label for="title"><span class="helptext">Datum</span></lable>
                <input type="date" name="einsatzende_date" value="<?php if($modus!="new") { echo $de[0]; } ?>">
            </div>
            <div>    
                <label for="title"><span class="helptext">Uhrzeit</span></lable>
                <input type="time" name="einsatzende_time" value="<?php if($modus!="new") { echo $de[1]; } ?>">
            </div>
            <hr class="clear" />
        </div>
        <div class="admin_table">    
            <div class="rowlabel">
                Einsatzdauer
            </div>
            <div>
                <label for="title"><span class="helptext">Angabe in Minuten</span></lable>
                <input type="text" class="minuten" name="einsatzdauer">
            </div>
            <hr class="clear" />
        </div>      
    </div>
    <div class="edit_form">
        <p>
            <label for="einsatzbericht">Einsatzbericht <span class="helptext">- Die vollständige Version</span></lable>
            <textarea class="text_long js_textoptions" name="text_long" id="einsatzbericht"><?php if($modus!="new") { echo $content["einsaetze"]["text_long"]; } ?></textarea>
        </p>
    </div>
    <div class="edit_form">
        <p class="admin_label">Alarmierte Feuerwehren</p>
        <div class="admin_table admin_table_list">    
            <?php 

                foreach($content['feuerwehren'] as $wehr) {

                    if($modus!="new") {
                        if(is_numeric(array_search($wehr["wehrID"], $content["einsaetze"]["wehren"]))) {
                            $wehr_check=" checked";
                        } else {
                            $wehr_check="";
                        }
                    } else {
                        $wehr_check="";
                    }

                    echo'
                    <div>
                        <input type="checkbox" id="checkbox_wehr_'.$wehr["wehrID"].'" class="js_admin_wehrselector" name="wehren[]" value="'.$wehr["wehrID"].'" '.$wehr_check.'>
                        <label for="checkbox_wehr_'.$wehr["wehrID"].'" class="inlinelabel">FFW '.$wehr["ort"].'</label>
                    </div>';
                } 

            ?>
            <hr class="clear" />
        </div>      
    </div>
    <div class="edit_form formblock car_selection">
        <p class="admin_label">Eingesetzte Fahrzeuge <span class="helptext">- Nur die Fahrzeuge der eingesetzten Wehren</span></p>
        <?php

        $akt_wehrID=0;
        if($modus!="new") { 
            $checked_cars=explode(":", $content["einsaetze"]["fahrzeuge"]); 
        }

        foreach($content['fahrzeuge'] as $car) {
            
            if($akt_wehrID!=$car["wehrID"]) {
                if($akt_wehrID!=0) { echo "</div>"; }
                if($content['einsaetze']['einsatzID']!="") {    
                    if(is_numeric(array_search($car["wehrID"], $content["einsaetze"]["wehren"]))) { $wehr_check=""; } else { $wehr_check="inactive"; }
                } else {
                   $wehr_check="inactive"; 
                }
                echo '<div class="js_carselection_'.$car["wehrID"].' '.$wehr_check.'"><h5><span class="helptext">FFW '.basicffw_get_vereindetails_singlevar($car["wehrID"], 'ort').'</span></h5>';
            }
            $akt_wehrID=$car["wehrID"];

            if($content['einsaetze']['einsatzID']!="") {
                if(is_numeric(array_search($car["fahrzeugID"],$checked_cars))) {
                   $checked_car=" checked"; 
                } else {
                   $checked_car=""; 
                }
            } else {
                $checked_car="";
            }

            echo'
            <input type="checkbox" id="checkbox_cars_'.$car["fahrzeugID"].'" name="cars[]" value="'.$car["fahrzeugID"].'" class="admin_car_tile" style="background-image: url('.base_url().'frontend/images_cms/fahrzeuge/mini/'.str_replace(" ", "", str_replace("/", "", $car['shortname'])).'_'.$car["fahrzeugID"].'.png")"'.$checked_car.'>
            <label for="checkbox_cars_'.$car["fahrzeugID"].'">'.$car["shortname"].'</label>
            ';
        }

        echo "</div>";

        ?>


    </div>
    <div class="edit_form formline">
        <p>
            <label for="eigenekraefte">Eigene Kräfte</lable>
            <input type="text" name="eigenekraefte" class="minuten" id="eigenekraefte" value="<?php if($modus!="new") { echo $content["einsaetze"]["eigenekraefte"]; } ?>" />
        </p>
        <p>
            <label for="stichwort">Weitere Einsatzkräfte <span class="helptext">- Trennen durch ein einfaches komma (Polizei, RTW)</span></lable>
            <textarea class="text_intro" name="einsatzkraefte"><?php if($modus!="new") { echo str_replace(":", ", ", $content["einsaetze"]["einsatzkraefte"]); } ?></textarea>
        </p>
        <!-- <p class="admin_p"><input type="button" value="Fahrzeuge hinzufügen" id="js-list-filter"></p> -->

    </div>

    

    <div class="edit_form">
        
        <?php 
        if($modus!="new") {
            echo '<p class="admin_label">Einsatzbilder</p>';
            echo '<p class="admin_label">';
            
            if($modus!="new") { 
                if($content["einsaetze"]["gallery"]!="") { 
                    $i=0;
                    foreach($content["einsaetze"]["gallery"] as $gallery) {
                        echo'
                            <div class="admin_einsatz_mini_gallery">
                                <img src="'.base_url().'frontend/images_cms/einsatz/'.$gallery.'" class="admin_einsatz_mini_gallery_hover" />
                                <a href="'.base_url().'admin/?op=einsatz_image_delete&path=einsatz&fileID='.$content["einsaetze"]["gallery_imgID"][$i]["imageID"].'&einsatzID='.$_GET["einsatzID"].'&target=einsatz_edit" class="admin_einsatz_gallery_delete" data-basepath="'.base_url().'" data-linkvars="&path=einsatz&fileID='.$content["einsaetze"]["gallery_imgID"][$i]["imageID"].'&einsatzID='.$_GET["einsatzID"].'">DELETE</a>
                            </div>
                        ';
                        $i++;
                    }
                }                 
            } 
            echo '</p>';
        }
        ?>

        <p class="admin_label">Bilder hinzufügen <span class="helptext">- Mehrere Bilder werden direkt in einer Galerie gesammelt</span></p>
        
        <div class="js_adminimageupload_box admin_image_upload">
            <div class="admin_uploadcontainer admin_uploadcontainer_1">
                <p><input type="file" name="media_file[]" class="js_media_file_1 js_meda_choosefile" data-uploadnumber="1" /></p>
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

        <div class="admin_image_upload">
            <input type="button" value="weiteres Bild mit hochladen" id="js_admin_moremediaupload" />
        </div>

    </div>
    
    </form>

    <div id="admin_footer"></div>
</div>

