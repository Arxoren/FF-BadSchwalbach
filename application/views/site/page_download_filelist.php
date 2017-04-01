<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

       	<div class="row"> 
           	<div class="col-4 download">
                <ul data-download-id="download_<?php echo $moduleID; ?>">
                <?php
                    if(isset($downloadfiles) && $downloadfiles!="") {
                        foreach($downloadfiles as $file) {
                            echo'
                            <li>
                            	<a href="'.base_url().'frontend/files_cms/'.$file["folder"].''.$file["filename"].'" download>
                                <img src="'.base_url().'frontend/images/icons/icon_download.svg" />
                                <p class="name">'.$file["name"].'</p>
                                <p class="desc">'.$file["format"].' Dokument / '.$file["size"].'</p>
                            	</a>
                            </li>';
                        }
                    } else {
                         if($GLOBALS['editable_tag']!="") {
                            echo'
                            <li>
                                <img src="'.base_url().'frontend/images/icons/icon_download.svg" />
                                <p class="name">Bitte eine Datei einfügen</p>
                                </a>
                            </li>';
                        }
                    }
                ?>
                </ul>
            </div>
            <hr class="clear" />
        </div>
