<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="page">
    <div id="header">
        <div class="container">
            <div class="left">
                <ul>

                <?php    
                    foreach($menue['meta'] as $menuitem) {
                        echo '<li><a href="'.base_url().''.$GLOBALS['varpath'].'/'.$menuitem['path'].'">'.$menuitem['label'].'</a></li>';
                    }
                ?>

                </ul>
                <a href="#" class="open-panel"></a>
            </div>
            <div class="middle">
            <?php    
                if($GLOBALS['location']=='all') {    
                    echo '<a class="logo" href="'.base_url().''.$GLOBALS['varpath'].'">Logo</a>';
                } else {
                    echo '<a class="logo '.$this->uri->segment(2).'" href="'.base_url().''.$GLOBALS['varpath'].'">Logo</a>';
                }
            ?>
            </div>
            <div class="right">
                <?php
               
                if(count($feuerwehren)>1) {
                    
                    echo'
                    <div class="black_select">
                        <a href="'.base_url().''.$GLOBALS['language'].'/allewehren/" class="all">Home</a>
                        <a href="#" class="select">'.$GLOBALS['location_link'].'</a>
                    </div>
                    <div class="metaff">
                        <ul>';

                         echo'<li><a href="'.base_url().$GLOBALS['language'].'/allewehren'.$GLOBALS['aktpath'].'">Alle Feuerwehren</a></li>';

                        foreach($feuerwehren as $wehr) {
                            if($GLOBALS['navigation_location_links']=='detailpage') {    
                                echo'<li><a href="'.base_url().$GLOBALS['language'].'/'.$wehr['pfad'].'/verein/Freiwillige_Feuerwehr_'.basic_convert_to_url($wehr['wehr_name']).'">FFW '.$wehr['ort'].'</a></li>';
                            } else {
                                 echo'<li><a href="'.base_url().$GLOBALS['language'].'/'.$wehr['pfad'].$GLOBALS['aktpath'].'">FFW '.$wehr['ort'].'</a></li>';
                            }
                        }
                
                    echo'</ul></div>';
                }
                ?>
            </div>
        </div>
    </div>
    <div id="js_modalbg" class="mobileNav_close">
        <a href="#" class="close-panel">X</a>
    </div>
    <nav>
        <ul class="meta">
            <?php    
                foreach($menue['meta'] as $menuitem) {
                    echo '<li><a href="'.base_url().$GLOBALS['varpath'].'/'.$menuitem['path'].'">'.$menuitem['label'].'</a></li>';
                }
            ?>
        </ul>
        <hr class="clear" />
        
        <?php
            if(count($feuerwehren)>1) {
        ?>
                <div class="cityselect">
                   <select name="wehrselect" onchange="location = this.options[this.selectedIndex].value;">
                   <?php             
                        if($GLOBALS['location_link']!="Alle Feuerwehren") {
                            echo '<option value="'.base_url().$GLOBALS['language'].'/allewehren'.$GLOBALS['aktpath'].'">Alle Feuerwehren</option>';
                        } else {
                            echo '<option value="'.base_url().$GLOBALS['language'].'/allewehren'.$GLOBALS['aktpath'].'" selected>Alle Feuerwehren</option>';
                        }

                        foreach($feuerwehren as $wehr) {
                            if($wehr['ort']!=$GLOBALS['location_link']) {
                                $select ="";
                            } else {
                                $select = " selected";
                            }
                            if($GLOBALS['navigation_location_links']=='detailpage') {    
                                echo'<option value="'.base_url().$GLOBALS['language'].'/'.$wehr['pfad'].'/verein/Freiwillige_Feuerwehr_'.basic_convert_to_url($wehr['wehr_name']).'"'.$select.'>FFW '.$wehr['ort'].'</a></option>';
                            } else {
                                echo'<option value="'.base_url().$GLOBALS['language'].'/'.$wehr['pfad'].$GLOBALS['aktpath'].'"'.$select.'>FFW '.$wehr['ort'].'</option>';
                            }
                        }
                    ?>
                   </select>
                </div>
        <?php } ?>
        
        <ul id="menu">
            
            <?php

                $flyeropen = 0;
                $prev_level = 1;

                foreach($menue['main'] as $menuitem) {
                    
                    if($menuitem['auto_subcategories']=="_blank") {  
                        $link = $menuitem['path'];
                        $target = ' target="_blank"';
                    } else {
                        $link = base_url().$GLOBALS['varpath'].'/'.$menuitem['path'];
                        $target = '';
                    }

                    switch($menuitem['level']) {
                            
                        // Erste Ebene
                        case 1:
                            if($flyeropen==1) {
                                echo '</ul><hr class="clear" /></div></div></li>';
                                $flyeropen = 0;
                            } elseif($prev_level>1) {
                                echo '</li>';
                            }
                            echo '<li><a href="'.$link.'"'.$target.'>'.$menuitem['label'].'</a>';
                            break;
                             
                        // Zweite Ebene
                        case 2:
                            // Testen ob Fahrzeuge
                            if($menuitem['label']=="Fahrzeuge") {
                                $class = ' class="col2"';
                            } else {
                                $class = '';
                            }

                            if($prev_level==1) {
                                echo '<div class="flyout"><div class="container"><ul'.$class.'>';
                                $flyeropen = 1;
                            } elseif($prev_level>=2) {
                                echo '</ul><ul'.$class.'>';
                            }
                            if($menuitem['path']!="#") {    
                                echo'<li class="headline"><a href="'.$link.'"'.$target.'><h1>'.$menuitem['label'].'</h1></a></li>';
                            }
                            break;

                        // Dritte Ebene
                        case 3:
                            echo '<li><a href="'.$link.'"'.$target.'>'.$menuitem['label'].'</a></li>';        
                            break;
                    }
                        
                    $prev_level = $menuitem['level'];
                }
                
                // Geöffnete Elemente schließen
                if($flyeropen==1) {
                    echo '<hr class="clear" /></div></div></li>';
                } else {
                    echo '</li>';
                }


            ?>

            
        </ul>
    </nav>
