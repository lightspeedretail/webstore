<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Web Store 3.0 Installation</title>
	<link rel="stylesheet" href="http://cdn.lightspeedretail.com/bootstrap/css/bootstrap.css">
	<?php $this->registerAsset('css/admin.css'); ?>
	<style type="text/css">
		.header-new {
			background: url("http://www.lightspeedretail.com/wp-content/themes/lightspeed/images/bg-header.jpg") repeat scroll 0 0 transparent;
			height: 80px;
			position: relative;
			width: 100%;
			z-index: 999;
		}
		.header-inner {margin: 0 auto; width: 960px; }
		.header-new .logo { float: left; padding: 24px 0 0 10px; }
		.header-new .welcome { float: left; padding: 30px 20px 20px 10px; margin-left: 470px; font-size: 28px; }
		.table { width: 700px; margin: 0 auto; }
		.hero-unit { padding: 20px; }
		.hero-unit p { font-size: 0.9em; }
		body { padding-top: 0px;}
	</style>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="http://cdn.lightspeedretail.com/bootstrap/js/bootstrap.js"></script>

</head>

<body>

<div class="header-new">
	<div class="header-inner">
		<div class="logo"><img src="http://www.lightspeedretail.com/wp-content/themes/lightspeed/images/logo-mini.png"></div>
	</div>
	<div class="welcome">Web Store Configuration</div>
</div>
<div class="container">
	<?php echo $content; ?>

</body>
</html>