<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['headline']; ?></h1>
		<p class="admin_p">
		<?php 
			echo 'Seiten Status: <a href="'.base_url().'admin/?op=settings_overview">'.$content['pagestatus'].'</a>';
		?>
		</p>
		<hr class="clear" />

	</div>
</div>
<div id="admin_dashboard">
	<div class="admin_col_3">
		<ul class="quicklinks">
		<?php
			foreach ($content['quicknavi'] as $quicklink) {
				echo'
				<li>
				<a href="'.base_url().'admin/?op='.$quicklink["var"].'"><div>
				<img src="'.base_url().'backend/images/'.$quicklink["image"].'" /><br/>
				'.$quicklink["linkname"].'
				</div></a>
				</li>';
			}
		?>
		</ul>
	</div>
	<div class="admin_col_3 admin_dashboradbox">
		<h2 class="head_log">Admin-Log <a href="<?php echo base_url().'admin/?op=show_adminlog'; ?>">(Gesamten Log zeigen)</a></h2>
		<ul class="adminlog">
		<?php

			foreach($content['log'] as $log) {
				echo'<li>
				<p class="action"><span>'.$log['userID'].'</span> '.$log['action'].'</p>
				<p class="time">'.basic_get_ger_datetime($log['datum'], 'datetime', 3).'</p>
				</li>';
			}

		?>
		</ul>
	</div>
	<div class="admin_col_3 admin_dashboradbox">
		<h2 class="head_version">Version <span><?php echo $GLOBALS['software_version']; ?></span></h2>
		<ul class="version_log">
		<?php
			$trenner = 'new';
			foreach ($content['version_log'] as $version) {
				
				if($version['type']=="fixed") { 
					$class='green'; 
				} else { 
					$class='blue'; 
				}
				if($trenner!=$version['type']) { 
					echo'<li class="logtrenner"><p class="admin_p">
						<span class="'.$class.'">['.$version['type'].']</span> 
						'.$version['text'].'
						</p></li>
					'; 
					$trenner="fixed"; 
				} else {
					echo'<li><p class="admin_p">
						<span class="'.$class.'">['.$version['type'].']</span> 
						'.$version['text'].'
						</p></li>
					';
				}
			}
		?>
		</ul>
	</div>
</div>
