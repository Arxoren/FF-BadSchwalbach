<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<section id="content" class="fahrzeugdetails">
        
<?php if($fahrzeugdetails!="404") { ?>
    <div class="name">
        <div class="row_nospace">        
            <div class="col-4">
                <h1><?php echo $fahrzeugdetails['shortname']; ?></h1>
                <h2><?php echo $fahrzeugdetails['name']; ?></h2>
                <p><?php echo $fahrzeugdetails['description']; ?></p>
            </div>
        </div>
    </div>

    <div class="techdetails">
        <div class="row_nospace raster-3col">
             <div class="col-1 basisfacts">
                <h2>Technische</h2>
                <h2 class="subtitle">Daten</h2>
                <ul>
                <?php        

                    if($fahrzeugdetails['radioname']!="") {
                        echo'
                        <li>
                            <h3>Funkrufname</h3>
                            <h4>'.$fahrzeugdetails['radioname'].'</h4>
                        </li>';
                    }
                    if($fahrzeugdetails['producer']!="") {
                        echo'
                        <li>
                            <h3>Hersteller</h3>
                            <h4>'.$fahrzeugdetails['producer'].'</h4>
                        </li>';
                    }
                    if($fahrzeugdetails['reconstruction']!="") {
                        echo'
                        <li>
                            <h3>Aufbau</h3>
                            <h4>'.$fahrzeugdetails['reconstruction'].'</h4>
                        </li>';
                    }

                ?>
                </ul>
            </div>
            <div class="col-2 wireframe">
                <img src="<?php echo base_url().'frontend/images_cms/fahrzeuge/stages/wireframe_'.str_replace(" ", "", str_replace("/", "", $fahrzeugdetails['shortname'])).'_'.$fahrzeugdetails['fahrzeugID'].'.svg'; ?>" alt="Blaupause <?php echo $fahrzeugdetails['name']; ?>" />
            </div>
        </div>            
        <div class="row_nospace raster-4col factrow">
            <?php    
               
                if($fahrzeugdetails['power']!="0") {
                    echo'
                    <div class="col-1 iconfacts">
                        <img src="'.base_url().'frontend/images_cms/fahrzeuge/icons/motor.svg" />
                        <h3>Leistung</h3>
                        <h4>'.$fahrzeugdetails['power'].' KW</h4>
                    </div>';
                }
                if($fahrzeugdetails['crew']!="") {
                    echo'
                    <div class="col-1 iconfacts">
                        <img src="'.base_url().'frontend/images_cms/fahrzeuge/icons/besatzung.svg" />
                        <h3>Besatzung</h3>
                        <h4>'.$fahrzeugdetails['crew'].'</h4>
                    </div>';
                }
                if($fahrzeugdetails['weight']!="") {
                    echo'
                    <div class="col-1 iconfacts">
                        <img src="'.base_url().'frontend/images_cms/fahrzeuge/icons/gewicht.svg" />
                        <h3>Gewicht</h3>
                        <h4>'.$fahrzeugdetails['weight'].' t</h4>
                    </div>';
                }

            ?>
            <hr class="clear" />
        </div>        
        <div class="row_nospace raster-4col factrow">
            <?php

                if($fahrzeugdetails['height']!="") {
                    echo'
                    <div class="col-1 iconfacts">
                        <img src="'.base_url().'frontend/images_cms/fahrzeuge/icons/hoehe.svg" />
                        <h3>H&ouml;he</h3>
                        <h4>'.$fahrzeugdetails['height'].' m</h4>
                    </div>';
                }
                if($fahrzeugdetails['length']!="") {
                    echo'
                    <div class="col-1 iconfacts">
                        <img src="'.base_url().'frontend/images_cms/fahrzeuge/icons/laenge.svg" />
                        <h3>L&auml;nge</h3>
                        <h4>'.$fahrzeugdetails['length'].' m</h4>
                    </div>';
                }
                if($fahrzeugdetails['width']!="") {
                    echo'
                    <div class="col-1 iconfacts">
                        <img src="'.base_url().'frontend/images_cms/fahrzeuge/icons/breite.svg" />
                        <h3>Breite</h3>
                        <h4>'.$fahrzeugdetails['width'].' m</h4>
                    </div>';
                }

            ?>
            <hr class="clear" />
        </div>        
    </div>
    
    <?php if($fahrzeugdetails['equipment']!=0) { 
    echo'<div class="row">';
        
        foreach($fahrzeugdetails['tools'] as $tool) {

            echo'
            <div class="col-1 detailimages detailimagsleft">
                <div class="image"> 
                    <img src="'.base_url().'frontend/images_cms/fahrzeuge/details/LF_right.jpg" />
                </div>
                <div class="desc">
                    <h3>'.$tool['headline'].'</h3>
                    <h4>'.$tool['subline'].'</h4>';
                    if($tool['type']=='list') {
                        echo'<ul>';
                        foreach($tool['value'] as $item) { 
                            echo'<li>'.$item.'</li>';
                        }
                        echo'</ul>';
                    } else {
                        echo'<p>'.$tool['value'].'</p>'; 
                    }
                echo'    
                </div>
            </div>';

        }

    echo'</div>';
    } ?>

    <?php if($fahrzeugdetails['gallery']!="") { ?>
    <div class="gallery darkbg">
        <div class="row_nospace">
            <div class="col-4">
                <ul class="slideshow" data-slidehow-id="slideshow_<?php echo $fahrzeugdetails['fahrzeugID']; ?>">
                    <?php

                        $i=0;

                        foreach ($fahrzeugdetails['gallery'] as $image) {
                                   
                            if($i==0) { $linevar = ' active'; } else { $linevar = ''; }

                            echo'
                            <li class="slideshow_'.$fahrzeugdetails['fahrzeugID'].'_'.($i+1).$linevar.'">
                                <img src="'.base_url().'frontend/images_cms/fahrzeuge/galerie/'.$fahrzeugdetails['fahrzeugID'].'/'.$image.'" alt="'.$fahrzeugdetails['name'].'" />
                            </li>';
                            $i++;
                        }
                   
                    ?>

                </ul>
                <?php           
                if($i>1) {
                    echo'
                    <div class="steuerung js_slideshow_'.$fahrzeugdetails['fahrzeugID'].'" data-slidehow-id="'.$fahrzeugdetails['fahrzeugID'].'"> 
                        <div class="prevImage"></div>
                        <div class="display"><span class="actualImg">11</span> / <span class="allImages">15</span></div>
                        <div class="nextImage"></div>
                        <hr class="clear" />
                    </div>';
                }
                ?>
            </div>
        </div>
        <hr class="clear" />
    </div>
    <?php 
        } 
    } else {
        show_404($page = '', $log_error = TRUE);
    }
?>
