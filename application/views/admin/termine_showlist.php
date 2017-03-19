<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['page_headline']; ?></h1>
		<a href="<?php echo base_url(); ?>admin/?op=termine_edit" class="admin_button"><?php echo $content['page_btn_addnew']; ?></a> 
		<hr class="clear" />

	</div>
	<div id="admin_pageheadline_placeholder" class="hide"></div>

	<div id="filterbar">
		<form name="filter" id="admin_form" method="post">
			
			<input type="hidden" name="op" value="termine_overview">
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
			<li class="head">
				<div>
					<div class="admin_pagetitle cell idcell"><p>ID</p></div>
					<div class="admin_pagetitle cell datum"><p>Einsatzbeginn</p></div>
					<div class="admin_pagetitle"><p>Titel</p></div>
				</div>
			</li>

		<?php

			$i=0;

			foreach($content['termine'] as $termin) {

				if($termin["date_ende"]!="0000-00-00 00:00:00") {
					$ende = ' bis '.basic_get_ger_datetime($termin['date_ende'], 'datetime', 2);
				} else {
					$ende = '';
				}

				$aktdate = basic_get_date().' '.basic_get_time();
				if($termin["date_anfang"]>$aktdate) {
					$bg = ' class="admin_outdatedtermin_bg"';
				} else {
					$bg = '';
				}

				echo'<li'.$bg.'><div>';
					
					echo'<a href="'.base_url().'admin/?op=termine_edit&termineID='.$termin['termineID'].'">';
					echo'<div class="admin_pagetitle cell idcell"><p>#'.$termin['termineID'].'</p></div>';
					echo'<div class="admin_pagetitle cell datum"><p>'.basic_get_ger_datetime($termin['date_anfang'], 'datetime', 2).'</p></div>';

					echo'<div class="admin_pagetitle einsatz">';
						echo'<p>'.$termin['headline'].'<br><span>'.$ende.'</span></p>';
						echo'<hr class="clear" />';
					echo'</div></a>';
	
					echo'<div class="admin_delete"><a href="'.base_url().'admin/?op=termine_delete&target=termine_overview&id='.$termin['termineID'].'">Delete</a></div>';
					
					if($termin['online']==1) {	
						echo'<div class="admin_option admin_option_online"><a href="'.base_url().'admin/?op=termine_publish&target=termine_overview&state=0&id='.$termin['termineID'].'">Online</a></div>';
					} else {
						echo'<div class="admin_option admin_option_offline"><a href="'.base_url().'admin/?op=termine_publish&target=termine_overview&state=1&id='.$termin['termineID'].'">Offline</a></div>';
					}
					
					echo'<hr class="clear" />';
				echo'</div></li>';
			}

		?>
		</ul>
	</div>
