<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<?php

if($GLOBALS['location']==0) {
  echo '<div id="anchorbar" class="anchorbarstyle">
        <span id="js_open_anchorbar" class="anchorbar_mobile-opener">Standorte anzeigen</span>
  <ul class="js-anchor-list">';
        echo'<li>Standorte:</li>';
    $wehrlist = basicffw_get_wehrlist();      
    foreach($wehrlist as $wehr) {
       echo'<li><a href="#'.$wehr["wehrID"].'">'.$wehr["ort"].'</a></li>';
    }
  echo'</ul></div>
  <div id="anchorbar_placeholder" class="hide"></div>';
}

?>

<div class="row"> 
  <div class="col-4 liste mannschaft">
              
  	
  <?php

    $wehrHeadline="";
    $trennerclass="";

    if(count($einsatzabteilung)!=0) {    
      foreach ($einsatzabteilung as $member) {
      
        if($member["position"]<5) {
          if($member["wehrID"]!=$wehrHeadline) {
              
              if($wehrHeadline!="") { echo '</ul>'; }

              $wehrdetails = basicffw_get_vereindetails($member["wehrID"]); 
              $wehrHeadline = $member["wehrID"];
              echo'
                <h2 class="headline_left '.$trennerclass.'" id="'.$member["wehrID"].'">Standort</h2>
                <h1 class="headline_left">'.$wehrdetails['ort'].'</h1>
                <ul>';
              $trennerclass='withline';
              $team=0;
          }
        } else {
          if($wehrHeadline=="") {
            $wehrdetails = basicffw_get_vereindetails($member["wehrID"]); 
            $wehrHeadline = "leads";
            echo'
              <h2 class="headline_left '.$trennerclass.'">Leitung</h2>
              <h1 class="headline_left">Statdbrandinspektoren</h1>
              <ul>';
            $trennerclass='withline';
              $team=0;
          }
        }

        if($member["position"]<5 && $team==0) {
          echo'</ul><h3>Wehr- und Einsatzleitung</h3><ul>';
          $team=1;
        }
        if($member["position"]==0 && $team==1) {
          echo'</ul><h3>Mannschaft</h3><ul>';
          $team=2;
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