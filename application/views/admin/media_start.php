<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">

	<div id="admin_pageheadline" class="admin_pageheadline">
		<h1 class="admin"><?php echo $content['headline']; ?></h1>
		<hr class="clear" />
	</div>
	<div id="admin_pageheadline_placeholder" class="hide"></div>

	<div>
		<ul class="admin_startblocklink">
			<li>
				<a href="<?php echo base_url(); ?>admin/?op=media_folder_list&amp;type=images">
				<div>
					<img src="<?php echo base_url(); ?>backend/images/icon_images.svg" />
					<br><br>Bilder Verwalten
				</div>
				</a>
			</li>
			<li>
				<a href="<?php echo base_url(); ?>admin/?op=media_folder_list&amp;type=files">
				<div>
					<img src="<?php echo base_url(); ?>backend/images/icon_files.svg" />
					<br><br>Dateien Verwalten
				</div>
				</a>
			</li>			

		</ul>
	</div>