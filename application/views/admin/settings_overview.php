<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['headline']; ?></h1>
		<hr class="clear" />

	</div>
    <div id="admin_pageheadline_placeholder" class="hide"></div>
	
	<div class="edit_form formblock">	
		<div class="admin_table">
		<?php 
			echo '<div><p>Seiten Status: '.$content['pagestatus'].'</p></div>';
			echo '<div>
			<p><a href="'.base_url().'admin/?op=settings_change_pagestatus&target=settings_overview" class="admin_secondbutton">
			'.$content['pagestatus_btntext'].'</a>
			</p></div>';
		?>
		</div>
		<hr class="clear" />
	</div>

	<?php
	$group = "";
	foreach($content['settings'] as $setting) {
		
		if($group!=$setting['gruppe']) {
			if($group!="") { echo '</div>'; }
			echo '<div class="edit_form"><h3 class="admin">'.$setting['gruppe'].' <a href="'.base_url().'admin?op=settings_edit&group='.$setting['gruppe'].'" class="editicon"><img src="'.base_url().'backend/images/icon_edit_white.svg" /></a></h3>';
			echo '<p></p>';
			$group = $setting['gruppe'];
		}

		echo'<p>
            <label for="'.$setting['name'].'">'.$setting['name'].' <span class="helptext"><br/>'.$setting['desc'].'</span></lable>
            <input type="text" name="'.$setting['name'].'" value="'.$setting['value'].'" readonly />
        </p>';
	}
	?>
	</div>

</div>

