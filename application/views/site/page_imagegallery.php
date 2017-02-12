<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    if(!isset($imagegallery)) {
        $imagegallery[0] = array(
            'folder' => "",
            'name' => "placeholder",
            'format' => "jpg",
            'alt' => "Hier einfach ein Bild einfügen",
        );
    }
?>

<div class="gallery">
	<div class="row col-4">
		<ul class="slideshow" data-slidehow-id="slideshow_<?php echo $moduleID; ?>">
			
        <?php 
            for($i=0; $i<count($imagegallery); $i++) { 
				if($i==0) { $linevar = ' active'; } else { $linevar = ''; }
			?>
            
            <li class="slideshow_<?php echo $moduleID; ?>_<?php echo ($i+1); echo $linevar; ?>">
                <img src="<?php echo base_url().'frontend/images_cms/'.$imagegallery[$i]['folder'].$imagegallery[$i]['name'].'.'.$imagegallery[$i]['format'].''; ?>" alt="<?php echo $imagegallery[$i]['alt']; ?>" />
            </li>
        <?php } ?>

        </ul>
        <?php       	
        if($i>1) {
            echo'
            <div class="steuerung js_slideshow_'.$moduleID.'" data-slidehow-id="'.$moduleID.'"> 
                <div class="prevImage"></div>
                <div class="display"><span class="actualImg">11</span> / <span class="allImages">15</span></div>
                <div class="nextImage"></div>
                <hr class="clear" />
            </div>';
        }
        ?>
	</div>
	<hr class="clear" />
</div>

