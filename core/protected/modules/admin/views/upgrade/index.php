<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Web Store 3.0 Installation</title>
	<link rel="stylesheet" href="http://cdn.lightspeedretail.com/bootstrap/css/bootstrap.css">
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
		#stats { font-size: 0.7em; }
	</style>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="http://cdn.lightspeedretail.com/bootstrap/js/bootstrap.js"></script>
</head>

<body>

<div class="header-new">
	<div class="header-inner">
		<div class="logo"><img src="http://www.lightspeedretail.com/wp-content/themes/lightspeed/images/logo-mini.png"></div>
	</div>
	<div class="welcome">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Web Store Upgrade</div>
</div>
<div class="container">
	<h2>Updating Web Store</h2>
	<div class="hero-unit">

		<p id="quip">This should be quick.</p>

		<div class="progress progress-striped active">
			<div class="bar" id="progressbar" style="width: 0%;"></div>
		</div>
		<div id="stats">Downloading patch file</div>
	</div>


	<script language="javascript">
		var prunning=0;
		var pinttimer=0;
		var online=10;
		var total = 100;

		function startInstall(key) {
			document.getElementById('progressbar').style.width = "10%";
			pinttimer=self.setInterval(function(){runUpgrade(key)},50);
			runUpgrade(key);
		}


		function runUpgrade(key)
		{

			if (prunning>2400)
			{
				clearInterval(pinttimer);
				prunning=0;
				alert("The install process has become unresponsive. This may indicate a problem with the database. Please contact technical support for additional information. Error information may be available in the xlsws_log table of your database for troubleshooting purposes.");
				document.getElementById('progressbar').style.width = 0;
				document.getElementById('stats').innerHTML = "Check xlsws_log for error information.";
				document.getElementById('quip').innerHTML = "Error, install halted.";

			}
			if (prunning>0) { prunning++; return; }
			prunning=1;
			var postvar = "online="+ online + "&total=" + total + "&patch=" + "<?php echo $patch ?>";

			var exporturl = window.location.href.replace("upgrade/index", "upgrade/upgrayedd");

			$.post(exporturl, postvar, function(data){
				if (data[0]=="{")
				{
					obj = JSON.parse(data);
					if (obj.result=='success')
					{
						total = obj.total;
						online = obj.makeline;
						var perc = online;
						document.getElementById('progressbar').style.width = perc + "%";
						if (obj.tag)
							document.getElementById('stats').innerHTML = obj.tag;
						else document.getElementById('stats').innerHTML = "";

						if (online==obj.total) {
							clearInterval(pinttimer);
							window.location.href = window.location.href.replace("upgrade/index", "default/releasenotes");
						}else {
							prunning=0;
						}

					}
					else {
						clearInterval(pinttimer);
						document.getElementById('stats').innerHTML = obj.tag;
						document.getElementById('progressbar').style.width=0;
						alert(obj.result);
					}
				}
				else {
					clearInterval(pinttimer);
					document.getElementById('stats').innerHTML = obj.tag;
					document.getElementById('progressbar').style.width=0;
					alert(data);
				}
			});

		}

		startInstall();

	</script>

</body>
</html>