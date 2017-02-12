<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

          
<ul id="admin_moduledit_imggal" class="js_admin_editorfolderlist">
<?php
$i=0;

foreach($imagelist as $img) {
    echo '<li class="js_admin_moduleedit_addimage" id="slideshow_'.$i.'" data-imgid="'.$img["imageID"].'" data-path="'.$img["folder"].$img["name"].'.'.$img["format"].'">
    <img src="'.base_url().'frontend/images_cms/'.$img["folder"].$img["name"].'.'.$img["format"].'" />
    </li>';
    $i++;
}

?>
</ul>

