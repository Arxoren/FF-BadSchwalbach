<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row"> 
   	<div class="col-4 teaser_row_news">
       	<h3 class="headline_center">Die aktuellsten Neuigkeiten</h3>
        <ul>
        <?php
            
	        foreach($last_news as $news) {
	            echo'
	            <a href="'.$news["link"].'">
	          	<li>
	               	<figure><img src="'.$news["image"].'" alt="'.$news["headline"].'" /></figure>
	                <div>'.$news["content"].'</div>
	          	</li>
	       		</a>';
	       	}
       	
       	?>
       	</ul>
	</div>
</div>



