<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<section id="stage">
	<div class="fahrzeug">
        <div class="breadcrump black">	
           
            <?php  echo basic_get_breadcrumppath($this->uri->segment_array(), $this->uri->rsegment_array(), base_url()); ?>

        </div>
       	<?php  	
       		if($fahrzeugdetails!="404") {
       			echo '<img src="'.base_url().'frontend/images_cms/fahrzeuge/stages/'.str_replace(" ", "", str_replace("/", "", $fahrzeugdetails['shortname'])).'_'.$fahrzeugdetails['fahrzeugID'].'.png" />';
       		} else {
       			echo '<h1>Error 404</h1><h2>Dieses Fahrzeug wurde nicht gefunden.</h2><p>&nbsp;</p>';
       		}
       	?>
    </div>
</section>

