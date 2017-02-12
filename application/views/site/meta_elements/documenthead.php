<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE HTML>
<html lang="de-de">
	
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Freiwillige Feuerwehren der Stadt Bad Schwalbach - <?php echo $meta["page_name"]; ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<meta http-equiv="X-UA-Compatible" content="edge" />
	
    <meta name="keywords" content="<?php echo $GLOBALS["seo_key_words"]; ?>" />
    <meta name="description" content="<?php echo $GLOBALS["seo_page_desc"]; ?>" />
    <meta name="publisher" content="<?php echo $GLOBALS["seo_publisher"]; ?>" />
    <meta name="copyright" content="<?php echo $GLOBALS["seo_copyright"]; ?>" />
    <meta name="audience" content="alle" />
    <meta name="expires" content="NEVER" />
    <meta name="language" content="de" />
    <meta name="page-type" content="<?php echo $GLOBALS["seo_page_types"]; ?>" />
    <meta name="robots" content="index,follow" />

    <meta property="og:title" content="<?php echo $opengraph_data["title"]; ?>" />
    <meta property="og:type" content="<?php echo $opengraph_data["type"]; ?>" />
    <meta property="og:description" content='<?php echo $opengraph_data["description"]; ?>' />
    <meta property="og:image" content="<?php echo $opengraph_data["image"]; ?>">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'frontend/css/styles.css'; ?>" media="all" />

   <?php
    /*
    <link rel="stylesheet/less" type="text/css" href="<?php echo base_url().'frontend/css/styles.less'; ?>" media="all" />
    <script src="<?php echo base_url(); ?>frontend/stage/less.min.js"></script>
    */
    ?>

	<!--[if lt IE 9]>
    	<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    	<script type="text/javascript" src="<?php echo base_url().'frontend/script/respond.js'; ?>"></script>
    <![endif]-->

	<script>
		// Picture element HTML5 shiv
		document.createElement( "picture" );
    </script>
    <?php    
        
        if($GLOBALS["header_assets"]=="load_charts") {
            echo '<script src="'.base_url().'frontend/script/chart/dist/Chart.bundle.js"></script>';
        }

    ?>
    <script type="text/javascript" src="<?php echo base_url().'frontend/stage/jquery-1.11.3.min.js' ?>"></script>

</head>