<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
	<form name="einsatzedit" id="admin_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="op" value="settings_save" />
        <input type="hidden" name="target" value="settings_overview" />
        <input type="hidden" name="group" value="<?php echo $_GET["group"]; ?>" />

	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['headline']; ?></h1>
		<input type="button" class="admin_button" value="Speichern" id="js-send-form" /> 
		<hr class="clear" />

	</div>
    <div id="admin_pageheadline_placeholder" class="hide"></div>
	
	<div class="edit_form">
	<?php
	$group = "";
	foreach($content['settings'] as $setting) {
		
		echo'<p>
            <label for="newval_'.$setting['name'].'">'.$setting['name'].' <span class="helptext"><br/>'.$setting['desc'].'</span></lable>
            <input type="text" name="newval_'.$setting['name'].'" value="'.$setting['value'].'" />
        </p>';
	}
	?>
	</div>

</div>

