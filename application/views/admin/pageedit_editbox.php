<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($_GET["op"])) {
	$_GET["op"] = $_POST["op"];
} 
?>

<div class="admin_layoutmodul js_adminlayoutmodul_<?php echo $moduleID; ?>" data-module-id="<?php echo $moduleID; ?>" data-pagemodule-type="<?php echo $pagemoduleID; ?>" data-contentmodule-id="<?php echo $contentmoduleID; ?>">
    <div class="admin_layoutmodul_panel admin_hide"></div>
    <div class="admin_layoutmodul_panel_delete admin_hide"></div>

	<?php

		if($pagemoduleID=='editorial') {
		    echo'
		    <div class="admin_layoutmodul_panel_editor admin_hide">
		    	<ul>
		    		<li><button class="textlink js_admin_insertlink js_admin_editorbutton">alert selection</button></li>
		    		<li><button class="textbold js_admin_insertbold js_admin_editorbutton">alert selection</button></li>
		    		<li><button class="textkursiv js_admin_insertkursiv js_admin_editorbutton">alert selection</button></li>
		    		<li><button class="textunderline js_admin_insertunderline js_admin_editorbutton">alert selection</button></li>
		    	</ul>
		    </div>';
		}

		if($contentmoduleID==6) {
		    echo'<div class="admin_layoutmodul_panel_edit admin_hide" data-moduletype="'.$_GET["op"].'" data-contentmoduleid="'.$contentmoduleID.'" data-moduleid="'.$moduleID.'"></div>';
		}
		if($pagemoduleID=="table") {
		    echo'<div class="admin_layoutmodul_panel_addnewcell admin_hide" data-moduletype="'.$_GET["op"].'" data-contentmoduleid="'.$contentmoduleID.'" data-moduleid="'.$moduleID.'"></div>';
		}
		if($pagemoduleID=="video") {
		    echo'<div class="admin_layoutmodul_panel_edit admin_hide" data-moduletype="'.$_GET["op"].'" data-contentmoduleid="'.$contentmoduleID.'" data-moduleid="'.$moduleID.'"></div>';
		}
		if($pagemoduleID=="download_filelist" || $pagemoduleID=="files") {
		    echo'<div class="admin_layoutmodul_panel_edit admin_hide" data-moduletype="'.$_GET["op"].'" data-contentmoduleid="'.$contentmoduleID.'" data-moduleid="'.$moduleID.'"></div>';
		}
		if($pagemoduleID=="teaser") {
		    echo'<div class="admin_layoutmodul_panel_addnewteaser admin_hide" data-moduletype="'.$_GET["op"].'" data-contentmoduleid="'.$contentmoduleID.'" data-moduleid="'.$moduleID.'"></div>';
		}	
	?>
    <input type="hidden" name="content_<?php echo $moduleID; ?>" value="<?php echo $module_data; ?>" />
    <input type="hidden" name="name_<?php echo $moduleID; ?>" value="<?php echo $module_name; ?>" />
    <input type="hidden" name="moduleType_<?php echo $moduleID; ?>" value="<?php echo $contentmoduleID; ?>" />
   