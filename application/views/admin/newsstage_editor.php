<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($content["stages"]["stageID"]=="") { $modus="new"; } else { $modus="edit"; } ?>

<div id="admin_contentbox">
    <form name="einsatzedit" id="admin_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="op" value="news_stagesave" />
        <input type="hidden" name="target" value="news_stageliste" />
        <input type="hidden" name="editID" value="<?php echo $content["stages"]["stageID"]; ?>" />

        <input type="hidden" name="media_type" value="image">
        <input type="hidden" name="folder" id="js_admin_uploadtarget" value="images_cms/stages_big" />


	<div id="admin_pageheadline" class="admin_pageheadline">


		<h1 class="admin">
            <?php echo $content['page_headline']; ?>
        </h1>
		<input type="button" class="admin_button" value="Speichern" id="js-send-form" /> 
		<hr class="clear" />

	</div>
    <div id="admin_pageheadline_placeholder" class="hide"></div>

	<div class="edit_form">
        <p>
            <label for="headline">Headline</lable>
            <input type="text" name="headline" value="<?php if($modus!="new") { echo $content["stages"]["headline"]; } ?>" />
        </p>
        <p>
            <label for="subline">Subline</lable>
            <input type="text" name="subline" value="<?php if($modus!="new") { echo $content["stages"]["subline"]; } ?>" />
        </p>
        <p>
            <label for="link">Link <span class="helptext">- (optional)</span></lable>
            <textarea name="link"><?php if($modus!="new") { echo $content["stages"]["link"]; } ?></textarea>
        </p>
    </div>
    <div class="edit_form formblock">
        <p>
            <?php 
                $check_s= $check_w="";
                if($modus!="new") {
                    if($content["stages"]["color"]=="black") { 
                        $check_s=" checked"; 
                    }
                    if($content["stages"]["color"]=="white") { 
                        $check_w=" checked"; 
                    }
                } else {
                    $check_w=" checked"; 
                }
                echo '<label for="color">Schriftfarbe</lable> <span class="helptext">- abängig von dem verwendeten Bild (i.d.R. weiß)</span><br/>';
                echo '<input type="radio" name="color" value="white" class="admin_tab" '.$check_w.'/> Weiß<br/>';
                echo '<input type="radio" name="color" value="black" class="admin_tab" '.$check_s.'/> Schwarz';
            ?>
        </p>
        <p>
            <?php 
                if($modus=="new") { 
                    $freeusecheck = " checked"; 
                } else {
                    if($content["stages"]["freeuse"]==1) {
                         $freeusecheck = " checked"; 
                    } else {
                         $freeusecheck = ""; 
                    }
                }
                echo'<input type="checkbox" name="freeuse" value="1" '.$freeusecheck.'>
                     <label for="freeuse">Für alle Wehren nutzbar <span class="helptext">- (empfohlen)</span></lable>';        
            ?>
        </p>
        <p>
            <label for="sort">Feuerwehr</lable>
            <select name="sort">                
                <option value="0">Alle Wehren</option>
                <?php
                if($content['stages']['wehrID']=="") {
                    $content['stages']['wehrID']=$_GET["sort"];
                }

                foreach($content['feuerwehren'] as $wehren) {    
                    if($wehren['wehrID']==$content['stages']['wehrID']) { 
                        $check = " selected"; 
                    } else { 
                        $check = ""; 
                    }
                    echo '<option value="'.$wehren['wehrID'].'"'.$check.'>FFW '.$wehren['ort'].'</option>';
                }
                ?>
            </select>
        </p>
        </p>
    </div>
    

    <div class="edit_form">
        
        <p class="admin_label">Bild hinzufügen <span class="helptext"></span></p>
        <?php 
        if($modus!="new") {
            if($content["stages"]["image"]!="") {
                echo '<p>
                        <img src="'.base_url().'frontend/images_cms/stages_big/'.$content["stages"]["image"].'" />
                    </p>';
            } else {
                echo '<p><span class="helptext">Es ist noch kein Bild hinterlegt</span></p>';
            }
        } else {
            echo '<p><span class="helptext">Laden Sie ein Bild hoch</span></p>';
        }
        ?>
        <div class="js_adminimageupload_box admin_image_upload">
            <div class="admin_uploadcontainer admin_uploadcontainer_1">
                <p><input type="file" name="media_file[]" class="js_media_file_1 js_meda_choosefile" data-uploadnumber="1" /></p>
                <p class="imagePreview_container">
                    <div class="admin_imagePreview" id="js_adminimage_preview_1" data-uploadnumber="1">
                        <div class="admin_upload_advice"><strong>hier klicken</strong><br>um ein Bild hoch zu laden</div>
                     </div>
                </p>
            </div>
        </div>

    </div>
    </form>

    <div id="admin_footer"></div>
</div>

