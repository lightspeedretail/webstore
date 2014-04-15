<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $this->pageTitle; ?></title>
	<link rel="stylesheet" href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css">
	<?php $this->registerAsset('css/admin.css'); ?>
	<style type="text/css">
		.container { width: 338px; }

		.header {
			background: url("http://www.lightspeedretail.com/wp-content/themes/lightspeed/images/ecommerce/tab-1.png");
			width: 338px;
			height: 50px;
		}

		.form {
			padding: 15px 0px 5px 35px;
			margin-top: 20px;
			/*border: 1px solid #0077b3;*/
			border-radius: 10px;
			background-color: #f9f9f9;
		}

		input[type="password"],
		input[type="text"] { width: 250px; height: 2em; }

		label { margin-top: 12px; font-size: 18px; font-weight: 300; padding-left: 3px; }

		.buttons { padding-top: 20px; }

		#admin-login,
		#admin-login:active,
		input[type="submit"]:active {
			color: #ffffff;
			font-weight: bold;
			padding: 10px 20px;
			background-color: rgb(87,146,255);
			border-radius: 8px;
			border: 0px;
			transition: all 0.5s ease 0s;
		}

		input[type="submit"]:hover,
		input[type="submit"]:focus,
		#admin-login:hover,
		#admin-login:focus {background-color: #000099;}

	</style>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="http://twitter.github.com/bootstrap/assets/js/bootstrap.js"></script>

</head>

<body>

<div class="container">
	<div class="header"></div>
		<?php echo $content; ?>

</body>
</html>