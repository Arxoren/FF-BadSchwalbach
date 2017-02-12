<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['page_headline']; ?></h1>
		<a href="<?php echo base_url(); ?>admin/?op=adminuser_edit" class="admin_button"><?php echo $content['page_btn_addnew']; ?></a> 
		<hr class="clear" />

	</div>

	<div>
		<ul id="page_list">
			<li class="head">
				<div>
					<div class="admin_pagetitle cell idcell"><p>ID</p></div>
					<div class="admin_pagetitle cell fullname"><p>Name</p></div>
					<div class="admin_pagetitle email"><p>E-Mail Adresse</p></div>
					<div class="admin_pagetitle datum"><p>Letzter Lgin</p></div>
				</div>
			</li>

		<?php

			$i=0;

			foreach($content['adminuser'] as $user) {

				echo'<li><div>';
					
					echo'<a href="'.base_url().'admin/?op=adminuser_edit&adminuserID='.$user['userID'].'">';
					echo'<div class="admin_pagetitle cell idcell"><p>#'.$user['userID'].'</p></div>';

					echo'<div class="admin_pagetitle cell fullname"><p>'.$user['vorname'].' '.$user['nachname'].'</p></div>';
					echo'<div class="admin_pagetitle cell email"><p>'.$user['email'].'</p></div>';
					echo'<div class="admin_pagetitle cell datum"><p>'.basic_get_ger_datetime($user['lastlogin'], 'datetime', 2).'</p></div>';
					
					echo'</a>';
	
					echo'<div class="admin_delete"><a href="'.base_url().'admin/?op=adminuser_delete&target=adminuser_showlist&id='.$user['userID'].'">Delete</a></div>';
					echo'<div class="admin_sendmail"><a href="'.base_url().'admin/?op=adminuser_sendmail&target=adminuser_showlist&id='.$user['userID'].'">Send Mail</a></div>';
					
					if($user['locked']==0) {	
						echo'<div class="admin_option admin_option_online"><a href="'.base_url().'admin/?op=adminuser_activate&target=adminuser_showlist&state=1&id='.$user['userID'].'">Aktiv</a></div>';
					} else {
						echo'<div class="admin_option admin_option_offline"><a href="'.base_url().'admin/?op=adminuser_activate&target=adminuser_showlist&state=0&id='.$user['userID'].'">Gesperrt</a></div>';
					}
					echo'<hr class="clear" />';
				echo'</div></li>';
			}

		?>
		</ul>
	</div>
