<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="editbox_news">
    <input type="hidden" name="news_headline" value="<?php echo $news_details['headline']; ?>" />
    <input type="hidden" name="news_shorttext" value="<?php echo $news_details['text']; ?>" />
    <input type="hidden" name="news_datetime" value="<?php echo $news_details['date']; ?>" />
    <input type="hidden" name="news_wehrID" value="<?php echo $news_details['wehrID']; ?>" />
   