<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($content["adminuser"]["userID"]=="") { $modus="new"; } else { $modus="edit"; } ?>

<div id="admin_contentbox">
    <form name="adminusersettings" id="admin_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="op" value="adminuser_usersettings_save" />
        <input type="hidden" name="target" value="adminuser_usersettings" />
        <input type="hidden" name="editID" value="<?php echo $content["adminuser"]["userID"]; ?>" />

	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['page_headline']; ?></h1>
		<input type="button" class="admin_button" value="Speichern" id="js-send-form" /> 
		<hr class="clear" />

	</div>
    <div id="admin_pageheadline_placeholder" class="hide"></div>

        <?php
            if($_SESSION["logincount"]==0) {
                echo '<div class="edit_form admin_pageadvice">
                <p>Willkommen '.$_SESSION["username"].'<br>Bevor du los legen kannst musst du einmal ein Passwort ändern.</p>
                </div>';
            }
        ?>

	<div class="edit_form">
        <p>
            <label for="title">Vorname</lable>
            <input type="text" name="vorname" value="<?php if($modus!="new") { echo $content["adminuser"]["vorname"]; } ?>" />
        </p>
        <p>
            <label for="stichwort">Nachname</lable>
            <input type="text" name="nachname" value="<?php if($modus!="new") { echo $content["adminuser"]["nachname"]; } ?>" />
        </p>
        <p>
            <label for="stichwort">E-Mail Adresse</lable>
            <input type="text" name="email" value="<?php if($modus!="new") { echo $content["adminuser"]["email"]; } ?>" />
        </p>
    </div>
    <div class="edit_form formblock">
        <p>
            <label for="stichwort">Altes Passwort</lable>
            <input type="password" name="password_old" value="" />
        </p>
        <p>
            <label for="stichwort">Neues Passwort</lable>
            <input type="password" name="password_new" value="" />
        </p>
    </div>
    </form>

    <div id="admin_footer"></div>
</div>

