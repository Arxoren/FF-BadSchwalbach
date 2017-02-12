<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['page_headline']; ?></h1>
		<a href="<?php echo base_url(); ?>admin/?op=team_edit" class="admin_button"><?php echo $content['page_btn_addnew']; ?></a> 
		<hr class="clear" />

	</div>
	<div id="admin_pageheadline_placeholder" class="hide"></div>

	<div id="filterbar">
		<form name="filter" id="admin_form" method="post">
			
			<input type="hidden" name="op" value="team_showlist">
			<input type="hidden" name="filter" value="yes">

			<div>
				<label for="wehren">Feuerwehren:</label>
				<select name="filter_wehren" id="wehren">
					<option value="alle">Alle</option>
				<?php	
					foreach($content["filter_wehren"] as $wehr) {
						$check="";
						if($wehr['wehrID']==$content['aktfilter_wehren']) { $check = " selected"; }
						echo'<option value="'.$wehr['wehrID'].'"'.$check.'>'.$wehr['ort'].'</option>';
					}
				?>
				</select>
			</div>
			<div>
				<input type="button" value="Filtern" id="js-send-form" />
			</div>
		</form>
	</div>

	<div>
		<ul id="page_list">
			<li class="ergebnis"><p>Anzahl der gefundenen Mitglieder: <span><?php echo $content['member_count']; ?></span><p></li>
			<li class="head">
				<div>
					<div class="admin_pagetitle cell idcell"><p>ID</p></div>
					<div class="admin_pagetitle cell rang"><p>Rang</p></div>
					<div class="admin_pagetitle"><p>Name</p></div>
				</div>
			</li>

		<?php

			$i=0;

			foreach($content['member'] as $member) {

				

				echo'<li><div>';
					
					echo'<a href="'.base_url().'admin/?op=team_edit&memberID='.$member['memberID'].'">';
					echo'<div class="admin_pagetitle cell idcell"><p>#'.$member['memberID'].'</p></div>';
					echo'<div class="admin_pagetitle cell rang"><p>'.$member['rang_details']['name'].'</p></div>';

					echo'<div class="admin_pagetitle einsatz">';
						if($member['bild']!="") {	
							echo'<img src="'.base_url().'frontend/images_cms/mannschaft/'.$member['bild'].'" class="profile_image" />';
						} else {
							echo'<img src="'.base_url().'backend/images/icon_imagedefault.svg" class="profile_image" />';
						}
						echo'<p>'.$member['nachname'].', '.$member['vorname'].'<br><span>'.$member['wehr_name'].''.$member['position_name'].'</span></p>';
						echo'<hr class="clear" />';
					echo'</div></a>';
	
					echo'<div class="admin_delete"><a href="'.base_url().'admin/?op=team_delete&target=team_showlist&id='.$member['memberID'].'">Delete</a></div>';
					
					if($member['online']==1) {	
						echo'<div class="admin_option admin_option_online"><a href="'.base_url().'admin/?op=team_publish&target=team_showlist&state=0&id='.$member['memberID'].'&sort='.$content['aktfilter_wehren'].'">Online</a></div>';
					} else {
						echo'<div class="admin_option admin_option_offline"><a href="'.base_url().'admin/?op=team_publish&target=team_showlist&state=1&id='.$member['memberID'].'&sort='.$content['aktfilter_wehren'].'">Offline</a></div>';
					}
					
					echo'<hr class="clear" />';
				echo'</div></li>';
			}

		?>
		</ul>
	</div>
