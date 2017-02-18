<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="row raster-4col">
    <div class="responsive-video">
		
		<?php 
		
		if(isset($video["type"])) {
	
			// --- EIGENE DATEI LADEN
			if($video["type"]=="file") {
				echo'<video poster="'.base_url().'frontend/files_cms/video/'.$video["name"].'.jpg" controls crossorigin>';
				  	echo'<source src="'.base_url().'frontend/files_cms/video/'.$video["file"].'" type="video/'.$video["fileend"].'">';
					if( file_exists(base_url().'frontend/files_cms/video/'.$video["name"].'.webm') ) {  	
					  	echo'<source src="'.base_url().'frontend/files_cms/video/'.$video["name"].'.webm" type="video/webm">';
					}
				echo'</video>';
			}

			// --- YOUTUBE LADEN
			if($video["type"]=="youtube") {
				echo'<iframe src="https://www.youtube.com/embed/'.$video["file"].'" frameborder="0" allowfullscreen></iframe>';
			}

			// --- VIMEO LADEN
			if($video["type"]=="vimeo") {
				echo'<iframe src="https://player.vimeo.com/video/'.$video["file"].'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
			}

		} else {
			// --- EDITMODUS
			echo'<img src="'.base_url().'backend/images/contentmodule/icon_module_video.svg" />';
		}
		?>
		
	</div>
	<hr class="clear" />
</div>

