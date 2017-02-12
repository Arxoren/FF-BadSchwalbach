<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="shortnews">
<div class="newsrow">

<?php 
if(count($news)!=0) { 
	foreach($news as $news_item) { ?>

	    <div>
	        <img src="images/news_images/feuerwehrpreis_small.jpg" class="img_right" />
	        <div>
	        <h4><?php echo $news_item['date']; ?> - <?php echo $news_item['category']; ?></h4>
	        <h2><?php echo $news_item['headline']; ?></h2>
	        <hr>
	        <p><?php echo $news_item['text']; ?></p>
	        <p class="button"><a href="#">weiter lesen</a></p>
	    </div>
	</div>

	<?php 
	}
} else {
	echo "<p>Leider haben wir zur Zeit nicht Neues zu berichten</p>";
} 
?>

<hr class="clear">
</div>
</div>

