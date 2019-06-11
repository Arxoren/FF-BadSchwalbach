<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php

    switch($einsatz['type']) {   
           case "Fehlalarm": 
                $image = ' style="background-image: url('.base_url().'frontend/images/stage/stage_einsatz_false.jpg);"'; 
                $color = 'white'; 
                break;
           case "Hilfeleistung": 
                $image = ' style="background-image: url('.base_url().'frontend/images/stage/stage_einsatz_help.jpg);"'; 
                $color = 'black';
                break;
           case "Brandeinsatz": 
                $image = ' style="background-image: url('.base_url().'frontend/images/stage/stage_einsatz_fire.jpg);"'; 
                $color= 'white';
                break;
           case "Gefahrenguteinsatz": 
                $image = ' style="background-image: url('.base_url().'frontend/images/stage/stage_einsatz_gefahr.jpg);"'; 
                $color = 'white';
                break;
    }

    echo'
    <section id="stage">
      <div class="standard einsatz fahrzeugliste "'.$image.'>
            <div class="breadcrump '.$color.'">';  
                
                if($this->uri->total_rsegments() > 2) {
                  echo basic_get_breadcrumppath($this->uri->segment_array(), $this->uri->rsegment_array(), base_url());
                }

            echo'
            </div>
        </div>
    </section>';

?>
<section id="content">

