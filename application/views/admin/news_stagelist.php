<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">
	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['page_headline']; ?></h1>
		<a href="<?php echo base_url(); ?>admin/?op=news_edit&amp;newsID=newnews" class="admin_button"><?php echo $content['page_btn_addnew']; ?></a> 
		<hr class="clear" />

	</div>
	<div id="admin_pageheadline_placeholder" class="hide"></div>

	<div id="filterbar">
		<form name="filter" id="admin_form" method="post">
			
			<input type="hidden" name="op" value="news_stageliste">
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
					<div class="admin_pagetitle cell rang"><p>Rang</p></div>
					<div class="admin_pagetitle"><p>Name</p></div>
				</div>
			</li>

		<?php

			$i=0;

			foreach($content['stages'] as $stages) {
				echo'<li><div>';
					echo'<a href="'.base_url().'admin/?op=news_edit&newsID='.$stages['stageID'].'">';
					echo'<div class="admin_pagetitle cell idcell"><p>#'.$stages['stageID'].'</p></div>';

					echo'<div class="admin_pagetitle einsatz">';
						echo'<p>'.$stages['headline'];
							if($stages['subline']!="") { echo'<br><span>Subline: '.$stages['subline'].'</span>'; } 
							if($stages['link']!="") { echo'<br><span>Button: '.$stages['link'].'</span>'; }
						echo'</p>';
						echo'<hr class="clear" />';
					echo'</div></a>';
	
					echo'<div class="admin_delete"><a href="'.base_url().'admin/?op=news_delete&target=news_liste&id='.$stages['stageID'].'">Delete</a></div>';
					
					echo'<div class="admin_option"><a href="'.base_url().'admin/?op=news_stageremove&target=news_stageliste&id='.$stages['stageID'].'&sort='.$content['aktfilter_wehren'].'">entfernen</a></div>';
					echo'<div class="admin_option_icon admin_movedown"><a class="admin_movedown" href="'.base_url().'admin/?op=news_stagepos&direction=down&target=news_stageliste&id='.$stages['stageID'].'&amp;sort='.$content['aktfilter_wehren'].'">DOWN</a></div>';
					if($i>0) {	
						echo'<div class="admin_option_icon admin_movedown"><a class="admin_moveup" href="'.base_url().'admin/?op=news_stagepos&direction=up&target=news_stageliste&id='.$stages['stageID'].'&amp;sort='.$content['aktfilter_wehren'].'">DOWN</a></div>';
					} else {
						echo'<div class="admin_option_icon_blank"></div>';
					}
					echo'<hr class="clear" />';
				echo'</div></li>';
				$i++;
			}

		?>
		</ul>
	</div>

		<div>
		<h3 class="admin">Alle Verfügbaren Bühnen</h3>
		<ul id="page_list">
			<li class="head">
				<div>
					<div class="admin_pagetitle cell idcell"><p>ID</p></div>
					<div class="admin_pagetitle cell rang"><p>Rang</p></div>
					<div class="admin_pagetitle"><p>Name</p></div>
				</div>
			</li>

		<?php

			$i=0;

			foreach($content['allstages'] as $stages) {
				
				$show = 1;
				foreach($content['stages'] as $aktstage) {
					if($stages['stageID']==$aktstage['stageID']) {
						$show = 0;
						break;
					}
				}
				
				if($show>0) {
					echo'<li><div>';
						echo'<a href="'.base_url().'admin/?op=news_edit&newsID='.$stages['stageID'].'">';
						echo'<div class="admin_pagetitle cell idcell"><p>#'.$stages['stageID'].'</p></div>';

						echo'<div class="admin_pagetitle einsatz">';
							echo'<p>'.$stages['headline'];
								if($stages['subline']!="") { echo'<br><span>Subline: '.$stages['subline'].'</span>'; } 
								if($stages['link']!="") { echo'<br><span>Button: '.$stages['link'].'</span>'; }
							echo'</p>';
							echo'<hr class="clear" />';
						echo'</div></a>';
		
						echo'<div class="admin_delete"><a href="'.base_url().'admin/?op=news_delete&target=news_liste&id='.$stages['stageID'].'">Delete</a></div>';
						echo'<div class="admin_option admin_option_addto"><a href="'.base_url().'admin/?op=news_addstage&target=news_stageliste&id='.$stages['stageID'].'&sort='.$content['aktfilter_wehren'].'">Zur Startseite hinzufügen</a></div>';
						
						echo'<hr class="clear" />';
					echo'</div></li>';
				}
				$i++;
			}

		?>
		</ul>
	</div>
