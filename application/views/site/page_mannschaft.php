<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<div class="row"> 
  <div class="col-4 liste mannschaft">
              
  	
  <?php

    $wehrHeadline="";
    $trennerclass="";

    if(count($einsatzabteilung)!=0) {    
      foreach ($einsatzabteilung as $member) {
      
        if($member["wehrID"]!=$wehrHeadline) {
          
          if($wehrHeadline!="") { echo '</ul>'; }

          $wehrdetails = basicffw_get_vereindetails($member["wehrID"]); 
          $wehrHeadline = $member["wehrID"];
          echo'
            <h2 class="headline_left '.$trennerclass.'">Standort</h2>
            <h1 class="headline_left">'.$wehrdetails['ort'].'</h1>
            <ul>';
          $trennerclass='withline';
          $team=0;

        }

        if($member["position"]==0 && $team==0) {
          echo'</ul><ul>';
          $team=1;
        }


        $rang = basicffw_get_rang($member["rang"], $member["geschlecht"]);

        echo'
          <li>
            <figure>';

              if($member["bild"]!="") {
                echo'<img src="'.base_url().'frontend/images_cms/mannschaft/'.$member["bild"].'" alt="'.$member["vorname"].' '.$member["nachname"].'" />';
              } else {
                echo'<img src="'.base_url().'frontend/images_cms/mannschaft/default.jpg" alt="'.$member["vorname"].' '.$member["nachname"].'" />';
              }

            echo'  
              
            </figure>
            <div class="rang">
              <img src="'.base_url().'frontend/images/abzeichen/'.$rang['bild'].'.png" alt="'.$rang['name'].'" title="'.$rang['name'].'" />
            </div>
            <div>
              <h3>'.$member["vorname"].' '.$member["nachname"].'</h3>
              <p>'.$rang['name'].basicffw_get_position($member["position"]).'</p>
              <p>'.$member['alter'].$member['beruf'].'</p>
            </div>
          </li>';

      }
    } else {
      echo '<p>Leider sind im Moment keine Mannschaftsmitglieder online geschaltet</p>';
    }

  ?>

  </ul>
                 
  </div>
</div>