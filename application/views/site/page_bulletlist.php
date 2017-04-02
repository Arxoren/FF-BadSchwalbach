<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="row raster-4col">
    <div class="col-4">
        <ul<?php echo $GLOBALS['editable_tag']; ?>>
        	<?php 
        		$list = explode("|", $modulecontent['list']);
        		unset($list[0]);
        		foreach($list as $item) {	
        			echo '<li>'.$item.'</li>'; 
        		}
        	?>
       	</ul>
    </div>
    <hr class="clear" />
</div> 

