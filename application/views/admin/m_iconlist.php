<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<h1>Wäle ein Icon zum einsetzen</h1>          
<ul id="admin_moduledit_icongal">
<?php
$i=0;

foreach($iconlist as $img) {
    echo '<li><img src="'.base_url().'frontend/images/icons/'.$img.'" class="js_admin_insert_table_icon" /></li>';
    $i++;
}

?>
</ul>

<div class="icondelete">
	<p class="button"><a href="#" class="js_admin_tabledeleteicon">Icon Entfernen</a></p>
</div>

