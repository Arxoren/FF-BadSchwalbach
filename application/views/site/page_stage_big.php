<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section id="stage">

<?php

    $stagebubble = "";
    $class = "";
    $i = 1;

    foreach($bigstage_image as $stage_content) {

   		if($stage_content['image']!="") {   
           $image = ' style="background-image: url('.base_url().'frontend/images_cms/stages_big/'.$stage_content['image'].');"';
        } else {
            $image = "";
        }

        echo'
    	<div class="js-stage-'.$i.' big'.$class.'"'.$image.'>
            <div class="breadcrump'.$stage_content['color'].'">';	
                
           		if($this->uri->total_rsegments() > 2) {
            		echo basic_get_breadcrumppath($this->uri->segment_array(), $this->uri->rsegment_array(), base_url());
            	}
                if($stage_content["headline"]!="") {
                    echo'<h1 class="'.$stage_content["color"].'">'.$stage_content["headline"].'</h1>';
                }	
                if($stage_content["subline"]!="") {
                    echo'<p class="quote '.$stage_content["color"].'">'.$stage_content["subline"].'</p>';
                }
                if($stage_content["link"]!="") {
                    echo'<p class="button"><a href="'.$stage_content["link"].'" class="s'.$stage_content["color"].'">Bericht lesen</a></p>';
                }
            echo'
            </div>
        </div>
        ';

        $class = " hide";
        $stagebubble =  $stagebubble.'<li class="js_callstage js-stage-link-'.$i.'" num="'.$i.'">#'.$i.'</li>';
        $i++;
    }

    if($i>2) {
        echo'<ul>'.$stagebubble.'</ul>';
    }
?>
    
</section>

<section id="content">