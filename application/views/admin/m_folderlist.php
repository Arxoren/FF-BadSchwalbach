<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

          
<ul id="admin_moduledit_imggal" class="js_admin_editorfolderlist">
<?php
$i=0;

if($_GET["type"] == 'image') {
	foreach($imagelist as $img) {
	    echo '<li class="js_admin_moduleedit_addimage" id="slideshow_'.$i.'" data-imgid="'.$img["imageID"].'" data-path="'.$img["folder"].$img["name"].'.'.$img["format"].'">
	    <img src="'.base_url().'frontend/images_cms/'.$img["folder"].$img["name"].'.'.$img["format"].'" />
	    </li>';
	    $i++;
	}
} else {
    foreach($files as $file) {
	    echo '<li class="file js_admin_moduleedit_addfile" id="slideshow_'.$i.'" data-imgid="'.$file["fileID"].'" data-path="'.$file["folder"].$file["filename"].'.'.$file["format"].'">
            <img src="'.base_url().'backend/images/'.basic_get_fileicon($file['format']).'" />
            <p><strong>'.$file['filename'].'</strong></p><p>'.$file['format'].' - '.$file['size'].'</p>
        	<hr class="clear" />
        </li>';
        $i++;
    }
}

?>
</ul>

