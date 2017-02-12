<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($content["adminuser"]["userID"]=="") { $modus="new"; } else { $modus="edit"; } ?>

<div id="admin_contentbox">
    <form name="einsatzedit" id="admin_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="op" value="adminuser_save" />
        <input type="hidden" name="target" value="einsatz_showlist" />
        <input type="hidden" name="editID" value="<?php echo $content["adminuser"]["userID"]; ?>" />


	<div id="admin_pageheadline" class="admin_pageheadline">

		<h1 class="admin"><?php echo $content['page_headline']; ?></h1>
		<input type="button" class="admin_button" value="Speichern" id="js-send-form" /> 
		<hr class="clear" />

	</div>
    <div id="admin_pageheadline_placeholder" class="hide"></div>

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
    </form>

    <div id="admin_footer"></div>
</div>

