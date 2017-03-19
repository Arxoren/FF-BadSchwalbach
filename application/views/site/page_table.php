<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    if(!isset($table)) {
        $table[0] = array(
            'tableID' => "",
            'label' => "Label",
            'icon' => "",
            'value' => "Wert",
        );
    }

?>

<div class="row raster-4col datafacts"> 
    
    <?php for($i=0; $i<count($table); $i++) {
        
        if($GLOBALS['editable_tag']!="") {
            $cellid = 'data-cell="'.$table[$i]['tableID'].'"';
        } else {
            $cellid = '';
        }

        echo '<div class="col-1 cell"'.$cellid.'>';
			$color_class="";
			if($GLOBALS['editable_tag']!="") {	
				echo'<div class="admin_table_delete admin_hide"></div>';
				echo'<div class="admin_table_edit admin_hide"></div>';
			}
			if($table[$i]['icon']!="") { 
				echo '<img src="'.base_url().'frontend/images/icons/'.$table[$i]['icon'].'" />'; 
				$color_class = "red";
			} 
			?>
            <p class="label"<?php echo $GLOBALS['editable_tag']; ?>><?php echo $table[$i]['label']; ?></p>
            <p class="fact <?php echo $color_class; ?>"<?php echo $GLOBALS['editable_tag']; ?>><?php echo $table[$i]['value']; ?></p>
        </div>
    <?php } ?>

    <hr class="clear" />
</div>