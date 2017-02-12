<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php

    foreach($smallstage_image as $stage_content) {

   		if($stage_content['image']!="") {   
           $image = ' style="background-image: url('.base_url().'frontend/images_cms/stages/'.$stage_content['image'].');"';
        } else {
            $image = "";
        }

        echo'
		<section id="stage">
			<div class="standard fahrzeugliste"'.$image.'>
		        <div class="breadcrump '.$stage_content['color'].'">';	
		            
		        		if($this->uri->total_rsegments() > 2) {
		        			echo basic_get_breadcrumppath($this->uri->segment_array(), $this->uri->rsegment_array(), base_url());
		        		}

		        echo'
		        </div>
		    </div>
		</section>';
	}
?>
<section id="content">