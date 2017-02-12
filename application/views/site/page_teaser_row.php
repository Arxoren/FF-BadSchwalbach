<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row"> 
   	<div class="col-4 teaser_row">
        <ul>
        <?php
            
	        foreach($teaser_list as $teaserdetails) {
	            echo'
	            <a href="'.$teaserdetails["link"].'">
	          	<li>
	               	<figure><img src="'.base_url().'frontend/images_cms/'.$teaserdetails["image"].'" alt="'.$teaserdetails["text"].'" /></figure>
	                <div class="subhead">';
		                if($teaserdetails["intro"]!="") { echo'<h3>'.$teaserdetails["intro"].'</h3>'; }
		                if($teaserdetails["text"]!="") { echo'<h2>'.$teaserdetails["text"].'</h2>'; }
	            	echo'    
	                </div>
	          	</li>
	       		</a>';
	       	}
       	
       	?>
       	</ul>
	</div>
</div>



