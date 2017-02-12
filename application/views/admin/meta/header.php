<?php 
if($_SERVER['HTTP_HOST']=='localhost') {    
    echo'<div id="admin_workingarea"><p>### Entwicklungsumgebung : '.$_SERVER['HTTP_HOST'].' ###</p></div>';
}
?>
<div id="admin_header">
    <div id="admin_logo">
        <a href="<?php echo base_url(); ?>admin/?op=dashboard"><img src="<?php echo base_url(); ?>/backend/images/easyclick_logo.png" /></a>
    </div>
    <div id="admin_mainnav">
        <ul>
            <li><a href="<?php echo base_url(); ?>" class="pagename" target="_blank"><?php echo $GLOBALS['project_domain']; ?></a></li>
        
            <?php
                
                $mainnavi = array("content", "media", "module", "config");
                $i=0;

                foreach($navigation as $mainmenu) {
                    echo '<li><a href="'.base_url().'admin/?op=function_dashboard&amp;sort='.$mainnavi[$i].'">'.$mainnavi[$i].'</a>';

                    if($mainmenu!="nonavi") { 
                        echo'<div class="flyout"><ul>';

                        foreach($mainmenu as $navitem) {    
                            echo'
                                <li>
                                    <a href="'.base_url().'admin/?op='.$navitem["var"].'">
                                    <div>
                                        <img src="'.base_url().'backend/images/'.$navitem["image"].'" />
                                        <br/>'.$navitem["linkname"].'
                                    </div>
                                    </a>
                                </li>
                            ';
                        }
                        echo'</ul></div>';
                    }
                    echo'</li>';
                    $i++;
                }
        ?>
        </ul>
    </div>
    <div id="admin_user">
        <a href="#" class="js_adminusersettings"><?php echo $_SESSION["username"]; ?></a>
    </div>
    <div class="adminusermenu hide">
        <p><a href="<?php echo base_url(); ?>admin/?op=adminuser_usersettings">Einstellungen</a></p>
        <p class="logout"><a href="<?php echo base_url(); ?>admin/?op=logout">Abmelden</a></p>
    </div>
</div>

<div id="admin_content">