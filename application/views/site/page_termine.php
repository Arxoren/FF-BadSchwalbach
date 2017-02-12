<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row"> 
    <div class="col-4">
        <h1 class="headline_left">Terminübersicht</h1>
    </div>
    <hr class="clear" />
</div> 

<div class="row">
  <div class="col-4">
    
  <?php
    $i=0;

    if(count($termine)!=0) {
        foreach($termine as $termin) {

          $zeitleiste = $termin['wochentag'].', den '.$termin['datum_von'];
          $timeleiste = '<span>'.$termin['zeit_von'].' Uhr</span>';

          if($termin['date_ende']!="0000-00-00 00:00:00") {
            $zeitleiste = $zeitleiste.' bis zum '.$termin['datum_bis'];
            $timeleiste = $timeleiste.' bis <span>'.$termin['zeit_bis'].' Uhr</span>';
          }


          echo'
          <div class="termin">
            <div class="date">
              <p class="day">'.$termin['tag'].'</p>
              <p>'.$termin['monat'].'</p>
              <p>'.$termin['jahr'].'</p>
            </div>
            <div class="content">  
              <h2>'.$termin['headline'].'</h2>
              <h3>'.$termin['zeit_von'].' - '.$termin['feuerwehr'].'</h3>
              <div class="js_termindetails_'.$i.' hide">
                <p>'.$termin['text'].'</p>
                <div class="box time">
                  <p>'.$zeitleiste.'</p>
                  <p>'.$timeleiste.'</p>
                </div>';

                if($termin['ort']!="") {
                echo'
                <div class="box location">
                  <p>'.$termin['ort'].'</p>
                </div>';
                }

              echo'  
              </div>
              <p class="close"><a href="#" class="js_closetermin" terminno="'.$i.'">Details</a></p>
            </div>
            <hr class="clear">
          </div>';
          $i++;
        }
    } else {
      echo "<p>Im Moment haben wir keine anstehenden Termine.</p>";
    }

  ?>
        
  </div>
</div>
