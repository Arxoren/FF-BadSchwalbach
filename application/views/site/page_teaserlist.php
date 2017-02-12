<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<div class="row"> 
  <div class="col-4 liste teaser_row">
              
  	
  <?php

    $wehrHeadline="";
    $trennerclass="";

    foreach($fahrzeugliste as $fahrzeug) {

      if($fahrzeug["wehrID"]!=$wehrHeadline) {
        $wehrdetails = basicffw_get_vereindetails($fahrzeug["wehrID"]); 
        $wehrHeadline = $fahrzeug["wehrID"];
        echo'
          <h2 class="headline_left '.$trennerclass.'">Standort</h2>
          <h1 class="headline_left">'.$wehrdetails['ort'].'</h1>
          <ul>';
        $trennerclass='withline';
      }

      $ch = curl_init();

      echo'
        <li>
          <a href="'.base_url().$this->uri->uri_string().'/'.$fahrzeug["fahrzeugID"].'/'.curl_escape($ch, str_replace("/", "_", $fahrzeug["shortname"])).'">
          <figure><img src="'.base_url().'frontend/images_cms/fahrzeuge/teaser/'.$fahrzeug["teaser_image"].'" alt="'. $fahrzeug["shortname"].' - '.$fahrzeug["name"].'" /></figure>
          <div>
            <h2>'.$fahrzeug["shortname"].'</h2>
            <h3>'.$fahrzeug["name"].'</h3>
          </div>
        </li>
        </a>';

    }

  ?>

  </ul>
                 
  </div>
</div>