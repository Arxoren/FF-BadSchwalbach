<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row contact_cards">
	<ul>

<?php 
foreach($kontakt_adresse as $item) { ?>

	    <li>
	        <div>
	        <h2><?php echo $item['vorname']; ?> <?php echo $item['nachname']; ?></h2>
	        <h4><?php echo $item['aufgabe']; ?></h4>
	        <hr>
	        <p><?php echo $item['email']; ?></p>
	        <p>Telefon: <?php echo $item['telefon']; ?></p>
	        <p class="adresse"><?php echo $item['str']; ?><br><?php echo $item['plz']; ?> <?php echo $item['ort']; ?></p>
	    </li>

<?php } ?>

	</ul>
	<hr class="clear">
</div>

