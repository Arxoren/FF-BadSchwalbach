<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$ch = curl_init();
?>
<div class="row raster-3col einsatzdetails">
    <div class="col-3">
        <p><?php echo $einsatz['text_short']; ?></p>
        <?php echo $einsatz['text_long']; ?>
        
        <div class="special_content">
        <?php

            if($einsatz['gallery']!="") {        
                echo'
                <div class="gallery">
                    <ul class="slideshow" data-slidehow-id="slideshow_einsatz">';

                    $i=0;
                    foreach($einsatz['gallery'] as $img) {

                        if($i==0) { $linevar = ' active'; } else { $linevar = ''; }
                            
                        echo'<li class="slideshow_einsatz_'.($i+1).$linevar.'">
                            <img src="'.base_url().'frontend/images_cms/einsatz/'.$img.'" alt="" />
                        </li>';
                        $i++;
                    }

                echo'</ul>';
                if($i>1) {
                    echo'
                    <div class="steuerung js_slideshow_einsatz" data-slidehow-id="einsatz"> 
                        <div class="prevImage"></div>
                        <div class="display"><span class="actualImg">11</span> / <span class="allImages">15</span></div>
                        <div class="nextImage"></div>
                        <hr class="clear" />
                    </div>';
                }
                echo'</div>';
            }
            if($einsatz['ort']!="keine Angaben") {
                echo'
                <div class="googlemaps">
                    <iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.de/maps?q='.$einsatz['ort'].'&iwloc=&ie=UTF8&t=m&z=13&output=embed&z=15"></iframe><br />
                    <small>Hinweis: Aus Datenschutzgründen stellen wir hier nicht die Hausnummer dar. Daher setzt Googlemaps diesen auf die Mitte der angegebenen Straße.</small>
                </div>';
            }
        ?>
        </div>
    </div>
    <div class="col-1">
        <?php
        
        if($einsatz['fahrzeuge']!="") {
            echo'
            <h2>Eingesetzte Fahrzeuge</h2>
            <ul>';
            
            foreach($einsatz['fahrzeuge'] as $car) {
                echo'    
                <li>
                    <a href="'.base_url().$GLOBALS['varpath'].'/technik/fahrzeuge/'.$car["fahrzeugID"].'/'.curl_escape($ch, str_replace("/", "_", $car["shortname"])).'">
                    <img src="'.base_url().'/frontend/images_cms/fahrzeuge/mini/'.str_replace(" ", "", str_replace("/", "", $car['shortname'])).'_'.$car['fahrzeugID'].'.png">
                    <div>
                        <h3>'.$car['shortname'].'</h3>
                        <h4>'.$car['name'].'</h4>
                    </div>
                    </a>
                    <hr class="clear" />
                </li>';
            }
        }

        echo'</ul>
        <h2>Alarmierte Einsatzkräfte</h2>
        <ul>';

            foreach($einsatz['einsatzkraefte'] as $externe) {    
                echo '<li>'.$externe.'</li>';
            }

        echo'</ul>';

    ?>
    </div>
    <hr class="clear" />
</div>  