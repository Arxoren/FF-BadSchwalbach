<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">

    <?php 
		
		if($content["path"]!="images_cms" && $content["path"]!="files_cms") {	
			$last_path = "&amp;path=".str_replace("/", ",", str_replace("".$_GET["type"]."_cms/", "", $content["path"]));
		} else {
			$last_path = "";
		}

		if($_GET["type"]=="images") {
			$addButton = '<a href="#" class="admin_button js_admin_addImage">Bild hinzufügen</a>';
		} else {
			$addButton = '<a href="#" class="admin_button js_admin_addImage">Datei hinzufügen</a>';
		}

		if($content['path']!="") {
       		echo'
			<div id="admin_siteeditbar">
				<div class="admin_back"><a href="'.base_url().'admin/?op=media_folder_list&amp;type='.$_GET["type"].''.$last_path.'"><img src="'.base_url().'backend/images/button_back.png" /></a></div>
		        <div class="admin_pagename">
   	      			<h4 class="admin">'.str_replace("/", " / ", $content['path']).' /</h4>
   	       			<h1 class="admin">'.str_replace("_", " ", $content['headline']).'</h1>
			    </div>
			    <div class="newentry">
			    	'.$addButton.'
			    </div>
		        <hr class="clear" />
			</div>';
       	} else {
       		echo'
       		<div id="admin_siteeditbar">
				<div class="admin_pagename">
					<h4 class="admin">&nbsp;</h4>
					<h1 class="admin">'.$content['headline'].'</h1>
				</div>
				<hr class="clear" />
			</div>
			';
       	}


    ?>

	<div>
		<?php


			if(isset($content['folder'])) {
				echo '<ul id="page_list">';
						
				foreach($content['folder'] as $folder) {
					
					if(substr($folder, -1)=="\\" || substr($folder, -1)=="/") {
						
						if($content['path']=="") {	
							$next_path = str_replace("\\", "", str_replace("/", "", $folder));
						} else {
							$next_path = str_replace("/", ",", str_replace("images_cms/", "", $content["media_path"])).','.str_replace("/", "", str_replace("\\", "", $folder));
						}

						echo'
						<li>
							<a href="'.base_url().'admin/?op=media_folder_list&type='.$_GET["type"].'&amp;path='.$next_path.'">
							<div>
								<img src="'.base_url().'backend/images/icon_folder.svg" class="admin_svg_icon" /> 
								<p class="admin_foldername">'.str_replace("\\", "/", $folder).'</p>
							</div>
							</a>
						</li>';
					}
				}
				echo '</ul>';
			}

				
			if(isset($content['images']) && $content['images']!="") {
				
				echo '<table>';
				foreach($content['images'] as $folder) {
					
					$img = $folder['name'].'.'.$folder['format'];

					echo'
					<a href="'.base_url().'admin/?op=media_folder_list&amp;folder="">
					<tr class="card">
						<td class="admin_preview">
							<img src="'.base_url().'frontend/images_cms/'.$folder["folder"].$img.'" class="admin_svg_icon" /> 
						</td>						
						<td class="alttext">
							ID: #'.$folder['imageID'].'
						</td>
						<td class="filename">
							'.$img.'
						</td>
						<td class="alttext">
							'.$folder['alt'].'
						</td>';

						if(!in_array($folder["folder"], $content['protection'])) {
							echo'
							<td class="editpanel">
								<a href="#" class="actionbutton btn_settings">bearbeiten</a>
								<a href="'.base_url().'admin/?op=media_delete&amp;target=media_folder_list&amp;type='.$_GET['type'].'&amp;path='.str_replace("/", ",", str_replace("images_cms/", "", $content["media_path"])).'&amp;fileID='.$folder['imageID'].'" class="actionbutton btn_delete">löschen</a>
							</td>';
						}
					echo'	
					</tr>
					</a>';
				}
				echo'</table>';
			} elseif(isset($content['files']) && $content['files']!="") {
				
				echo '<table>';
				foreach($content['files'] as $folder) {
					
					switch($folder['format']) {	
						case "pdf": $file_icon = 'icon_files_pdf.svg'; break;
						case "txt": $file_icon = 'icon_files_txt.svg'; break;
						case "zip": $file_icon = 'icon_files_zip.svg'; break;
						case "doc": $file_icon = 'icon_files_doc.svg'; break;
						case "docx": $file_icon = 'icon_files_doc.svg'; break;
						case "ppt": $file_icon = 'icon_files_ppt.svg'; break;
						case "pptx": $file_icon = 'icon_files_ppt.svg'; break;
						default: $file_icon = 'icon_files_blank.svg'; break;
					}

					echo'
					<a href="'.base_url().'admin/?op=media_folder_list&amp;folder="">
					<tr class="card">
						<td class="admin_filepreview">
							<img src="'.base_url().'backend/images/'.$file_icon.'" /> 
						</td>
						<td class="filename_org">
							'.$folder['filename'].'
						</td>
						<td class="filename">
							'.$folder['name'].'<br>'.$folder['size'].'
						</td>
						<td class="alttext">
							'.$folder['description'].'
						</td>
						<td class="editpanel">
							<a href="#" class="actionbutton btn_settings">bearbeiten</a>
							<a href="'.base_url().'admin/?op=media_delete&amp;target=media_folder_list&amp;type='.$_GET['type'].'&amp;path='.str_replace("/", ",", str_replace("images_cms/", "", $content["media_path"])).'&amp;fileID='.$folder['fileID'].'" class="actionbutton btn_delete">löschen</a>
						</td>
					</tr>
					</a>';
				}
				echo'</table>';#
			}
		?>
	</div>

	<?php
	if($_GET["type"]=="images") {
	?>
		<div id="js_media_upload" class="admin_hide">
			<div class="admin_lightbox"></div>
			<div class="admin_lightbox_content">
				<div class="admin_lightbox_close" id="js_admin_lightbox_close"><a href="#" id="js_admin_uploadbox_close">CLOSE</a></div>
				<div class="admin_imageupload_bg">

					<h1 class="admin">Ein neues Bild hochladen</h1>
					<h2 class="admin">Zielpfad: <span><?php echo $content["path"].'/'.lcfirst($content["headline"]); ?><span></h2>

					<form action="" method="post" id="js_admin_fileuploadform" enctype="multipart/form-data">
						<input type="hidden" name="op" value="media_upload">
						<input type="hidden" name="media_type" value="image">
						<input type="hidden" name="target" value="media_folder_list">
						<input type="hidden" name="folder" id="js_admin_uploadtarget" value="<?php echo $content["path"].'/'.lcfirst($content["headline"]); ?>" />

						<div class="js_adminimageupload_box">
							
							<div class="admin_uploadcontainer admin_uploadcontainer_1">
								<p><input type="file" name="media_file[]" class="js_media_file_1 js_meda_choosefile" data-uploadnumber="1" /></p>
								<p class="imagePreview_container">
									<div class="admin_imagePreview" id="js_adminimage_preview_1" data-uploadnumber="1">
										<div class="admin_upload_advice"><strong>hier klicken</strong><br>um ein Bild hoch zu laden</div>
									</div>
								</p>

								<p>
									<label for="alt_text"><span class="helptext">ALT-Text</span></lable>
									<input type="text" name="alt_text" />
								</p>
							</div>

						</div>

						<div class="">
							<input type="button" value="weiteres Bild mit hochladen" id="js_admin_moremediaupload" />
						</div>
						

						<div class="admin_uploadbutton">
							<input type="button" value="speichern" id="js_admin_savefile" />
						</div>
					</form>

				</div>
			</div>
		</div>
	<?php
	} else {
	?>
		<div id="js_media_upload" class="admin_hide">
			<div class="admin_lightbox"></div>
			<div class="admin_lightbox_content">
				<div class="admin_lightbox_close" id="js_admin_lightbox_close"><a href="#" id="js_admin_uploadbox_close">CLOSE</a></div>
				<div class="admin_imageupload_bg">

					<h1 class="admin">Eine neue Datei hochladen</h1>
					<h2 class="admin">Zielpfad: <span><?php echo $content["path"].'/'.lcfirst($content["headline"]); ?><span></h2>

					<form action="" method="post" id="js_admin_fileuploadform" enctype="multipart/form-data">
						<input type="hidden" name="op" value="media_upload">
						<input type="hidden" name="media_type" value="file">
						<input type="hidden" name="target" value="media_folder_list">
						<input type="hidden" name="folder" id="js_admin_uploadtarget" value="<?php echo $content["path"].'/'.lcfirst($content["headline"]); ?>" />

						<div class="js_adminimageupload_box">
							
							<div class="admin_uploadcontainer admin_uploadcontainer_1">
								<p><input type="file" name="media_file[]" class="" data-uploadnumber="1" /></p>
								<p>&nbsp;</p>
								<p>
									<label for="displayname"><span class="helptext">Anzeige Name</span></lable>
									<input type="text" name="displayname" />
								</p>
								<p>&nbsp;</p>
								<p>
									<label for="description"><span class="helptext">Beschreibung</span></lable>
									<input type="text" name="description" />
								</p>
							</div>
					        <div class="admin_image_upload">
					            <input type="button" value="weiteres Bild mit hochladen" id="js_admin_moremediaupload" />
					        </div>
						</div>

						<div class="admin_uploadbutton">
							<input type="button" value="speichern" id="js_admin_savefile" />
						</div>
						
					</form>

				</div>
			</div>
		</div>
	<?php
	}
	?>