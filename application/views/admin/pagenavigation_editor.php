<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($content["navdetails"]["navID"]=="") { $modus="new"; } else { $modus="edit"; } ?>

<div id="admin_contentbox">
    <form name="einsatzedit" id="admin_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="op" value="pagenavigation_save" />
        <input type="hidden" name="target" value="pagenavigation_showlist" />
        <input type="hidden" name="editID" value="<?php echo $content["navdetails"]["navID"]; ?>" />
        <input type="hidden" name="online" value="<?php if($modus!="new") { echo $content["navdetails"]["online"]; } else { echo 0; } ?>" />

	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['page_headline']; ?></h1>
		<input type="button" class="admin_button" value="Speichern" id="js-send-form" /> 
		<hr class="clear" />

	</div>
    <div id="admin_pageheadline_placeholder" class="hide"></div>

	<div class="edit_form">
        <p>
            <label for="title">Navigations-Label</lable>
            <input type="text" name="title" value="<?php if($modus!="new") { echo $content["navdetails"]["label"]; } ?>" />
        </p>
        <p>
            <label for="ziel">Ziel</lable>
            <select name="ziel">
                <option value="notarget">--- Ziel wählen ---</option>
                <?php   
                    foreach($content["pages"] as $pages) {
                        if($content["navdetails"]["pagesID"]==$pages['pagesID']) { $check=" selected"; } else { $check=""; }
                        echo'<option value="'.$pages['pagesID'].'"'.$check.'>'.$pages['page_name'].'</option>';
                    }
                ?>
            </select>
        </p>
        <p>
            <?php 
                $check=$pathvalue=""; 
                $hide="admin_hide";
                
                if($modus!="new") {
                    if($content["navdetails"]["auto_subcategories"]=="_blank") { 
                        $check=" checked";
                        $pathvalue=$content["navdetails"]["path"];
                        $hide=""; 
                    } 
                }
            ?>
            <input type="checkbox" id="meta_autoonline" name="urlpath" class="js_admin_opendrawer" value="1" data-drawer="openurl"<?php echo $check; ?> />
            <label for="meta_autoonline" class="linelabel">
                URL an Stelle von einem Ziel einfügen
            </label>
            <div class="drawer js_admin_opendrawer_openurl <?php echo $hide; ?>">
                <div>
                    <label for="url">URL als Ziel festlegen</lable>
                    <input type="text" name="url" value="<?php if($modus!="new") { echo $pathvalue; } ?>" />
                </div>
            </div>
        </p>
    </div>

    <?php
        $radiocheck=" checked";
        $radiocheck_sub="";
        $radio_hide="";
        $radio_hide_sub=" admin_hide";

        if($modus!="new") {
            if($content["navdetails"]["subcategory"]!=0) {
                $radiocheck="";
                $radiocheck_sub=" checked";
                $radio_hide=" admin_hide";
                $radio_hide_sub="";
            }
        }
    ?>

    <div class="edit_form">
        <div class="tabs">
            <h3 class"admin">Sortierung</h3>
            <div class="admin_tabs" data="1">
                <input type="radio" class="admin_tab" name="submenue" id="admin_tab_1" value="navgroup"<?php echo $radiocheck; ?> /> 
                <label class="admin_tablabel" for="admin_tab_1">Eigener Hauptpunkt</label>
                <div class="admin_tabcontent<?php echo $radio_hide; ?>" id="js_admin_tabcontent_1">
                    <label for="navgroup">Navigations Gruppe (Positionierung)</lable>
                    <select name="navgroup">
                  	<option value="notarget">--- Gruppe wählen ---</option>
                    <?php   
                        foreach($content["navgroups"] as $navgroup) {
                            if($content["navdetails"]["nav_group"]==$navgroup['name']) { $groupcheck=" selected"; } else { $groupcheck=""; }
                            echo'<option value="'.$navgroup['name'].'"'.$groupcheck.'>'.$navgroup['name'].'</option>';
                        }
                    ?>
                    </select>
                </div>
            </div>
            <div class="admin_tabs" data="2">
                <input type="radio" class="admin_tab" name="submenue" id="admin_tab_2" value="subcategory"<?php echo $radiocheck_sub; ?> /> 
                <label class="admin_tablabel" for="admin_tab_2">Unterpunkt erstellen</label>
                <div class="admin_tabcontent<?php echo $radio_hide_sub; ?>" id="js_admin_tabcontent_2">
                    <label for="subcategory">Hauptpunkt wählen</lable>
                    <select name="subcategory">
                    <option value="0">--- Menüpunkt wählen ---</option>
                    <?php   
                        $level=1;
                        foreach($content["structure"] as $structure) {
                            if($level!=$structure['level']) {
                                $level = $structure['level'];
                                if($structure['level']>1) {    
                                    $levelsign = "";
                                    for($x=1; $x<$structure['level']; $x++) {    
                                        $levelsign=$levelsign.">";
                                    }
                                } else {
                                    $levelsign = "";
                                }
                                $levelsign=$levelsign." ";
                            }
                            $check="";
                            if($modus!="new") {
                                if($content["navdetails"]["subcategory"]==$structure['navID']) { $check=" selected"; } else { $check=""; }
                            }
                            echo'<option value="'.$structure['navID'].'"'.$check.'>'.$levelsign.$structure['label'].'</option>';
                        }
                    ?>
                    </select>
                </div>
            </div>
            <hr class="clear"/>
        </div> 
    </div>

    <div class="edit_form">
        <div class="tabs">
            <h3 class"admin">Wherzuordnung</h3>
            <ul>
            <?php
            
            $i=1;
            if($modus!="new") {
                foreach($content["navzuordnung"] as $zuordnung) {
                    if($zuordnung["wehrID"]==0) {  
                        $check=" checked"; 
                        break;
                    } else {
                        $check="";
                    } 
                }
            }   
            
            echo'<li>
            <input type="checkbox" name="wehren[0]" id="wehren[0]" value="0"'.$check.'> 
            <label class="admin_tablabel" for="wehren[0]">Alle Wehren</label>
            </li>';

            foreach($content["wehren"] as $wehr) {
                if($modus!="new") {
                    foreach($content["navzuordnung"] as $zuordnung) {
                        if($zuordnung["wehrID"]==$wehr["wehrID"]) {  
                            $check=" checked"; 
                            break;
                        } else {
                            $check="";
                        }
                    }
                } else {
                    $check="";
                }             
                echo'<li>
                <input type="checkbox" name="wehren['.$i.']" id="wehren['.$i.']" value="'.$wehr['wehrID'].'"'.$check.'> 
                <label class="admin_tablabel" for="wehren['.$i.']">'.$wehr['wehr_name'].'</label>
                </li>';
                $i++;
            }
            ?>
            </ul>
        </div>
    </div>
    
    </form>

    <div id="admin_footer"></div>
</div>

