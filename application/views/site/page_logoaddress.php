<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<div class="row standort"> 
    <div class="logo">
        <img src="<?php echo base_url().'frontend/images/standort_logos/'.$vereinsintro['wehr_logo']; ?>" />
    </div>
    <div class="address">
        <h2>Freiwillige Feuerwehr</h2>
        <h1><?php echo $vereinsintro['wehr_name']; ?></h1>
        <p>
            <?php echo $vereinsintro['plz']." ".$vereinsintro['ort'].", ".$vereinsintro['str']." ".$vereinsintro['hausnr']; ?>
        </p>
    </div>
    <hr class="clear" />
</div>       



