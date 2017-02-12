<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['headline']; ?></h1>
		<a href="<?php echo base_url(); ?>admin/?op=pages_edit&amp;id=newpage" class="admin_button">Neue Seite hinzufügen</a> 
		<hr class="clear" />

	</div>
	<div id="admin_pageheadline_placeholder" class="hide"></div>

	<div>
		<ul id="page_list">
		<?php
			$pagelevel=0;

			foreach($content['pages'] as $pages) {
				
				if($pages['pagetype']=="folder") {
					$pageicon = 'icon_folder.png';
				} else {
					if($pages['protected']==0) {
						$pageicon = 'icon_page.png';
					} else {
						$pageicon = 'icon_page_locked.png';
					}
				}

				if($pages['pagetype']=="autopage") {	
					$edit_link = base_url().'admin/?op=pages_edit&id='.$pages['pagesID'].'&subpage='.$pages['expected_var'];
				} else {
					$edit_link = base_url().'admin/?op=pages_edit&id='.$pages['pagesID'];
				}

				if($pagelevel>$pages['level']) {
					for($x=0; $x<$pagelevel; $x++) {
						echo '</ul>';
					}
					$pagelevel = $pages['level'];
				}
				if($pagelevel<$pages['level']) {
					echo '<ul>';
					$pagelevel = $pages['level'];
				}

				echo'<li><div>';
					//for($x=0; $x<$pages['level']; $x++) {	
						//echo'<div class="admin_structure admin_vertical"></div>';
				//	}
					echo'<a href="'.$edit_link.'"><div class="admin_pagetitle">';
						echo'<img src="'.base_url().'backend/images/'.$pageicon.'" class="js_admin_pagesorthandler" />';
						echo'<p>'.$pages['page_name'].'</p>';
						echo'<hr class="clear" />';
					echo'</div></a>';
	
					echo'<div class="admin_delete"><a href="'.base_url().'admin/?op=pages_delete&target=pages_showlist&id='.$pages['pagesID'].'">Delete</a></div>';
					
					if($pages['protected']==1) {	
						echo'<div class="admin_option admin_option_locked"><a href="'.base_url().'admin/?op=pages_lock&target=pages_showlist&state=0&id='.$pages['pagesID'].'">Geschützt</a></div>';
					} else {
						echo'<div class="admin_option admin_option_open"><a href="'.base_url().'admin/?op=pages_lock&target=pages_showlist&state=1&id='.$pages['pagesID'].'">Öffentlich</a></div>';
					}
					
					if($pages['online']==1) {	
						echo'<div class="admin_option admin_option_online"><a href="'.base_url().'admin/?op=pages_publish&target=pages_showlist&state=0&id='.$pages['pagesID'].'">Online</a></div>';
					} else {
						echo'<div class="admin_option admin_option_offline"><a href="'.base_url().'admin/?op=pages_publish&target=pages_showlist&state=1&id='.$pages['pagesID'].'">Offline</a></div>';
					}
					
					echo'<hr class="clear" />';
				echo'</div></li>';
			}

		?>
		</ul>
	</div>
