<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    if($this->uri->rsegment(4)=="") {
        $act_year = basic_get_year();
    } else {
        $act_year = $this->uri->rsegment(4);
    }

?>
<div class="row"> 
    <div class="col-4 liste pageHeadline">
        <h1 class="headline_left">Unsere Eins&auml;tze <?php echo $act_year; ?></h1>
        <h2 class="headline_left">Bei Wind und Wetter f&uuml;r Ihre Sicherheit</h2>
    </div>
    <div>
        <form action="">
        <input type="hidden" name="" value="">
        <?php
            $start_year = 2016;
            $linkyear = basic_get_year();
            if($linkyear>$start_year) {        
                echo'<p><a href="#" id="js_einsatzyear_celector" class="singlelink">Einsatzarchiv</a></p>';
                echo'<div id="archive" class="hide"><div class="links">';
                while($start_year <= $linkyear) {
                    if($act_year==$linkyear) {
                        $class='active';
                    } else {
                        $class='';
                    }
                    echo'<a href="'.base_url().$GLOBALS["varpath"].'/'.$einsatzliste['detaillink'].'/'.$linkyear.'" class="'.$class.'">'.$linkyear.'</a>';
                    $linkyear--;
                }
                echo'
                </div></div>';
            }
        ?>
        </form>
    </div>
</div>     
<div class="row raster-4col datafacts"> 
    <div class="col-1">
        <img src="<?php echo base_url().'/frontend/images/icons_einsatz/firealarm.svg'; ?>" alt="Brandeinsätze" />
        <p class="label">Brandeins&auml;tze</p>
        <p class="fact red"><?php echo $einsatzliste['stats']['brandeinsatz']; ?></p>
    </div>
    <div class="col-1">
        <img src="<?php echo base_url().'/frontend/images/icons_einsatz/helpalarm.svg'; ?>" alt="Hilfeleistungseinsätze" />
        <p class="label">Hilfeleistung</p>
        <p class="fact red"><?php echo $einsatzliste['stats']['hilfeleistung']; ?></p>
    </div>
    <div class="col-1">
        <img src="<?php echo base_url().'/frontend/images/icons_einsatz/hazardouse.svg'; ?>" alt="Gefahrenguteinsätze" />
        <p class="label">Gefahrengut</p>
        <p class="fact red"><?php echo $einsatzliste['stats']['gefahrengut']; ?></p>
    </div>
    <div class="col-1">
        <img src="<?php echo base_url().'/frontend/images/icons_einsatz/falsealarm.svg'; ?>" alt="Fehlalarmierungen" />
        <p class="label">Fehlalarm</p>
        <p class="fact red"><?php echo $einsatzliste['stats']['fehlalarm']; ?></p>
    </div>
    <hr class="clear" />
</div>
<div class="row raster-4col datafacts"> 
    <div class="col-2 bottomline">
        <p class="label">Eins&auml;tze insgesamt</p>
        <p class="fact"><?php echo $einsatzliste['stats']['alle']; ?></p>
    </div>
    <div class="col-2 bottomline">
        <p class="label">&uuml;ber&ouml;rtliche Einsätze</p>
        <p class="fact"><?php echo $einsatzliste['stats']['ueberoertlich']; ?></p>
    </div>
    <hr class="clear" />
</div>
<?php
    
    $aktMonth = "";

    foreach($einsatzliste['einsatz'] as $einsatz) {
  
        $month = basic_get_datedetail($einsatz['date_start'], 'monat');
        $year = basic_get_datedetail($einsatz['date_start'], 'jahr');

        if($aktMonth!=$month) {   

            if($aktMonth!="") {
                echo'</ul></div><hr class="clear" /></div>';
            }
            $aktMonth = $month;

            echo'
            <div class="row raster-4col datum"> 
                <div class="col-4">
                    <h1>'.$month.'</h1>
                    <h2>'.$year.'</h2>
                </div>
                <hr class="clear" />
            </div>';

            echo'
            <div class="row raster-4col einsatzliste"> 
            <div class="col-4">
            <ul>';
        }

        echo'
        <a href="'.base_url().$GLOBALS["varpath"].'/'.$einsatzliste['detaillink'].'/'.$year.'/'.$einsatz["einsatzID"].'">
        <li>
            <div class="icon">
                <img src="'.base_url().'/frontend/images/icons_einsatz/'.$einsatz['icon'].'.svg" alt="'.$einsatz['type'].'" />
            </div>
            <div class="content">
                <div class="datetime">
                    <p class="date">'.basic_get_ger_datetime($einsatz['date_start'], 'dateonly', 2).'</p>
                    <p>'.basic_get_ger_datetime($einsatz['date_start'], 'time', 2).' Uhr</p>
                </div>
                <div class="number">#'.$einsatz['number'].'</div>
                <div class="infos">
                    <h3>'.$einsatz['title'].'</h3>
                </div>
            </div>
            <hr class="clear" />
        </li>
        </a>';

    }

?>
</ul></div><hr class="clear" /></div>
<div id="footer_placeholder"></div>