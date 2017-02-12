<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['page_headline']; ?></h1>
		<a href="<?php echo base_url(); ?>admin/?op=einsatz_edit" class="admin_button"><?php echo $content['page_btn_addnew']; ?></a> 
		<hr class="clear" />

	</div>
	<div id="admin_pageheadline_placeholder" class="hide"></div>

	<div id="filterbar">
		<form name="filter" id="admin_form" method="post">
			
			<input type="hidden" name="op" value="einsatz_liste">
			<input type="hidden" name="filter" value="yes">
			<input type="hidden" name="sort" value="<?php echo $content['sort']; ?>">
			<input type="hidden" name="order" value="<?php echo $content['order']; ?>">

			<div>
				<label for="year">Jahr:</label>
				<select name="year" id="einsatzart">
				<?php	
					$akt_year = basic_get_year();
					$firstyear = 2016;
					while($firstyear<=$akt_year) {
						$check="";
						if($akt_year==$_POST['year']) { $check = " selected"; }						
						echo'<option value="'.$akt_year.'"'.$check.'>'.$akt_year.'</option>';
						$akt_year--;
					}
				?>
				</select>
			</div>
			<div>
				<label for="einsatzart">Einsatzart:</label>
				<select name="filter_type" id="einsatzart">
					<option value="alle">Alle</option>
				<?php	
					foreach($content["filter_einsatz_type"] as $type) {
						$check="";
						if($type==$content['aktfilter_type']) { $check = " selected"; }						
						echo'<option value="'.$type.'"'.$check.'>'.$type.'</option>';
					}
				?>
				</select>
			</div>
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
					<div class="admin_pagetitle cell idcell"><p><a href="<?php echo base_url().'admin/?op=einsatz_liste&sort=einsatzID&order='.$content["order"]; ?>">ID</a></p></div>
					<div class="admin_pagetitle cell datum"><p><a href="<?php echo base_url().'admin/?op=einsatz_liste&sort=date&order='.$content["order"]; ?>">Einsatzbeginn</a></p></div>
					<div class="admin_pagetitle"><p><a href="<?php echo base_url().'admin/?op=einsatz_liste&sort=title&order='.$content["order"]; ?>">Titel</a></p></div>
				</div>
			</li>

		<?php

			$i=0;

			foreach($content['einsaetze'] as $einsatz) {



				echo'<li><div>';
					
					echo'<a href="'.base_url().'admin/?op=einsatz_edit&einsatzID='.$einsatz['einsatzID'].'">';
					echo'<div class="admin_pagetitle cell idcell"><p>#'.$einsatz['einsatzID'].'</p></div>';
					echo'<div class="admin_pagetitle cell datum"><p>'.basic_get_ger_datetime($einsatz['date_start'], 'datetime', 2).'</p></div>';

					echo'<div class="admin_pagetitle einsatz">';
						echo'<img src="'.base_url().'frontend/images/icons_einsatz/'.basicffw_get_alarmtype($einsatz['type']).'_w.svg" />';
						echo'<p>'.$einsatz['title'].'<br><span>'.$einsatz['alamiertewheren'].'</span></p>';
						echo'<hr class="clear" />';
					echo'</div></a>';
	
					echo'<div class="admin_delete"><a href="'.base_url().'admin/?op=einsatz_delete&target=einsatz_liste&id='.$einsatz['einsatzID'].'">Delete</a></div>';
					
					if($einsatz['online']==1) {	
						echo'<div class="admin_option admin_option_online"><a href="'.base_url().'admin/?op=einsatz_publish&target=einsatz_liste&state=0&id='.$einsatz['einsatzID'].'">Online</a></div>';
					} else {
						echo'<div class="admin_option admin_option_offline"><a href="'.base_url().'admin/?op=einsatz_publish&target=einsatz_liste&state=1&id='.$einsatz['einsatzID'].'">Offline</a></div>';
					}
					
					echo'<hr class="clear" />';
				echo'</div></li>';
			}

		?>
		</ul>
	</div>
