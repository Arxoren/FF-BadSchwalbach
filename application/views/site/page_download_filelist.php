<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

       	<div class="row"> 
           	<div class="col-4 download">
				<ul>
                <?php
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
                ?>
                </ul>
            </div>
            <hr class="clear" />
        </div>
