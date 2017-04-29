<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php //print_r($content); ?>


    <div id="admin_contentbox">
        <form name="einsatzedit" id="admin_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="op" value="page_layoutsave" />
        <input type="hidden" name="target" value="pages_edit" />
        <input type="hidden" name="pagesID" value="<?php echo $content["metadata"]["pagesID"]; ?>" />

        <input type="hidden" name="module_reihe" class="js_module_reihe" value="" />
        <input type="hidden" name="page_name" class="js_admin_pagename" value="" />
        <input type="hidden" name="small_stage_image" class="js_admin_smallstage" value="" />
        <input type="hidden" name="small_stage_moduleID" class="js_admin_smallstage_moduleID" value="" />

        <div id="admin_siteeditbar">

            <div class="admin_back"><a href="<?php echo base_url(); ?>admin/?op=pages_showlist"><img src="<?php echo base_url(); ?>backend/images/button_back.png" /></a></div>
            <ul>
                
                <?php

                	if($content['metadata']['online']==0) {
                		$class_online='offline'; 
                	} else {
                		$class_online='online'; 
                	}
                	if($content['metadata']['protected']==0) {
                		$class_lock='open'; 
                	} else {
                		$class_lock='locked'; 
                	}

                ?>
                
                <li><a href="#" class="admin_<?php echo $class_online; ?>">Live</a></li>
                <li><a href="#" class="admin_<?php echo $class_lock; ?>">Öffentlich</a></li>
                <li><a href="#" class="js_admin_metamenue_open">Metadaten</a></li>
                <li><a href="#" class="admin_button js_admin_pagelayoutsave">Speichern</a></li>
            </ul>
            <ul class="admin_mobile">
                <li><a href="#" class="js_admin_metamenue_open">Metadaten</a></li>
                <li><a href="#" class="admin_button js_admin_pagelayoutsave">Speichern</a></li>
            </ul>              
            <div class="admin_pagename">
                <h4 class="admin">Startseite / <?php echo str_replace("/", " / ", $content['metadata']['path']); ?> /</h4>
                <h1 class="admin js_admin_pagenameeditbox" contenteditable="true"><?php echo str_replace("_", " ", $content['metadata']['page_name']); ?></h1>
            </div>
      
        </div>
        <div id="admin_pageheadline_placeholder" class="hide"></div>

        <hr class="clear" />

        <div id="admin_edit_area">
        <div id="admin_pagemetaform" class="admin_hide">
            <div class="js_admin_metamenue_close"><img src="<?php echo base_url(); ?>backend/images/icon_close.svg" /></div>
            <h2 class="admin">Metadaten bearbeiten</h2>

            <div>
                <label for="meta_keywords">
                    <p>Keywords <span>(mit Komma trennen)<span></p>
                    <input type="text" name="meta_keywords" value="<?php echo $content['metadata']['page_keywords']; ?>" />
                </label>
            </div>
            <div>
                <label for="meta_description">
                    <p>Seitenbeschreibung <span>(Kurze Inhaltsangabe für SEO)<span></p>
                    <textarea name="meta_description" /><?php echo $content['metadata']['page_description']; ?></textarea>
                </label>
            </div>
            <hr />
            <div>
                <label for="meta_parentpage" class="linelabel">
                    Unterseite von:
                    <select name="meta_parentpage" />
                        <option value="0">Eigene Seite</seelct>
                        <?php
                            foreach($content['pagelist'] as $page) {
                                if($page['pagesID']==$content['metadata']['subpage']) {    
                                    echo'<option value="'.$page['pagesID'].'" selected>'.$page['page_name'].'</option>';
                                } else {
                                    echo'<option value="'.$page['pagesID'].'">'.$page['page_name'].'</option>';
                                }
                            }
                        ?>
                    </select>
                </label>
            </div>
            <hr />
            <div>
                <input type="checkbox" id="meta_autoonline" name="meta_autoonline" class="js_admin_opendrawer" data-drawer="autoonline" />
                <label for="meta_autoonline" class="linelabel">
                    Automatisch ONLINE stellen
                </label>
                <div class="drawer js_admin_opendrawer_autoonline admin_hide">
                    <div>
                        <p>Datum</p>
                        <input type="date" name="meta_autoonline_date" value="" />
                    </div>
                    <div>
                        <p>Uhrzeit</p>
                        <input type="time" name="meta_autoonline_time" value="" />
                    </div>
                </div>
            </div>
            <div>
                <input type="checkbox" id="meta_autooffline" name="meta_autooffline" class="js_admin_opendrawer" data-drawer="autooffline" />
                <label for="meta_autooffline" class="linelabel">
                    Automatisch OFFLINE stellen
                </label>
                <div class="drawer js_admin_opendrawer_autooffline admin_hide">
                    <div>
                        <p>Datum</p>
                        <input type="date" name="meta_autooffline_date" value="" />
                    </div>
                    <div>
                        <p>Uhrzeit</p>
                        <input type="time" name="meta_autooffline_time" value="" />
                    </div>
                </div>
            </div>
            <div class="placeholder"></div>

        </div>