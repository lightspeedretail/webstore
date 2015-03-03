<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Web Store Installation</title>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap.min.css">
	<?php $this->registerAsset('css/admin.css'); ?>
	<style type="text/css">
		.header-new {
			background: url("//www.lightspeedpos.com/wp-content/themes/lightspeed/images/bg-header.jpg") repeat scroll 0 0 transparent;
			height: 80px;
			position: relative;
			width: 100%;
			z-index: 999;
		}
		.header-inner {margin: 0 auto; width: 960px; }
		.header-new .logo { float: left; padding: 3px 0 0 20px; }
		.header-new .logo img { max-height: 75px; }
		.header-new .welcome { float: left; padding: 30px 20px 20px 10px; margin-left: 185px; font-size: 28px; }
		.table { width: 700px; margin: 0 auto; }
		.hero-unit { padding: 20px; }
		.hero-unit p { font-size: 0.9em; }
		body { padding-top: 0px;}
	</style>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>

</head>

<body>

<div class="header-new">
	<div class="header-inner">
		<div class="logo"><img src="//www.lightspeedpos.com/wp-content/themes/lightspeed/sharedmenu/imgs/logo-red-bl.png"></div>
	</div>
	<div class="welcome">Web Store Configuration</div>
</div>
<div class="container">
	<?php echo $content; ?>

</body>
</html>