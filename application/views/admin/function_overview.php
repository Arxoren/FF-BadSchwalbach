<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin_contentbox">

	<div id="admin_pageheadline" class="admin_pageheadline">
		<h1 class="admin"><?php echo $content['headline']; ?></h1>
		<hr class="clear" />
	</div>

	<div>
		<ul class="admin_startblocklink">
		<?php	

			foreach($content['dashboard_functionlist'] as $navi) {
				echo'
				<li>
					<a href="'.base_url().'admin/?op='.$navi['var'].'">
					<div>
						<img src="'.base_url().'backend/images/'.$navi['image'].'" />
						<br><br>'.$navi['linkname'].'
					</div>
					</a>
				</li>';
			}

		?>
		</ul>
	</div>