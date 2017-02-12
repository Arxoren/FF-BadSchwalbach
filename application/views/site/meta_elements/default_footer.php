<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    
   	</section>

   	<section id="footer">

		<div class="container">
            <h2>Feuerwehren der Stadt Bad Schwalbach</h2>
            <h3>
                <?php
                    $i=0;

                    foreach($feuerwehren as $wehr) {
                        $link = base_url().$GLOBALS['language'].'/'.$wehr['pfad'].'/';
                        if($i==0) {
                            echo'<a href="'.$link.'">FF '.$wehr['ort'].'</a>';
                        } else {
                            echo' / <a href="'.$link.'">FF '.$wehr['ort'].'</a>';
                        }
                        $i++;
                    }
                
                ?>
           </h3> 
            <div class="sitemap">
				<?php
                    
                    $sitemap = "<ul>";
                    $akt_level = 1;
                    $i=0;

                    foreach($menue['main'] as $menuitem) {
                        
                        if($menuitem['level']<3) {
                            $link = base_url().$GLOBALS['varpath'].'/'.$menuitem['path'];

                            if($akt_level>$menuitem['level']) {    
                                $sitemap = $sitemap.'</ul><ul>';
                                $i++;
                            }
                            if($menuitem['level']==1) {
                                $sitemap = $sitemap.'<li><a href="'.$link.'"><h3>'.$menuitem['label'].'</h3></a></li>';
                            }
                            if($menuitem['level']==2) {
                                $sitemap = $sitemap.'<li><a href="'.$link.'">'.$menuitem['label'].'</a></li>';
                            }
                            $akt_level = $menuitem['level'];
                        }
                        if($i==3) { break; }
                        	                         
                     }
                     $sitemap = $sitemap.'</ul>';

                    echo $sitemap;

                ?>
            	<hr class="clear" />
            </div>
            <div class="meta">
                <ul>
                    <?php

                    foreach($menue['footer'] as $menuitem) {
                        
                        if($menuitem['auto_subcategories']=="_blank") {  
                            $link = $menuitem['path'];
                            $target = ' target="_blank"';
                        } else {
                            $link = base_url().$GLOBALS['varpath'].'/'.$menuitem['path'];
                            $target = '';
                        }
                        
                        echo '<li><a href="'.$link.'"'.$target.'>'.$menuitem['label'].'</a></li>';
                    }
                    
                    ?>
                </ul>
                <p><a href="#">Sprache: DE</a></p>
                <hr class="clear" />
            </div>
            <div class="legal">
                <?php
                if($GLOBALS['akt_wehr_details']['wehrID']!=0) {    
                    echo'<p>Freiwillige Feuerwehr '.$GLOBALS['akt_wehr_details']['wehr_name'].' / '.$GLOBALS['akt_wehr_details']['str'].' '.$GLOBALS['akt_wehr_details']['hausnr'].' / '.$GLOBALS['akt_wehr_details']['plz'].' '.$GLOBALS['akt_wehr_details']['ort'].'  / Tel: '.$GLOBALS['akt_wehr_details']['tel'].' /  E-mail: '.$GLOBALS['akt_wehr_details']['email'].'</p>';
                } else {
                    echo'<p>Freiwillige Feuerwehren der Bad Schwalbach / Bahnhofstrasse 39 / 65307 Bad Schwalbach / Tel: 06124 - 9500 /  E-mail: info@feuerwehr-badschwalbach.de</p>';
                }
                echo'<p>&copy; Feuerwehren der Stadt Bad Schwalbach 2015</p>';
                ?>
            </div>
        </div>
        
    </section>

</div>

<script type="text/javascript" charset="utf-8" src="<?php echo base_url().'frontend/script/basic-min.js'; ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url().'frontend/script/doubletaptogo.js'; ?>"></script>
<script>$( function() { $( '#menu li:has(div)' ).doubleTapToGo(); });</script>


