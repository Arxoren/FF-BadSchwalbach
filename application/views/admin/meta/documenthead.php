<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE HTML>
<html lang="de-de">
    
<head>
    <meta http-equiv="content-type" content="text/html, charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="X-UA-Compatible" content="edge" />
    <title>easyClick 2.0</title>
    
    <!--
    <link rel="stylesheet" type="text/css" href="css/styles.css" media="all" />
    -->

    <link rel="stylesheet/less" type="text/css" href="<?php echo base_url().'frontend/css/styles.less'; ?>" media="all" />
    <link rel="stylesheet/less" type="text/css" href="<?php echo base_url().'backend/css/adminstyles.less'; ?>" media="all" />

    <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.5.0/less.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo base_url().'backend/script/jquery-ui.min.js'; ?>"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo base_url().'backend/script/tinymce/tinymce.min.js'; ?>"></script>
    <!-- <script type="text/javascript" charset="utf-8" src="<?php //echo base_url().'backend/script/editor.js'; ?>"></script> -->

    <script type="text/javascript">
     
        $( init );
         
        function init() {
            
            $( "#content" ).sortable({
              handle: ".admin_layoutmodul_panel",
              placeholder: "admin_dropplaceholder",
              revert: true
            });

            $( ".datafacts" ).sortable({cancel: 'p'});
            $( ".teaser_row > ul" ).sortable();
            
        }
       
    </script>

</head>

<body> 