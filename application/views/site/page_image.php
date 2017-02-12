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
        <img src="<?php echo base_url().'frontend/images_cms/'.$imagegallery[0]['folder'].$imagegallery[0]['name'].'.'.$imagegallery[0]['format'].''; ?>" alt="<?php echo $imagegallery[0]['alt']; ?>" />
	</div>
</div>

