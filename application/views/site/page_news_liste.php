<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row"> 
  <div class="col-4 liste news">
    <div class="shortnews">
    <div class="newsrow">
  
  <?php
    $i=0;

    if(count($newsliste)!=0) {
        foreach($newsliste as $news) {

          $ch = curl_init();
          
          if(($i%2)==0 && $i!=0) {
            echo'<hr class="clear"></div><div class="newsrow">';
          }

          if($news['link']=="") {
            $link = base_url().$GLOBALS['varpath'].'/aktuelles/news/'.$news["newsID"].'/'.curl_escape($ch, $news["headline"]);
          } else {
            $link = $news['link'];
          }

          echo'
          <div>
            <img src="'.base_url().'frontend/images_cms/news/news_'.$news['newsID'].'_big.jpg" alt="'.$news["headline"].'" />
            <div>
              <h4>'. basic_get_ger_datetime($news['date'], 'datetime', 2).' - '.$news['category'].'</h4>
              <h2>'.$news['headline'].'</h2>
              <hr class="trenner">
              <p>'.$news['text'].'</p>
              <p class="button"><a href="'.$link.'">weiter lesen</a></p>
            </div>
          </div>';
          $i++;
        }
    } else {
      echo "<p>Leider haben wir zur Zeit nicht Neues zu berichten</p>";
    } 

  ?>
        
  <hr class="clear">               
  </div></div>
  </div>
</div>
