<div id="globalmessage">
    <?php
        
        $messages = explode("|", $GLOBALS["globalmessage"]);

        foreach($messages as $msg) {

            $var = explode(":", $msg);
            echo'<p class="'.$var[0].'">'.$var[1].'</p>';
        }

        $GLOBALS["globalmessage"] = "";

    ?>
</div>