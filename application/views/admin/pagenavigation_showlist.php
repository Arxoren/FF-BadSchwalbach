<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin">Navigation</h1>
		<a href="<?php echo base_url(); ?>admin/?op=pagenavigation_edit&amp;id=new&amp;wehrID=<?php echo $content['akt_wehr']; ?>" class="admin_button">Neuen Navigationspunkt anlegen</a>
		<hr class="clear" />

	</div>
	<div id="admin_pageheadline_placeholder" class="hide"></div>

	<div id="filterbar">
		<form name="filter" id="admin_form" method="post">
			
			<input type="hidden" name="op" value="pagenavigation_showlist">
			<input type="hidden" name="filter" value="">

			<div>
				<label for="wehren">Feuerwehren:</label>
				<select name="wehrID" id="wehren">
					<option value="alle">Alle</option>
				<?php
					$wehrname="Alle Wehren";	
					foreach($content["filter_wehren"] as $wehr) {
						$check="";
						if($wehr['wehrID']==$content['akt_wehr']) { $check = " selected"; $wehrname=$wehr['ort']; }
						echo'<option value="'.$wehr['wehrID'].'"'.$check.'>'.$wehr['ort'].'</option>';
					}
				?>
				</select>
			</div>
			<div>
				<input type="button" value="Filtern" id="js-send-form" />
			</div>
			<div class="admin_addonbutton">
				<a class="admin_secondbutton" href="<?php echo base_url(); ?>admin/?op=pagenavigation_copy&amp;wehrID=<?php echo $content['akt_wehr']; ?>">Menü einer anderen Wehr übernehmen</a>
			</div>
		</form>
	</div>

	<div>
		<?php
			
			$headline="";
			$level=1;
			$row=0;

			if($content['menueitems']!="") {
				foreach($content['menueitems'] as $menue) {
					
					$test = $menue['level'].' < '.$level.' ---- ';
					
					if($menue['level']>$level) {
						echo '<ul>';
						$level = $menue['level'];
					} else {
						for($i=0; $i<($level-$menue['level']); $i++) {
							echo '</ul>';
						}
						$level = $menue['level'];
					}
					if($menue['nav_group']!=$headline) {
						if($headline!="") {	echo'</ul>'; }
						echo '<h2 class="admin_listheadline">'.$menue['nav_group'].'</h2>';
						echo '<ul id="page_list">';
						$headline=$menue['nav_group'];
					}
					switch($menue['level']) {
						case 1: $linestyle = 'admin_navfirstlevel'; break;
						case 2: $linestyle = 'admin_navsecondlevel'; break;
						case 3: $linestyle = 'admin_navthirdlevel'; break;
					}				
					$row++;

					echo'<li class="'.$linestyle.'""><div>';
						
						echo'<a href="'.base_url().'admin/?op=pagenavigation_edit&amp;id='.$menue['navID'].'&amp;wehrID='.$content['akt_wehr'].'">';

						echo'<div class="admin_pagetitle">';
							echo'<p>'.$menue['level'].' - '.$menue['sort'].' - #'.$menue['navID'].'</p>';
							echo'<hr class="clear" />';
						echo'</div>';
						echo'<div class="admin_pagetitle">';
							echo'<p>'.$menue['label'].'</p>';
							echo'<hr class="clear" />';
						echo'</div></a>';

						echo'<div class="admin_delete"><a href="'.base_url().'admin/?op=pagenavigation_delete&target=pagenavigation_showlist&id='.$menue['navID'].'&wehrID='.$content['akt_wehr'].'" class="js_admin_dialogbox" data-box="deleteswitch" data-headtxt="Menüpunkt '.$menue['label'].' l&ouml;schen">Delete</a></div>';
						
						if($menue['online']==1) {	
							echo'<div class="admin_option admin_option_online"><a href="'.base_url().'admin/?op=pagenavigation_publish&target=pagenavigation_showlist&state=0&id='.$menue['navID'].'&amp;wehrID='.$content['akt_wehr'].'">Online</a></div>';
						} else {
							echo'<div class="admin_option admin_option_offline"><a href="'.base_url().'admin/?op=pagenavigation_publish&target=pagenavigation_showlist&state=1&id='.$menue['navID'].'&amp;wehrID='.$content['akt_wehr'].'">Offline</a></div>';
						}

						if($menue['navID']!="x") {
							echo'<div class="admin_option_icon admin_movedown"><a class="admin_movedown" href="'.base_url().'admin/?op=pagenavigation_pos&direction=down&target=pagenavigation_showlist&id='.$menue['navID'].'&amp;wehrID='.$content['akt_wehr'].'">DOWN</a></div>';
							if($menue['sort']!=0) {	
								echo'<div class="admin_option_icon"><a class="admin_moveup" href="'.base_url().'admin/?op=pagenavigation_pos&direction=up&target=pagenavigation_showlist&id='.$menue['navID'].'&amp;wehrID='.$content['akt_wehr'].'">UP</a></div>';
							} else {
								echo'<div class="admin_option_icon_blank"></div>';
							}
						}

						echo'<hr class="clear" />';
					echo'</div></li>';
				}
			} else {
				echo'<h2 class="admin">Es wurden keine Menüpunkte angelegt</h2>';
			}
		?>
		</ul>
	</div>

	<div class="admin_modaldialog admin_hide" id="js_admin_dialog_deleteswitch">
		<p class="admin_close"><a href="#" class="admin_closedialogbox">CLOSE</a></o>
		<hr class="clear" />
		<h1 class="admin" id="js_admin_dialogbox_headline">Menüpunkt löschen</h1>
		<p class="admin">Möchten Sie den Menüpunktfür die Seite der Wehr: (<?php echo $wehrname; ?>) entfernen?</p>
		<p class="admin_panel">
			<a href="#" class="admin_button" id="js_admin_dialog_opt1">Ja, bitte jetzt löschen</a>
			<a href="#" class="admin_button js_admin_closedialogbox">Nein, doch nicht löschen</strong>" löschen</a>
		</p>
	</div>