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
			
			<input type="hidden" name="op" value="news_liste">
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

			foreach($content['news'] as $news) {

				

				echo'<li><div>';
					
					echo'<a href="'.base_url().'admin/?op=news_edit&newsID='.$news['newsID'].'">';
					echo'<div class="admin_pagetitle cell idcell"><p>#'.$news['newsID'].'</p></div>';

					echo'<div class="admin_pagetitle einsatz">';
						echo'<p>'.$news['headline'].'<br><span>'.$news['date'].'</span></p>';
						echo'<hr class="clear" />';
					echo'</div></a>';
	
					echo'<div class="admin_delete"><a href="'.base_url().'admin/?op=news_delete&target=news_liste&id='.$news['newsID'].'">Delete</a></div>';
					
					if($news['online']==1) {	
						echo'<div class="admin_option admin_option_online"><a href="'.base_url().'admin/?op=news_publish&target=news_liste&state=0&id='.$news['newsID'].'&sort='.$content['aktfilter_wehren'].'">Online</a></div>';
					} else {
						echo'<div class="admin_option admin_option_offline"><a href="'.base_url().'admin/?op=news_publish&target=news_liste&state=1&id='.$news['newsID'].'&sort='.$content['aktfilter_wehren'].'">Offline</a></div>';
					}
					
					if($news['archive']==0) {	
						echo'<div class="admin_option admin_option_online"><a href="'.base_url().'admin/?op=news_archive&target=news_liste&state=1&id='.$news['newsID'].'&sort='.$content['aktfilter_wehren'].'">LIVE</a></div>';
					} else {
						echo'<div class="admin_option admin_option_offline"><a href="'.base_url().'admin/?op=news_archive&target=news_liste&state=0&id='.$news['newsID'].'&sort='.$content['aktfilter_wehren'].'">Archiv</a></div>';
					}

					echo'<hr class="clear" />';
				echo'</div></li>';
			}

		?>
		</ul>
	</div>
