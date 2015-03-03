<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $this->pageTitle; ?></title>
	<?php
	$this->registerAsset('css/admin.css');
	// we prefix with baseUrl to handle instances where Web Store is installed in a sub folder
	$strHeaderImageUrl = Yii::app()->params['admin_assets'] . '/img/webstore-logo2x.png';
	?>
	<style type="text/css">
		.container { width: 338px; }

		.header {
			background-image: url('<?= $strHeaderImageUrl ?>');
			background-size: 338px 76px;
			background-repeat: no-repeat;
			display: block;
			width: 338px;
			height: 76px;
		}

		.form {
			padding: 15px 0px 5px 35px;
			margin-top: 20px;
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
			background-color: #3287cc;
			border-radius: 8px;
			border: 0px;
			transition: all 0.5s ease 0s;
		}

		input[type="submit"]:hover,
		input[type="submit"]:focus,
		#admin-login:hover,
		#admin-login:focus {background-color: #286ca3;}

	</style>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

</head>

<body>

<div class="container">
	<div class="header"></div>
		<?php echo $content; ?>

</body>
</html>