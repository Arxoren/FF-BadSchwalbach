<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
    
    if(!isset($teaser_list)) {
        $teaser_list[0] = array(
            'adminimgID' => "",
            'link' => "Link",
            'image' => "placeholder.jpg",
            'intro' => "Intro",
            'text' => "Text",
        );
    }
    $i=0;
?>

<div class="row"> 
    <div class="col-4 teaser_row">
        <ul>
        <?php

          foreach($teaser_list as $teaserdetails) {
              if($GLOBALS['editable_tag']!="") {  
                echo'<li class="js_admin_teaseredit_'.$i.'" data-imgid="'.$teaserdetails["adminimgID"].'">';
                    echo'<div class="admin_teaser_edit" data-itemID="'.$i.'"></div>';
                    echo'<div class="admin_teaser_delete"></div>';
                    echo'<input type="hidden" name="js_admin_teaserimage_'.$i.'" value="'.$teaserdetails["adminimgID"].'" />';
              } else {
                echo'<li>';
              }
                echo'
                  <a href="'.$teaserdetails["link"].'">
                  <figure><img src="'.base_url().'frontend/images_cms/'.$teaserdetails["image"].'" alt="'.$teaserdetails["text"].'" /></figure>
                  <div class="subhead">';
                    if($teaserdetails["intro"]!="") { echo'<h3>'.$teaserdetails["intro"].'</h3>'; }
                    if($teaserdetails["text"]!="") { echo'<h2>'.$teaserdetails["text"].'</h2>'; }
                echo'    
                  </div>
                  </a>
              </li>
            ';
            $i++;
          }
        
        ?>
        </ul>
  </div>
</div>
