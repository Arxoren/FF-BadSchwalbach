<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
	<div id="admin_pageheadline" class="admin_pageheadline">
		<h1 class="admin">Admin Log</h1>
	</div>
</div>
<div id="admin_dashboard" class="standalonebox">
	<div class="admin_dashboradbox">
		<h2 class="head_log"></h2>
		<ul class="adminlog">
		<?php
				
			foreach($content['log'] as $log) {
				echo'<li>
				<p class="action"><span>'.$log['userID'].'</span> '.$log['action'].'<br/>'.$log['function'].'</p>
				<p class="time">'.basic_get_ger_datetime($log['datum'], 'datetime', 3).'</p>
				</li>';
			}
		?>
		</ul>
	</div>
</div>
