<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php 
$imgname = 'news_'.$news_details['newsID'].'_big';
if($GLOBALS['editable_tag']!="") {
	$img = 'news_'.$news_details['newsID'].'_big.jpg';
	if(!is_file("./frontend/images_cms/news/".$img)) {	
		$img = 'news_XXX_big.jpg';
	}
	$edit_date = ' js_admin_news_opendatetime" data-moduletype="news" data-contentmoduleid="'.basic_get_moduleID("Basic News Details").'" data-id="'.$news_details['newsID'].'" data-moduleid="adminnews';
	$edit_image = ' id="js_admin_imageupload" data-imgtagtarget="js_admin_imageupload" data-type="image" data-path="news" data-imgname="'.$imgname.'"';
	$newsimage = '<img src="'.base_url().'frontend/images_cms/news/'.$img.'"'.$edit_image.' />';
} else {
	$edit_image = $edit_date = '';
	if(is_file('./frontend/images_cms/news/news_'.$news_details['newsID'].'_big.jpg')) {		
		$newsimage = '<img src="'.base_url().'frontend/images_cms/news/news_'.$news_details['newsID'].'_big.jpg" />';
	} else {
		$newsimage = '';
	}
}

if(isset($news_details['headline'])) { ?>
	<div class="row"> 
	    <div class="col-4">
	    	<?php echo $newsimage; ?>
	    	<h3 class="datum<?php echo $edit_date; ?>"><?php echo basic_get_ger_datetime($news_details['date'], 'datetime', 2).' - '.$news_details['category']; ?></h3>
	    	<h1 class="headline_left"<?php echo $GLOBALS['editable_tag']; ?>><?php echo $news_details['headline']; ?></h1>
	    	<p<?php echo $GLOBALS['editable_tag']; ?>><?php echo $news_details['text']; ?></p>
	    </div>
	    <hr class="clear" />
	</div>

<?php
} else {
	show_404($page = '', $log_error = TRUE);
}
?>


