<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row einsatzdetails"> 
    <div class="col-4 name">
        <img src="<?php echo base_url().'frontend/images/icons_einsatz/'.$einsatz['icon'].'.svg'; ?>" />
        <h1 class="headline_left"><?php echo $einsatz['title']; ?></h1>
        <h2 class="headline_left"><?php echo $einsatz['type']; ?> #<?php echo $einsatz['number']; ?></h2>
        <hr class="clear" />
    </div>
</div> 
<div class="row raster-4col datafacts"> 
    <div class="col-2">
        <p class="label">Alarmstichwort</p>
        <p class="fact red"><?php echo $einsatz['stichwort']; ?></p>
    </div>
    <div class="col-2">
        <p class="label">Einsatzort</p>
        <p class="fact red"><?php echo $einsatz['ort']; ?></p>
    </div>
    <hr class="clear" />
</div>
<div class="row raster-4col datafacts"> 
    <div class="col-1">
        <div class="fact-icon">    
            <img src="<?php echo base_url().'frontend/images/icons/icon_calendar.svg'; ?>" alt="Beginn des Einsatzes" />
        </div>
        <div class="fact-text">
            <p class="label">Einsatzbeginn</p>
            <p class="timefact red"><?php echo basic_get_ger_datetime($einsatz['date_start'], 'dateonly', 2); ?></p>
            <p class="timefact time red"><?php echo basic_get_ger_datetime($einsatz['date_start'], 'time', 2); ?> Uhr</p>
        </div>
        <hr class="clear"/>
    </div>
    <div class="col-1">
        <div class="fact-icon">
            <img src="<?php echo base_url().'frontend/images/icons/icon_einsatzdauer.svg'; ?>" alt="Dauer des Einsatzes" />
        </div>
        <div class="fact-icon">
            <p class="label">Einsatzdauer</p>
            <p class="timefact red"><?php echo $einsatz['dauer_h']; ?> h</p>
            <p class="timefact time red"><?php echo $einsatz['dauer_m']; ?> Min</p>
        </div>
    </div>
    <div class="col-1">
        <div class="fact-icon">
            <img src="<?php echo base_url().'frontend/images/icons/icon_fahrzeuge.svg'; ?>" alt="Eingesetzte Fahrzeuge" />
        </div>
        <div class="fact-text">
            <p class="label">Fahrzeuge</p>
            <p class="fact number red"><?php echo $einsatz['fahrzeuge_anzahl']; ?></p>
        </div>
    </div>
    <div class="col-1">
        <div class="fact-icon">
            <img src="<?php echo base_url().'frontend/images/icons/icon_helmmannschaft.svg'; ?>" alt="Eingesetzte eigene Kr&aum;fte" />
        </div>
        <div class="fact-text">
            <p class="label">Eigene Kr&auml;fte</p>
            <p class="fact number red"><?php echo $einsatz['eigenekraefte']; ?></p>
        </div>
    </div>
    <hr class="clear" />
</div>
<div class="row raster-4col datafacts"> 
    <div class="col-4">
        <p class="label wache">Alarmierte Wachen</p>
        <p class="fact wache red"><?php echo $einsatz['alamiertewheren']; ?>
        </p>
    </div>
    <hr class="clear" />
</div>