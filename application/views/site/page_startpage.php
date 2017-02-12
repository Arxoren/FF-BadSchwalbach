<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $ch = curl_init(); ?>

    <?php 

        if(isset($unwetter)) {
            echo'
            <div id="unwetterwarnung">
                <div class="container">
                    <div class="wettericon"></div>
                    <div class="wettertext">
                        <h1>'.$unwetter->headline.'</h1>
                        <h2>Gültig von: '.$unwetter->start.' Uhr<span>bis: '.$unwetter->end.' Uhr</span></h2>
                    </div>
                    <div class="dwdlink">
                        <p class="button">
                            <a href="http://www.dwd.de/DE/wetter/warnungen/warnkarten/warnWetter_hes_node.html?bundesland=hes" target="_blank" class="red">Details zur Warnung</a>
                        </p>
                    </div>
                </div>
                <hr class="clear" />
            </div>';
        }

    ?>
        <div class="row raster-4col">
            
            <div class="einsatzticker">
            	<h2>Einsatz<br/>ticker</h2>
                <h2 class="mobile">Einsatzticker</h2>
                
                <ul>
                	<?php 
                    if(count($einsatz)!=0) {

                        foreach($einsatz as $einstaz_items) {
                                
                             echo'
                             <li>
                                <a href="'.base_url().$GLOBALS['varpath'].'/aktuelles/einsaetze'.$einstaz_items['modulepath'].'">
                                <div class="icon '.$einstaz_items['icon'].'">  
                                    <h4 class="date">'.basic_get_ger_datetime($einstaz_items['date_start'], 'datetime', 2).'</h4>
                                    <h4>'.$einstaz_items['type'].' (#'.$einstaz_items['number'].')</h4>
                                </div>
                                <h3>'.$einstaz_items['title'].'</h3>
                                <p>'.$einstaz_items['text_short'].'</p>
                                </a>
                            </li>';
                                
                         } 
                    } else {
                        echo '<li><p>Zum Glück haben wir in diesem Jahr noch keinen Einsatz zu verzeichnen.</p></li>';
                    }
                    ?>

                </ul>
                <?php if(count($einsatz)>1) { ?>
                    <div class="mobile_panel left js_lastEinsatz"></div>
                    <p class="button"><a href="<?php echo base_url().$GLOBALS['varpath'].'/aktuelles/einsaetze/'; ?>">Alle anzeigen</a></p>
                    <div class="mobile_panel right js_nextEinsatz"></div>
                <?php } ?>
            </div>
            
            <div class="news">
            	<?php if(count($news)!=0) { ?>
                    <div class="bignews">
                    	<picture>
                            <img src="<?php echo base_url().'frontend/images_cms/news/news_'.$news[0]['newsID'].'_big.jpg'; ?>" alt="<?php echo $news[0]['headline']; ?>" />
                        </picture>
                        <div>
                            <h4><?php echo basic_get_ger_datetime($news[0]['date'], 'datetime', 2); ?><span> - <?php echo $news[0]['category']; ?></span></h4>
                            <h2><?php echo $news[0]['headline']; ?></h2>
                            <hr class="trenner">
                            <p><?php echo $news[0]['text']; ?></p>
                            <?php
 
                                 if($news[0]["link"]=="") {
                                    $link = base_url().$GLOBALS['varpath'].'/aktuelles/news/'.$news[0]["newsID"].'/'.curl_escape($ch, $news[0]["headline"]);
                                } else {
                                    $link = $news[0]["link"];
                                }
                                  
                            ?>
                            <p class="button"><a href="<?php echo $link; ?>">weiter lesen</a></p>
                        </div>
                    </div>
                <?php } ?>
                
                <?php
                if($appointments!="NO_TERMIN") {
                    
                    echo'
                    <div class="bignews">
                        <div class="appointments">
                            <h2>Termine</h2>
                            <ul>';
                                        
                            foreach($appointments as $termin_items) {
                                        
                                echo'<li>
                                <h3 class="date">'.$termin_items['date_anfang'].'</h3>
                                <h3>'.$termin_items['headline'].'</h3>
                                </li>';
                                        
                            }
                            echo '</ul><p class="button"><a href="'.base_url().$GLOBALS['varpath'].'/aktuelles/termine/" class="red">Alle Termine</a></p>'; 

                        echo'
                        </div>
                    </div>';
                } 
                ?>

                
                <?php if(count($news)>1) { ?>
                <div class="shortnews">
                    <div class="newsrow">

                        <?php  
                        
                        $b = 0; // Berechnung des Umbruchs
                       
                        for($i=1; $i<count($news); $i++) { 

                            // Alle 2 Newskacheln eine Row öffnen
                            if(($b%2)==0 && $b>0) {
                                echo '<hr class="clear">';
                                echo '</div>';
                                echo '<div class="newsrow">'; 
                            }
                            $b++;

                            if($news[$i]['link']=="") {
                                $link = base_url().$GLOBALS['varpath'].'/aktuelles/news/'.$news[$i]["newsID"].'/'.curl_escape($ch, $news[$i]["headline"]);
                            } else {
                                $link = $news[$i]['link'];
                            }

                        ?>

                            <div>
                                <img src="<?php echo base_url().'frontend/images_cms/news/news_'.$news[$i]['newsID'].'_big.jpg'; ?>" alt="<?php echo $news[$i]['headline']; ?>" />
                                <div>
                                    <h4><?php echo basic_get_ger_datetime($news[$i]['date'], 'datetime', 2); ?><span> - <?php echo $news[$i]['category']; ?></span></h4>
                                    <h2><?php echo $news[$i]['headline']; ?></h2>
                                    <hr class="trenner">
                                    <p><?php echo $news[$i]['text']; ?></p>
                                    <p class="button"><a href="<?php echo $link; ?>">weiter lesen</a></p>
                                </div>
                            </div>
						
                        <?php } ?>
		                <hr class="clear">
                    </div>
                </div>
                <?php } ?>

            </div>
            
        </div>
        <hr class="clear" />
