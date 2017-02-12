<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Error</title>
<style type="text/css">

::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }

body {
		top: 0;
		left: 0;
		background-color: #bf2228;
		font-family: Arial, Helvetica, sans-serif;
		width: 100%;
		padding: 0 0 0 0;
		margin: 0 0 0 0;
		color: #FFF;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 24px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 12px 10px 12px 10px;
}

#container {
	margin: 10px;
	border: 1px solid #D0D0D0;
	box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 12px 15px 12px 15px;
}

	div {
		width: 100%;
		text-align: center;
	}
	.logo {
		margin: 100px 0 40px 0;	
	}
	ul {
		width: 250px;
		padding: 0;
		margin: 0 auto;
		list-style: none;
		text-align: center;
	}
	li {
		margin: 5px 0 0 0;
		padding: 10px 0;
		border-bottom: 1px solid #FFF;
		color: #999;	
	}
	a { 
		color: #FFF;
		text-decoration: none;
		font-size: 18px;
		font-weight: normal;
	}
	a:hover { 
		color: #FF0;
		text-decoration: underline;
	}
	span {
		background-color: #FF0;
		font-size: 10px;
		padding: 2px 4px;
		color: #9a2529;
		margin: 0 0 0 8px;
		text-transform: uppercase;
	}
	.soon { 
		background-color: #999;
		color: #FFF;
	}
</style>
</head>
<body>
	<div class="logo">
		<img src="<?php echo base_url().'frontend/images/logo.png'; ?>" width="350" />
	</div>
	<div>
		<h1><?php echo $heading; ?></h1>
		<p><?php echo $message; ?></p>
	</div>
</body>
</html>