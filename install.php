<?php
set_time_limit(300);
define('DBARRAY_NUM', MYSQL_NUM);define('DBARRAY_ASSOC', MYSQL_ASSOC);define('DBARRAY_BOTH', MYSQL_BOTH);if (!defined('DB_EXPLAIN')) { define('DB_EXPLAIN', false);}if (!defined('DB_QUERIES')) { define('DB_QUERIES', false);}


if (version_compare(PHP_VERSION, '5.3.0') < 0) {
	die('WebStore requires at least PHP version of 5.3 (5.4 is preferable). Sorry cannot continue installation.');
}


define ('__SUBDIRECTORY__', preg_replace('/\/?\w+\.php$/', '', $_SERVER['PHP_SELF']));
define ('__DOCROOT__', substr(dirname(__FILE__), 0, strlen(dirname(__FILE__)) - strlen(__SUBDIRECTORY__)));
define ('__VIRTUAL_DIRECTORY__', '');

//Installer can only run if the site hasn't been set up
if(file_exists("config/main.php") && isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != __SUBDIRECTORY__ . "/install.php?check")
{
	header("Location: index.php");
	exit;
}

if(isset($_POST['getpg']))
{
	if(file_exists("progress.txt"))
	{
		$c = file_get_contents("progress.txt");
		if(empty($c)) $c=1;
		echo json_encode(array('result'=>"success",'progress'=>$c));
	}
	else echo json_encode(array('result'=>"success",'progress'=>1));
	return;
}

if(isset($argv)) $arg = arguments($argv); else $arg=array();
if(count($arg))
{
	//We're running this install from the command line
	//usage: php install.php --dbhost=localhost --dbuser=root
	//   --dbpass=mypass --dbname=webstore --url=store.example.com
	//   --oldbname=webstorebak (optional)
	//WARNING: This method of install does not run the environment check
	//We cannot guarantee all libraries are installed
	//(This is because command line php may differ from Apache server php)

	if(isset($arg['help'])) showCommandLine();
	if(isset($arg['dbupdate']) && $arg['dbupdate']==1)
	{
		//This is command line for applying any database updates
		echo "\n**Applying latest database changes**\n\n";
		runYii('www.example.com','/install.php',49);
		die();
	}
	if(file_exists("config/main.php")) die("\nENTER 1 OR 2 FOR INSTRUCTIONS (ENTER 2 TO PAGE)\n\nENTER SEED NUMBER
INITIALIZING...\n\nYOU MUST DESTROY 17 KINGONS IN 30 STARDATES WITH 3 STARBASES\n\n-=--=--=--=--=--=--=--=-\n
    *\n                         STARDATE  2100\n                *     *  CONDITION GREEN\n            <*>          QUADRANT  5,2\n    *                    SECTOR    5,4\n                         ENERGY    3000\n                         SHIELDS   0\n                   *     PHOTON TORPEDOES 10\n-=--=--=--=--=--=--=--=-\nCOMMAND\n\nHey, ensign Wesley, Web Store is already installed!\n\n");


	$arrRequired = array('dbhost','dbuser','dbpass','dbname','url');
	foreach($arrRequired as $item)
		if(!isset($arg[$item]))
			showCommandLine();

	echo "\n**Installing Web Store**\n\n";

	if(isset($arg['dboldname']))
		$_POST['dboldname']=$arg['dboldname'];

	downloadLatest();
	zipAndFolders();
	writeDB($arg['dbhost'],$arg['dbuser'],$arg['dbpass'],$arg['dbname']);

	$arg=modifyArgs($arg);




	$db = createDbConnection();
	$sqlline = 1;
	$endline = 99999999;
	$tag="";
	$saveperc=0;
	while($sqlline<=$endline)
	{
		$retVal = runInstall($db,$sqlline);
		$j = json_decode($retVal);

		if($j->result=="success")
		{
			$sqlline = $j->line+1;
			$endline = $j->total;
			if(isset($j->tag) && $tag != $j->tag)
			{
				echo $j->tag."\n";
				$tag=$j->tag;
			}
			$perc = round($sqlline/$endline*50,0);
			if ($perc != $saveperc)
			{
				$saveperc = $perc;
				echo $perc."%\n";
			}

		} else {
			echo $j->result;
			die();
		}
	}

	if(isset($arg['hosted']))
		$db->query("update xlsws_configuration set key_value=1 where key_name='LIGHTSPEED_HOSTING'");

	echo "\nLaunching Yii bootstrap\n";
	runYii($arg['url'],$_SERVER['SCRIPT_NAME']);

}
else {
	//We're running this install from the browser

	//////////////////////////////////////////////////////////////////
	// Set up initial pathing so install can continue

	$step = 1;
	if (isset($_POST['step']))
		$step = preg_replace('/[^0-9]/', '', $_POST['step']);
	if (isset($_POST['sqlline'])) { $db = createDbConnection(); echo runInstall($db,preg_replace('/[^0-9]/', '', $_POST['sqlline'])); exit(); }
	switch ($step)
	{
		case 2:displayFormTwo(); break;
		case 3:displayForm(); break;
		case 1: default:
		$checkenv = xls_check_server_environment();
		if ((in_array("fail", $checkenv) && $_SERVER['REQUEST_URI'] != __SUBDIRECTORY__ . "/install.php?ignore")
			|| $_SERVER['REQUEST_URI'] == __SUBDIRECTORY__ . "/install.php?check"
		) {
			displayNotAcceptable($checkenv);
		} else {

			displayForm();
		}
		break;

	}
}

function showCommandLine()
{
	die("\n*error halting*\n\nusage: php install.php --dbhost=localhost --dbuser=root --dbpass=mypass --dbname=webstore --url=store.example.com --dboldname=webstorebak (optional) --hosted=1 (optional)\n\n");
}
function runYii($url,$scriptname,$sqlline=1)
{
	//This is the halfway point, we have to switch to the Yii framework now, so let's bootstrap it
	$yii=dirname(__FILE__).'/core/framework/yii.php';
	$config=dirname(__FILE__).'/config/main.php';
	require_once($yii);
	$objYii = Yii::createWebApplication($config);

	Configuration::exportConfig();
	Configuration::exportLogging();

	//Since we're in this same instance, reread the variables we just wrote
	//Because we've updated the config, rerun
	Yii::app()->theme='brooklyn';
	Yii::app()->language='en';
	$objConfig = Configuration::model()->findAllByAttributes(array('param'=>'1'),array('order'=>'key_name'));
	foreach ($objConfig as $oConfig)
		Yii::app()->params[$oConfig->key_name]=str_replace('\'','\\\'',$oConfig->key_value);
	Yii::app()->params['OFFLINE']=0;
	Yii::app()->params['INSTALLED']=1;


	$_SERVER=array(
		'REQUEST_URI'=>'/index.php',
		'SERVER_NAME'=>$url,
		'SCRIPT_NAME'=>$scriptname,
		'PHP_SELF'=>$scriptname,
		'HTTP_USER_AGENT'=>'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/536.30.1 (KHTML, like Gecko)',
		'HTTP_HOST'=>$url,
		'QUERY_STRING'=>'',

	);
	$_SESSION['DUMMY']="nothing"; //force creation of session just in case


	do
	{
		$_POST['online']=$sqlline;
		ob_start();
		$objYii->runController("install/upgrade");
		$retVal = ob_get_contents();

		$j = json_decode($retVal);

		if(isset($j->result) && $j->result=="success")
		{
			ob_clean();
			$sqlline = $j->makeline;
			$endline = $j->total;
			if(isset($j->tag))
				echo $j->tag." ";
			echo (50+$sqlline)."%\n";
		} else die();

		ob_end_flush();

	} while ($sqlline<50);

	if($url!= "www.example.com") //IOW only command line db updates
		echo "\n** finished **\n\nCustomer needs to go to http://".$url."/admin/license to complete installation.\n\n";

}
function xls_check_file_signatures($complete = false)
{
	$url = "http://updater.lightspeedretail.com";
	//$url = "http://www.lsvercheck.site";

	$url .= "/webstore/hash";

	$json = json_encode(array('version'=> _ws_version()));

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER,
		array("Content-type: application/json"));
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);


	$strXml = curl_exec($ch);
	curl_close($ch);
	$oXML = new SimpleXMLElement($strXml);

	$signatures = $oXML->signatures;
	$versions = explode(",",$oXML->versions);

	$checked = array();
	$checked['<b>--File Signatures Check for ' . _ws_version() . '--</b>'] = "pass";


	$fn = unserialize($signatures);
	if (!isset($signatures)) {
		$checked['Signature File in /core/protected/modules/admin'] = "fail";
	}

	foreach ($fn as $key => $value) {

		if (!file_exists($key)) {
			$checked[$key] = "MISSING";
		} else {
			$hashes = explode(",", $value);
			$hashfile = md5_file($key);
			if (!in_array($hashfile, $hashes)) {
				$checked[$key] = "modified";
			} elseif (_ws_version() != $versions[array_search($hashfile, $hashes)] || $complete) {
				$checked[$key] = $versions[array_search($hashfile, $hashes)];
			}
		}


	}
	return $checked;
}

function makeHtaccess()
{
	//Update Rewrite Base in htaccess
	$origText = "RewriteBase /";
	$replText = "RewriteBase ".$_SERVER['SCRIPT_NAME'];
	$replText = str_replace("/install.php", "", $replText);
	if ($replText=="RewriteBase ") $replText="RewriteBase /";
	$strFileContents = file_get_contents('htaccess');
	@$strFileContents2 = file_get_contents('.htaccess');
	if ($strFileContents2 && $strFileContents2 ==$replText )
	{
		//our .htaccess is fine
	} elseif ($strFileContents) {
		$fp = @fopen('.htaccess', 'w');

		if ($fp) {
			$str = str_replace($origText, $replText, $strFileContents);
			fwrite($fp, $str, strlen($str));
			fclose($fp);
		} else die("cannot create/update your .htaccess file. Try renaming/removing the existing one and running install again.");
	}


	//Write robots.txt too
	$strFileContents = "User-agent: *
Disallow:

Sitemap: http://www.example.com/store/sitemap.xml
";
	$replText = $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] . "/sitemap.xml";
	$replText = str_replace("/install.php", "", $replText);

	$fp = @fopen('robots.txt', 'w');

	if ($fp) {
		$str = str_replace('http://www.example.com/store/sitemap.xml', $replText, $strFileContents);
		fwrite($fp, $str, strlen($str));
		fclose($fp);
	} else die("cannot create/update your robots.txt file. Try renaming/removing the existing one and running install again.");


}

function  _ws_version()
{
	if(file_exists("core/protected/config/wsver.php"))
	{
		include_once("core/protected/config/wsver.php");

		return XLSWS_VERSION;
	}
	else return 3;
}

function displayForm()
{
	displayHeader();
	$dbhost = "localhost";
	$dbport = "3306";
	$dbuser = ini_get('mysql.default_user');
	$dbpass = "";
	$dbname = "";

	if (file_exists("config/wsdb.php"))
	{
		$arrSql = require("config/wsdb.php");
		if(isset($arrSql['username'])) $dbuser = $arrSql['username'];
		if(isset($arrSql['password'])) $dbpass = $arrSql['password'];
		if(isset($arrSql['connectionString']))
		{
			$connstring = $arrSql['connectionString'];
			$arrC = array();
			preg_match('/host=(.*);/', $connstring,$arrC);
			$dbhost = $arrC[1];
			preg_match('/dbname=(.*)/',$connstring,$arrC);
			$dbname = $arrC[1];
		}

	}


	?>



	<h2>Welcome!</h2>
	<div class="hero-unit">
		<p>This process will install the latest Web Store, optionally importing your old 2.x information. This initial page will set up the database for you, and then you will be redirected for additional setup steps. We've made install as simple as possible to get you going on your new eCommerce store!</p>
		<p><strong>Warning: Do not close this browser window until your setup has completed. Doing so will cause an incomplete install and you will have to begin again.</strong></p>
	</div>

	<h2>Install</h2>
	<label>Enter your database connection information below. <strong>Note: This database must already exist and be blank.</strong></label>

	<!-- Search form with input field and button -->
	<form id="installform" action="install.php?<?php if(isset($_GET['debug'])) echo "&debug";if(isset($_GET['qa'])) echo "&qa"; ?>" method="POST" class="well form-search">
		<table class="table table-striped">
			<tr>
				<td nowrap>MySQL Database Host (Server name or IP):</td>
				<td><input id="dbhost" name="dbhost" value="<?php echo $dbhost; ?>" type="text" class="input-medium"></td>
			</tr>
			<tr>
				<td nowrap>MySQL Port:</td>
				<td><input id="dbport" name="dbport" value="<?php echo $dbport; ?>" type="text" class="input-medium"></td>
			</tr>
			<tr>
				<td nowrap>MySQL Username:</td>
				<td><input id="dbuser" name="dbuser" value="<?php echo $dbuser; ?>" type="text" class="input-medium"></td>
			</tr>
			<tr>
				<td nowrap>MySQL Password:</td>
				<td><input id="dbpass" name="dbpass" value="<?php echo $dbpass; ?>" type="password" class="input-medium"></td>
			</tr>
			<tr>
				<td nowrap>Database Name:</td>
				<td><input id="dbname" name="dbname" value="<?php echo $dbname; ?>" type="text" class="input-medium"></td>

			</tr>
		</table>

		<input type="hidden" name="step" value="2">
		<p><strong>If you are upgrading, enter your old database name here. Because this installer copies your database, you cannot use the same database name if upgrading. If your original database was named "webstore" and you wish to keep that name, you will need to rename your existing database to something like "webstoreold" first, and then create a new blank "webstore" database.</strong></p>

		<table class="table table-striped">
			<tr>
				<td nowrap>Web Store 2.x Database Name:</td>
				<td><input name="dboldname" value="" type="text" class="input-medium"></td>
			</tr>
		</table>

		<button type="submit" class="btn btn-primary pull-right">Install</button>
		<P>&nbsp;</P>
	</form>

	</div>

	<?php
	displayFooter();
}


function displayNotAcceptable($checkenv)
{
	displayHeader();

	$warning_text = "<table class='table table-striped'>";
	if (stripos($_SERVER['REQUEST_URI'],"install.php?check") !== false) {
		?><h2>System Check</h2><?php
		$warning_text .= "<tr><td colspan='2'><b>SYSTEM CHECK for " . _ws_version() . "</b></td></tr>";
		$warning_text .= "<tr><td colspan='2'>The chart below shows the results of the system check and if upgrades have been performed.</td></td>";

		//For 2.1.x upgrade, have the upgrades been run?
		if (stripos($_SERVER['REQUEST_URI'],"install.php?check") !== false) {
			//$checkenv = array_merge($checkenv, xls_check_upgrades());
			$checkenv = array_merge($checkenv, xls_check_file_signatures());
		}

	} else {
		?>
		<h2>Error</h2>
		<div class="hero-unit">
			<p>Oops, we've detected a problem with your environment that will conflict with Web Store. The environment check results are shown below. Anything that has failed will need to be addressed, then just refresh this page.</p>
		</div>
		<?php
		$warning_text .= "<tr><td colspan='2'><b>CANNOT INSTALL</b></td></tr>";
		$warning_text .= "<tr><td colspan='2'>There are issues with your PHP environment which need to be fixed before you can install WebStore. Please check the chart below for required changes to your PHP installation which must be changed, and subdirectories which you need to make writeable. (Making php.ini changes on a web hosting service will vary by company. Please consult their technical support for exact instructions.)</td></td>";
	}
	$warning_text .= "<tr><td colspan='2'><hr></td></tr>";
	$curver = _ws_version();
	foreach ($checkenv as $key => $value) {
		$warning_text
			.= "<tr><td>$key</td><td>" . (($value == "pass" || $value == $curver) ? "$value"
				: "<font color='#cc0000'><b>$value</b></font>") . "</td>";
	}


	$warning_text .= "</table>";
	?>




	<div>
		<?php echo $warning_text; ?>
	</div>
	<p>&nbsp;</p>
	<?php
	displayFooter();
}
function displayFormTwo()
{
	writeDB($_POST['dbhost'],$_POST['dbuser'],$_POST['dbpass'],$_POST['dbname']);




	displayHeader();


	if (strlen($_POST['dboldname'])>0)
	{   $headerstring = "Installing and migrating..."; $quip = "You probably have time to get a coffee."; }
	else {  $headerstring= "Installing..."; $quip = "This shouldn't take too long."; }

	if (!isset($_POST['dboldname'])) $_POST['dboldname'] = "";
	?>

	<h2><?php echo $headerstring; ?></h2>
	<div class="hero-unit">

		<p id="quip"><?php echo $quip; ?></p>

		<div class="progress progress-striped active">
			<div class="bar" id="progressbar" style="width: 0%;"></div>
		</div>
		<div id="stats"></div>
	</div>


	<script language="javascript">
		var prunning=0;
		var pinttimer=0;
		var online=1;
		var total = 0;

		function startInstall(key) {
			document.getElementById('progressbar').style.width = "1%";
			pinttimer=self.setInterval(function(){runInstall(key)},50);
			runInstall(key);
		}
		function runInstall(key)
		{
			if (prunning==1)
			{
				if(online==2)
				{
					var postvar = "getpg=1";

					$.post("install.php", postvar, function(data)
					{
						if (data[0]=="{")
						{
							obj = JSON.parse(data);
							if (obj.result=='success' && obj.progress>1) {
								document.getElementById('progressbar').style.width = obj.progress + "%";
								document.getElementById('progressbar').style.backgroundColor = "#AA0000";
							}
						}
					});
				}
				return;
			}
			prunning=1;
			var postvar = "sqlline="+ online +
				"&dbname=" + "<?php echo $_POST['dbname'] ?>" +
				"&dboldname=" + "<?php echo $_POST['dboldname'] ?><?php if(isset($_GET['qa'])) echo "&qa=1"?><?php if(isset($_GET['debug'])) echo "&debug=1"?>";


			$.post("install.php", postvar, function(data){
				if (data[0]=="{")
				{
					obj = JSON.parse(data);
					if (obj.result=='success')
					{
						var perc = Math.round((100*(online/obj.total)));
						perc = perc/2;
						if(perc<1) perc=1;
						document.getElementById('progressbar').style.width = perc + "%";
						document.getElementById('progressbar').style.backgroundColor = "#149BDF";
						if (!obj.tag) obj.tag = "";
						document.getElementById('stats').innerHTML = obj.tag;
						<?php if(isset($_GET['debug'])): ?>
						document.getElementById('stats').innerHTML = obj.tag + " Running line "+online + " of " + obj.total + " (" + perc + "%)";
						<?php endif; ?>
						if (online==obj.total) {
							clearInterval(pinttimer);
							prunning=0;
							var exporturl = window.location.href.replace("/install.php", "/install/exportconfig");
							$.post(exporturl, "", function(data){  if (data[0]!="{") alert(data); });
							online = 1;
							pinttimer=self.setInterval(function(){runUpgrade(key)},50);
						}else {
							prunning=0;
							online = online + 1;
						}

					}
				}
				else {
					clearInterval(pinttimer);

					if(data.indexOf("Table 'xlsws_customer' already exists")>0)
						data = "Helpful information: This appears to be an error caused by installing into a database that is not blank. Web Store 3 requires a blank database to install.\n\n" + data;

					data = "An error has occured. If this does not appear to be an issue you can easily remedy based on the information below, please contact Web Store technical support for additional assistance.\n\n" + data;
					document.getElementById('progressbar').style.width = 0;
					document.getElementById('stats').innerHTML = "";
					document.getElementById('quip').innerHTML = "Error, install halted.";
					alert(data);
				}
			});

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
			var postvar = "online="+ online + "&total=" + total +
				"&dbname=" + "<?php echo $_POST['dbname'] ?>" +
				"&dboldname=" + "<?php echo $_POST['dboldname'] ?>";

			var exporturl = window.location.href.replace("/install.php", "/install/upgrade");
			$.post(exporturl, postvar, function(data){
				if (data[0]=="{")
				{
					obj = JSON.parse(data);
					if (obj.result=='success')
					{
						total = obj.total;
						online = obj.makeline;
						var perc = 50 + online;
						document.getElementById('progressbar').style.width = perc + "%";
						if (!obj.tag) obj.tag = "";
						document.getElementById('stats').innerHTML = obj.tag;
						<?php if(isset($_GET['debug'])): ?>
						document.getElementById('stats').innerHTML = obj.tag + " at " + " (" + perc + "%)";
						<?php endif; ?>
						if (online==obj.total) {
							clearInterval(pinttimer);
							window.location.href = window.location.href.replace("/install.php", "/admin/license");
						}else {
							prunning=0;
						}

					}
					else {
						clearInterval(pinttimer);
						alert(obj.result);
					}
				}
				else {
					clearInterval(pinttimer);
					alert(data);
				}
			});

		}

		startInstall();

	</script>

	<?php

	displayFooter();
}

function displayHeader()
{
	?>
	<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/html" lang="en-US">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Web Store Installation</title>
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
		<div class="welcome">Web Store Installation</div>
	</div>
	<div class="container">

<?php
}

function displayFooter()
{
	?>
	<script>
		$('#installform').submit(validate);
		function validate(){
			var dbhost = $('#dbhost').val();
			if (!$.trim(dbhost)) {alert('Database Host is required!'); return false; }
			var dbuser = $('#dbuser').val();
			if (!$.trim(dbuser)) {alert('Database Username is required!'); return false; }
			var dbpass = $('#dbpass').val();
			if (!$.trim(dbpass)) {alert('Database Password is required!'); return false; }
			var dbname = $('#dbname').val();
			if (!$.trim(dbname)) {alert('Database name is required!'); return false; }
		}
	</script>
	</body>
	</html>
<?php
}


function writeDB($dbhost,$dbuser,$dbpass,$dbname)
{
	if (strlen($dbhost)==0 || strlen($dbuser)==0 || strlen($dbname)==0 || strlen($dbpass)==0)
		return json_encode(array('result'=>"Database connection info missing."));

	$strtoexport = "<?php

return array(
			'connectionString' => 'mysql:host=".$dbhost.";dbname=".$dbname."',
			'emulatePrepare' => true,
			'username' => '".$dbuser."',
			'password' => '".$dbpass."',
			'charset' => 'utf8',
			'tablePrefix'=>'xlsws_',
			'schemaCachingDuration'=>30,

);";

	@mkdir("config",0755,true);
	$fp2 = fopen("config/wsdb.php","w");
	if ($fp2 === false) die("Error, can't write file config/wsdb.php");
	fwrite($fp2,$strtoexport);
	fclose($fp2);


	$strtoexport = "<?php

return array(


);";

	$fp2 = fopen("config/wsemail.php","w");
	fwrite($fp2,$strtoexport);
	fclose($fp2);

}


class DB_Class {
	var $db;
	var $olddb;
	var $newdb;
	var $schemaNumber = 0;

	public function __construct($servername, $dbuser, $dbpassword, $dbname) {
		$this->db = new mysqli($servername, $dbuser, $dbpassword);
		if (!$this->db)
		{ echo ("Unable to connect to Database Server. Invalid server, username or password."); die(); }
		$this->newdb = $dbname;

	}

	public function changedb($to)
	{
		if ($to=="old")
		{
			$blnSuccess =$this->db->select_db($this->olddb);
			if (!$blnSuccess)
			{ echo ("Cannot find or use \"".$this->olddb."\" database to upgrade."); die(); }


			$this->getSchema();
		}
		if ($to=="new")
		{
			$blnSuccess =$this->db->select_db($this->newdb);
			if (!$blnSuccess)
			{ echo ("Cannot find or use \"".$this->newdb."\" database. Make sure it has been created and is blank."); die(); }
			$this->getSchema();
		}

	}

	public function getSchema()
	{
		$res = $this->fetch("show tables");
		$tablenames= array();
		foreach ($res as $row=>$value)
			foreach ($value as $k=>$v)
				$tablenames[] = $v;
		if (in_array('xlsws_configuration',$tablenames))
		{
			$res = $this->fetch("SHOW COLUMNS FROM xlsws_configuration" );
			foreach ($res as $row) $fieldnames[] = $row['Field'];

			if(in_array('key',$fieldnames)) $kn = "key";
			if(in_array('key_name',$fieldnames)) $kn = "key_name";
			if(in_array('value',$fieldnames)) $vn = "value";
			if(in_array('key_value',$fieldnames)) $vn = "key_value";

			$res = $this->fetch("select `".$vn."` as id from xlsws_configuration where `".$kn."`='DATABASE_SCHEMA_VERSION'");

			if (isset($res[0]))
				$this->schemaNumber=$res[0]['id'];
			else $this->schemaNumber=0;
		} else $this->schemaNumber=0;


	}

	public function query($sql) {
		if(!empty($sql))
		{
			$result = $this->db->query($sql) or die("Invalid query: " . $this->db->error."\n\nwhen attemping to run ".$sql);
			return $result;
		}
		else return;
	}

	public function fetch($sql) {
		$data = array();
		$result = $this->query($sql);

		while ($row = $result->fetch_array()){
			$data[] = $row;
		}
		return $data;
	}

	public function add_index($table,$indexname) {
		$res = $this->fetch("SHOW INDEXES FROM $table WHERE key_name='$indexname'" );

		if($res) return false; //index already exists

		$this->query("ALTER TABLE `$table` ADD INDEX `$indexname` (`$indexname`)");
		return true;

	}

	public function add_column($table , $column , $create_sql , $version = false) {

		$res = $this->fetch("SHOW COLUMNS FROM $table WHERE Field='$column'" );

		if($res) return false;

		$this->query($create_sql);
		return true;
	}


	public function check_column_type($table , $column , $type , $misc ,$version = false){
		$res = $this->fetch("SHOW COLUMNS FROM $table WHERE Field='$column'" );

		if(!$res) return;

		$ctype = $res[0]['Type'];

		if($ctype != $type)
			$this->query("ALTER TABLE  `$table` CHANGE  `$column`  `$column` $type  $misc ;");

	}


	//title,key,value,helper,config,sort,options
	public function add_config_key($key,$title,$value,$helper,$config,$sort,$options = null, $template_specific=0, $param=1,$required=null)
	{

		$res = $this->fetch("SHOW COLUMNS FROM xlsws_configuration" );
		foreach ($res as $row) $fields[] = $row['Field'];

		if(in_array('key',$fields)) $kn = "key";
		if(in_array('key_name',$fields)) $kn = "key_name";
		if(in_array('value',$fields)) $vn = "value";
		if(in_array('key_value',$fields)) $vn = "key_value";

		$conf = $this->fetch("select * from xlsws_configuration where `".$kn."`='".$key."'");

		if(!$conf)
		{
			$res = $this->fetch("SHOW COLUMNS FROM xlsws_configuration" );
			foreach ($res as $row) $fieldnames[] = $row['Field'];

			$sql = "insert into xlsws_configuration set title='".mysqli_real_escape_string($this->db,$title)."' ";
			if(in_array('key',$fieldnames)) $sql .= ", `key`='$key' ";
			if(in_array('key_name',$fieldnames)) $sql .= ", `key_name`='$key' ";
			if(in_array('value',$fieldnames)) $sql .= ", `value`='$value' ";
			if(in_array('key_value',$fieldnames)) $sql .= ", `key_value`='$value' ";
			$sql .= ",helper_text='".mysqli_real_escape_string($this->db,$helper)."', `configuration_type_id`='$config', `sort_order`='$sort' ";
			if(!is_null($options)) $sql .= ", `options`='".$options."'";
			if(in_array('template_specific',$fieldnames)) $sql .= ", `template_specific`=".$template_specific;
			if(in_array('param',$fieldnames)) $sql .= ", `param`=".$param;
			if(in_array('required',$fieldnames) && !is_null($required)) $sql .= ", `required`=".$required;

			$sql .= ", `created`='".date("Y-m-d H:i:s")."'";

			$this->query($sql);
		}

	}

	public function add_table($table , $create_sql ,  $version = false){
		$res = $this->fetch("show tables");

		foreach ($res as $row=>$value)
			foreach ($value as $k=>$v)
				$fieldnames[] = $v;

		if(!in_array($table,$fieldnames)){
			$this->query($create_sql);

		}
	}


	public function update_row($table , $key_column , $key , $value_column , $value , $version = false){

		$sql = "UPDATE $table SET $value_column = $value WHERE $key_column =  $key ";
		$this->query($sql);
	}


}

function downloadFile($url)
{

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progressCallback');
	if(stripos($url,".zip") !== false)
		curl_setopt($ch, CURLOPT_NOPROGRESS, false); // needed to make progress function work
	else
		curl_setopt($ch, CURLOPT_NOPROGRESS, true); // needed to make progress function work
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$resp = curl_exec($ch);
	curl_close($ch);
	return $resp;


}


function progressCallback( $download_size, $downloaded, $upload_size, $uploaded_size )
{
	if ($download_size == 0)
		$progress = 1;
	else
		$progress = round( ($downloaded / $download_size  * 100 ),0);

	file_put_contents("progress.txt",$progress);

}

function downloadLatest()
{
	if(isset($_POST['debug'])) error_log(__FUNCTION__,3,"error.txt");
	//if we've already downloaded and extracted, don't do it twice
	if (!file_exists('core') && !file_exists('webstore.zip'))
	{
		$dest = (isset($_POST['qa']) ? "qa" : "latestwebstore");
		$cdn = (isset($_POST['qa']) ? "webstore-qa" : "webstore-full");
		$jLatest= downloadFile("http://updater.lightspeedretail.com/site/".$dest);
		$result = json_decode($jLatest);
		$strWebstoreInstall = "http://cdn.lightspeedretail.com/webstore/".$cdn."/".$result->latest->filename;
		if(isset($_POST['debug'])) error_log("downloading $strWebstoreInstall",3,"error.txt");
		$data = downloadFile($strWebstoreInstall);
		if (stripos($data,"404 - Not Found")>0 || empty($data))
			echo("ERROR downloading ".$result->latest->filename." from LightSpeed");
		if(isset($_POST['debug'])) error_log("writing to to".$result->latest->filename,3,"error.txt");
		$f=file_put_contents("webstore.zip", $data);
		if(isset($_POST['debug'])) error_log("wrote to".$result->latest->filename,3,"error.txt");
		if ($f)
		{

			if(!isset($_POST['debug'])) @unlink("progress.txt");
		}
		else {
			echo("ERROR downloading ".$result->latest->filename." from LightSpeed");
		}
	}
}

function zipAndFolders()
{
	if(isset($_POST['debug'])) error_log(__FUNCTION__,3,"error.txt");
	//if we've already downloaded and extracted, don't do it twice
	if (!file_exists('core') && file_exists("webstore.zip"))
	{
		if(isset($_POST['debug'])) error_log("decompressing webstore.zip",3,"error.txt");
		decompress("webstore.zip");
		if(isset($_POST['debug'])) error_log("removing webstore.zip",3,"error.txt");
		if(!isset($_POST['debug'])) @unlink("webstore.zip");
	}

	//////////////////////////////////////////////////////////////////
	// Verify the cache folders exist and if not, create them
	// These may fail if cache isn't yet writable, so we ignore errors
	// and will get them again after fixing cache
	if (!file_exists('assets')) {
		@mkdir('assets');
	}
	if (!file_exists('runtime')) {
		@mkdir('runtime');
	}
	if (!file_exists('runtime/cache')) {
		@mkdir('runtime/cache');
	}
	if (!file_exists('themes')) {
		@mkdir('themes');
	}
}


/**
 * This is actually a copy of our zip.php from Web store from Florian
 * This violates our DRY principle but we need everything in the installer
 * @param string $zipFile
 * @param string $dirFromZip
 * @param null $zipDir
 * @return bool
 */
function decompress($zipFile = '', $dirFromZip = '', $zipDir=null)
{

	if (is_null($zipDir)) $zipDir = getcwd() . '/'; else $zipDir .=  '/';
	$zip = zip_open($zipDir.$zipFile);

	if (is_resource($zip))
	{
		while ($zip_entry = zip_read($zip))
		{
			$completePath = $zipDir . dirname(zip_entry_name($zip_entry));
			$completeName = $zipDir . zip_entry_name($zip_entry);

			//Zip Mac OS hidden folders
			if (stripos($completeName,"__MACOSX") === false && stripos($completePath,"__MACOSX") === false) {
				// Walk through path to create non existing directories
				// This won't apply to empty directories ! They are created further below
				if(!file_exists($completePath) && preg_match( '#^' . $dirFromZip .'.*#', dirname(zip_entry_name($zip_entry)) ) )
				{
					$tmp = '';
					foreach(explode('/',$completePath) AS $k)
					{
						$tmp .= $k.'/';
						if(!file_exists($tmp) )
						{
							@mkdir($tmp, 0777);
						}
					}
				}

				if (zip_entry_open($zip, $zip_entry, "r"))
				{
					if( preg_match( '#^' . $dirFromZip .'.*#', dirname(zip_entry_name($zip_entry)) ) )
					{
						if ($fd = @fopen($completeName, 'w+'))
						{
							fwrite($fd, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
							fclose($fd);
						}
						else
						{
							// We think this was an empty directory
							@mkdir($completeName, 0777);
						}
						zip_entry_close($zip_entry);
					}
				}
			}
		}
		zip_close($zip);
	}
	return true;

}

function createDbConnection()
{
	//Since we have our db information already saved in the Yii file, read it back here
	$dbinfo = require(dirname(__FILE__).'/config/wsdb.php');

	$dbuser = $dbinfo['username'];
	$dbpassword = $dbinfo['password'];


	$connstring = $dbinfo['connectionString'];
	$arrC = array();
	preg_match('/host=(.*);/', $connstring,$arrC);

	$servername = $arrC[1];

	preg_match('/dbname=(.*)/',$connstring,$arrC);
	$dbname = $arrC[1];

	$dboldname = isset($_POST['dboldname']) ? $_POST['dboldname'] : "";

	if (strlen($servername)==0 || strlen($dbuser)==0 || strlen($dbname)==0 || strlen($dbpassword)==0)
		return json_encode(array('result'=>"Database connection info missing."));


	$db = new DB_Class($servername, $dbuser, $dbpassword, $dbname);

	$db->olddb = $dboldname;
	$db->newdb = $dbname;

	return $db;
}

/* The master function which is called by Javascript. We upgrade line by line */
function runInstall($db,$sqlline = 0)
{
	if ($sqlline==0) return;

	if (strlen($db->olddb)>0)
		$upgrade=1; else $upgrade=0;


	//Depending on our scenario, gather SQL strings

	//The beginning of our line# process is for migrating

	if ($upgrade)
		$sqlstrings = initialMigrateTables().migrateTwoFiveToThree();
	else
		$sqlstrings = migrateTwoFiveToThree();
	$arrSql = explode(";",$sqlstrings);
	$total = count($arrSql)+13;

	switch ($sqlline)
	{

		case 1:

			$dest = (isset($_POST['qa']) ? "qa" : "latestwebstore");
			$cdn = (isset($_POST['qa']) ? "webstore-qa" : "webstore-full");
			$jLatest= downloadFile("http://updater.lightspeedretail.com/site/".$dest);
			$result = json_decode($jLatest);
			return json_encode(array('result'=>"success",
				'tag'=>'Downloading Web Store program file '.$result->latest->filename.'...','line'=>$sqlline,'total'=>$total,'upgrade'=>$upgrade));
			break;

		case 2:
			downloadLatest();
			return json_encode(array('result'=>"success",
				'tag'=>'Extracting Web Store files...','line'=>$sqlline,'total'=>$total,'upgrade'=>$upgrade));
			break;

		case 3:
			zipAndFolders();
			$tag = "Applying pre-3.0 changes. Line #".$sqlline;
			return json_encode(array('result'=>"success",
				'tag'=>$tag,'line'=>$sqlline,'total'=>$total,'upgrade'=>$upgrade));
			break;

		case 4:
			if ($upgrade) $db->changedb('old');
			if ($upgrade) if ($db->schemaNumber<217) up217($db);
			if ($upgrade) $db->changedb('old');
			if ($upgrade) if ($db->schemaNumber==217) up250($db,$sqlline);
			$tag = "Applying pre-3.0 changes. Line #".$sqlline;
			break;

		case 5:
		case 6:
		case 7:
		case 8:
		case 9:
		case 10:
		case 11:
			if ($upgrade) $db->changedb('old');
			if ($upgrade) if ($db->schemaNumber==217) up250($db,$sqlline);
			$tag = "Applying pre-3.0 changes. Line #".$sqlline;
			break;
		case 12:
			if ($upgrade) $db->changedb('old');
			if ($upgrade) if ($db->schemaNumber==250) up251($db);
			$tag = "Applying pre-3.0 changes. Line #".$sqlline;
			break;
		case 13:
			if ($upgrade) $db->changedb('old');
			if ($upgrade) if ($db->schemaNumber==251) up252($db);
			$tag = "Creating new tables. Line #".$sqlline;
			break;

		case 14:
			$db->changedb('new');
			initialCreateTables($db); //Create all tables at once
			if (!$upgrade)
				initialConfigLoad($db);
			break;

		default:
			$db->changedb('new');

			$sqlStringtoRun = trim($arrSql[$sqlline-15],"\n\r\t");
			$sqlStringtoRun = str_replace("{newdbname}",$db->newdb,$sqlStringtoRun);


			if(!($upgrade==0 && strpos($sqlStringtoRun,'{olddbname}') !== false))
			{
				$sqlStringtoRun = str_replace("{olddbname}",$db->olddb,$sqlStringtoRun);
				$db->query('SET NAMES utf8');
				$db->query('SET FOREIGN_KEY_CHECKS=0');
				$db->query($sqlStringtoRun);
				if ($sqlline<28) $tag = "Creating tables";
				if ($sqlline>=28 && $sqlline<=75) $tag = "Processing images table";

			}

			if ($sqlline==$total && !$upgrade)
			{
				initialDataLoad($db);
			}

			//Build our main.php in config so we can run the system
			if ($sqlline==$total)
			{
				makeHtaccess();
				installMainConfig();
				$tag = "Downloading Brooklyn template (this is the halfway mark, isn't this exciting?!)...";
			}

	}


	if (isset($tag))
		return json_encode(array('result'=>"success",'line'=>$sqlline,'tag'=>$tag,'total'=>$total,'upgrade'=>$upgrade));
	else return json_encode(array('result'=>"success",'line'=>$sqlline,'total'=>$total,'upgrade'=>$upgrade));

}

function installMainConfig()
{
	$configtext = file_get_contents("core/protected/config/_main.php");
	$fp2 = fopen("config/main.php","w");
	fwrite($fp2,$configtext);
	fclose($fp2);

	//shell which will be updated later
	$configtext = file_get_contents("core/protected/config/_wsfacebook.php");
	$fp2 = fopen("config/wsfacebook.php","w");
	fwrite($fp2,$configtext);
	fclose($fp2);


}


function createOldConfiguration()
{
	//If we're migrating, we need to copy the old config first, then run updates against it

	$db = createDbConnection();
	$db->query('SET NAMES utf8');
	$db->query('SET FOREIGN_KEY_CHECKS=0');

	$db->query("CREATE TABLE `xlsws_configuration` (
	  `rowid` bigint(20) NOT NULL AUTO_INCREMENT,
	  `title` varchar(64) NOT NULL,
	  `key` varchar(64) NOT NULL,
	  `value` mediumtext NULL,
	  `helper_text` varchar(255) NOT NULL,
	  `configuration_type_id` int(11) NOT NULL DEFAULT '0',
	  `sort_order` int(5) DEFAULT NULL,
	  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  `created` datetime DEFAULT NULL,
	  `options` varchar(255) DEFAULT NULL,
	  PRIMARY KEY (`rowid`),
	  UNIQUE KEY `key` (`key`),
	  KEY `configuration_type_id` (`configuration_type_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;");



}

function initialCreateTables($db)
{
	$sql = "CREATE TABLE `{newdbname}`.`xlsws_customer`
	  (
	     `rowid`                BIGINT(20) unsigned NOT NULL auto_increment,
	     `address1_1`           VARCHAR(255) DEFAULT NULL,
	     `address1_2`           VARCHAR(255) DEFAULT NULL,
	     `address2_1`           VARCHAR(255) DEFAULT NULL,
	     `address_2_2`          VARCHAR(255) DEFAULT NULL,
	     `city1`                VARCHAR(64) DEFAULT NULL,
	     `city2`                VARCHAR(64) DEFAULT NULL,
	     `company`              VARCHAR(255) DEFAULT NULL,
	     `country1`             VARCHAR(32) DEFAULT NULL,
	     `country2`             VARCHAR(32) DEFAULT NULL,
	     `currency`             VARCHAR(3) DEFAULT NULL,
	     `email`                VARCHAR(255) DEFAULT NULL,
	     `firstname`            VARCHAR(64) DEFAULT NULL,
	     `pricing_level`        INT(11) unsigned DEFAULT 1,
	     `homepage`             VARCHAR(255) DEFAULT NULL,
	     `id_customer`          VARCHAR(32) DEFAULT NULL,
	     `language`             VARCHAR(8) DEFAULT NULL,
	     `lastname`             VARCHAR(64) DEFAULT NULL,
	     `mainname`             VARCHAR(255) DEFAULT NULL,
	     `mainphone`            VARCHAR(32) DEFAULT NULL,
	     `mainephonetype`       VARCHAR(8) DEFAULT NULL,
	     `phone1`               VARCHAR(32) DEFAULT NULL,
	     `phonetype1`           VARCHAR(8) DEFAULT NULL,
	     `phone2`               VARCHAR(32) DEFAULT NULL,
	     `phonetype2`           VARCHAR(8) DEFAULT NULL,
	     `phone3`               VARCHAR(32) DEFAULT NULL,
	     `phonetype3`           VARCHAR(8) DEFAULT NULL,
	     `phone4`               VARCHAR(32) DEFAULT NULL,
	     `phonetype4`           VARCHAR(8) DEFAULT NULL,
	     `state1`               VARCHAR(32) DEFAULT NULL,
	     `state2`               VARCHAR(32) DEFAULT NULL,
	     `type`                 VARCHAR(1) DEFAULT NULL,
	     `user`                 VARCHAR(32) DEFAULT NULL,
	     `zip1`                 VARCHAR(16) DEFAULT NULL,
	     `zip2`                 VARCHAR(16) DEFAULT NULL,
	     `check_same`           INT(11) DEFAULT NULL,
	     `newsletter_subscribe` TINYINT(1) DEFAULT NULL,
	     `html_email`           TINYINT(1) DEFAULT '1',
	     `password`             VARCHAR(255) DEFAULT NULL,
	     `temp_password`        VARCHAR(255) DEFAULT NULL,
	     `allow_login`          TINYINT(1) DEFAULT NULL,
	     `created`              DATETIME NOT NULL,
	     `modified`             TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	     PRIMARY KEY (`rowid`),
	     KEY `email` (`email`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_cart`
	  (
	     `rowid`           BIGINT(20) unsigned NOT NULL auto_increment,
	     `id_str`          VARCHAR(64) DEFAULT NULL,
	     `address_bill`    VARCHAR(255) DEFAULT NULL,
	     `address_ship`    VARCHAR(255) DEFAULT NULL,
	     `ship_firstname`  VARCHAR(64) DEFAULT NULL,
	     `ship_lastname`   VARCHAR(64) DEFAULT NULL,
	     `ship_company`    VARCHAR(255) DEFAULT NULL,
	     `ship_address1`   VARCHAR(255) DEFAULT NULL,
	     `ship_address2`   VARCHAR(255) DEFAULT NULL,
	     `ship_city`       VARCHAR(64) DEFAULT NULL,
	     `ship_zip`        VARCHAR(10) DEFAULT NULL,
	     `ship_state`      VARCHAR(16) DEFAULT NULL,
	     `ship_country`    VARCHAR(16) DEFAULT NULL,
	     `ship_phone`      VARCHAR(32) DEFAULT NULL,
	     `zipcode`         VARCHAR(10) DEFAULT NULL,
	     `contact`         VARCHAR(255) DEFAULT NULL,
	     `discount`        DOUBLE DEFAULT NULL,
	     `firstname`       VARCHAR(64) DEFAULT NULL,
	     `lastname`        VARCHAR(64) DEFAULT NULL,
	     `company`         VARCHAR(255) DEFAULT NULL,
	     `name`            VARCHAR(255) DEFAULT NULL,
	     `phone`           VARCHAR(64) DEFAULT NULL,
	     `po`              VARCHAR(64) DEFAULT NULL,
	     `type`            MEDIUMINT(9) DEFAULT NULL,
	     `status`          VARCHAR(32) DEFAULT NULL,
	     `cost_total`      DOUBLE DEFAULT NULL,
	     `currency`        VARCHAR(3) DEFAULT NULL,
	     `currency_rate`   DOUBLE DEFAULT NULL,
	     `datetime_cre`    DATETIME DEFAULT NULL,
	     `datetime_due`    DATETIME DEFAULT NULL,
	     `datetime_posted` DATETIME DEFAULT NULL,
	     `email`           VARCHAR(255) DEFAULT NULL,
	     `sell_total`      DOUBLE DEFAULT NULL,
	     `printed_notes`   TEXT,
	     `shipping_method` VARCHAR(255) DEFAULT NULL,
	     `shipping_module` VARCHAR(64) DEFAULT NULL,
	     `shipping_data`   VARCHAR(255) DEFAULT NULL,
	     `shipping_cost`   DOUBLE DEFAULT NULL,
	     `shipping_sell`   DOUBLE DEFAULT NULL,
	     `payment_method`  VARCHAR(255) DEFAULT NULL,
	     `payment_module`  VARCHAR(64) DEFAULT NULL,
	     `payment_data`    VARCHAR(255) DEFAULT NULL,
	     `payment_amount`  DOUBLE DEFAULT NULL,
	     `tracking_number` VARCHAR(255) DEFAULT NULL,
	     `fk_tax_code_id`  INT(11) UNSIGNED,
	     `tax_inclusive`   TINYINT(1) DEFAULT NULL,
	     `subtotal`        DOUBLE DEFAULT NULL,
	     `tax1`            DOUBLE DEFAULT '0',
	     `tax2`            DOUBLE DEFAULT '0',
	     `tax3`            DOUBLE DEFAULT '0',
	     `tax4`            DOUBLE DEFAULT '0',
	     `tax5`            DOUBLE DEFAULT '0',
	     `total`           DOUBLE DEFAULT NULL,
	     `count`           INT(11) DEFAULT '0',
	     `downloaded`      TINYINT(1) DEFAULT '0',
	     `user`            VARCHAR(32) DEFAULT NULL,
	     `ip_host`         VARCHAR(255) DEFAULT NULL,
	     `customer_id`     BIGINT(20) unsigned DEFAULT NULL,
	     `gift_registry`   BIGINT(20) DEFAULT NULL,
	     `send_to`         VARCHAR(255) DEFAULT NULL,
	     `submitted`       DATETIME DEFAULT NULL,
	     `modified`        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	     `linkid`          VARCHAR(32) DEFAULT NULL,
	     `fk_promo_id`     INT(5) DEFAULT NULL,
	     PRIMARY KEY (`rowid`),
	     KEY `customer_id` (`customer_id`)
	  ) ENGINE=InnoDB DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_cart_item`
	  (
	     `rowid`              BIGINT(20) unsigned NOT NULL auto_increment,
	     `cart_id`            BIGINT(20) unsigned NOT NULL,
	     `cart_type`          INT(11) DEFAULT '1',
	     `product_id`         BIGINT(20) unsigned NOT NULL,
	     `code`               VARCHAR(255) NOT NULL,
	     `description`        VARCHAR(255) NOT NULL,
	     `discount`           VARCHAR(16) DEFAULT NULL,
	     `qty`                FLOAT NOT NULL,
	     `sell`               DOUBLE NOT NULL,
	     `sell_base`          DOUBLE NOT NULL,
	     `sell_discount`      DOUBLE NOT NULL,
	     `sell_total`         DOUBLE NOT NULL,
	     `serial_numbers`     VARCHAR(255) DEFAULT NULL,
	     `gift_registry_item` BIGINT(20) DEFAULT NULL,
	     `datetime_added`     DATETIME NOT NULL,
	     `datetime_mod`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	     PRIMARY KEY (`rowid`),
	     KEY `cart_id` (`cart_id`),
	     KEY `code` (`code`),
	     KEY `product_id` (`product_id`),
	     KEY `gift_registry_item` (`gift_registry_item`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_cart_messages`
	  (
	     `rowid`   INT(11) UNSIGNED NOT NULL auto_increment,
	     `cart_id` BIGINT(20) DEFAULT NULL,
	     `message` TEXT,
	     PRIMARY KEY (`rowid`),
	     KEY `cart_id` (`cart_id`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_category`
	  (
	     `rowid`            INT(11) NOT NULL auto_increment,
	     `name`             VARCHAR(64) DEFAULT NULL,
	     `parent`           INT(11) DEFAULT NULL,
	     `position`         INT(11) NOT NULL,
	     `child_count`      INT(11) DEFAULT '1',
	     `request_url`      VARCHAR(255) DEFAULT NULL,
	     `custom_page`      VARCHAR(64) DEFAULT NULL,
	     `image_id`         BIGINT(20) DEFAULT NULL,
	     `google_id`        INT(11) DEFAULT NULL,
	     `meta_keywords`    VARCHAR(255) DEFAULT NULL,
	     `meta_description` VARCHAR(255) DEFAULT NULL,
	     `created`          DATETIME DEFAULT NULL,
	     `modified`         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	     PRIMARY KEY (`rowid`),
	     KEY `name` (`name`),
	     KEY `parent` (`parent`),
	     KEY `request_url` (`request_url`)
	  )
	engine=innodb
	DEFAULT charset=utf8;


	CREATE TABLE `{newdbname}`.`xlsws_category_addl`
	  (
	     `rowid`    INT(11) NOT NULL auto_increment,
	     `name`     VARCHAR(64) DEFAULT NULL,
	     `parent`   INT(11) DEFAULT NULL,
	     `position` INT(11) NOT NULL,
	     `created`  DATETIME DEFAULT NULL,
	     `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	     PRIMARY KEY (`rowid`),
	     KEY `name` (`name`),
	     KEY `parent` (`parent`)
	  )
	engine=innodb
	DEFAULT charset=utf8;


	CREATE TABLE `{newdbname}`.`xlsws_configuration`
	  (
	     `rowid`                 BIGINT(20) NOT NULL auto_increment,
	     `title`                 VARCHAR(64) NOT NULL,
	     `key`                   VARCHAR(64) NOT NULL,
	     `value`                 MEDIUMTEXT NOT NULL,
	     `helper_text`           VARCHAR(255) NOT NULL,
	     `configuration_type_id` INT(11) NOT NULL DEFAULT '0',
	     `sort_order`            INT(5) DEFAULT NULL,
	     `modified`              TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	     `created`               DATETIME DEFAULT NULL,
	     `options`               VARCHAR(255) DEFAULT NULL,
	     `template_specific` tinyint(1) DEFAULT '0',
	     PRIMARY KEY (`rowid`),
	     UNIQUE KEY `key` (`key`),
	     KEY `configuration_type_id` (`configuration_type_id`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_country`
	  (
	     `rowid`             BIGINT(20) NOT NULL auto_increment,
	     `code`              CHAR(2) NOT NULL,
	     `code_a3`           CHAR(3),
	     `region`            CHAR(2) NOT NULL,
	     `avail`             CHAR(1) NOT NULL DEFAULT 'Y',
	     `sort_order`        INT(11) DEFAULT '10',
	     `country`           VARCHAR(255) NOT NULL,
	     `zip_validate_preg` VARCHAR(255) DEFAULT NULL,
	     PRIMARY KEY (`rowid`),
	     UNIQUE KEY `code` (`code`),
	     KEY `avail` (`avail`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_credit_card`
	  (
	     `rowid`      INT(11) NOT NULL auto_increment,
	     `name`       VARCHAR(32) NOT NULL,
	     `length`     VARCHAR(16) NOT NULL,
	     `prefix`     VARCHAR(64) NOT NULL,
	     `sort_order` INT(11) NOT NULL DEFAULT '0',
	     `enabled`    TINYINT(1) NOT NULL,
	     `validfunc`  VARCHAR(32) DEFAULT NULL,
	     `modified`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	     PRIMARY KEY (`rowid`),
	     UNIQUE KEY `name` (`name`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_custom_page`
	  (
	     `rowid`            BIGINT(20) NOT NULL auto_increment,
	     `key`              VARCHAR(32) NOT NULL,
	     `title`            VARCHAR(64) NOT NULL,
	     `page`             MEDIUMTEXT,
	     `request_url`      VARCHAR(255) DEFAULT NULL,
	     `meta_keywords`    VARCHAR(255) DEFAULT NULL,
	     `meta_description` VARCHAR(255) DEFAULT NULL,
	     `modified`         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	     `created`          DATETIME DEFAULT NULL,
	     `product_tag`      VARCHAR(255) DEFAULT NULL,
	     `tab_position`     INT(11) DEFAULT NULL,
	     PRIMARY KEY (`rowid`),
	     UNIQUE KEY `key` (`key`),
	     KEY `request_url` (`request_url`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_destination`
	  (
	     `rowid`       INT(11) NOT NULL auto_increment,
	     `country`     VARCHAR(5) DEFAULT NULL,
	     `state`       VARCHAR(5) DEFAULT NULL,
	     `zipcode1`    VARCHAR(10) DEFAULT NULL,
	     `zipcode2`    VARCHAR(10) DEFAULT NULL,
	     `taxcode`     INT(11) DEFAULT NULL,
	     `name`        VARCHAR(32) DEFAULT NULL,
	     `base_charge` FLOAT DEFAULT NULL,
	     `ship_free`   FLOAT DEFAULT NULL,
	     `ship_rate`   FLOAT DEFAULT NULL,
	     `modified`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	     PRIMARY KEY (`rowid`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_family`
	  (
	     `rowid`       INT(11) NOT NULL auto_increment,
	     `family`      VARCHAR(255) DEFAULT NULL,
	     `request_url` VARCHAR(255) DEFAULT NULL,
	     PRIMARY KEY (`rowid`),
	     UNIQUE KEY `family` (`family`),
	     KEY `request_url` (`request_url`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_gift_registry`
	  (
	     `rowid`                INT(11) NOT NULL auto_increment,
	     `registry_name`        VARCHAR(100) NOT NULL,
	     `registry_password`    VARCHAR(100) NOT NULL,
	     `registry_description` TEXT,
	     `event_date`           DATE NULL,
	     `html_content`         TEXT NOT NULL,
	     `ship_option`          VARCHAR(100) DEFAULT NULL,
	     `customer_id`          INT(11) NOT NULL,
	     `gift_code`            VARCHAR(100) NOT NULL,
	     `created`              DATETIME NOT NULL,
	     `modified`             TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	     PRIMARY KEY (`rowid`),
	     UNIQUE KEY `gift_code` (`gift_code`),
	     KEY `customer_id` (`customer_id`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_gift_registry_items`
	  (
	     `rowid`           INT(11) NOT NULL auto_increment,
	     `registry_id`     INT(11) NOT NULL,
	     `product_id`      BIGINT(20) unsigned NOT NULL,
	     `qty`             DOUBLE NOT NULL DEFAULT '1',
	     `registry_status` VARCHAR(50) DEFAULT '0',
	     `purchase_status` BIGINT(20) DEFAULT '0',
	     `purchased_by`    VARCHAR(100) DEFAULT NULL,
	     `created`         DATETIME NOT NULL,
	     `modified`        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	     PRIMARY KEY (`rowid`),
	     UNIQUE KEY `rowid` (`rowid`, `registry_id`),
	     KEY `registry_id` (`registry_id`),
	     KEY `product_id` (`product_id`)
	  )
	engine=innodb
	DEFAULT charset=utf8;


	CREATE TABLE `{newdbname}`.`xlsws_modules`
	  (
	     `rowid`         BIGINT(20) NOT NULL auto_increment,
	     `active`        INT(11) DEFAULT NULL,
	     `file`          VARCHAR(64) NOT NULL,
	     `type`          VARCHAR(255) NOT NULL,
	     `sort_order`    INT(5) DEFAULT NULL,
	     `configuration` MEDIUMTEXT,
	     `modified`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	     `created`       DATETIME DEFAULT NULL,
	     PRIMARY KEY (`rowid`),
	     UNIQUE KEY `file` (`file`, `type`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_product`
	  (
	     `rowid`                BIGINT(20) unsigned NOT NULL auto_increment,
	     `name`                 VARCHAR(255) NOT NULL,
	     `image_id`             BIGINT(20) DEFAULT NULL,
	     `class_name`           VARCHAR(32) DEFAULT NULL,
	     `code`                 VARCHAR(255) NOT NULL,
	     `current`              TINYINT(1) DEFAULT NULL,
	     `description`          MEDIUMTEXT,
	     `description_short`    MEDIUMTEXT,
	     `family`               VARCHAR(255) DEFAULT NULL,
	     `gift_card`            TINYINT(1) DEFAULT NULL,
	     `inventoried`          TINYINT(1) DEFAULT NULL,
	     `inventory`            FLOAT DEFAULT NULL,
	     `inventory_total`      FLOAT DEFAULT NULL,
	     `inventory_reserved`   FLOAT NOT NULL DEFAULT '0',
	     `inventory_avail`      FLOAT NOT NULL DEFAULT '0',
	     `master_model`         TINYINT(1) DEFAULT NULL,
	     `fk_product_master_id` BIGINT(20) DEFAULT '0',
	     `product_size`         VARCHAR(255) DEFAULT NULL,
	     `product_color`        VARCHAR(255) DEFAULT NULL,
	     `product_height`       FLOAT DEFAULT NULL,
	     `product_length`       FLOAT DEFAULT NULL,
	     `product_width`        FLOAT DEFAULT NULL,
	     `product_weight`       FLOAT DEFAULT '0',
	     `fk_tax_status_id`     BIGINT(20) DEFAULT '0',
	     `sell`                 FLOAT DEFAULT NULL,
	     `sell_tax_inclusive`   FLOAT DEFAULT NULL,
	     `sell_web`             FLOAT DEFAULT NULL,
	     `upc`                  VARCHAR(255) DEFAULT NULL,
	     `web`                  TINYINT(1) DEFAULT NULL,
	     `web_keyword1`         VARCHAR(255) DEFAULT NULL,
	     `web_keyword2`         VARCHAR(255) DEFAULT NULL,
	     `web_keyword3`         VARCHAR(255) DEFAULT NULL,
	     `request_url`          VARCHAR(255) DEFAULT NULL,
	     `meta_desc`            VARCHAR(255) DEFAULT NULL,
	     `meta_keyword`         VARCHAR(255) DEFAULT NULL,
	     `featured`             TINYINT(1) NOT NULL DEFAULT '0',
	     `created`              DATETIME DEFAULT NULL,
	     `modified`             TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	     PRIMARY KEY (`rowid`),
	     KEY `code` (`code`),
	     KEY `web` (`web`),
	     KEY `name` (`name`),
	     KEY `fk_product_master_id` (`fk_product_master_id`),
	     KEY `master_model` (`master_model`),
	     KEY `fk_tax_status_id` (`fk_tax_status_id`),
	     KEY `featured` (`featured`),
	     KEY `request_url` (`request_url`),
	     KEY `image_id` (`image_id`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_images`
	  (
	     `id`      BIGINT(20) unsigned NOT NULL auto_increment,
	     `image_path` VARCHAR(255) DEFAULT NULL,
	     `width`      MEDIUMINT(9) DEFAULT NULL,
	     `height`     MEDIUMINT(9) DEFAULT NULL,
	     `parent`     BIGINT(20) DEFAULT NULL,
	     `index` int(11) DEFAULT NULL,
  		 `product_id` bigint(20) unsigned DEFAULT NULL,
	     `created`    DATETIME NOT NULL,
	     `modified`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	     PRIMARY KEY (`id`),
	     UNIQUE KEY `width` (`width`, `height`, `parent`),
	     KEY `index` (`index`),
	     KEY `product_id` (`product_id`),
	     KEY `image_path` (`image_path`),
	     KEY `parent` (`parent`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_product_category_assn`
	  (
	     `product_id`  BIGINT(20) unsigned NOT NULL,
	     `category_id` INT(11) unsigned NOT NULL,
	     PRIMARY KEY (`product_id`, `category_id`)
	  )
	engine=innodb
	DEFAULT charset=utf8;


	CREATE TABLE `{newdbname}`.`xlsws_product_qty_pricing`
	  (
	     `rowid`         BIGINT(20) unsigned NOT NULL auto_increment,
	     `product_id`    BIGINT(20) unsigned NOT NULL,
	     `pricing_level` INT(11) DEFAULT NULL,
	     `qty`           FLOAT DEFAULT NULL,
	     `price`         FLOAT DEFAULT NULL,
	     PRIMARY KEY (`rowid`),
	     KEY `product_id` (`product_id`),
	     KEY `product_id_2` (`product_id`, `pricing_level`)
	  )
	engine=innodb
	DEFAULT charset=utf8;


	CREATE TABLE `{newdbname}`.`xlsws_product_related`
	  (
	     `rowid`      BIGINT(20) unsigned NOT NULL auto_increment,
	     `product_id` BIGINT(20) unsigned NOT NULL,
	     `related_id` BIGINT(20) unsigned NOT NULL,
	     `autoadd`    TINYINT(1) DEFAULT NULL,
	     `qty`        FLOAT DEFAULT NULL,
	     PRIMARY KEY (`rowid`),
	     UNIQUE KEY `product_id` (`product_id`, `related_id`),
	     KEY `product_id_2` (`product_id`),
	     KEY `related_id` (`related_id`)
	  )
	engine=innodb
	DEFAULT charset=utf8;


	CREATE TABLE `{newdbname}`.`xlsws_promo_code`
	  (
	     `rowid`         INT(11) NOT NULL auto_increment,
	     `enabled`       TINYINT(1) NOT NULL DEFAULT '1',
	     `except`        TINYINT(1) NOT NULL DEFAULT '0',
	     `code`          VARCHAR(255) DEFAULT NULL,
	     `type`          INT(11) DEFAULT '0',
	     `amount`        DOUBLE NOT NULL,
	     `valid_from`    DATE NULL,
	     `qty_remaining` INT(11) NOT NULL DEFAULT '-1',
	     `valid_until`   DATE NULL,
	     `lscodes`       LONGTEXT NULL,
	     `threshold`     DOUBLE NOT NULL  DEFAULT '0',
	     PRIMARY KEY (`rowid`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_shipping_tiers`
	  (
	     `rowid`       INT(11) NOT NULL auto_increment,
	     `start_price` DOUBLE DEFAULT '0',
	     `end_price`   DOUBLE DEFAULT '0',
	     `rate`        DOUBLE DEFAULT '0',
	     `class_name`  VARCHAR(255) DEFAULT NULL,
	     PRIMARY KEY (`rowid`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_sro`
	  (
	     `rowid`                INT(11) NOT NULL auto_increment,
	     `ls_id`                VARCHAR(20) DEFAULT NULL,
	     `customer_name`        VARCHAR(255) DEFAULT NULL,
	     `customer_email_phone` VARCHAR(255) NOT NULL,
	     `zipcode`              VARCHAR(10) DEFAULT NULL,
	     `problem_description`  MEDIUMTEXT,
	     `printed_notes`        MEDIUMTEXT,
	     `work_performed`       MEDIUMTEXT,
	     `additional_items`     MEDIUMTEXT,
	     `warranty`             MEDIUMTEXT,
	     `warranty_info`        MEDIUMTEXT,
	     `status`               VARCHAR(32) DEFAULT NULL,
	     `cart_id`              BIGINT(20) DEFAULT NULL,
	     `datetime_cre`         DATETIME DEFAULT NULL,
	     `datetime_mod`         TIMESTAMP NULL DEFAULT NULL,
	     PRIMARY KEY (`rowid`),
	     UNIQUE KEY `ls_id` (`ls_id`),
	     KEY `cart_id` (`cart_id`),
	     KEY `customer_email_phone` (`customer_email_phone`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_sro_repair`
	  (
	     `rowid`         INT(11) NOT NULL auto_increment,
	     `sro_id`        VARCHAR(20) DEFAULT NULL,
	     `family`        VARCHAR(255) DEFAULT NULL,
	     `description`   VARCHAR(255) DEFAULT NULL,
	     `purchase_date` VARCHAR(32) DEFAULT NULL,
	     `serial_number` VARCHAR(255) DEFAULT NULL,
	     `datetime_cre`  DATETIME DEFAULT NULL,
	     `datetime_mod`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	     PRIMARY KEY (`rowid`),
	     KEY `sro_id` (`sro_id`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_state` (
	  `rowid` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `country_code` char(2) NOT NULL,
	  `code` varchar(32) NOT NULL,
	  `avail` char(1) NOT NULL DEFAULT 'Y',
	  `sort_order` int(11) DEFAULT '10',
	  `state` varchar(255) NOT NULL,
	  PRIMARY KEY (`rowid`),
	  UNIQUE KEY `country_code` (`country_code`,`code`),
	  KEY `code` (`code`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


	CREATE TABLE `{newdbname}`.`xlsws_tax`
	  (
	     `rowid`      BIGINT(20) NOT NULL auto_increment,
	     `lsid`		   INT(11) unsigned NOT NULL,
	     `tax`        CHAR(32),
	     `max`        DOUBLE DEFAULT '0',
	     `compounded` TINYINT(1) DEFAULT '0',
	     PRIMARY KEY (`rowid`),
	     KEY `tax` (`tax`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_tax_code`
	  (
	     `rowid`      BIGINT(20) NOT NULL auto_increment,
	     `lsid`		   INT(11) unsigned NOT NULL,
	     `code`       CHAR(32) NOT NULL,
	     `list_order` INT(11) NOT NULL DEFAULT '0',
	     `tax1_rate`  DOUBLE NOT NULL DEFAULT '0',
	     `tax2_rate`  DOUBLE NOT NULL DEFAULT '0',
	     `tax3_rate`  DOUBLE NOT NULL DEFAULT '0',
	     `tax4_rate`  DOUBLE NOT NULL DEFAULT '0',
	     `tax5_rate`  DOUBLE NOT NULL DEFAULT '0',
	     PRIMARY KEY (`rowid`),
	     UNIQUE KEY `code` (`code`)
	  )
	engine=innodb
	DEFAULT charset=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_tax_status`
	  (
	     `rowid`       BIGINT(20) NOT NULL auto_increment,
	     `lsid`		   INT(11) unsigned NOT NULL,
	     `status`      CHAR(32) NOT NULL,
	     `tax1_status` TINYINT(1) NOT NULL DEFAULT '1',
	     `tax2_status` TINYINT(1) NOT NULL DEFAULT '1',
	     `tax3_status` TINYINT(1) NOT NULL DEFAULT '1',
	     `tax4_status` TINYINT(1) NOT NULL DEFAULT '1',
	     `tax5_status` TINYINT(1) NOT NULL DEFAULT '1',
	     PRIMARY KEY (`rowid`)
	  )
	engine=innodb
	DEFAULT charset=utf8;";

	$arrSql = explode(";",$sql);
	foreach($arrSql as $sqlline)
	{
		$sqlStringtoRun = trim($sqlline,"\n\r\t");
		$sqlStringtoRun = str_replace("{newdbname}",$db->newdb,$sqlStringtoRun);
		$db->query($sqlStringtoRun);
	}


}
function initialMigrateTables()
{
	return "INSERT INTO `{newdbname}`.`xlsws_customer`
	SELECT *
	FROM   `{olddbname}`.`xlsws_customer`;

	INSERT INTO `{newdbname}`.`xlsws_cart`
	SELECT *
	FROM   `{olddbname}`.`xlsws_cart`;


	INSERT INTO `{newdbname}`.`xlsws_cart_item`
	SELECT *
	FROM   `{olddbname}`.`xlsws_cart_item`;


	INSERT INTO `{newdbname}`.`xlsws_cart_messages`
	SELECT *
	FROM   `{olddbname}`.`xlsws_cart_messages`;


	INSERT INTO `{newdbname}`.`xlsws_category`
	SELECT *
	FROM   `{olddbname}`.`xlsws_category`;


	INSERT INTO `{newdbname}`.`xlsws_category_addl`
	SELECT *
	FROM   `{olddbname}`.`xlsws_category_addl`;

	INSERT INTO `{newdbname}`.`xlsws_configuration`
	SELECT *
	FROM   `{olddbname}`.`xlsws_configuration`;

	INSERT INTO `{newdbname}`.`xlsws_country`
	SELECT *
	FROM   `{olddbname}`.`xlsws_country`;

	INSERT INTO `{newdbname}`.`xlsws_credit_card`
	SELECT *
	FROM   `{olddbname}`.`xlsws_credit_card`;

	INSERT INTO `{newdbname}`.`xlsws_custom_page`
	SELECT *
	FROM   `{olddbname}`.`xlsws_custom_page`;

	INSERT INTO `{newdbname}`.`xlsws_destination`
	SELECT *
	FROM   `{olddbname}`.`xlsws_destination`;

	INSERT INTO `{newdbname}`.`xlsws_family`
	SELECT *
	FROM   `{olddbname}`.`xlsws_family`;

	INSERT INTO `{newdbname}`.`xlsws_gift_registry`
	SELECT *
	FROM   `{olddbname}`.`xlsws_gift_registry`;

	INSERT INTO `{newdbname}`.`xlsws_gift_registry_items`
	SELECT *
	FROM   `{olddbname}`.`xlsws_gift_registry_items`;

	INSERT INTO `{newdbname}`.`xlsws_modules`
	SELECT *
	FROM   `{olddbname}`.`xlsws_modules`;

	INSERT INTO `{newdbname}`.`xlsws_product`
	SELECT *
	FROM   `{olddbname}`.`xlsws_product`;

	INSERT INTO `{newdbname}`.`xlsws_product_category_assn`
	SELECT *
	FROM   `{olddbname}`.`xlsws_product_category_assn`;

	INSERT INTO `{newdbname}`.`xlsws_product_qty_pricing`
	SELECT *
	FROM   `{olddbname}`.`xlsws_product_qty_pricing`;

	INSERT INTO `{newdbname}`.`xlsws_product_related`
	SELECT *
	FROM   `{olddbname}`.`xlsws_product_related`;

	INSERT INTO `{newdbname}`.`xlsws_images` (id,image_path,width,height,parent,created,modified) SELECT rowid,image_path,width,height,parent,created,modified FROM `{olddbname}`.`xlsws_images`;

	update `{newdbname}`.`xlsws_images` as a left join `{newdbname}`.`xlsws_product` as b on b.image_id=a.id set a.product_id=b.rowid;

	update `{newdbname}`.`xlsws_images` as a left join `{newdbname}`.`xlsws_images` as b on b.id=a.parent set a.product_id=b.product_id where a.id<>a.parent;

	UPDATE `{newdbname}`.`xlsws_images` set `index`=0 where product_id IS NOT NULL;


	update `{newdbname}`.`xlsws_images` as a left join `{olddbname}`.`xlsws_product_image_assn` as b on b.image_id=a.id set a.product_id=b.product_id where a.product_id is null and b.product_id is not null;

	update `{newdbname}`.`xlsws_images` as a left join `{newdbname}`.`xlsws_images` as b on b.id=a.parent set a.product_id=b.product_id where a.id<>a.parent and a.product_id is null;

	update `{newdbname}`.`xlsws_images` set `index`=4 where `index` is null and image_path is not null and image_path like '%_3_add.%';
	update `{newdbname}`.`xlsws_images` set `index`=4 where `index` is null and image_path is not null and image_path like '%-add-3.%';
	update `{newdbname}`.`xlsws_images` set `index`=4 where `index` is null and image_path is not null and image_path like '%-add-3-%';
	update `{newdbname}`.`xlsws_images` set `index`=4 where `index` is null and image_path is not null and image_path like '%_2_add.%';
	update `{newdbname}`.`xlsws_images` set `index`=3 where `index` is null and image_path is not null and image_path like '%-add-2.%';
	update `{newdbname}`.`xlsws_images` set `index`=3 where `index` is null and image_path is not null and image_path like '%-add-2-%';
	update `{newdbname}`.`xlsws_images` set `index`=2 where `index` is null and image_path is not null and image_path like '%_1_add.%';
	update `{newdbname}`.`xlsws_images` set `index`=2 where `index` is null and image_path is not null and image_path like '%-add-1.%';
	update `{newdbname}`.`xlsws_images` set `index`=2 where `index` is null and image_path is not null and image_path like '%-add-1-%';
	update `{newdbname}`.`xlsws_images` set `index`=1 where `index` is null and image_path is not null and image_path like '%_add.%';
	update `{newdbname}`.`xlsws_images` set `index`=1 where `index` is null and image_path is not null and image_path like '%-add.%';
	update `{newdbname}`.`xlsws_images` set `index`=1 where `index` is null and image_path is not null and image_path like '%-add-%';




	INSERT INTO `{newdbname}`.`xlsws_promo_code`
	SELECT *
	FROM   `{olddbname}`.`xlsws_promo_code`;

	INSERT INTO `{newdbname}`.`xlsws_shipping_tiers`
	SELECT *
	FROM   `{olddbname}`.`xlsws_shipping_tiers`;

	INSERT INTO `{newdbname}`.`xlsws_sro`
	SELECT *
	FROM   `{olddbname}`.`xlsws_sro`;

	INSERT INTO `{newdbname}`.`xlsws_sro_repair`
	SELECT *
	FROM   `{olddbname}`.`xlsws_sro_repair`;

	INSERT INTO `{newdbname}`.`xlsws_state`
	SELECT *
	FROM   `{olddbname}`.`xlsws_state`;

	INSERT INTO `{newdbname}`.`xlsws_tax` (lsid,tax,`max`,compounded) SELECT rowid,`tax`,`max`,compounded FROM `{olddbname}`.`xlsws_tax`;


	INSERT INTO `{newdbname}`.`xlsws_tax_code` (lsid,code,list_order,tax1_rate,tax2_rate,tax3_rate,tax4_rate,tax5_rate) SELECT rowid,code,list_order,tax1_rate,tax2_rate,tax3_rate,tax4_rate,tax5_rate FROM `{olddbname}`.`xlsws_tax_code`;

	INSERT INTO `{newdbname}`.`xlsws_tax_status`
	(lsid,status,tax1_status,tax2_status,tax3_status,tax4_status,tax5_status)
	SELECT rowid,status,tax1_status,tax2_status,tax3_status,tax4_status,tax5_status FROM `{olddbname}`.`xlsws_tax_status`;";


}

function migrateTwoFiveToThree()
{

	return "alter table `{newdbname}`.xlsws_cart CHANGE `rowid` `id` BIGINT(20) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_cart_item CHANGE `rowid` `id` BIGINT(20) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_cart_messages CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_category CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_category CHANGE `parent` `parent` INT(11) unsigned NULL;
	alter table `{newdbname}`.xlsws_category_addl CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_configuration CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_country CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_credit_card CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_custom_page CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_customer CHANGE `rowid` `id` BIGINT(20) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_destination CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_family CHANGE `rowid` `id` BIGINT(20) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_gift_registry CHANGE `rowid` `id` BIGINT(20) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_gift_registry_items CHANGE `rowid` `id` BIGINT(20) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_modules CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_product CHANGE `rowid` `id` BIGINT(20) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_product_qty_pricing CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_product_related CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_promo_code CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_shipping_tiers CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_sro CHANGE `rowid` `id` BIGINT(20) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_sro_repair CHANGE `rowid` `id`  BIGINT(20) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_sro_repair CHANGE `sro_id` `sro_id`  BIGINT(20) unsigned NOT NULL;
	alter table `{newdbname}`.xlsws_state CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_tax CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_tax_code CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;
	alter table `{newdbname}`.xlsws_tax_status CHANGE `rowid` `id` INT(11) unsigned NOT NULL  AUTO_INCREMENT;

	ALTER TABLE `{newdbname}`.`xlsws_cart` CHANGE `name` `full_name` VARCHAR(255)  NULL  DEFAULT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_cart` CHANGE `type` `cart_type` MEDIUMINT(9)  NULL  DEFAULT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_cart` CHANGE `lastname` `last_name` VARCHAR(64)  NULL  DEFAULT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_cart` CHANGE `firstname` `first_name` VARCHAR(64)  NULL  DEFAULT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_cart` CHANGE `count` `item_count` INT(11)  NULL  DEFAULT '0';
	ALTER TABLE `{newdbname}`.`xlsws_cart` CHANGE `user` `lightspeed_user` VARCHAR(32)  NULL  DEFAULT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_category` CHANGE `name` `label` VARCHAR(64)  NULL  DEFAULT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_category` CHANGE `position` `menu_position` INT(11)  NOT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_category_addl` CHANGE `name` `label` VARCHAR(64)  NULL  DEFAULT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_category_addl` CHANGE `position` `menu_position` INT(11)  NOT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_configuration` CHANGE `key` `key_name` VARCHAR(64)  NOT NULL  DEFAULT '';
	ALTER TABLE `{newdbname}`.`xlsws_configuration` CHANGE `value` `key_value` MEDIUMTEXT  NOT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_credit_card` CHANGE `name` `label` VARCHAR(32)  NOT NULL  DEFAULT '';
	ALTER TABLE `{newdbname}`.`xlsws_credit_card` CHANGE `length` `numeric_length` VARCHAR(16)  NOT NULL  DEFAULT '';
	ALTER TABLE `{newdbname}`.`xlsws_custom_page` CHANGE `key` `page_key` VARCHAR(32)  NOT NULL  DEFAULT '';
	ALTER TABLE `{newdbname}`.`xlsws_customer` CHANGE `language` `preferred_language` VARCHAR(8)  NULL  DEFAULT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_customer` CHANGE `type` `record_type` INT(11)  NULL  DEFAULT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_customer` CHANGE `user` `lightspeed_user` VARCHAR(32)  NULL  DEFAULT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_customer` ADD `facebook` BIGINT(20) unsigned NULL  DEFAULT NULL  AFTER `lightspeed_user`;
	ALTER TABLE `{newdbname}`.`xlsws_destination` CHANGE `name` `label` VARCHAR(32)  NULL  DEFAULT NULL;


	ALTER TABLE `{newdbname}`.`xlsws_modules` CHANGE `type` `category` VARCHAR(255)  NOT NULL  DEFAULT '';
	ALTER TABLE `{newdbname}`.`xlsws_modules` CHANGE `file` `module` VARCHAR(64)  NOT NULL  DEFAULT '';
	ALTER TABLE `{newdbname}`.`xlsws_product` CHANGE `description` `description_long` MEDIUMTEXT  NULL;
	ALTER TABLE `{newdbname}`.`xlsws_product` CHANGE `name` `title` VARCHAR(255)  NOT NULL  DEFAULT '';
	ALTER TABLE `{newdbname}`.`xlsws_product` CHANGE `fk_product_master_id` `parent` BIGINT(20) unsigned NULL;
	ALTER TABLE `{newdbname}`.`xlsws_product` CHANGE `image_id` `image_id` BIGINT(20) unsigned NULL;
	ALTER TABLE `{newdbname}`.`xlsws_promo_code` CHANGE `except` `exception` TINYINT(1)  NOT NULL  DEFAULT '0';
	ALTER TABLE `{newdbname}`.`xlsws_tax` CHANGE `max` `max_tax` DOUBLE  NULL  DEFAULT '0';

	ALTER TABLE `{newdbname}`.`xlsws_state` ADD `country_id` INT  UNSIGNED  NULL  DEFAULT NULL  AFTER `id`;

	DROP TABLE IF EXISTS `{newdbname}`.`xlsws_log`;

	create table `{newdbname}`.`xlsws_log`
	(
	  id       INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
		level    VARCHAR(128),
		category VARCHAR(128),
		logtime  INTEGER,
		message  TEXT,
		created TIMESTAMP,
		KEY `createdidx` (`created`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_sessions` (
	  `id` char(32) NOT NULL,
	  `expire` int(11) DEFAULT NULL,
	  `created` TIMESTAMP ,
	  `modified` TIMESTAMP,
	  `data` blob,
	  PRIMARY KEY (`id`),
	  KEY `yiisession_expire_idx` (`expire`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE  `{newdbname}`.`xlsws_transaction_log` (
	  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `cart_id` bigint(20) unsigned DEFAULT NULL,
	  `logline` varchar(255) DEFAULT NULL,
	  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  PRIMARY KEY (`id`),
	  KEY `fk_cart` (`cart_id`),
	  CONSTRAINT `fk_cart` FOREIGN KEY (`cart_id`) REFERENCES `xlsws_cart` (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;


	CREATE TABLE `{newdbname}`.`xlsws_sro_item` (
	  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `sro_id` bigint(20) unsigned NOT NULL,
	  `cart_type` int(11) DEFAULT '1',
	  `product_id` bigint(20) unsigned NOT NULL,
	  `code` varchar(255) NOT NULL,
	  `description` varchar(255) NOT NULL,
	  `discount` varchar(16) DEFAULT NULL,
	  `qty` float NOT NULL,
	  `sell` double NOT NULL,
	  `sell_base` double NOT NULL,
	  `sell_discount` double NOT NULL,
	  `sell_total` double NOT NULL,
	  `serial_numbers` varchar(255) DEFAULT NULL,
	  `datetime_added` datetime NOT NULL,
	  `datetime_mod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  PRIMARY KEY (`id`),
	  KEY `code` (`code`),
	  KEY `product_id` (`product_id`),
	  KEY `sro_id` (`sro_id`),
	  CONSTRAINT `xlsws_sro_item_ibfk_3` FOREIGN KEY (`sro_id`) REFERENCES `xlsws_sro` (`id`),
	  CONSTRAINT `xlsws_sro_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `xlsws_product` (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;


	DELETE a.* FROM `{newdbname}`.`xlsws_cart_item` as a left join `{newdbname}`.`xlsws_cart` as b on a.cart_id=b.id where b.id is null;
	ALTER TABLE `{newdbname}`.`xlsws_cart` ADD CONSTRAINT `xlsws_cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `xlsws_customer` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_cart_item` ADD CONSTRAINT `xlsws_cart_item_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `xlsws_cart` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_cart_item` ADD CONSTRAINT `xlsws_cart_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `xlsws_product` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_product_category_assn` ADD CONSTRAINT `xlsws_product_category_assn_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `xlsws_product` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_product_category_assn` ADD CONSTRAINT `xlsws_product_category_assn_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `xlsws_category` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_category` ADD CONSTRAINT `xlsws_product_category_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `xlsws_category` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

	ALTER TABLE `{newdbname}`.`xlsws_product` ADD CONSTRAINT `xlsws_product_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `xlsws_product` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_product` ADD CONSTRAINT `xlsws_product_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `xlsws_images` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_state` ADD CONSTRAINT `fk_country` FOREIGN KEY (`country_id`) REFERENCES `xlsws_country` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_sro_repair` ADD CONSTRAINT `xlsws_sro_repair_ibfk_1` FOREIGN KEY (`sro_id`) REFERENCES `xlsws_sro` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_sro` ADD `customer_id` BIGINT  UNSIGNED  NULL  DEFAULT NULL  AFTER `ls_id`;
	ALTER TABLE `{newdbname}`.`xlsws_sro` ADD CONSTRAINT `xlsws_sro_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `xlsws_customer` (`id`);

	RENAME TABLE `{newdbname}`.`xlsws_gift_registry_items` TO `{newdbname}`.`xlsws_wishlist_item`;
	RENAME TABLE `{newdbname}`.`xlsws_gift_registry` TO `{newdbname}`.`xlsws_wishlist`;


	ALTER TABLE `{newdbname}`.`xlsws_wishlist` CHANGE `customer_id` `customer_id` BIGINT(20)  UNSIGNED  NOT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_wishlist` ADD `visibility` INT  NULL  DEFAULT NULL  AFTER `registry_description`;

	ALTER TABLE `{newdbname}`.`xlsws_wishlist_item` CHANGE `registry_id` `registry_id` BIGINT(20)  UNSIGNED  NOT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_wishlist_item` CHANGE `purchase_status` `cart_item_id` BIGINT(20)  UNSIGNED  NULL;
	ALTER TABLE `{newdbname}`.`xlsws_wishlist_item` CHANGE `purchased_by` `purchased_by` BIGINT(20)  UNSIGNED  NULL;
	UPDATE `{newdbname}`.`xlsws_wishlist_item` SET cart_item_id=NULL WHERE cart_item_id=0;

	ALTER TABLE `{newdbname}`.`xlsws_wishlist_item` ADD `qty_received` INT  NULL  DEFAULT NULL  AFTER `qty`;
	ALTER TABLE `{newdbname}`.`xlsws_wishlist_item` ADD `qty_received_manual` INT  NULL  DEFAULT NULL  AFTER `qty_received`;
	ALTER TABLE `{newdbname}`.`xlsws_wishlist_item` ADD `priority` INT  NULL  DEFAULT 2  AFTER `qty_received`;
	ALTER TABLE `{newdbname}`.`xlsws_wishlist_item` ADD `comment` TEXT NULL  DEFAULT NULL AFTER `priority`;
	update `{newdbname}`.`xlsws_wishlist_item` set priority=1;


	ALTER TABLE `{newdbname}`.`xlsws_wishlist` ADD CONSTRAINT `xlsws_wishlist_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `xlsws_customer` (`id`);

	ALTER TABLE `{newdbname}`.`xlsws_wishlist_item` ADD CONSTRAINT `xlsws_wishlist_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `xlsws_product` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_wishlist_item` ADD CONSTRAINT `xlsws_wishlist_item_ibfk_3` FOREIGN KEY (`cart_item_id`) REFERENCES `xlsws_cart_item` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_wishlist_item` ADD CONSTRAINT `xlsws_wishlist_item_ibfk_4` FOREIGN KEY (`purchased_by`) REFERENCES `xlsws_customer` (`id`);


		CREATE TABLE `{newdbname}`.`xlsws_category_integration` (
	  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `module` varchar(30) DEFAULT NULL,
	  `foreign_id` int(11) unsigned DEFAULT NULL,
	   `extra` varchar(255) DEFAULT NULL,
	  KEY `module` (`module`),
	  KEY `foreign_id` (`foreign_id`),
	  KEY `category_id` (`category_id`),
	  CONSTRAINT `xlsws_category_integration_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `xlsws_category` (`id`)

	) ENGINE=InnoDB DEFAULT CHARSET=utf8;


	CREATE TABLE `{newdbname}`.`xlsws_category_amazon` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `name0` varchar(255) DEFAULT NULL,
	  `name1` varchar(255) DEFAULT NULL,
	  `name2` varchar(255) DEFAULT NULL,
	  `name3` varchar(255) DEFAULT NULL,
	  `name4` varchar(255) DEFAULT NULL,
	  `name5` varchar(255) DEFAULT NULL,
	  `name6` varchar(255) DEFAULT NULL,
	  `name7` varchar(255) DEFAULT NULL,
	  `name8` varchar(255) DEFAULT NULL,
	  `name9` varchar(255) DEFAULT NULL,
	  `item_type` varchar(255) DEFAULT NULL,
	  `product_type` varchar(255) DEFAULT NULL,
	  `refinements` text,
	  PRIMARY KEY (`id`),
	  KEY `name` (`name0`),
	  KEY `name1` (`name1`),
	  KEY `name2` (`name2`),
	  KEY `name3` (`name3`),
	  KEY `name4` (`name4`),
	  KEY `name5` (`name5`),
	  KEY `name6` (`name6`),
	  KEY `name7` (`name7`),
	  KEY `name8` (`name8`),
	  KEY `name9` (`name9`),
	  KEY `item_type` (`item_type`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;


	CREATE TABLE `{newdbname}`.`xlsws_category_google` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `name0` varchar(255) DEFAULT NULL,
	  `name1` varchar(255) DEFAULT NULL,
	  `name2` varchar(255) DEFAULT NULL,
	  `name3` varchar(255) DEFAULT NULL,
	  `name4` varchar(255) DEFAULT NULL,
	  `name5` varchar(255) DEFAULT NULL,
	  `name6` varchar(255) DEFAULT NULL,
	  `name7` varchar(255) DEFAULT NULL,
	  `name8` varchar(255) DEFAULT NULL,
	  `name9` varchar(255) DEFAULT NULL,
	  PRIMARY KEY (`id`),
	  KEY `name` (`name0`),
	  KEY `name1` (`name1`),
	  KEY `name2` (`name2`),
	  KEY `name3` (`name3`),
	  KEY `name4` (`name4`),
	  KEY `name5` (`name5`),
	  KEY `name6` (`name6`),
	  KEY `name7` (`name7`),
	  KEY `name8` (`name8`),
	  KEY `name9` (`name9`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_customer_address` (
	  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `customer_id` bigint(20) unsigned DEFAULT NULL,
	  `address_label` varchar(255) DEFAULT NULL,
	  `active` int(11) DEFAULT '1',
	  `first_name` varchar(255) DEFAULT NULL,
	  `last_name` varchar(255) DEFAULT NULL,
	  `company` varchar(255) DEFAULT NULL,
	  `address1` varchar(255) DEFAULT NULL,
	  `address2` varchar(255) DEFAULT NULL,
	  `city` varchar(255) DEFAULT NULL,
	  `state_id` int(11) unsigned DEFAULT NULL,
	  `postal` varchar(64) DEFAULT NULL,
	  `country_id` int(11) unsigned DEFAULT NULL,
	  `phone` varchar(64) DEFAULT NULL,
	  `residential` int(11) DEFAULT NULL,
	  `modified` TIMESTAMP,
	  `created` TIMESTAMP,
	  PRIMARY KEY (`id`),
	  KEY `fk_customer_id` (`customer_id`),
	  KEY `state_id` (`state_id`),
	  KEY `country_id` (`country_id`),
	  CONSTRAINT `xlsws_customer_address_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `xlsws_country` (`id`),
	  CONSTRAINT `fk_customer_id` FOREIGN KEY (`customer_id`) REFERENCES `xlsws_customer` (`id`),
	  CONSTRAINT `xlsws_customer_address_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `xlsws_state` (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;


	CREATE TABLE IF NOT EXISTS `{newdbname}`.`xlsws_stringsource` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `category` varchar(32) DEFAULT NULL,
	  `message` varchar(1024) DEFAULT '',
	  PRIMARY KEY (`id`),
	  KEY `category` (`category`),
	  KEY `message` (`message`(255))
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

	CREATE TABLE IF NOT EXISTS `{newdbname}`.`xlsws_stringtranslate` (
	  `id` int(11) NOT NULL,
	  `language` varchar(16) NOT NULL default '',
	  `translation` varchar(1024),
	  PRIMARY KEY  (`id`,`language`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	ALTER TABLE `{newdbname}`.`xlsws_stringtranslate`
	  ADD CONSTRAINT `xlsws_stringsource_ibfk_1` FOREIGN KEY (`id`) REFERENCES `xlsws_stringsource` (`id`) ON DELETE CASCADE;

	INSERT INTO `{newdbname}`.`xlsws_stringsource` (`id`, `category`, `message`)
	VALUES
	(21, 'cart', 'Qty'),
	(22, 'cart', 'SubTotal'),
	(23, 'cart', 'Checkout'),
	(24, 'cart', 'Edit Cart'),
	(97, 'cart', 'Description'),
	(98, 'cart', 'Price'),
	(99, 'cart', 'Total'),
	(100, 'cart', 'Shipping'),
	(130, 'cart', 'Thank you for your order!'),
	(131, 'cart', 'Order ID'),
	(132, 'cart', 'Date'),
	(133, 'cart', 'Status'),
	(134, 'cart', 'Payment'),
	(135, 'cart', 'Authorization'),
	(136, 'cart', 'Notes'),
	(137, 'cart', 'Promo Code {code} Applied'),
	(202, 'cart', 'Clear Cart'),
	(203, 'cart', 'Are you sure you want to erase your cart items?'),
	(204, 'cart', 'Email Cart'),
	(205, 'cart', 'Continue Shopping'),
	(206, 'cart', 'Update Cart'),
	(20, 'checkout', 'Shopping Cart'),
	(73, 'checkout', 'Choose your shipping address'),
	(74, 'checkout', 'Or enter a new address'),
	(75, 'checkout', 'Shipping Address'),
	(88, 'checkout', 'Choose your billing address'),
	(89, 'checkout', 'Billing Address'),
	(90, 'checkout', 'Promo Code'),
	(91, 'checkout', 'Enter a Promotional Code here to receive a discount.'),
	(92, 'checkout', 'Apply Promo Code'),
	(93, 'checkout', 'Shipping'),
	(96, 'checkout', 'Click to Calculate Shipping'),
	(101, 'checkout', 'Payment'),
	(109, 'checkout', 'Submit your order'),
	(111, 'checkout', 'I hereby agree to the Terms and Conditions of shopping with {storename}'),
	(128, 'checkout', 'Customer Contact'),
	(188, 'checkout', 'Billing'),
	(77, 'CheckoutForm', 'Label for this address (i.e. Home, Work)'),
	(78, 'CheckoutForm', 'First Name'),
	(79, 'CheckoutForm', 'Last Name'),
	(80, 'CheckoutForm', 'Address'),
	(81, 'CheckoutForm', 'Address 2 (optional)'),
	(82, 'CheckoutForm', 'City'),
	(83, 'CheckoutForm', 'Country'),
	(84, 'CheckoutForm', 'State/Province'),
	(85, 'CheckoutForm', 'Zip/Postal'),
	(86, 'CheckoutForm', 'This is a residential address'),
	(87, 'CheckoutForm', 'My shipping address is also my billing address'),
	(94, 'CheckoutForm', 'Shipping Method'),
	(95, 'CheckoutForm', 'Delivery Speed'),
	(102, 'CheckoutForm', 'Payment Provider'),
	(103, 'CheckoutForm', 'Card Type'),
	(104, 'CheckoutForm', 'Card Number'),
	(105, 'CheckoutForm', 'CVV'),
	(106, 'CheckoutForm', 'Expiry Month'),
	(107, 'CheckoutForm', 'Expiry Year'),
	(108, 'CheckoutForm', 'Cardholder Name'),
	(110, 'CheckoutForm', 'Comments'),
	(112, 'CheckoutForm', 'Accept Terms'),
	(198, 'CheckoutForm', 'Phone'),
	(186, 'email', 'Dear'),
	(187, 'email', 'Thank you for your order with'),
	(194, 'email', 'This email is a confirmation for the order. To view details or track your order, click on the visit link:'),
	(195, 'email', 'Please refer to your order ID '),
	(196, 'email', ' if you want to contact us about this order.'),
	(197, 'email', 'Thank you, {storename}'),
	(199, 'email', '{storename} Order Notification {orderid}'),
	(7, 'global', '{description} : {storename}'),
	(8, 'global', '{longdescription}'),
	(9, 'global', 'Hover over image to zoom'),
	(14, 'global', 'The following related products will be added to your cart automatically with this purchase:'),
	(16, 'global', 'Other items you may be interested in:'),
	(19, 'global', 'Submit'),
	(25, 'global', 'Order Lookup'),
	(26, 'global', 'Wish Lists'),
	(27, 'global', 'View all my wish lists'),
	(28, 'global', 'Create a Wish List'),
	(29, 'global', 'Search for a wish list'),
	(30, 'global', 'Logout'),
	(31, 'global', 'Products'),
	(55, 'global', 'SEARCH'),
	(56, 'global', 'About Us'),
	(57, 'global', 'Terms and Conditions'),
	(58, 'global', 'Privacy Policy'),
	(59, 'global', 'Sitemap'),
	(60, 'global', 'Copyright'),
	(61, 'global', 'All Rights Reserved'),
	(62, 'global', '{storename} : {storetagline}'),
	(63, 'global', 'First'),
	(64, 'global', 'Last'),
	(65, 'global', 'Previous'),
	(66, 'global', 'Next'),
	(67, 'global', 'Size'),
	(68, 'global', 'Select {label}...'),
	(69, 'global', 'Color'),
	(70, 'global', 'Edit Cart'),
	(71, 'global', 'Checkout'),
	(72, 'global', 'Fields with {*} are required.'),
	(76, 'global', 'You must accept Terms and Conditions'),
	(113, 'global', '{label} ({price})'),
	(114, 'global', 'Available during normal business hours'),
	(115, 'global', 'Welcome'),
	(116, 'global', 'Edit Profile'),
	(117, 'global', 'My Addresses'),
	(118, 'global', 'Add new address'),
	(119, 'global', 'Default Billing Address'),
	(120, 'global', 'Default Shipping Address'),
	(121, 'global', 'My Orders'),
	(122, 'global', 'Awaiting Processing'),
	(123, 'global', 'My Wish Lists'),
	(124, 'global', 'Click here to create a wish list.'),
	(125, 'global', 'You have not created any wish list yet.'),
	(126, 'global', 'Create a new address book entry'),
	(127, 'global', 'Update your account'),
	(129, 'global', 'Enter a new password here to change your password'),
	(138, 'global', 'New Wish List'),
	(140, 'global', 'Name'),
	(141, 'global', 'Contains'),
	(142, 'global', 'Description'),
	(143, 'global', 'Edit'),
	(144, 'global', 'Create a new Wish List'),
	(159, 'global', '{items} item|{items} items'),
	(161, 'global', 'Wish List'),
	(162, 'global', 'View All Lists'),
	(163, 'global', 'Settings'),
	(164, 'global', 'Share'),
	(165, 'global', 'Qty'),
	(166, 'global', 'Status'),
	(176, 'global', 'Update'),
	(177, 'global', 'DELETE THIS ITEM'),
	(180, 'global', 'Send'),
	(181, 'global', 'Wish List Search'),
	(183, 'global', 'Search for a wish list by email address'),
	(184, 'global', 'Promo Code'),
	(185, 'global', 'Promo Code applied at {amount}.'),
	(189, 'global', 'Item'),
	(190, 'global', 'Price'),
	(191, 'global', 'SubTotal'),
	(192, 'global', 'Total'),
	(193, 'global', 'Payment Data'),
	(200, 'global', '{name} : {storename}'),
	(10, 'product', 'Regular Price'),
	(11, 'product', '{qty} Available'),
	(12, 'product', 'Add to Wish List'),
	(13, 'product', 'Add to Cart'),
	(15, 'product', 'Product Description'),
	(32, 'tabs', 'Products'),
	(51, 'tabs', 'New Products'),
	(52, 'tabs', 'Top Products'),
	(53, 'tabs', 'Promotions'),
	(54, 'tabs', 'Contact Us'),
	(17, 'wishlist', 'Add to Wish List'),
	(18, 'wishlist', 'Add to what list'),
	(139, 'wishlist', 'Click on the wish list name to view list contents, or click on edit to make changes to settings.'),
	(145, 'wishlist', 'Name your Wish List'),
	(146, 'wishlist', 'Description (Optional)'),
	(147, 'wishlist', 'Event Date (Optional)'),
	(148, 'wishlist', 'Visibility'),
	(149, 'wishlist', 'Public, searchable by my email address'),
	(150, 'wishlist', 'Personal, shared only by a special URL'),
	(151, 'wishlist', 'Private, only viewable with my login'),
	(152, 'wishlist', 'None'),
	(153, 'wishlist', 'Ship Option'),
	(154, 'wishlist', 'Leave the item in the Wish List, marked as Purchased'),
	(155, 'wishlist', 'Delete the item automatically from Wish List'),
	(156, 'wishlist', 'After purchase'),
	(157, 'wishlist', 'My Wish List'),
	(158, 'wishlist', 'Item has been added to your Wish List.'),
	(160, 'wishlist', 'Please check out my Wish List at {url}'),
	(167, 'wishlist', 'You can share this wish list with anyone using the URL: {url}'),
	(168, 'wishlist', 'Edit Wish List Item'),
	(169, 'wishlist', 'Qty Desired'),
	(170, 'wishlist', 'Qty Received'),
	(171, 'wishlist', 'Priority'),
	(172, 'wishlist', 'Item Comment (max 500 characters)'),
	(173, 'wishlist', 'Low Priority'),
	(174, 'wishlist', 'Normal Priority'),
	(175, 'wishlist', 'High Priority'),
	(178, 'wishlist', 'Share my Wish List'),
	(179, 'wishlist', 'Share via email'),
	(182, 'wishlist', 'Click on the wish list name to view.'),
	(201, 'wishlist', 'Please check out my shopping cart at {url}'),
	(207, 'wishlist', 'Share my Cart'),
	(208, 'wishlist', 'No publicly searchable wish lists for this email address.');




	CREATE TABLE `{newdbname}`.`xlsws_tags` (
	  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `tag` varchar(30) DEFAULT NULL,
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `tag` (`tag`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_product_tags` (
	  `product_id` bigint(20) unsigned DEFAULT NULL,
	  `tag_id` bigint(20) unsigned DEFAULT NULL,
	  KEY `product_id` (`product_id`),
	  KEY `tag` (`tag_id`),
	  CONSTRAINT `xlsws_product_tags_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `xlsws_product` (`id`),
	  CONSTRAINT `xlsws_product_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `xlsws_tags` (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	 CREATE TABLE `{newdbname}`.`xlsws_cart_shipping` (
	 `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `shipping_method` varchar(255) DEFAULT NULL,
	  `shipping_module` varchar(64) DEFAULT NULL,
	  `shipping_data` varchar(255) DEFAULT NULL,
	  `shipping_cost` double DEFAULT NULL,
	  `shipping_sell` double DEFAULT NULL,
	  `tracking_number` varchar(255) DEFAULT NULL,
	   PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;


	 CREATE TABLE `{newdbname}`.`xlsws_cart_payment` (
	 `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `payment_method` varchar(255) DEFAULT NULL,
	  `payment_module` varchar(64) DEFAULT NULL,
	  `payment_data` varchar(255) DEFAULT NULL,
	  `payment_amount` double DEFAULT NULL,
	  `datetime_posted` datetime default NULL,
	  `promocode` varchar(255) DEFAULT NULL,
	   PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;


	CREATE TABLE `{newdbname}`.`xlsws_document` (
	  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `cart_id` bigint(20) unsigned DEFAULT NULL,
	  `order_str` varchar(64) DEFAULT NULL,
	  `invoice_str` varchar(64) DEFAULT NULL,
	  `customer_id` bigint(20) unsigned DEFAULT NULL,
	  `shipaddress_id` bigint(20) unsigned DEFAULT NULL,
	  `billaddress_id` bigint(20) unsigned DEFAULT NULL,
	  `shipping_id` bigint(20) unsigned DEFAULT NULL,
	  `payment_id` bigint(20) unsigned DEFAULT NULL,
	  `discount` double DEFAULT NULL,
	  `po` varchar(64) DEFAULT NULL,
	  `order_type` mediumint(9) DEFAULT NULL,
	  `status` varchar(32) DEFAULT NULL,
	  `cost_total` double DEFAULT NULL,
	  `currency` varchar(3) DEFAULT NULL,
	  `currency_rate` double DEFAULT NULL,
	  `datetime_cre` datetime DEFAULT NULL,
	  `datetime_due` datetime DEFAULT NULL,
	  `sell_total` double DEFAULT NULL,
	  `printed_notes` text,
	  `fk_tax_code_id` int(11),
	  `tax_inclusive` tinyint(1) DEFAULT NULL,
	  `subtotal` double DEFAULT NULL,
	  `tax1` double DEFAULT '0',
	  `tax2` double DEFAULT '0',
	  `tax3` double DEFAULT '0',
	  `tax4` double DEFAULT '0',
	  `tax5` double DEFAULT '0',
	  `total` double DEFAULT NULL,
	  `item_count` int(11) DEFAULT '0',
	  `lightspeed_user` varchar(32) DEFAULT NULL,
	  `gift_registry` bigint(20) DEFAULT NULL,
	  `send_to` varchar(255) DEFAULT NULL,
	  `submitted` datetime DEFAULT NULL,
	  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `linkid` varchar(32) DEFAULT NULL,
	  PRIMARY KEY (`id`),
	  KEY `customer_id` (`customer_id`),
	  KEY `fk_ship` (`shipaddress_id`),
	  KEY `fk_bill` (`billaddress_id`),
	  KEY `fk_shiprecord` (`shipping_id`),
	  KEY `fk_payrecord` (`payment_id`),
	  KEY `cart_id` (`cart_id`),
	  CONSTRAINT `xlsws_document_ibfk_8` FOREIGN KEY (`cart_id`) REFERENCES `xlsws_cart` (`id`),
	  CONSTRAINT `xlsws_document_ibfk_1` FOREIGN KEY (`billaddress_id`) REFERENCES `xlsws_customer_address` (`id`),
	  CONSTRAINT `xlsws_document_ibfk_3` FOREIGN KEY (`shipaddress_id`) REFERENCES `xlsws_customer_address` (`id`),
	  CONSTRAINT `xlsws_document_ibfk_5` FOREIGN KEY (`customer_id`) REFERENCES `xlsws_customer` (`id`),
	  CONSTRAINT `xlsws_document_ibfk_6` FOREIGN KEY (`shipping_id`) REFERENCES `xlsws_document_shipping` (`id`),
	  CONSTRAINT `xlsws_document_ibfk_7` FOREIGN KEY (`payment_id`) REFERENCES `xlsws_document_payment` (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_document_item` (
	  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `document_id` bigint(20) unsigned NOT NULL,
	  `cart_type` int(11) DEFAULT '1',
	  `product_id` bigint(20) unsigned NOT NULL,
	  `code` varchar(255) NOT NULL,
	  `description` varchar(255) NOT NULL,
	  `discount` varchar(16) DEFAULT NULL,
	  `qty` float NOT NULL,
	  `sell` double NOT NULL,
	  `sell_base` double NOT NULL,
	  `sell_discount` double NOT NULL,
	  `sell_total` double NOT NULL,
	  `serial_numbers` varchar(255) DEFAULT NULL,
	  `gift_registry_item` bigint(20) DEFAULT NULL,
	  `datetime_added` datetime NOT NULL,
	  `datetime_mod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  PRIMARY KEY (`id`),
	  KEY `code` (`code`),
	  KEY `product_id` (`product_id`),
	  KEY `gift_registry_item` (`gift_registry_item`),
	  KEY `document_id` (`document_id`),
	  CONSTRAINT `xlsws_document_item_ibfk_3` FOREIGN KEY (`document_id`) REFERENCES `xlsws_document` (`id`),
	  CONSTRAINT `xlsws_document_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `xlsws_product` (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_document_payment` (
	  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `payment_method` varchar(255) DEFAULT NULL,
	  `payment_module` varchar(64) DEFAULT NULL,
	  `payment_data` varchar(255) DEFAULT NULL,
	  `payment_amount` double DEFAULT NULL,
	  `datetime_posted` datetime DEFAULT NULL,
	  `promocode` varchar(255) DEFAULT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_document_shipping` (
	  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `shipping_method` varchar(255) DEFAULT NULL,
	  `shipping_module` varchar(64) DEFAULT NULL,
	  `shipping_data` varchar(255) DEFAULT NULL,
	  `shipping_cost` double DEFAULT NULL,
	  `shipping_sell` double DEFAULT NULL,
	  `tracking_number` varchar(255) DEFAULT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;


	UPDATE `{newdbname}`.`xlsws_category` SET parent=NULL WHERE parent=0;
	UPDATE `{newdbname}`.`xlsws_product` SET parent=NULL WHERE parent=0;
	DELETE FROM `{newdbname}`.`xlsws_configuration` WHERE key_name='IMAGE_STORE';
	ALTER TABLE `{newdbname}`.`xlsws_customer` CHANGE `id_customer` `lightspeed_id` BIGINT(20)  NULL  DEFAULT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_customer` MODIFY COLUMN `lightspeed_id` BIGINT(20) DEFAULT NULL AFTER `id`;
	ALTER TABLE `{newdbname}`.`xlsws_customer` ADD `email_verified` INT  NULL  DEFAULT NULL  AFTER `email`;


	ALTER TABLE `{newdbname}`.`xlsws_images` ADD FOREIGN KEY (`product_id`) REFERENCES `xlsws_product` (`id`);

	ALTER TABLE `{newdbname}`.`xlsws_cart` MODIFY COLUMN `first_name` VARCHAR(64) DEFAULT NULL AFTER `id_str`;
	ALTER TABLE `{newdbname}`.`xlsws_cart` MODIFY COLUMN `last_name` VARCHAR(64) DEFAULT NULL AFTER `first_name`;
	ALTER TABLE `{newdbname}`.`xlsws_customer` MODIFY COLUMN `firstname` VARCHAR(64) DEFAULT NULL AFTER `id`;
	ALTER TABLE `{newdbname}`.`xlsws_customer` MODIFY COLUMN `lastname` VARCHAR(64) DEFAULT NULL AFTER `firstname`;
	ALTER TABLE `{newdbname}`.`xlsws_customer` CHANGE `firstname` `first_name` VARCHAR(64)  NULL  DEFAULT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_customer` CHANGE `lastname` `last_name` VARCHAR(64)  NULL  DEFAULT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_customer` MODIFY COLUMN `record_type` INT(11) DEFAULT NULL AFTER `id`;
	ALTER TABLE `{newdbname}`.`xlsws_cart` MODIFY COLUMN `customer_id` BIGINT(20) UNSIGNED DEFAULT NULL AFTER `id_str`;


	UPDATE `{newdbname}`.`xlsws_customer` SET record_type=1 where first_name is not null;

	ALTER TABLE `{newdbname}`.`xlsws_cart` ADD `shipaddress_id` BIGINT(20)  UNSIGNED  NULL  DEFAULT NULL  AFTER `customer_id`;
	ALTER TABLE `{newdbname}`.`xlsws_cart` ADD `billaddress_id` BIGINT(20)  UNSIGNED  NULL  DEFAULT NULL  AFTER `shipaddress_id`;
	ALTER TABLE `{newdbname}`.`xlsws_cart` ADD `shipping_id` BIGINT(20)  UNSIGNED  NULL  DEFAULT NULL  AFTER `billaddress_id`;
	ALTER TABLE `{newdbname}`.`xlsws_cart` ADD `payment_id` BIGINT(20)  UNSIGNED  NULL  DEFAULT NULL  AFTER `shipping_id`;
	ALTER TABLE `{newdbname}`.`xlsws_cart` ADD CONSTRAINT `fk_ship` FOREIGN KEY (`shipaddress_id`) REFERENCES `xlsws_customer_address` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_cart` ADD CONSTRAINT `fk_bill` FOREIGN KEY (`billaddress_id`) REFERENCES `xlsws_customer_address` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_cart` ADD CONSTRAINT `fk_shiprecord` FOREIGN KEY (`shipping_id`) REFERENCES `xlsws_cart_shipping` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_cart` ADD CONSTRAINT `fk_payrecord` FOREIGN KEY (`payment_id`) REFERENCES `xlsws_cart_payment` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_cart` DROP `discount`;
	ALTER TABLE `{newdbname}`.`xlsws_customer` ADD `default_billing_id` BIGINT(20) UNSIGNED  NULL  DEFAULT NULL  AFTER `company`;
	ALTER TABLE `{newdbname}`.`xlsws_customer` ADD `default_shipping_id` BIGINT(20) UNSIGNED NULL  DEFAULT NULL  AFTER `default_billing_id`;
	ALTER TABLE `{newdbname}`.`xlsws_customer` ADD FOREIGN KEY (`default_billing_id`) REFERENCES `xlsws_customer_address` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_customer` ADD FOREIGN KEY (`default_shipping_id`) REFERENCES `xlsws_customer_address` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_customer` ADD `last_login` TIMESTAMP  NULL  AFTER `modified`;

	use `{newdbname}`;

	CREATE TABLE `{newdbname}`.`xlsws_email_queue` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `sent_attempts` int(11) DEFAULT NULL,
	  `customer_id` bigint(20) unsigned DEFAULT NULL,
	  `cart_id` bigint(20) unsigned DEFAULT NULL,
	  `to` text,
	  `subject` varchar(255) DEFAULT NULL,
	  `plainbody` text,
	  `htmlbody` text,
	  `datetime_cre` datetime DEFAULT NULL,
	  PRIMARY KEY (`id`),
	  KEY `xlsws_email_queue_ibfk_1` (`customer_id`),
	  KEY `cart_id` (`cart_id`),
	  CONSTRAINT `xlsws_email_queue_ibfk_2` FOREIGN KEY (`cart_id`) REFERENCES `xlsws_cart` (`id`),
	  CONSTRAINT `xlsws_email_queue_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `xlsws_customer` (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE  `{newdbname}`.`xlsws_product_text` (
	  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `product_id` bigint(20) unsigned DEFAULT NULL,
	  `lang` varchar(6) DEFAULT NULL,
	  `title` varchar(255) DEFAULT NULL,
	  `description_short` mediumtext,
	  `description_long` mediumtext,
	  PRIMARY KEY (`id`),
	  KEY `lang` (`lang`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_classes` (
	  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `class_name` varchar(255) DEFAULT NULL,
	  `child_count` int(11) NOT NULL DEFAULT '0',
	  `request_url` varchar(255) DEFAULT NULL,
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `class_name` (`class_name`),
	  KEY `request_url` (`request_url`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE `{newdbname}`.`xlsws_pricing_levels` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `label` varchar(255) DEFAULT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	INSERT INTO `xlsws_pricing_levels` (`id`, `label`) VALUES ('1', 'Regular Prices');
	INSERT INTO `xlsws_pricing_levels` (`id`, `label`) VALUES ('2', 'Pricing Level A');
	INSERT INTO `xlsws_pricing_levels` (`id`, `label`) VALUES ('3', 'Pricing Level B');
	INSERT INTO `xlsws_pricing_levels` (`id`, `label`) VALUES ('4', 'Pricing Level C');
	INSERT INTO `xlsws_pricing_levels` (`id`, `label`) VALUES ('5', 'Pricing Level D');
	INSERT INTO `xlsws_pricing_levels` (`id`, `label`) VALUES ('6', 'Pricing Level E');
	INSERT INTO `xlsws_pricing_levels` (`id`, `label`) VALUES ('7', 'Pricing Level F');
	INSERT INTO `xlsws_pricing_levels` (`id`, `label`) VALUES ('8', 'Pricing Level G');
	INSERT INTO `xlsws_pricing_levels` (`id`, `label`) VALUES ('9', 'Pricing Level H');
	INSERT INTO `xlsws_pricing_levels` (`id`, `label`) VALUES ('10', 'Pricing Level J');
	ALTER TABLE `xlsws_product_qty_pricing` CHANGE `pricing_level` `pricing_level` INT(11)  UNSIGNED  NULL  DEFAULT NULL;
	ALTER TABLE `xlsws_product_qty_pricing` ADD FOREIGN KEY (`pricing_level`) REFERENCES `xlsws_pricing_levels` (`id`);

	CREATE TABLE `xlsws_task_queue` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `module` varchar(255) DEFAULT NULL,
	  `controller` varchar(255) DEFAULT NULL,
	  `action` varchar(255) DEFAULT NULL,
	  `data_id` varchar(255) DEFAULT NULL,
	  `product_id` bigint(20) unsigned DEFAULT NULL,
	  `created` datetime DEFAULT NULL,
	  `modified` datetime DEFAULT NULL,
	  PRIMARY KEY (`id`),
	  KEY `product_id` (`product_id`),
	  CONSTRAINT `xlsws_task_queue_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `xlsws_product` (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	update `{newdbname}`.xlsws_state as a set country_id=(select id from `{newdbname}`.xlsws_country as b where country_code=code);
	ALTER TABLE `{newdbname}`.`xlsws_state` ADD `active` INT(11)  UNSIGNED  NULL  DEFAULT NULL  AFTER `avail`;
	UPDATE `{newdbname}`.xlsws_state set active=1 where avail='Y';
	UPDATE `{newdbname}`.xlsws_state set active=0 where avail='N';
	ALTER TABLE `{newdbname}`.`xlsws_state` DROP `avail`;

	ALTER TABLE `{newdbname}`.`xlsws_country` ADD `active` INT(11)  UNSIGNED  NULL  DEFAULT NULL  AFTER `avail`;
	UPDATE `{newdbname}`.xlsws_country set active=1 where avail='Y';
	UPDATE `{newdbname}`.xlsws_country set active=0 where avail='N';
	ALTER TABLE `{newdbname}`.`xlsws_country` DROP `avail`;
	ALTER TABLE `{newdbname}`.`xlsws_customer` CHANGE `mainephonetype` `mainphonetype` VARCHAR(8)  NULL  DEFAULT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_product` ADD `sell_web_tax_inclusive` FLOAT  NULL  DEFAULT NULL  AFTER `sell_web`;
	UPDATE `{newdbname}`.`xlsws_product` set `sell_web_tax_inclusive`=`sell_web`;
	UPDATE `{newdbname}`.`xlsws_product` set `sell_web_tax_inclusive`=`sell_tax_inclusive` where `sell_tax_inclusive`>0;
	ALTER TABLE `{newdbname}`.`xlsws_cart_item` ADD `tax_in` TINYINT(2)  UNSIGNED  NULL  DEFAULT NULL  AFTER `serial_numbers`;
	ALTER TABLE `{newdbname}`.`xlsws_document_item` ADD `tax_in` TINYINT(2)  UNSIGNED  NULL  DEFAULT NULL  AFTER `serial_numbers`;
	ALTER TABLE `{newdbname}`.`xlsws_family` ADD `child_count` INT(11)  NOT NULL  DEFAULT '0'  AFTER `family`;
	ALTER TABLE `{newdbname}`.`xlsws_product` ADD `family_id` BIGINT(20)  UNSIGNED  NULL  DEFAULT NULL  AFTER `family`;
	ALTER TABLE `{newdbname}`.`xlsws_product` ADD FOREIGN KEY (`family_id`) REFERENCES `xlsws_family` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_product` ADD `class_id` BIGINT(20)  UNSIGNED  NULL  DEFAULT NULL  AFTER `class_name`;
	ALTER TABLE `{newdbname}`.`xlsws_product` ADD FOREIGN KEY (`class_id`) REFERENCES `xlsws_classes` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_configuration` ADD `param` INT  NOT NULL  DEFAULT '1'  AFTER `template_specific`;
	ALTER TABLE `{newdbname}`.`xlsws_cart_item` CHANGE `gift_registry_item` `wishlist_item` BIGINT(20) UNSIGNED NULL  DEFAULT NULL;
	ALTER TABLE `{newdbname}`.`xlsws_cart_item` DROP INDEX `gift_registry_item`;
	update `{newdbname}`.`xlsws_cart_item` set wishlist_item=null where wishlist_item=0;
	ALTER TABLE `{newdbname}`.`xlsws_cart_item` ADD FOREIGN KEY (`wishlist_item`) REFERENCES `{newdbname}`.`xlsws_wishlist_item` (`id`);
	ALTER TABLE `{newdbname}`.`xlsws_wishlist` ADD `after_purchase` INT  NOT NULL  AFTER `ship_option`;
	update `{newdbname}`.`xlsws_wishlist` set after_purchase=1;

	update xlsws_cart set fk_tax_code_id=null where fk_tax_code_id=-1;
	UPDATE `xlsws_configuration` SET `param` = '0' WHERE `key_name` = 'NEXT_ORDER_ID';
	UPDATE `xlsws_configuration` SET `key_name` = 'LANG_CODE' WHERE `key_name` = 'LANGUAGE_DEFAULT';
	UPDATE `xlsws_configuration` SET `key_value` = '6' WHERE `key_name` = 'RESET_GIFT_REGISTRY_PURCHASE_STATUS' and `key_value`=0;
	UPDATE `xlsws_configuration` SET `configuration_type_id` = '27',options=NULL,title='Enter relative URL',helper_text='This path should start with /images' WHERE `key_name` = 'HEADER_IMAGE';


	ALTER TABLE `xlsws_cart` CHANGE `fk_tax_code_id` `tax_code_id` INT(11) UNSIGNED NULL ;

	ALTER TABLE `xlsws_product` CHANGE `fk_tax_status_id` `tax_status_id` INT(11)  UNSIGNED  NULL;
	ALTER TABLE `xlsws_tax` ADD INDEX (`lsid`);
	ALTER TABLE `xlsws_tax_code` ADD INDEX (`lsid`);
	ALTER TABLE `xlsws_tax_status` ADD INDEX (`lsid`);
	ALTER TABLE `xlsws_document` ADD INDEX (`order_type`);
	ALTER TABLE `xlsws_cart` ADD INDEX (`cart_type`);


	ALTER TABLE `xlsws_product` ADD FOREIGN KEY (`tax_status_id`) REFERENCES `xlsws_tax_status` (`lsid`);
	ALTER TABLE `xlsws_cart` ADD FOREIGN KEY (`tax_code_id`) REFERENCES `xlsws_tax_code` (`lsid`);
	ALTER TABLE `xlsws_product_qty_pricing` ADD FOREIGN KEY (`product_id`) REFERENCES `xlsws_product` (`id`);
	ALTER TABLE `xlsws_customer` ADD FOREIGN KEY (`pricing_level`) REFERENCES `xlsws_pricing_levels` (`id`);
	update xlsws_product_qty_pricing set pricing_level=pricing_level+1;
	ALTER TABLE `xlsws_product_related` ADD FOREIGN KEY (`product_id`) REFERENCES `xlsws_product` (`id`);
	ALTER TABLE `xlsws_product_related` ADD FOREIGN KEY (`related_id`) REFERENCES `xlsws_product` (`id`);
	ALTER TABLE `xlsws_cart` ADD `document_id` BIGINT(20)  UNSIGNED  NULL  DEFAULT NULL  AFTER `payment_id`;
	ALTER TABLE `xlsws_cart` ADD FOREIGN KEY (`document_id`) REFERENCES `xlsws_document` (`id`);

	ALTER TABLE `xlsws_category` CHANGE `meta_keywords` `google_extra` VARCHAR(255)  NULL  DEFAULT NULL;
	ALTER TABLE `xlsws_modules` ADD `version` INT  NULL  DEFAULT NULL  AFTER `category`;
	INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`)
	VALUES
		('Share Cart Email Subject Line', 'EMAIL_SUBJECT_CART', '{storename} Cart for {customername}', 'Configure Email Subject line with variables for Customer Email', 24, 10, '2012-08-28 14:07:09', '2012-08-28 14:07:09', NULL, 0, 1);
	INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`)
	VALUES	('Wishlist Email Subject Line', 'EMAIL_SUBJECT_WISHLIST', '{storename} Wishlist for {customername}', 'Configure Email Subject line with variables for Customer Email', 24, 10, '2012-08-28 14:07:09', '2012-08-28 14:07:09', NULL, 0, 1);
	ALTER TABLE `xlsws_configuration` CHANGE `helper_text` `helper_text` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_general_ci  NULL  DEFAULT '';

	ALTER TABLE `xlsws_cart` CHANGE `linkid` `linkid` VARCHAR(64)  NULL  DEFAULT NULL;
	ALTER TABLE `xlsws_document` CHANGE `linkid` `linkid` VARCHAR(64)  NULL  DEFAULT NULL;
	ALTER TABLE `xlsws_sro` CHANGE `cart_id` `linkid` VARCHAR(64)  NULL  DEFAULT NULL;

	update xlsws_shipping_tiers set `class_name`='tieredshipping';
	ALTER TABLE `xlsws_promo_code` CHANGE `qty_remaining` `qty_remaining` INT(11)  NULL  DEFAULT NULL;
	update xlsws_promo_code set qty_remaining=NULL where qty_remaining=-1;
	ALTER TABLE `xlsws_promo_code` CHANGE `threshold` `threshold` DOUBLE  NULL  DEFAULT NULL;
	update `xlsws_promo_code` set threshold=null where threshold=0;
	UPDATE `xlsws_configuration` SET `helper_text` = '' WHERE `key_name` = 'EMAIL_SEND_CUSTOMER';
	UPDATE `xlsws_configuration` SET `helper_text` = 'Email store on every order' WHERE `key_name` = 'EMAIL_SEND_STORE';
	UPDATE `xlsws_configuration` SET `helper_text` = '' WHERE `key_name` = 'STORE_NAME';
	INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`) VALUES (NULL, 'System Logging', 'DEBUG_LOGGING', 'error', '', '1', '21', '2013-02-07 11:13:36', '2012-03-08 09:57:38', 'LOGGING', '0', '1');
	UPDATE `xlsws_configuration` SET `configuration_type_id` = '25', `sort_order` = '3' WHERE `key_name` = 'WEIGHT_UNIT';
	UPDATE `xlsws_configuration` SET `configuration_type_id` = '25', `sort_order` = '4' WHERE `key_name` = 'DIMENSION_UNIT';
	UPDATE `xlsws_configuration` SET `configuration_type_id` = '25', `sort_order` = '5' WHERE `key_name` = 'SHIPPING_TAXABLE';
	ALTER TABLE `xlsws_category` CHANGE `google_id` `google_id` INT(11)  UNSIGNED  NULL  DEFAULT NULL;
	ALTER TABLE `xlsws_promo_code` ADD `module` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `threshold`;
	update xlsws_promo_code set module='freeshipping' where left(lscodes,10)='shipping:,';
	update xlsws_promo_code set code='freeshipping:' where code='free_shipping:';
	ALTER TABLE `xlsws_modules` ADD `name` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `version`;
	ALTER TABLE `xlsws_tax_code` DROP INDEX `code`;
	ALTER TABLE `xlsws_tax_code` ADD INDEX (`code`);
	UPDATE `xlsws_configuration` SET title='Place Web Store in Maintenance Mode', options='BOOL',configuration_type_id=2 where `key_name` ='STORE_OFFLINE';
	update xlsws_configuration set helper_text='' where helper_text=' ';

	update xlsws_configuration set `key_name`='ENABLE_WISH_LIST' where `key_name`='ENABLE_GIFT_REGISTRY';
	update xlsws_configuration set `key_value`='title' where `key_name`='PRODUCT_SORT_FIELD' AND `key_value`='Name';
	update xlsws_configuration set `key_value`='-id' where `key_name`='PRODUCT_SORT_FIELD' AND `key_value`='-Rowid';
	update xlsws_configuration set `key_value`='-modified' where `key_name`='PRODUCT_SORT_FIELD' AND `key_value`='-Modified';
	update xlsws_configuration set `key_value`='code' where `key_name`='PRODUCT_SORT_FIELD' AND `key_value`='Code';
	update xlsws_configuration set `key_value`='sell_web' where `key_name`='PRODUCT_SORT_FIELD' AND `key_value`='SellWeb';
	update xlsws_configuration set `key_value`='-inventory_avail' where `key_name`='PRODUCT_SORT_FIELD' AND `key_value`='-InventoryAvail';
	update xlsws_configuration set `key_value`='description_short' where `key_name`='PRODUCT_SORT_FIELD' AND `key_value`='DescriptionShort';
	update xlsws_configuration set `key_value`='' where `key_name`='LSKEY';
	update xlsws_configuration set `options`='PASSWORD' where `key_name`='EMAIL_SMTP_PASSWORD';
	update xlsws_configuration set `key_value`='brooklyn' where `key_name`='DEFAULT_TEMPLATE';
	update xlsws_configuration set `title`='Non-inventoried Item Display Message' where `key_name`='INVENTORY_NON_TITLE';

	INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`) VALUES ('Photo Processor', 'CEventPhoto', 'wsphoto', 'Component that handles photos', '28', '1', CURRENT_TIMESTAMP, NULL, 'CEventPhoto', '0', '1');
	INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`) VALUES ('Menu Processor', 'PROCESSOR_MENU', 'wsmenu', 'Component that handles menu display', '28', '2', CURRENT_TIMESTAMP, NULL, 'PROCESSOR_MENU', '0', '1');
	INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`) VALUES ('Language Menu shows', 'PROCESSOR_LANGMENU', 'wslanglinks', 'Component that handles language menu display', '15', '2', CURRENT_TIMESTAMP, NULL, 'PROCESSOR_LANGMENU', '0', '1');
	INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`) VALUES ('Delivery Speed format', 'SHIPPING_FORMAT', '{label} ({price})', 'Formatting for Delivery Speed. The variables {label} and {price} can be used.', '25', '5', CURRENT_TIMESTAMP, NULL, NULL, '0', '1');
	INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`) VALUES ('Products Per Row', 'PRODUCTS_PER_ROW', '3', 'Products per row on grid. (Note this number must be divisible evenly into 12. That\'s why \'5\' is missing.)', '8', '3', CURRENT_TIMESTAMP, NULL, 'PRODUCTS_PER_ROW', '0', '1');
	ALTER TABLE `xlsws_configuration` ADD `required` INT  NULL  DEFAULT NULL  AFTER `param`;
	INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `options`, `template_specific`, `param`, `required`)
VALUES ('Home page', 'HOME_PAGE', '*products', 'Home page viewers should first see', 19, 4, 'HOME_PAGE', 0, 1, NULL);
INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `options`, `template_specific`, `param`, `required`)
VALUES ('Use Short Description', 'USE_SHORT_DESC', 1, 'Home page viewers should first see', 19, 5, 'BOOL', 0, 1, NULL);
	ALTER TABLE `xlsws_customer` ADD INDEX (`facebook`);
INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES ('Facebook Secret Key', 'FACEBOOK_SECRET', '', 'Secret Key found with your App ID', 26, 2, CURRENT_TIMESTAMP, NULL, NULL, 0, 0, NULL);
INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES ('Show Facebook Comments on Product details', 'FACEBOOK_COMMENTS', 0, '', 26, 3, CURRENT_TIMESTAMP, NULL, 'BOOL', 0, 0, NULL);
INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES ('Show Post to Wall after checkout', 'FACEBOOK_CHECKOUT', 0, '', 26, 4, CURRENT_TIMESTAMP, NULL, 'BOOL', 0, 0, NULL);
INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES ('Share to Wall Caption', 'FACEBOOK_WALL_CAPTION', 'I found some great deals at {storename}!', '', 26, 5, CURRENT_TIMESTAMP, NULL, NULL, 0, 0, NULL);
INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES ('Share to Wall Button', 'FACEBOOK_WALL_PUBLISH', 'Post to your wall', '', 26, 7, CURRENT_TIMESTAMP, NULL, NULL, 0, 0, NULL);
INSERT IGNORE INTO `xlsws_modules` (`active`, `module`, `category`, `version`, `name`, `sort_order`, `configuration`, `modified`, `created`)
VALUES (1, 'wsphoto', 'CEventPhoto', 1, 'Web Store Internal', 1, NULL, NULL, NULL);
DELETE FROM `xlsws_configuration` where `key_name`='PRODUCT_ENLARGE_SHOW_LIGHTBOX';
DELETE FROM `xlsws_configuration` where `key_name`='DEBUG_DELETE_DUPES';
DELETE FROM `xlsws_configuration` where `key_name`='DEBUG_DISABLE_AJAX';
DELETE FROM `xlsws_configuration` where `key_name`='DB_BACKUP_FOLDER';
DELETE FROM `xlsws_configuration` where `key_name`='DEBUG_TEMPLATE';
DELETE FROM `xlsws_configuration` where `key_name`='HTML_EMAIL';
DELETE FROM `xlsws_configuration` where `key_name`='SESSION_HANDLER';
INSERT IGNORE INTO `xlsws_configuration` ( `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES ('Jpg Image Quality (1 to 100)', 'IMAGE_QUALITY', '75', 'Compression for JPG images', '17', '15', CURRENT_TIMESTAMP, NULL, NULL, '0', '1', '1');
INSERT IGNORE INTO `xlsws_configuration` ( `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES ('Jpg Sharpen (1 to 50)', 'IMAGE_SHARPEN', '25', 'Sharpening for JPG images', '17', '16', CURRENT_TIMESTAMP, NULL, NULL, '0', '1', '1');
INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES ('Image Background {color} Fill', 'IMAGE_BACKGROUND', '#FFFFFF', 'Optional image background color (#HEX)', 17, 20, CURRENT_TIMESTAMP, NULL, NULL, 1, 1, 0);
ALTER TABLE `xlsws_cart` CHANGE `ip_host` `origin` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_general_ci  NULL  DEFAULT NULL;
INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `options`, `template_specific`, `param`, `required`) VALUES ('Installed', 'INSTALLED', 0, '', 0, 0, 'BOOL', 0, 1, NULL);
INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `options`, `template_specific`, `param`, `required`) VALUES ('Use Categories in Product URLs', 'SEO_URL_CATEGORIES', 0, 'This will include the Category path when creating the SEO formatted URLs.',21,2, 'BOOL', 0, 1, NULL);
INSERT IGNORE INTO `xlsws_configuration` (`title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES ('Use Quantity Entry Blank', 'SHOW_QTY_ENTRY', '0', 'If enabled, show freeform qty entry for Add To Cart', '19', '12', CURRENT_TIMESTAMP, NULL, 'BOOL', '1', '1', NULL);
UPDATE xlsws_configuration set key_value='dark' where key_value='webstore-dark' AND key_name='DEFAULT_TEMPLATE_THEME';
UPDATE xlsws_configuration set key_value='light' where key_value='webstore-light' AND key_name='DEFAULT_TEMPLATE_THEME';
UPDATE xlsws_configuration set key_value='Hurry, only {qty} left in stock!' where key_name='INVENTORY_LOW_TITLE';
UPDATE xlsws_configuration set key_value='{qty} Available' where key_name='INVENTORY_AVAILABLE';
DELETE FROM xlsws_configuration where key_name='INVENTORY_DISPLAY_LEVEL';
UPDATE xlsws_configuration set title='Display Inventory on Product Details' where key_name='INVENTORY_DISPLAY';
UPDATE xlsws_configuration set title='Authorized IPs For LightSpeed uploading (USE WITH CAUTION)',helper_text='List of IP Addresses (comma separated) which are allowed to upload products and download orders. NOTE: DO NOT USE THIS OPTION IF YOU DO NOT HAVE A STATIC IP ADDRESS' where key_name='LSAUTH_IPS';
DELETE FROM `xlsws_configuration` where `key_name`='DEFAULT_EXPIRY_GIFT_REGISTRY';
UPDATE xlsws_configuration set title='Product {color} Label',helper_text='Rename {color} Option of LightSpeed to this' where key_name='PRODUCT_COLOR_LABEL';
UPDATE xlsws_configuration set title='Image Background {color} Fill',helper_text='Optional image background {color} (#HEX)' where key_name='IMAGE_BACKGROUND';
UPDATE xlsws_configuration set configuration_type_id=29 where key_name like '%IMAGE_WIDTH';
DELETE from xlsws_configuration where key_name = 'ENABLE_COLOR_FILTER';
UPDATE xlsws_configuration set configuration_type_id=29 where key_name like '%IMAGE_HEIGHT';
UPDATE xlsws_configuration set configuration_type_id=24, key_value='Thank you, {storename}' where key_name ='EMAIL_SIGNATURE';
UPDATE xlsws_configuration set options='BOOL' where key_name = 'ENABLE_SLASHED_PRICES';
UPDATE xlsws_configuration set key_value=1 where key_value=2 AND key_name = 'ENABLE_SLASHED_PRICES';
UPDATE xlsws_modules set category='theme' where category='template';
INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL, 'After adding item to cart', 'AFTER_ADD_CART', '0', 'What should site do after shopper adds item to cart', '4', '5', '2009-04-06 10:34:34', '2009-04-06 10:34:34', 'AFTER_ADD_CART', '0', '1', NULL);
INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL, 'Send test email on Save', 'EMAIL_TEST', '0', 'When clicking Save, system will attempt to send a test email through', '5', '20', '2012-05-22 07:55:29', '2012-04-13 10:07:41', 'BOOL', '0', '0', NULL);
INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL, 'Appending ?group=1 (=2 etc) to Url will break feed into groups of', 'GOOGLE_PARSE', '5000', 'For large db\'s, break up google merchant feed', '20', '5', '2012-09-26 12:20:00', '2012-08-28 14:07:09', NULL, '0', '1', NULL);
INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL, 'Homepage Title format', 'SEO_HOMEPAGE_TITLE', '{storename} : {storetagline}', 'Format for homepage title', '22', '5', '2012-09-26 12:20:00', '2012-08-28 14:07:09', NULL, '0', '1', NULL);
DELETE FROM `xlsws_configuration` where `key_name`='HTML_DESCRIPTION';

	SET FOREIGN_KEY_CHECKS=1";
}

function initialConfigLoad($db)
{

	$db->add_config_key("LSAUTH_IPS","Authorized IPs For LightSpeed uploading (USE WITH CAUTION)","","List of IP Addresses (comma separated) which are allowed to upload products and download orders. NOTE: DO NOT USE THIS OPTION IF YOU DO NOT HAVE A STATIC IP ADDRESS",16,4,"");
	$db->add_config_key("DISABLE_CART","Disable Cart","","If selected, products will only be shown but not sold",4,4,"BOOL");
	$db->add_config_key("LANG_CODE","Default Language","en"," ",15,1,NULL);
	$db->add_config_key("CURRENCY_DEFAULT","Default Currency","USD"," ",15,7,NULL);
	$db->add_config_key("LANGUAGES","Languages","fr","",3,4,NULL);
	$db->add_config_key("EMAIL_SMTP_SERVER","SMTP Server","","SMTP Server to send emails",5,11,NULL);
	$db->add_config_key("MIN_PASSWORD_LEN","Minimum Password Length",6,"Minimum password length",3,5,"INT");
	$db->add_config_key("EMAIL_FROM","Store Email","","From which address emails will be sent",2,3,NULL);
	$db->add_config_key("STORE_NAME","Store Name","LightSpeed Web Store","",2,1,NULL);
	$db->add_config_key("EMAIL_BCC","BCC Address","","Enter an email address here if you would like to get BCCed on all emails sent by the webstore.",5,2,NULL);
	$db->add_config_key("EMAIL_SIGNATURE","Email Signature","Thank you, {storename}","Email signature for all outgoing emails",24,10,NULL);
	$db->add_config_key("ENABLE_WISH_LIST","Enable Wish List",1,"",7,1,"BOOL");
	$db->add_config_key("ENABLE_SRO","Display My Repairs (SROs) under My Account",0,"If your store uses SROs for repairs and uploads them to Web Store, turn this option on to allow customers to view pending repairs.",6,4,"BOOL");
	$db->add_config_key("DATE_FORMAT","Date Format","m/d/Y","The date format to be used in store. Please see http://www.php.net/date for more information",15,3,NULL);
	$db->add_config_key("ENABLE_FAMILIES","Show Families on Product Menu?",1,"",19,5,"ENABLE_FAMILIES");
	$db->add_config_key("PRODUCTS_PER_PAGE","Products Per Page",12,"Number of products per page to display in product listing or search",8,3,"INT");
	$db->add_config_key("PRODUCT_SORT_FIELD","Products Sorting","-modified","By which field products will sorted in result",8,4,"PRODUCT_SORT");
	$db->add_config_key("ORDER_FROM","Order From","","Order email address from which order notification is sent. This email address also gets the notification of the order",5,1,NULL);
	$db->add_config_key("ALLOW_GUEST_CHECKOUT","New customers can purchase",1,"Force customers to sign up with an account before shopping? Note this some customers will abandon a forced-signup process. Customer cards are created in LightSpeed based on all orders, not dependent on customer registrations.",3,2,"ALLOW_GUEST_CHECKOUT");
	$db->add_config_key("INVENTORY_LOW_THRESHOLD","Low Inventory Threshold",3,"If inventory of a product is below this quantity, Low inventory threshold title will be displayed in place of inventory value.",11,8,"INT");
	$db->add_config_key("INVENTORY_AVAILABLE","Available Inventory Message","{qty} Available","This text will be shown when product is available for shipping. This value will only be shown if you choose Display Inventory Level in place of actual inventory value",11,6,NULL);
	$db->add_config_key("INVENTORY_ZERO_NEG_TITLE","Zero or Negative Inventory Message","This item is not currently available","This text will be shown in place of showing 0 or negative inventory when you choose Display Inventory Level",11,5,NULL);
	$db->add_config_key("DISPLAY_EMPTY_CATEGORY","Display Empty Categories?",1,"Show categories that have no child category or images?",8,12,"BOOL");
	$db->add_config_key("INVENTORY_DISPLAY","Display Inventory",1,"Show the number of items in inventory?",11,1,"BOOL");
	$db->add_config_key("INVENTORY_LOW_TITLE","Low Inventory Message","There is low inventory for this item","If inventory of a product is below the low threshold, this text will be shown.",11,7,NULL);
	$db->add_config_key("INVENTORY_FIELD_TOTAL","Inventory should include Virtual Warehouses",0,"If selected yes, the inventory figure shown will be that of  available, reserved and inventory in warehouses. If no, only that of available in store will be shown",11,3,"BOOL");
	$db->add_config_key("INVENTORY_NON_TITLE","Non-inventoried Item Display Message","Available on request","Title to be shown for products that are not normally stocked",11,9,"");
	$db->add_config_key("SHIP_RESTRICT_DESTINATION","Only Ship To Defined Destinations",0,"If selected yes, web shopper can only choose addresses in defined Destinations. See Destinations for more information",25,1,"BOOL");
	$db->add_config_key("LISTING_IMAGE_WIDTH","Product Grid image width",180,"Product Listing Image Width. Comes in search or category listing page",29,1,"INT");
	$db->add_config_key("LISTING_IMAGE_HEIGHT","Product Grid image height",190,"Product Listing Image Height. Comes in search or category listing page",29,2,"INT");
	$db->add_config_key("DETAIL_IMAGE_WIDTH","Product Detail Image Width",256,"Product Detail Page Image Width. When the product is being viewed in the product detail page.",29,5,"INT");
	$db->add_config_key("DETAIL_IMAGE_HEIGHT","Product Detail Image Height",256,"Product Detail Page Image Height. When the product is being viewed in the product detail page.",29,6,"INT");
	$db->add_config_key("PRODUCT_SIZE_LABEL","Product Size Label","Size","Rename Size Option of LightSpeed to this",8,2,NULL);
	$db->add_config_key("PRODUCT_COLOR_LABEL","Product Color Label","Color","Rename Color Option of LightSpeed to this",8,1,NULL);
	$db->add_config_key("MINI_IMAGE_WIDTH","Shopping Cart image width",30,"Mini Cart Image Width. For images in the mini cart for every page.",29,3,"INT");
	$db->add_config_key("MINI_IMAGE_HEIGHT","Shopping Cart image height",30,"Mini Cart Image Height. For images in the mini cart for every page.",29,4,"INT");
	$db->add_config_key("TAX_INCLUSIVE_PRICING","Tax Inclusive Pricing",0,"If selected yes, all prices will be shown tax inclusive in webstore.",15,6,"BOOL");
	$db->add_config_key("ENCODING","Browser Encoding","UTF-8","What character encoding would you like to use for your visitors?  UTF-8 should be normal for all users.",15,10,"ENCODING");
	$db->add_config_key("TIMEZONE","Web Store Time Zone","America/New_York","The timezone in which your Web Store should display and store time.",15,4,"TIMEZONE");
	$db->add_config_key("ENABLE_SSL","Enable SSL","","You must have SSL/https enabled on your site to use SSL.",16,2,"BOOL");
	$db->add_config_key("RESET_GIFT_REGISTRY_PURCHASE_STATUS","Number Of Hours Before Purchase Status Is Reset",6,"A visitor may add an item to cart from gift registry but may never order it. The option will reset the status to available for purchase after the specified number of hours since it was added to cart.",7,3,"INT");
	$db->add_config_key("CURRENCY_FORMAT","Currency Printing Format","%n","Currency will be printed in this format. Please see http://www.php.net/money_format for more details.",0,8,"");
	$db->add_config_key("LOCALE","Locale","en_US","Locale for your web store. See http://www.php.net/money_format for more information",15,1,"");

	$db->add_config_key("STORE_PHONE","Store Phone","555-555-1212","Phone number displayed in email footer.",2,2,NULL);
	$db->add_config_key("DEFAULT_COUNTRY","Default Country",224,"Default country for shipping or customer registration",15,2,"COUNTRY");
	$db->add_config_key("DEFAULT_TEMPLATE","Template","brooklyn","The default template from templates directory to be used for Web Store",19,1,"TEMPLATE");
	$db->add_config_key("QUOTE_EXPIRY","Quote Expiry Days",30,"Number of days before discount in quote will expire.",4,5,"INT");
	$db->add_config_key("CART_LIFE","Cart Expiry Days",30,"Number of days before ordered/process carts are deleted from the system",4,6,"INT");
	$db->add_config_key("WEIGHT_UNIT","Weight Unit","lb","What is the weight unit used in Web Store?",25,3,"WEIGHT");

	$db->add_config_key("INVENTORY_OUT_ALLOW_ADD","When a product is Out of Stock",1,"How should system treat products currently out of stock. Note: Turn OFF the checkbox for -Only Upload Products with Available Inventory- in Tools->eCommerce.",11,10,"INVENTORY_OUT_ALLOW_ADD");
	$db->add_config_key("DIMENSION_UNIT","Dimension Unit","in","What is the dimension unit used in Web Store?",25,4,"DIMENSION");
	$db->add_config_key("LSKEY","LightSpeed Secure Key","","The secure key or password for administrative access to your lightspeed web store",0,1,NULL);

	$db->add_config_key("HEADER_IMAGE","Enter relative URL","/images/header/defaultheader.png","This path should start with /images",27,1,NULL);
	$db->add_config_key("STORE_OFFLINE","Put store in Maintenance Mode",0,"If selected, store will be offline.",2,16,"BOOL");
	$db->add_config_key("EMAIL_SMTP_PORT","SMTP Server Port",80,"SMTP Server Port",5,12,"INT");
	$db->add_config_key("EMAIL_SMTP_USERNAME","SMTP Server Username","","If your SMTP server requires a username, please enter it here",5,13,"");
	$db->add_config_key("EMAIL_SMTP_PASSWORD","SMTP Server Password","","If your SMTP server requires a password, please enter it here.",5,14,"PASSWORD");
	$db->add_config_key("TAX_DECIMAL","Number of decimal places used in tax calculation",2,"Please specify the number of decimal places to be used in tax calculation. This should be the same as the number of decimal places your currency format is shown as. ",0,9,NULL);
	$db->add_config_key("QTY_FRACTION_PURCHASE","Allow Qty-purchase in fraction",0,"If enabled, customers will be able to purchase items in fractions. E.g. 0.5 of an item can ordered by a customer.",0,10,"BOOL");
	$db->add_config_key("SITEMAP_SHOW_PRODUCTS","Show products in Sitemap",0,"Enable this option if you want to show products in your sitemap page. If you have a very large product database, we recommend you turn off this option",8,14,"BOOL");
	$db->add_config_key("NEXT_ORDER_ID","Next Order Id",30000,"What is the next order id webstore will use? This value will incremented at every order submission.",15,11,"PINT");
	$db->add_config_key("SHIPPING_TAXABLE","Add taxes for shipping fees",0,"Enable this option if you want taxes to be calculated for shipping fees and applied to the total.",25,5,"BOOL");
	$db->add_config_key("MATRIX_PRICE","In Product Grid, when child product prices vary",3,"How should system treat child products when different child products have different prices.",8,8,"MATRIX_PRICE");
	$db->add_config_key("CHILD_SEARCH","Show child products in search results",0,"If you want child products from a size color matrix to show up in search results, enable this option",8,10,"BOOL");
	$db->add_config_key("EMAIL_SMTP_SECURITY_MODE","Security mode for outbound SMTP",0,"Automatic based on SMTP Port, or force security.",5,15,"EMAIL_SMTP_SECURITY_MODE");
	$db->add_config_key("MAX_PRODUCTS_IN_SLIDER","Maximum Products in Slider",64,"For a custom page, max products in slider",8,16,"INT");
	$db->add_config_key("DATABASE_SCHEMA_VERSION","Database Schema Version",250,"Used for tracking schema changes",0,0,NULL);
	$db->add_config_key("MODERATE_REGISTRATION","Moderate Customer Registration",0,"If enabled, customer registrations will need to be moderated before they are approved",3,1,"BOOL");
	$db->add_config_key("FEATURED_KEYWORD","Featured Keyword","featured","If this keyword is one of your product keywords, the product will be featured on the Web Store homepage.",8,13,NULL);
	$db->add_config_key("DEBUG_PAYMENTS","Debug Payment Methods",0,"If selected, WS log all activity for credit card processing and other payment methods.",1,18,"BOOL");
	$db->add_config_key("DEBUG_SHIPPING","Debug Shipping Methods",0,"If selected, WS log all activity for shipping methods.",1,19,"BOOL");
	$db->add_config_key("DEBUG_RESET","Reset Without Flush",0,"If selected, WS will not perform a flush on content tables when doing a Reset Store Products.",1,20,"BOOL");
	$db->add_config_key("ENABLE_FAMILIES_MENU_LABEL","Show Families Menu label","By Manufacturer","",19,6,NULL);
	$db->add_config_key("ENABLE_SLASHED_PRICES","Enabled Slashed \"Original\" Prices",2,"If selected, will display original price slashed out and Web Price as a Sale Price.",19,9,"ENABLE_SLASHED_PRICES");

	$db->add_config_key("RECAPTCHA_PUBLIC_KEY","ReCaptcha Public Key","6LfxAtASAAAAADyBjHu6_cfVdMYLVBzgEnbTSbWi","Sign up for an account at http://www.google.com/recaptcha",18,2,NULL);
	$db->add_config_key("RECAPTCHA_PRIVATE_KEY","ReCaptcha Private Key","6LfxAtASAAAAACkJllJojWMmxvQZf2Mtt3IAMnF0","Sign up for an account at http://www.google.com/recaptcha",18,3,NULL);
	$db->add_config_key("CAPTCHA_STYLE","Captcha Style",0,"Sign up for an account at http://www.google.com/recaptcha",18,1,"CAPTCHA_STYLE");
	$db->add_config_key("CAPTCHA_CHECKOUT","Use Captcha on Checkout",0,"",18,4,"CAPTCHA_CHECKOUT");
	$db->add_config_key("CAPTCHA_CONTACTUS","Use Captcha on Contact Us",0,"",18,5,"CAPTCHA_CONTACTUS");
	$db->add_config_key("CAPTCHA_REGISTRATION","Use Captcha on Registration",0,"",18,6,"CAPTCHA_REGISTRATION");
	$db->add_config_key("EMAIL_SMTP_AUTH_PLAIN","Force AUTH PLAIN Authentication",0,"Force plain text password in rare circumstances",5,16,"BOOL");
	$db->add_config_key("INVENTORY_RESERVED","Deduct Pending Orders from Available Inventory",1,"This option will calculate Qty Available minus Pending Orders. Turning on Upload Orders in LightSpeed Tools->eCommerce->Documents is required to make this feature work properly.",11,4,"BOOL");
	$db->add_config_key("LIGHTSPEED_HOSTING","LightSpeed Hosting",0,"Flag which indicates site is hosted by LightSpeed",0,0,"BOOL");
	$db->add_config_key("PRICE_REQUIRE_LOGIN","Require login to view prices",0,"System will not display prices to anyone not logged in.",3,3,"BOOL");
	$db->add_config_key("UPLOADER_TIMESTAMP","Last timestamp uploader ran",0,"Internal",0,0,"NULL");
	$db->add_config_key("GOOGLE_ANALYTICS","Google Analytics Code (format: UA-00000000-0)","","Google Analytics code for tracking",20,1,"NULL");
	$db->add_config_key("STORE_TAGLINE","Store Tagline","Amazing products available to order online!","Used as default for Title bar for home page",2,4,"NULL");

	$db->add_config_key("LOG_ROTATE_DAYS","Log Rotate Days",30,"How many days System Log should be retained.",1,30,"INT");
	$db->add_config_key("CAPTCHA_THEME","ReCaptcha Theme","white","",18,4,"CAPTCHA_THEME");
	$db->add_config_key("EMAIL_SEND_CUSTOMER","Send Receipts to Customers",1,"",24,1,"BOOL");
	$db->add_config_key("EMAIL_SEND_STORE","Send Order Alerts to Store",1,"Email store on every order",24,2,"BOOL");
	$db->add_config_key("EMAIL_SUBJECT_CUSTOMER","Customer Email Subject Line","{storename} Order Notification {orderid}","Configure Email Subject line with variables for Customer Email",24,10,NULL);
	$db->add_config_key("EMAIL_SUBJECT_OWNER","Owner Email Subject Line","{storename} Order Notification {orderid}","Configure Email Subject line with variables for Owner email",24,11,NULL);
	$db->add_config_key("SHOW_TEMPLATE_CODE","Show Product Code on Product Details",1,"Determines if the Product Code should be visible",19,20,"BOOL");
	$db->add_config_key("SHOW_SHARING","Show Sharing Buttons on Product Details",1,"Show Sharing buttons such as Facebook and Pinterest",19,21,"BOOL");
	$db->add_config_key("SEO_URL_CODES","Use Product Codes in Product URLs",0,"If your Product Codes are important (such as model numbers), this will include them when making SEO formatted URLs. If you generate your own Product Codes that are only internal, you can leave this off.",21,1,"BOOL");
	$db->add_config_key("GOOGLE_ADWORDS","Google AdWords ID (format: 000000000)","","Google AdWords Conversion ID (found in line 'var google_conversion_id' when viewing code from Google AdWords setup)",20,2,"NULL");
	$db->add_config_key("GOOGLE_VERIFY","Google Site Verify ID (format: _PRasdu8f9a8F9A etc)","","Google Verify Code (found in google-site-verification meta header)",20,3,"NULL");
	$db->add_config_key("SEO_PRODUCT_TITLE","Product Title format","{description} : {storename}","Which elements appear in the Title",22,2,"NULL");
	$db->add_config_key("SEO_PRODUCT_DESCRIPTION","Product Meta Description format","{longdescription}","Which elements appear in the Meta Description",22,3,"NULL");
	$db->add_config_key("SEO_CATEGORY_TITLE","Category pages Title format","{name} : {storename}","Which elements appear in the title of a category page",23,1,"NULL");
	$db->add_config_key("SEO_CUSTOMPAGE_TITLE","Custom pages Title format","{name} : {storename}","Which elements appear in the title of a custom page",23,2,"NULL");
	$db->add_config_key("CATEGORY_IMAGE_WIDTH","Category Page Image Width",180,"if using a Category Page image",29,7,"INT");
	$db->add_config_key("CATEGORY_IMAGE_HEIGHT","Category Page Image Height",180,"if using a Category Page image",29,8,"INT");
	$db->add_config_key("PREVIEW_IMAGE_WIDTH","Preview Thumbnail (Product Detail Page) Width",60,"Preview Thumbnail image",29,9,"INT");
	$db->add_config_key("PREVIEW_IMAGE_HEIGHT","Preview Thumbnail (Product Detail Page) Height",60,"Preview Thumbnail image",29,10,"INT");
	$db->add_config_key("SLIDER_IMAGE_WIDTH","Slider Image Width",90,"Slider on custom pages",29,11,"INT");
	$db->add_config_key("SLIDER_IMAGE_HEIGHT","Slider Image Height",90,"Slider on custom pages",29,12,"INT");
	$db->add_config_key("IMAGE_FORMAT","Image Format","jpg","Use .jpg or .png format for images. JPG files are smaller but slightly lower quality. PNG is higher quality and supports transparency, but has a larger file size.",17,18,"IMAGE_FORMAT");
	$db->add_config_key("ENABLE_CATEGORY_IMAGE","Display Image on Category Page (when set)",0,"Requires a defined Category image under SEO settings",0,13,"BOOL");
	$db->add_config_key("SHIP_SAME_BILLSHIP","Require Billing and Shipping Address to Match",0,"Locks the Shipping and Billing are same checkbox to not allow separate shipping address.",25,2,"BOOL");
	$db->add_config_key("DEBUG_LS_SOAP_CALL","Debug SOAP Calls",0,"Debug",1,17,"BOOL");
	$db->add_config_key("STORE_ADDRESS1","Store Address","123 Main St.","Address line 1",2,5,"NULL");
	$db->add_config_key("STORE_ADDRESS2","Store City, State, Postal","Anytown, NY 12345","Address line 2",2,6,"NULL");
	$db->add_config_key("STORE_HOURS","Store Operating Hours","MON-FRI: 9AM-9PM SAT: 11AM-6PM SUN: CLOSED","Store hours. Use &lt;br&gt; tag to create two lines if desired.",2,7,"NULL");
	$db->add_config_key("DEFAULT_TEMPLATE_THEME","Template theme","light","If supported, changable colo(u)rs for template files.",0,2,"DEFAULT_TEMPLATE_THEME");
	$db->add_config_key("GOOGLE_MPN","Product Codes are Manufacturer Part Numbers in Google Shopping",0,"If your Product Codes are Manufacturer Part Numbers, turn this on to apply this to Google Shopping feed.",20,4,"BOOL");
	$db->add_config_key("EMAIL_SUBJECT_WISHLIST","Wishlist Email Subject Line","{storename} Wishlist for {customername}","Configure Email Subject line with variables for Customer Email",24,10,NULL);
	$db->add_config_key("FACEBOOK_APPID","Facebook App ID",'',"Create Facebook AppID",26,1,NULL);
	$db->add_config_key("EMAIL_SUBJECT_CART","Share Cart Email Subject Line","{storename} Cart for {customername}","Configure Email Subject line with variables for Customer Email",24,10,NULL);
	$db->add_config_key("DEBUG_LOGGING","System Logging","error"," ",1,21,"LOGGING");
	$db->add_config_key("SHIPPING_FORMAT","Delivery Speed format","{label} ({price})","Formatting for Delivery Speed. The variables {label} and {price} can be used.",25,5,NULL);

}

function initialDataLoad($db)
{
	$sql = array();


	$sql[] = "insert into xlsws_country set id=39,code='CA', region='NA', active=1, sort_order=2, country='Canada', zip_validate_preg='/^[ABCEGHJKLMNPRSTVXY]\\\d[A-Z]( )?\\\d[A-Z]\\\d$/'";
	$sql[] = "insert into xlsws_country set id=13,code='AU', region='AU', active=1, sort_order=4, country='Australia', zip_validate_preg='/\\\d{4}/'";
	$sql[] = "insert into xlsws_country set id=224,code='US', region='NA', active=1, sort_order=1, country='United States', zip_validate_preg='/^([0-9]{5})(-[0-9]{4})?$/i'";

	$sql[] = "insert into xlsws_country set id=1,code='AF', region='AS', active=1, sort_order=100, country='Afghanistan'";
	$sql[] = "insert into xlsws_country set id=2,code='AL', region='EU', active=1, sort_order=10, country='Albania'";
	$sql[] = "insert into xlsws_country set id=3,code='DZ', region='AF', active=1, sort_order=10, country='Algeria'";
	$sql[] = "insert into xlsws_country set id=4,code='AS', region='AU', active=1, sort_order=10, country='American Samoa'";
	$sql[] = "insert into xlsws_country set id=6,code='AO', region='AF', active=1, sort_order=10, country='Angola'";
	$sql[] = "insert into xlsws_country set id=7,code='AI', region='LA', active=1, sort_order=10, country='Anguilla'";
	$sql[] = "insert into xlsws_country set id=8,code='AQ', region='AN', active=1, sort_order=10, country='Antarctica'";
	$sql[] = "insert into xlsws_country set id=9,code='AG', region='LA', active=1, sort_order=10, country='Antigua and Barbuda'";
	$sql[] = "insert into xlsws_country set id=10,code='AR', region='LA', active=1, sort_order=10, country='Argentina'";
	$sql[] = "insert into xlsws_country set id=11,code='AM', region='AS', active=1, sort_order=10, country='Armenia'";
	$sql[] = "insert into xlsws_country set id=12,code='AW', region='LA', active=1, sort_order=10, country='Aruba'";
	$sql[] = "insert into xlsws_country set id=14,code='AT', region='EU', active=1, sort_order=10, country='Austria'";
	$sql[] = "insert into xlsws_country set id=15,code='AZ', region='AS', active=1, sort_order=10, country='Azerbaijan'";
	$sql[] = "insert into xlsws_country set id=16,code='BS', region='LA', active=1, sort_order=10, country='Bahamas'";
	$sql[] = "insert into xlsws_country set id=17,code='BH', region='AS', active=1, sort_order=10, country='Bahrain'";
	$sql[] = "insert into xlsws_country set id=18,code='BD', region='AS', active=1, sort_order=10, country='Bangladesh'";
	$sql[] = "insert into xlsws_country set id=19,code='BB', region='LA', active=1, sort_order=10, country='Barbados'";
	$sql[] = "insert into xlsws_country set id=20,code='BY', region='EU', active=1, sort_order=10, country='Belarus'";
	$sql[] = "insert into xlsws_country set id=21,code='BE', region='EU', active=1, sort_order=10, country='Belgium'";
	$sql[] = "insert into xlsws_country set id=22,code='BZ', region='LA', active=1, sort_order=10, country='Belize'";
	$sql[] = "insert into xlsws_country set id=23,code='BJ', region='AF', active=1, sort_order=10, country='Benin'";
	$sql[] = "insert into xlsws_country set id=24,code='BM', region='LA', active=1, sort_order=10, country='Bermuda'";
	$sql[] = "insert into xlsws_country set id=25,code='BT', region='AS', active=1, sort_order=10, country='Bhutan'";
	$sql[] = "insert into xlsws_country set id=26,code='BO', region='LA', active=1, sort_order=10, country='Bolivia'";
	$sql[] = "insert into xlsws_country set id=27,code='BA', region='EU', active=1, sort_order=10, country='Bosnia and Herzegowina'";
	$sql[] = "insert into xlsws_country set id=28,code='BW', region='AF', active=1, sort_order=10, country='Botswana'";
	$sql[] = "insert into xlsws_country set id=29,code='BV', region='AN', active=1, sort_order=10, country='Bouvet Island'";
	$sql[] = "insert into xlsws_country set id=30,code='BR', region='LA', active=1, sort_order=10, country='Brazil'";
	$sql[] = "insert into xlsws_country set id=31,code='IO', region='AS', active=1, sort_order=10, country='British Indian Ocean Territory'";
	$sql[] = "insert into xlsws_country set id=32,code='VG', region='LA', active=1, sort_order=10, country='British Virgin Islands'";
	$sql[] = "insert into xlsws_country set id=33,code='BN', region='AS', active=1, sort_order=10, country='Brunei Darussalam'";
	$sql[] = "insert into xlsws_country set id=34,code='BG', region='EU', active=1, sort_order=10, country='Bulgaria'";
	$sql[] = "insert into xlsws_country set id=35,code='BF', region='AF', active=1, sort_order=10, country='Burkina Faso'";
	$sql[] = "insert into xlsws_country set id=36,code='BI', region='AF', active=1, sort_order=10, country='Burundi'";
	$sql[] = "insert into xlsws_country set id=37,code='KH', region='AS', active=1, sort_order=10, country='Cambodia'";
	$sql[] = "insert into xlsws_country set id=38,code='CM', region='AF', active=1, sort_order=10, country='Cameroon'";
	$sql[] = "insert into xlsws_country set id=40,code='CV', region='AF', active=1, sort_order=10, country='Cape Verde'";
	$sql[] = "insert into xlsws_country set id=41,code='KY', region='LA', active=1, sort_order=10, country='Cayman Islands'";
	$sql[] = "insert into xlsws_country set id=42,code='CF', region='AF', active=1, sort_order=10, country='Central African Republic'";
	$sql[] = "insert into xlsws_country set id=43,code='TD', region='AF', active=1, sort_order=10, country='Chad'";
	$sql[] = "insert into xlsws_country set id=44,code='CL', region='LA', active=1, sort_order=10, country='Chile'";
	$sql[] = "insert into xlsws_country set id=45,code='CN', region='AS', active=1, sort_order=10, country='China'";
	$sql[] = "insert into xlsws_country set id=46,code='CX', region='AU', active=1, sort_order=10, country='Christmas Island'";
	$sql[] = "insert into xlsws_country set id=47,code='CC', region='AU', active=1, sort_order=10, country='Cocos (Keeling) Islands'";
	$sql[] = "insert into xlsws_country set id=48,code='CO', region='LA', active=1, sort_order=10, country='Colombia'";
	$sql[] = "insert into xlsws_country set id=49,code='KM', region='AF', active=1, sort_order=10, country='Comoros'";
	$sql[] = "insert into xlsws_country set id=50,code='CG', region='AF', active=1, sort_order=10, country='Congo'";
	$sql[] = "insert into xlsws_country set id=51,code='CK', region='AU', active=1, sort_order=10, country='Cook Islands'";
	$sql[] = "insert into xlsws_country set id=52,code='CR', region='LA', active=1, sort_order=10, country='Costa Rica'";
	$sql[] = "insert into xlsws_country set id=53,code='CI', region='AF', active=1, sort_order=10, country='Cote D\'ivoire'";
	$sql[] = "insert into xlsws_country set id=54,code='HR', region='EU', active=1, sort_order=10, country='Croatia'";
	$sql[] = "insert into xlsws_country set id=55,code='CU', region='LA', active=1, sort_order=10, country='Cuba'";
	$sql[] = "insert into xlsws_country set id=56,code='CY', region='EU', active=1, sort_order=10, country='Cyprus'";
	$sql[] = "insert into xlsws_country set id=57,code='CZ', region='EU', active=1, sort_order=10, country='Czech Republic'";
	$sql[] = "insert into xlsws_country set id=58,code='DK', region='EU', active=1, sort_order=10, country='Denmark'";
	$sql[] = "insert into xlsws_country set id=59,code='DJ', region='AF', active=1, sort_order=10, country='Djibouti'";
	$sql[] = "insert into xlsws_country set id=60,code='DM', region='LA', active=1, sort_order=10, country='Dominica'";
	$sql[] = "insert into xlsws_country set id=61,code='DO', region='LA', active=1, sort_order=10, country='Dominican Republic'";
	$sql[] = "insert into xlsws_country set id=62,code='TP', region='AS', active=1, sort_order=10, country='East Timor'";
	$sql[] = "insert into xlsws_country set id=63,code='EC', region='LA', active=1, sort_order=10, country='Ecuador'";
	$sql[] = "insert into xlsws_country set id=64,code='EG', region='AF', active=1, sort_order=10, country='Egypt'";
	$sql[] = "insert into xlsws_country set id=65,code='SV', region='LA', active=1, sort_order=10, country='El Salvador'";
	$sql[] = "insert into xlsws_country set id=66,code='GQ', region='AF', active=1, sort_order=10, country='Equatorial Guinea'";
	$sql[] = "insert into xlsws_country set id=67,code='ER', region='AF', active=1, sort_order=10, country='Eritrea'";
	$sql[] = "insert into xlsws_country set id=68,code='EE', region='EU', active=1, sort_order=10, country='Estonia'";
	$sql[] = "insert into xlsws_country set id=69,code='ET', region='AF', active=1, sort_order=10, country='Ethiopia'";
	$sql[] = "insert into xlsws_country set id=70,code='FK', region='LA', active=1, sort_order=10, country='Falkland Islands (Malvinas)'";
	$sql[] = "insert into xlsws_country set id=71,code='FO', region='EU', active=1, sort_order=10, country='Faroe Islands'";
	$sql[] = "insert into xlsws_country set id=72,code='FJ', region='AU', active=1, sort_order=10, country='Fiji'";
	$sql[] = "insert into xlsws_country set id=73,code='FI', region='EU', active=1, sort_order=10, country='Finland'";
	$sql[] = "insert into xlsws_country set id=74,code='FR', region='EU', active=1, sort_order=10, country='France'";
	$sql[] = "insert into xlsws_country set id=76,code='GF', region='LA', active=1, sort_order=10, country='French Guiana'";
	$sql[] = "insert into xlsws_country set id=77,code='PF', region='AU', active=1, sort_order=10, country='French Polynesia'";
	$sql[] = "insert into xlsws_country set id=78,code='TF', region='AN', active=1, sort_order=10, country='French Southern Territories'";
	$sql[] = "insert into xlsws_country set id=79,code='GA', region='AF', active=1, sort_order=10, country='Gabon'";
	$sql[] = "insert into xlsws_country set id=80,code='GE', region='AS', active=1, sort_order=10, country='Georgia'";
	$sql[] = "insert into xlsws_country set id=81,code='GM', region='AF', active=1, sort_order=10, country='Gambia'";
	$sql[] = "insert into xlsws_country set id=82,code='PS', region='AS', active=1, sort_order=10, country='Palestine Authority'";
	$sql[] = "insert into xlsws_country set id=83,code='DE', region='EU', active=1, sort_order=10, country='Germany'";
	$sql[] = "insert into xlsws_country set id=84,code='GH', region='AF', active=1, sort_order=10, country='Ghana'";
	$sql[] = "insert into xlsws_country set id=85,code='GI', region='EU', active=1, sort_order=10, country='Gibraltar'";
	$sql[] = "insert into xlsws_country set id=86,code='GR', region='EU', active=1, sort_order=10, country='Greece'";
	$sql[] = "insert into xlsws_country set id=87,code='GL', region='NA', active=1, sort_order=10, country='Greenland'";
	$sql[] = "insert into xlsws_country set id=88,code='GD', region='LA', active=1, sort_order=10, country='Grenada'";
	$sql[] = "insert into xlsws_country set id=89,code='GP', region='LA', active=1, sort_order=10, country='Guadeloupe'";
	$sql[] = "insert into xlsws_country set id=90,code='GU', region='AU', active=1, sort_order=10, country='Guam'";
	$sql[] = "insert into xlsws_country set id=91,code='GT', region='LA', active=1, sort_order=10, country='Guatemala'";
	$sql[] = "insert into xlsws_country set id=92,code='GN', region='AF', active=1, sort_order=10, country='Guinea'";
	$sql[] = "insert into xlsws_country set id=93,code='GW', region='AF', active=1, sort_order=10, country='Guinea-Bissau'";
	$sql[] = "insert into xlsws_country set id=94,code='GY', region='LA', active=1, sort_order=10, country='Guyana'";
	$sql[] = "insert into xlsws_country set id=95,code='HT', region='LA', active=1, sort_order=10, country='Haiti'";
	$sql[] = "insert into xlsws_country set id=96,code='HM', region='AU', active=1, sort_order=10, country='Heard and McDonald Islands'";
	$sql[] = "insert into xlsws_country set id=97,code='HN', region='LA', active=1, sort_order=10, country='Honduras'";
	$sql[] = "insert into xlsws_country set id=98,code='HK', region='AS', active=1, sort_order=10, country='Hong Kong'";
	$sql[] = "insert into xlsws_country set id=99,code='HU', region='EU', active=1, sort_order=10, country='Hungary'";
	$sql[] = "insert into xlsws_country set id=100,code='IS', region='EU', active=1, sort_order=10, country='Iceland'";
	$sql[] = "insert into xlsws_country set id=101,code='IN', region='AS', active=1, sort_order=10, country='India'";
	$sql[] = "insert into xlsws_country set id=102,code='ID', region='AS', active=1, sort_order=10, country='Indonesia'";
	$sql[] = "insert into xlsws_country set id=103,code='IQ', region='AS', active=1, sort_order=10, country='Iraq'";
	$sql[] = "insert into xlsws_country set id=104,code='IE', region='EU', active=1, sort_order=10, country='Ireland'";
	$sql[] = "insert into xlsws_country set id=105,code='IR', region='AS', active=1, sort_order=10, country='Iran'";
	$sql[] = "insert into xlsws_country set id=106,code='IL', region='AS', active=1, sort_order=10, country='Israel'";
	$sql[] = "insert into xlsws_country set id=107,code='IT', region='EU', active=1, sort_order=10, country='Italy'";
	$sql[] = "insert into xlsws_country set id=108,code='JM', region='LA', active=1, sort_order=10, country='Jamaica'";
	$sql[] = "insert into xlsws_country set id=109,code='JP', region='AS', active=1, sort_order=10, country='Japan'";
	$sql[] = "insert into xlsws_country set id=110,code='JO', region='AS', active=1, sort_order=10, country='Jordan'";
	$sql[] = "insert into xlsws_country set id=111,code='KZ', region='AS', active=1, sort_order=10, country='Kazakhstan'";
	$sql[] = "insert into xlsws_country set id=112,code='KE', region='AF', active=1, sort_order=10, country='Kenya'";
	$sql[] = "insert into xlsws_country set id=113,code='KI', region='AU', active=1, sort_order=10, country='Kiribati'";
	$sql[] = "insert into xlsws_country set id=114,code='KP', region='AS', active=1, sort_order=10, country='Korea'";
	$sql[] = "insert into xlsws_country set id=115,code='KR', region='AS', active=1, sort_order=10, country='Korea, Republic of'";
	$sql[] = "insert into xlsws_country set id=116,code='KW', region='AS', active=1, sort_order=10, country='Kuwait'";
	$sql[] = "insert into xlsws_country set id=117,code='KG', region='AS', active=1, sort_order=10, country='Kyrgyzstan'";
	$sql[] = "insert into xlsws_country set id=118,code='LA', region='AS', active=1, sort_order=10, country='Laos'";
	$sql[] = "insert into xlsws_country set id=119,code='LV', region='EU', active=1, sort_order=10, country='Latvia'";
	$sql[] = "insert into xlsws_country set id=120,code='LB', region='AS', active=1, sort_order=10, country='Lebanon'";
	$sql[] = "insert into xlsws_country set id=121,code='LS', region='AF', active=1, sort_order=10, country='Lesotho'";
	$sql[] = "insert into xlsws_country set id=122,code='LR', region='AF', active=1, sort_order=10, country='Liberia'";
	$sql[] = "insert into xlsws_country set id=123,code='LY', region='AF', active=1, sort_order=10, country='Libyan Arab Jamahiriya'";
	$sql[] = "insert into xlsws_country set id=124,code='LI', region='EU', active=1, sort_order=10, country='Liechtenstein'";
	$sql[] = "insert into xlsws_country set id=125,code='LT', region='EU', active=1, sort_order=10, country='Lithuania'";
	$sql[] = "insert into xlsws_country set id=126,code='LU', region='EU', active=1, sort_order=10, country='Luxembourg'";
	$sql[] = "insert into xlsws_country set id=127,code='MO', region='AS', active=1, sort_order=10, country='Macau'";
	$sql[] = "insert into xlsws_country set id=128,code='MK', region='EU', active=1, sort_order=10, country='Macedonia'";
	$sql[] = "insert into xlsws_country set id=129,code='MG', region='AF', active=1, sort_order=10, country='Madagascar'";
	$sql[] = "insert into xlsws_country set id=130,code='MW', region='AF', active=1, sort_order=10, country='Malawi'";
	$sql[] = "insert into xlsws_country set id=131,code='MY', region='AS', active=1, sort_order=10, country='Malaysia'";
	$sql[] = "insert into xlsws_country set id=132,code='MV', region='AS', active=1, sort_order=10, country='Maldives'";
	$sql[] = "insert into xlsws_country set id=133,code='ML', region='AF', active=1, sort_order=10, country='Mali'";
	$sql[] = "insert into xlsws_country set id=134,code='MT', region='EU', active=1, sort_order=10, country='Malta'";
	$sql[] = "insert into xlsws_country set id=135,code='MH', region='AU', active=1, sort_order=10, country='Marshall Islands'";
	$sql[] = "insert into xlsws_country set id=136,code='MQ', region='LA', active=1, sort_order=10, country='Martinique'";
	$sql[] = "insert into xlsws_country set id=137,code='MR', region='AF', active=1, sort_order=10, country='Mauritania'";
	$sql[] = "insert into xlsws_country set id=138,code='MU', region='AF', active=1, sort_order=10, country='Mauritius'";
	$sql[] = "insert into xlsws_country set id=139,code='YT', region='AF', active=1, sort_order=10, country='Mayotte'";
	$sql[] = "insert into xlsws_country set id=140,code='MX', region='LA', active=1, sort_order=10, country='Mexico'";
	$sql[] = "insert into xlsws_country set id=141,code='FM', region='AU', active=1, sort_order=10, country='Micronesia'";
	$sql[] = "insert into xlsws_country set id=142,code='MD', region='EU', active=1, sort_order=10, country='Moldova, Republic of'";
	$sql[] = "insert into xlsws_country set id=143,code='MC', region='EU', active=1, sort_order=10, country='Monaco'";
	$sql[] = "insert into xlsws_country set id=144,code='MN', region='AS', active=1, sort_order=10, country='Mongolia'";
	$sql[] = "insert into xlsws_country set id=145,code='MS', region='LA', active=1, sort_order=10, country='Montserrat'";
	$sql[] = "insert into xlsws_country set id=146,code='MA', region='AF', active=1, sort_order=10, country='Morocco'";
	$sql[] = "insert into xlsws_country set id=147,code='MZ', region='AF', active=1, sort_order=10, country='Mozambique'";
	$sql[] = "insert into xlsws_country set id=148,code='MM', region='AS', active=1, sort_order=10, country='Myanmar'";
	$sql[] = "insert into xlsws_country set id=149,code='NA', region='AF', active=1, sort_order=10, country='Namibia'";
	$sql[] = "insert into xlsws_country set id=150,code='NR', region='AU', active=1, sort_order=10, country='Nauru'";
	$sql[] = "insert into xlsws_country set id=151,code='NP', region='AS', active=1, sort_order=10, country='Nepal'";
	$sql[] = "insert into xlsws_country set id=152,code='NL', region='EU', active=1, sort_order=10, country='Netherlands'";
	$sql[] = "insert into xlsws_country set id=153,code='AN', region='LA', active=1, sort_order=10, country='Netherlands Antilles'";
	$sql[] = "insert into xlsws_country set id=154,code='NC', region='AU', active=1, sort_order=10, country='New Caledonia'";
	$sql[] = "insert into xlsws_country set id=155,code='NZ', region='AU', active=1, sort_order=10, country='New Zealand'";
	$sql[] = "insert into xlsws_country set id=156,code='NI', region='LA', active=1, sort_order=10, country='Nicaragua'";
	$sql[] = "insert into xlsws_country set id=157,code='NE', region='AF', active=1, sort_order=10, country='Niger'";
	$sql[] = "insert into xlsws_country set id=158,code='NG', region='AF', active=1, sort_order=10, country='Nigeria'";
	$sql[] = "insert into xlsws_country set id=159,code='NU', region='AU', active=1, sort_order=10, country='Niue'";
	$sql[] = "insert into xlsws_country set id=160,code='NF', region='AU', active=1, sort_order=10, country='Norfolk Island'";
	$sql[] = "insert into xlsws_country set id=161,code='MP', region='AU', active=1, sort_order=10, country='Northern Mariana Islands'";
	$sql[] = "insert into xlsws_country set id=162,code='NO', region='EU', active=1, sort_order=10, country='Norway'";
	$sql[] = "insert into xlsws_country set id=163,code='OM', region='AS', active=1, sort_order=10, country='Oman'";
	$sql[] = "insert into xlsws_country set id=164,code='PK', region='AS', active=1, sort_order=10, country='Pakistan'";
	$sql[] = "insert into xlsws_country set id=165,code='PW', region='AU', active=1, sort_order=10, country='Palau'";
	$sql[] = "insert into xlsws_country set id=166,code='PA', region='LA', active=1, sort_order=10, country='Panama'";
	$sql[] = "insert into xlsws_country set id=167,code='PG', region='AS', active=1, sort_order=10, country='Papua New Guinea'";
	$sql[] = "insert into xlsws_country set id=168,code='PY', region='LA', active=1, sort_order=10, country='Paraguay'";
	$sql[] = "insert into xlsws_country set id=169,code='PE', region='LA', active=1, sort_order=10, country='Peru'";
	$sql[] = "insert into xlsws_country set id=170,code='PH', region='AS', active=1, sort_order=10, country='Philippines'";
	$sql[] = "insert into xlsws_country set id=171,code='PN', region='AU', active=1, sort_order=10, country='Pitcairn'";
	$sql[] = "insert into xlsws_country set id=172,code='PL', region='EU', active=1, sort_order=10, country='Poland'";
	$sql[] = "insert into xlsws_country set id=173,code='PT', region='EU', active=1, sort_order=10, country='Portugal'";
	$sql[] = "insert into xlsws_country set id=174,code='PR', region='LA', active=1, sort_order=10, country='Puerto Rico'";
	$sql[] = "insert into xlsws_country set id=175,code='QA', region='AS', active=1, sort_order=10, country='Qatar'";
	$sql[] = "insert into xlsws_country set id=176,code='RE', region='AF', active=1, sort_order=10, country='Reunion'";
	$sql[] = "insert into xlsws_country set id=177,code='RO', region='EU', active=1, sort_order=10, country='Romania'";
	$sql[] = "insert into xlsws_country set id=178,code='RU', region='EU', active=1, sort_order=10, country='Russia'";
	$sql[] = "insert into xlsws_country set id=179,code='RW', region='AF', active=1, sort_order=10, country='Rwanda'";
	$sql[] = "insert into xlsws_country set id=180,code='LC', region='LA', active=1, sort_order=10, country='Saint Lucia'";
	$sql[] = "insert into xlsws_country set id=181,code='WS', region='AU', active=1, sort_order=10, country='Samoa'";
	$sql[] = "insert into xlsws_country set id=182,code='SM', region='EU', active=1, sort_order=10, country='San Marino'";
	$sql[] = "insert into xlsws_country set id=183,code='ST', region='AF', active=1, sort_order=10, country='Sao Tome and Principe'";
	$sql[] = "insert into xlsws_country set id=184,code='SA', region='AS', active=1, sort_order=10, country='Saudi Arabia'";
	$sql[] = "insert into xlsws_country set id=185,code='SN', region='AF', active=1, sort_order=10, country='Senegal'";
	$sql[] = "insert into xlsws_country set id=186,code='SC', region='AF', active=1, sort_order=10, country='Seychelles'";
	$sql[] = "insert into xlsws_country set id=187,code='SL', region='AF', active=1, sort_order=10, country='Sierra Leone'";
	$sql[] = "insert into xlsws_country set id=188,code='SG', region='AS', active=1, sort_order=10, country='Singapore'";
	$sql[] = "insert into xlsws_country set id=189,code='SK', region='EU', active=1, sort_order=10, country='Slovakia'";
	$sql[] = "insert into xlsws_country set id=190,code='SI', region='EU', active=1, sort_order=10, country='Slovenia'";
	$sql[] = "insert into xlsws_country set id=191,code='SB', region='AU', active=1, sort_order=10, country='Solomon Islands'";
	$sql[] = "insert into xlsws_country set id=192,code='SO', region='AF', active=1, sort_order=10, country='Somalia'";
	$sql[] = "insert into xlsws_country set id=193,code='ZA', region='AF', active=1, sort_order=10, country='South Africa'";
	$sql[] = "insert into xlsws_country set id=194,code='ES', region='EU', active=1, sort_order=10, country='Spain'";
	$sql[] = "insert into xlsws_country set id=195,code='LK', region='AS', active=1, sort_order=10, country='Sri Lanka'";
	$sql[] = "insert into xlsws_country set id=196,code='SH', region='AF', active=1, sort_order=10, country='St. Helena'";
	$sql[] = "insert into xlsws_country set id=197,code='KN', region='LA', active=1, sort_order=10, country='St. Kitts and Nevis'";
	$sql[] = "insert into xlsws_country set id=198,code='PM', region='NA', active=1, sort_order=10, country='St. Pierre and Miquelon'";
	$sql[] = "insert into xlsws_country set id=199,code='VC', region='LA', active=1, sort_order=10, country='St. Vincent and the Grenadines'";
	$sql[] = "insert into xlsws_country set id=200,code='SD', region='AF', active=1, sort_order=10, country='Sudan'";
	$sql[] = "insert into xlsws_country set id=201,code='SR', region='LA', active=1, sort_order=10, country='Suriname'";
	$sql[] = "insert into xlsws_country set id=202,code='SJ', region='EU', active=1, sort_order=10, country='Svalbard and Jan Mayen Islands'";
	$sql[] = "insert into xlsws_country set id=203,code='SZ', region='AF', active=1, sort_order=10, country='Swaziland'";
	$sql[] = "insert into xlsws_country set id=204,code='SE', region='EU', active=1, sort_order=10, country='Sweden'";
	$sql[] = "insert into xlsws_country set id=205,code='CH', region='EU', active=1, sort_order=10, country='Switzerland'";
	$sql[] = "insert into xlsws_country set id=206,code='SY', region='AS', active=1, sort_order=10, country='Syrian Arab Republic'";
	$sql[] = "insert into xlsws_country set id=207,code='TW', region='AS', active=1, sort_order=10, country='Taiwan'";
	$sql[] = "insert into xlsws_country set id=208,code='TJ', region='AS', active=1, sort_order=10, country='Tajikistan'";
	$sql[] = "insert into xlsws_country set id=209,code='TZ', region='AF', active=1, sort_order=10, country='Tanzania, United Republic of'";
	$sql[] = "insert into xlsws_country set id=210,code='TH', region='AS', active=1, sort_order=10, country='Thailand'";
	$sql[] = "insert into xlsws_country set id=211,code='TG', region='AF', active=1, sort_order=10, country='Togo'";
	$sql[] = "insert into xlsws_country set id=212,code='TK', region='AU', active=1, sort_order=10, country='Tokelau'";
	$sql[] = "insert into xlsws_country set id=213,code='TO', region='AU', active=1, sort_order=10, country='Tonga'";
	$sql[] = "insert into xlsws_country set id=214,code='TT', region='LA', active=1, sort_order=10, country='Trinidad and Tobago'";
	$sql[] = "insert into xlsws_country set id=215,code='TN', region='AF', active=1, sort_order=10, country='Tunisia'";
	$sql[] = "insert into xlsws_country set id=216,code='TR', region='EU', active=1, sort_order=10, country='Turkey'";
	$sql[] = "insert into xlsws_country set id=217,code='TM', region='AS', active=1, sort_order=10, country='Turkmenistan'";
	$sql[] = "insert into xlsws_country set id=218,code='TC', region='LA', active=1, sort_order=10, country='Turks and Caicos Islands'";
	$sql[] = "insert into xlsws_country set id=219,code='TV', region='AU', active=1, sort_order=10, country='Tuvalu'";
	$sql[] = "insert into xlsws_country set id=220,code='UG', region='AF', active=1, sort_order=10, country='Uganda'";
	$sql[] = "insert into xlsws_country set id=221,code='UA', region='EU', active=1, sort_order=10, country='Ukraine'";
	$sql[] = "insert into xlsws_country set id=222,code='AE', region='AS', active=1, sort_order=10, country='United Arab Emirates'";
	$sql[] = "insert into xlsws_country set id=223,code='GB', region='EU', active=1, sort_order=3, country='United Kingdom (Great Britain)'";
	$sql[] = "insert into xlsws_country set id=225,code='VI', region='LA', active=1, sort_order=10, country='United States Virgin Islands'";
	$sql[] = "insert into xlsws_country set id=226,code='UY', region='LA', active=1, sort_order=10, country='Uruguay'";
	$sql[] = "insert into xlsws_country set id=227,code='UZ', region='AS', active=1, sort_order=10, country='Uzbekistan'";
	$sql[] = "insert into xlsws_country set id=228,code='VU', region='AU', active=1, sort_order=10, country='Vanuatu'";
	$sql[] = "insert into xlsws_country set id=229,code='VA', region='EU', active=1, sort_order=10, country='Vatican City State'";
	$sql[] = "insert into xlsws_country set id=230,code='VE', region='LA', active=1, sort_order=10, country='Venezuela'";
	$sql[] = "insert into xlsws_country set id=231,code='VN', region='AS', active=1, sort_order=10, country='Viet Nam'";
	$sql[] = "insert into xlsws_country set id=232,code='WF', region='AU', active=1, sort_order=10, country='Wallis And Futuna Islands'";
	$sql[] = "insert into xlsws_country set id=233,code='EH', region='AF', active=1, sort_order=10, country='Western Sahara'";
	$sql[] = "insert into xlsws_country set id=234,code='YE', region='AS', active=1, sort_order=10, country='Yemen'";
	$sql[] = "insert into xlsws_country set id=235,code='CS', region='EU', active=1, sort_order=10, country='Serbia and Montenegro'";
	$sql[] = "insert into xlsws_country set id=236,code='ZR', region='AF', active=1, sort_order=10, country='Zaire'";
	$sql[] = "insert into xlsws_country set id=237,code='ZM', region='AF', active=1, sort_order=10, country='Zambia'";
	$sql[] = "insert into xlsws_country set id=238,code='ZW', region='AF', active=1, sort_order=10, country='Zimbabwe'";
	$sql[] = "insert into xlsws_country set id=239,code='AP', region='', active=1, sort_order=10, country='Asia-Pacific'";
	$sql[] = "insert into xlsws_country set id=240,code='RS', region='', active=1, sort_order=10, country='Republic of Serbia'";
	$sql[] = "insert into xlsws_country set id=241,code='AX', region='', active=1, sort_order=10, country='Aland Islands'";
	$sql[] = "insert into xlsws_country set id=242,code='EU', region='', active=1, sort_order=10, country='Europe'";


	$sql[] = "insert into xlsws_state set id=10, country_id=224, country_code='US', code='AL', active=1, sort_order=10, state='Alabama'";
	$sql[] = "insert into xlsws_state set id=11, country_id=224, country_code='US', code='AK', active=1, sort_order=10, state='Alaska'";
	$sql[] = "insert into xlsws_state set id=12, country_id=224, country_code='US', code='AZ', active=1, sort_order=10, state='Arizona'";
	$sql[] = "insert into xlsws_state set id=13, country_id=224, country_code='US', code='AR', active=1, sort_order=10, state='Arkansas'";
	$sql[] = "insert into xlsws_state set id=15, country_id=224, country_code='US', code='CA', active=1, sort_order=10, state='California'";
	$sql[] = "insert into xlsws_state set id=16, country_id=224, country_code='US', code='CO', active=1, sort_order=10, state='Colorado'";
	$sql[] = "insert into xlsws_state set id=17, country_id=224, country_code='US', code='CT', active=1, sort_order=10, state='Connecticut'";
	$sql[] = "insert into xlsws_state set id=18, country_id=224, country_code='US', code='DE', active=1, sort_order=10, state='Delaware'";
	$sql[] = "insert into xlsws_state set id=19, country_id=224, country_code='US', code='DC', active=1, sort_order=10, state='District of Columbia'";
	$sql[] = "insert into xlsws_state set id=20, country_id=224, country_code='US', code='FL', active=1, sort_order=10, state='Florida'";
	$sql[] = "insert into xlsws_state set id=21, country_id=224, country_code='US', code='GA', active=1, sort_order=10, state='Georgia'";
	$sql[] = "insert into xlsws_state set id=22, country_id=224, country_code='US', code='GU', active=1, sort_order=10, state='Guam'";
	$sql[] = "insert into xlsws_state set id=23, country_id=224, country_code='US', code='HI', active=1, sort_order=10, state='Hawaii'";
	$sql[] = "insert into xlsws_state set id=24, country_id=224, country_code='US', code='ID', active=1, sort_order=10, state='Idaho'";
	$sql[] = "insert into xlsws_state set id=25, country_id=224, country_code='US', code='IL', active=1, sort_order=10, state='Illinois'";
	$sql[] = "insert into xlsws_state set id=26, country_id=224, country_code='US', code='IN', active=1, sort_order=10, state='Indiana'";
	$sql[] = "insert into xlsws_state set id=27, country_id=224, country_code='US', code='IA', active=1, sort_order=10, state='Iowa'";
	$sql[] = "insert into xlsws_state set id=28, country_id=224, country_code='US', code='KS', active=1, sort_order=10, state='Kansas'";
	$sql[] = "insert into xlsws_state set id=29, country_id=224, country_code='US', code='KY', active=1, sort_order=10, state='Kentucky'";
	$sql[] = "insert into xlsws_state set id=30, country_id=224, country_code='US', code='LA', active=1, sort_order=10, state='Louisiana'";
	$sql[] = "insert into xlsws_state set id=31, country_id=224, country_code='US', code='ME', active=1, sort_order=10, state='Maine'";
	$sql[] = "insert into xlsws_state set id=32, country_id=224, country_code='US', code='MD', active=1, sort_order=10, state='Maryland'";
	$sql[] = "insert into xlsws_state set id=33, country_id=224, country_code='US', code='MA', active=1, sort_order=10, state='Massachusetts'";
	$sql[] = "insert into xlsws_state set id=34, country_id=224, country_code='US', code='MI', active=1, sort_order=10, state='Michigan'";
	$sql[] = "insert into xlsws_state set id=35, country_id=224, country_code='US', code='MN', active=1, sort_order=10, state='Minnesota'";
	$sql[] = "insert into xlsws_state set id=36, country_id=224, country_code='US', code='MS', active=1, sort_order=10, state='Mississippi'";
	$sql[] = "insert into xlsws_state set id=37, country_id=224, country_code='US', code='MO', active=1, sort_order=10, state='Missouri'";
	$sql[] = "insert into xlsws_state set id=38, country_id=224, country_code='US', code='MT', active=1, sort_order=10, state='Montana'";
	$sql[] = "insert into xlsws_state set id=39, country_id=224, country_code='US', code='NE', active=1, sort_order=10, state='Nebraska'";
	$sql[] = "insert into xlsws_state set id=40, country_id=224, country_code='US', code='NV', active=1, sort_order=10, state='Nevada'";
	$sql[] = "insert into xlsws_state set id=41, country_id=224, country_code='US', code='NH', active=1, sort_order=10, state='New Hampshire'";
	$sql[] = "insert into xlsws_state set id=42, country_id=224, country_code='US', code='NJ', active=1, sort_order=10, state='New Jersey'";
	$sql[] = "insert into xlsws_state set id=43, country_id=224, country_code='US', code='NM', active=1, sort_order=10, state='New Mexico'";
	$sql[] = "insert into xlsws_state set id=44, country_id=224, country_code='US', code='NY', active=1, sort_order=10, state='New York'";
	$sql[] = "insert into xlsws_state set id=45, country_id=224, country_code='US', code='NC', active=1, sort_order=10, state='North Carolina'";
	$sql[] = "insert into xlsws_state set id=46, country_id=224, country_code='US', code='ND', active=1, sort_order=10, state='North Dakota'";
	$sql[] = "insert into xlsws_state set id=47, country_id=224, country_code='US', code='OH', active=1, sort_order=10, state='Ohio'";
	$sql[] = "insert into xlsws_state set id=48, country_id=224, country_code='US', code='OK', active=1, sort_order=10, state='Oklahoma'";
	$sql[] = "insert into xlsws_state set id=49, country_id=224, country_code='US', code='OR', active=1, sort_order=10, state='Oregon'";
	$sql[] = "insert into xlsws_state set id=50, country_id=224, country_code='US', code='PA', active=1, sort_order=10, state='Pennsylvania'";
	$sql[] = "insert into xlsws_state set id=51, country_id=224, country_code='US', code='PR', active=1, sort_order=10, state='Puerto Rico'";
	$sql[] = "insert into xlsws_state set id=52, country_id=224, country_code='US', code='RI', active=1, sort_order=10, state='Rhode Island'";
	$sql[] = "insert into xlsws_state set id=53, country_id=224, country_code='US', code='SC', active=1, sort_order=10, state='South Carolina'";
	$sql[] = "insert into xlsws_state set id=54, country_id=224, country_code='US', code='SD', active=1, sort_order=10, state='South Dakota'";
	$sql[] = "insert into xlsws_state set id=55, country_id=224, country_code='US', code='TN', active=1, sort_order=10, state='Tennessee'";
	$sql[] = "insert into xlsws_state set id=56, country_id=224, country_code='US', code='TX', active=1, sort_order=10, state='Texas'";
	$sql[] = "insert into xlsws_state set id=57, country_id=224, country_code='US', code='UT', active=1, sort_order=10, state='Utah'";
	$sql[] = "insert into xlsws_state set id=58, country_id=224, country_code='US', code='VT', active=1, sort_order=10, state='Vermont'";
	$sql[] = "insert into xlsws_state set id=59, country_id=224, country_code='US', code='VI', active=1, sort_order=10, state='Virgin Islands'";
	$sql[] = "insert into xlsws_state set id=60, country_id=224, country_code='US', code='VA', active=1, sort_order=10, state='Virginia'";
	$sql[] = "insert into xlsws_state set id=61, country_id=224, country_code='US', code='WA', active=1, sort_order=10, state='Washington'";
	$sql[] = "insert into xlsws_state set id=62, country_id=224, country_code='US', code='WV', active=1, sort_order=10, state='West Virginia'";
	$sql[] = "insert into xlsws_state set id=63, country_id=224, country_code='US', code='WI', active=1, sort_order=10, state='Wisconsin'";
	$sql[] = "insert into xlsws_state set id=64, country_id=224, country_code='US', code='WY', active=1, sort_order=10, state='Wyoming'";
	$sql[] = "insert into xlsws_state set id=66, country_id=74, country_code='FR', code='2', active=1, sort_order=10, state='Aisne'";
	$sql[] = "insert into xlsws_state set id=67, country_id=74, country_code='FR', code='3', active=1, sort_order=10, state='Allier'";
	$sql[] = "insert into xlsws_state set id=68, country_id=74, country_code='FR', code='4', active=1, sort_order=10, state='Alpes-de-Haute-Provence'";
	$sql[] = "insert into xlsws_state set id=69, country_id=74, country_code='FR', code='6', active=1, sort_order=10, state='Alpes-Maritimes'";
	$sql[] = "insert into xlsws_state set id=70, country_id=74, country_code='FR', code='7', active=1, sort_order=10, state='Ardèche'";
	$sql[] = "insert into xlsws_state set id=71, country_id=74, country_code='FR', code='8', active=1, sort_order=10, state='Ardennes'";
	$sql[] = "insert into xlsws_state set id=72, country_id=74, country_code='FR', code='9', active=1, sort_order=10, state='Ariège'";
	$sql[] = "insert into xlsws_state set id=73, country_id=74, country_code='FR', code='10', active=1, sort_order=10, state='Aube'";
	$sql[] = "insert into xlsws_state set id=74, country_id=74, country_code='FR', code='1', active=1, sort_order=10, state='Ain'";
	$sql[] = "insert into xlsws_state set id=75, country_id=74, country_code='FR', code='11', active=1, sort_order=10, state='Aude'";
	$sql[] = "insert into xlsws_state set id=76, country_id=74, country_code='FR', code='12', active=1, sort_order=10, state='Aveyron'";
	$sql[] = "insert into xlsws_state set id=77, country_id=74, country_code='FR', code='13', active=1, sort_order=10, state='Bouches-du-Rhône'";
	$sql[] = "insert into xlsws_state set id=78, country_id=74, country_code='FR', code='14', active=1, sort_order=10, state='Calvados'";
	$sql[] = "insert into xlsws_state set id=79, country_id=74, country_code='FR', code='15', active=1, sort_order=10, state='Cantal'";
	$sql[] = "insert into xlsws_state set id=80, country_id=74, country_code='FR', code='16', active=1, sort_order=10, state='Charente'";
	$sql[] = "insert into xlsws_state set id=81, country_id=74, country_code='FR', code='17', active=1, sort_order=10, state='Charente-Maritime'";
	$sql[] = "insert into xlsws_state set id=82, country_id=74, country_code='FR', code='18', active=1, sort_order=10, state='Cher'";
	$sql[] = "insert into xlsws_state set id=83, country_id=74, country_code='FR', code='19', active=1, sort_order=10, state='Corrèze'";
	$sql[] = "insert into xlsws_state set id=84, country_id=74, country_code='FR', code='2A', active=1, sort_order=10, state='Corse-du-Sud'";
	$sql[] = "insert into xlsws_state set id=85, country_id=74, country_code='FR', code='21', active=1, sort_order=10, state='Côte-d\'Or'";
	$sql[] = "insert into xlsws_state set id=86, country_id=74, country_code='FR', code='22', active=1, sort_order=10, state='Côtes-d\'Armor'";
	$sql[] = "insert into xlsws_state set id=87, country_id=74, country_code='FR', code='23', active=1, sort_order=10, state='Creuse'";
	$sql[] = "insert into xlsws_state set id=88, country_id=74, country_code='FR', code='24', active=1, sort_order=10, state='Dordogne'";
	$sql[] = "insert into xlsws_state set id=89, country_id=74, country_code='FR', code='25', active=1, sort_order=10, state='Doubs'";
	$sql[] = "insert into xlsws_state set id=90, country_id=74, country_code='FR', code='26', active=1, sort_order=10, state='Drôme'";
	$sql[] = "insert into xlsws_state set id=91, country_id=74, country_code='FR', code='91', active=1, sort_order=10, state='Essonne'";
	$sql[] = "insert into xlsws_state set id=92, country_id=74, country_code='FR', code='27', active=1, sort_order=10, state='Eure'";
	$sql[] = "insert into xlsws_state set id=93, country_id=74, country_code='FR', code='28', active=1, sort_order=10, state='Eure-et-Loir'";
	$sql[] = "insert into xlsws_state set id=94, country_id=74, country_code='FR', code='29', active=1, sort_order=10, state='Finistére'";
	$sql[] = "insert into xlsws_state set id=95, country_id=74, country_code='FR', code='30', active=1, sort_order=10, state='Gard'";
	$sql[] = "insert into xlsws_state set id=96, country_id=74, country_code='FR', code='32', active=1, sort_order=10, state='Gers'";
	$sql[] = "insert into xlsws_state set id=97, country_id=74, country_code='FR', code='33', active=1, sort_order=10, state='Gironde'";
	$sql[] = "insert into xlsws_state set id=98, country_id=74, country_code='FR', code='2B', active=1, sort_order=10, state='Haute-Corse'";
	$sql[] = "insert into xlsws_state set id=99, country_id=74, country_code='FR', code='31', active=1, sort_order=10, state='Haute-Garonne'";
	$sql[] = "insert into xlsws_state set id=100, country_id=74, country_code='FR', code='43', active=1, sort_order=10, state='Haute-Loire'";
	$sql[] = "insert into xlsws_state set id=101, country_id=74, country_code='FR', code='52', active=1, sort_order=10, state='Haute-Marne'";
	$sql[] = "insert into xlsws_state set id=102, country_id=74, country_code='FR', code='87', active=1, sort_order=10, state='Haute-Vienne'";
	$sql[] = "insert into xlsws_state set id=103, country_id=74, country_code='FR', code='5', active=1, sort_order=10, state='Haute-Vienne'";
	$sql[] = "insert into xlsws_state set id=104, country_id=74, country_code='FR', code='92', active=1, sort_order=10, state='Hauts-de-Seine'";
	$sql[] = "insert into xlsws_state set id=105, country_id=74, country_code='FR', code='34', active=1, sort_order=10, state='Hérault'";
	$sql[] = "insert into xlsws_state set id=106, country_id=74, country_code='FR', code='35', active=1, sort_order=10, state='Ille-et-Vilaine'";
	$sql[] = "insert into xlsws_state set id=107, country_id=74, country_code='FR', code='36', active=1, sort_order=10, state='Indre'";
	$sql[] = "insert into xlsws_state set id=108, country_id=74, country_code='FR', code='37', active=1, sort_order=10, state='Indre-et-Loire'";
	$sql[] = "insert into xlsws_state set id=109, country_id=74, country_code='FR', code='38', active=1, sort_order=10, state='Isère'";
	$sql[] = "insert into xlsws_state set id=110, country_id=74, country_code='FR', code='39', active=1, sort_order=10, state='Jura'";
	$sql[] = "insert into xlsws_state set id=111, country_id=74, country_code='FR', code='40', active=1, sort_order=10, state='Landes'";
	$sql[] = "insert into xlsws_state set id=112, country_id=74, country_code='FR', code='41', active=1, sort_order=10, state='Loir-et-Cher'";
	$sql[] = "insert into xlsws_state set id=113, country_id=74, country_code='FR', code='42', active=1, sort_order=10, state='Loire'";
	$sql[] = "insert into xlsws_state set id=114, country_id=74, country_code='FR', code='44', active=1, sort_order=10, state='Loire-Atlantique'";
	$sql[] = "insert into xlsws_state set id=115, country_id=74, country_code='FR', code='45', active=1, sort_order=10, state='Loiret'";
	$sql[] = "insert into xlsws_state set id=116, country_id=74, country_code='FR', code='46', active=1, sort_order=10, state='Lot'";
	$sql[] = "insert into xlsws_state set id=117, country_id=74, country_code='FR', code='47', active=1, sort_order=10, state='Lot-et-Garonne'";
	$sql[] = "insert into xlsws_state set id=118, country_id=74, country_code='FR', code='48', active=1, sort_order=10, state='Lozère'";
	$sql[] = "insert into xlsws_state set id=119, country_id=74, country_code='FR', code='49', active=1, sort_order=10, state='Maine-et-Loire'";
	$sql[] = "insert into xlsws_state set id=120, country_id=74, country_code='FR', code='50', active=1, sort_order=10, state='Manche'";
	$sql[] = "insert into xlsws_state set id=121, country_id=74, country_code='FR', code='51', active=1, sort_order=10, state='Marne'";
	$sql[] = "insert into xlsws_state set id=122, country_id=74, country_code='FR', code='75', active=1, sort_order=10, state='Paris'";
	$sql[] = "insert into xlsws_state set id=123, country_id=74, country_code='FR', code='93', active=1, sort_order=10, state='Seine-Saint-Denis'";
	$sql[] = "insert into xlsws_state set id=124, country_id=74, country_code='FR', code='80', active=1, sort_order=10, state='Somme'";
	$sql[] = "insert into xlsws_state set id=125, country_id=74, country_code='FR', code='81', active=1, sort_order=10, state='Tarn'";
	$sql[] = "insert into xlsws_state set id=126, country_id=74, country_code='FR', code='82', active=1, sort_order=10, state='Tarn-et-Garonne'";
	$sql[] = "insert into xlsws_state set id=127, country_id=74, country_code='FR', code='90', active=1, sort_order=10, state='Territoire de Belfort'";
	$sql[] = "insert into xlsws_state set id=128, country_id=74, country_code='FR', code='95', active=1, sort_order=10, state='Val-d\'Oise'";
	$sql[] = "insert into xlsws_state set id=129, country_id=74, country_code='FR', code='94', active=1, sort_order=10, state='Val-de-Marne'";
	$sql[] = "insert into xlsws_state set id=130, country_id=74, country_code='FR', code='83', active=1, sort_order=10, state='Var'";
	$sql[] = "insert into xlsws_state set id=131, country_id=74, country_code='FR', code='84', active=1, sort_order=10, state='Vaucluse'";
	$sql[] = "insert into xlsws_state set id=132, country_id=74, country_code='FR', code='85', active=1, sort_order=10, state='Vendée'";
	$sql[] = "insert into xlsws_state set id=133, country_id=74, country_code='FR', code='86', active=1, sort_order=10, state='Vienne'";
	$sql[] = "insert into xlsws_state set id=134, country_id=74, country_code='FR', code='88', active=1, sort_order=10, state='Vosges'";
	$sql[] = "insert into xlsws_state set id=135, country_id=74, country_code='FR', code='89', active=1, sort_order=10, state='Yonne'";
	$sql[] = "insert into xlsws_state set id=136, country_id=39, country_code='CA', code='AB', active=1, sort_order=10, state='Alberta'";
	$sql[] = "insert into xlsws_state set id=137, country_id=39, country_code='CA', code='BC', active=1, sort_order=10, state='British Columbia'";
	$sql[] = "insert into xlsws_state set id=138, country_id=39, country_code='CA', code='MB', active=1, sort_order=10, state='Manitoba'";
	$sql[] = "insert into xlsws_state set id=139, country_id=39, country_code='CA', code='NB', active=1, sort_order=10, state='New Brunswick'";
	$sql[] = "insert into xlsws_state set id=140, country_id=39, country_code='CA', code='NL', active=1, sort_order=10, state='Newfoundland and Labrador'";
	$sql[] = "insert into xlsws_state set id=141, country_id=39, country_code='CA', code='NT', active=1, sort_order=10, state='Northwest Territories'";
	$sql[] = "insert into xlsws_state set id=142, country_id=39, country_code='CA', code='NS', active=1, sort_order=10, state='Nova Scotia'";
	$sql[] = "insert into xlsws_state set id=143, country_id=39, country_code='CA', code='NU', active=1, sort_order=10, state='Nunavut'";
	$sql[] = "insert into xlsws_state set id=144, country_id=39, country_code='CA', code='ON', active=1, sort_order=10, state='Ontario'";
	$sql[] = "insert into xlsws_state set id=145, country_id=39, country_code='CA', code='PE', active=1, sort_order=10, state='Prince Edward Island'";
	$sql[] = "insert into xlsws_state set id=146, country_id=39, country_code='CA', code='QC', active=1, sort_order=10, state='Québec'";
	$sql[] = "insert into xlsws_state set id=147, country_id=39, country_code='CA', code='SK', active=1, sort_order=10, state='Saskatchewan'";
	$sql[] = "insert into xlsws_state set id=148, country_id=39, country_code='CA', code='YT', active=1, sort_order=10, state='Yukon Territory'";
	$sql[] = "insert into xlsws_state set id=149, country_id=13, country_code='AU', code='ACT', active=1, sort_order=10, state='Australian Capital Territory'";
	$sql[] = "insert into xlsws_state set id=150, country_id=13, country_code='AU', code='NSW', active=1, sort_order=10, state='New South Wales'";
	$sql[] = "insert into xlsws_state set id=151, country_id=13, country_code='AU', code='NT', active=1, sort_order=10, state='Northern Territory'";
	$sql[] = "insert into xlsws_state set id=152, country_id=13, country_code='AU', code='QLD', active=1, sort_order=10, state='Queensland'";
	$sql[] = "insert into xlsws_state set id=153, country_id=13, country_code='AU', code='SA', active=1, sort_order=10, state='South Australia'";
	$sql[] = "insert into xlsws_state set id=154, country_id=13, country_code='AU', code='TAS', active=1, sort_order=10, state='Tasmania'";
	$sql[] = "insert into xlsws_state set id=155, country_id=13, country_code='AU', code='VIC', active=1, sort_order=10, state='Victoria'";
	$sql[] = "insert into xlsws_state set id=156, country_id=13, country_code='AU', code='WA', active=1, sort_order=10, state='Western Australia'";
	$sql[] = "insert into xlsws_state set id=157, country_id=152, country_code='NL', code='DR', active=1, sort_order=10, state='Drenthe'";
	$sql[] = "insert into xlsws_state set id=158, country_id=152, country_code='NL', code='FL', active=1, sort_order=10, state='Flevoland'";
	$sql[] = "insert into xlsws_state set id=159, country_id=152, country_code='NL', code='FR', active=1, sort_order=10, state='Friesland'";
	$sql[] = "insert into xlsws_state set id=160, country_id=152, country_code='NL', code='GE', active=1, sort_order=10, state='Gelderland'";
	$sql[] = "insert into xlsws_state set id=161, country_id=152, country_code='NL', code='GR', active=1, sort_order=10, state='Groningen'";
	$sql[] = "insert into xlsws_state set id=162, country_id=152, country_code='NL', code='LI', active=1, sort_order=10, state='Limburg'";
	$sql[] = "insert into xlsws_state set id=163, country_id=152, country_code='NL', code='NB', active=1, sort_order=10, state='Noord Brabant'";
	$sql[] = "insert into xlsws_state set id=164, country_id=152, country_code='NL', code='NH', active=1, sort_order=10, state='Noord Holland'";
	$sql[] = "insert into xlsws_state set id=165, country_id=152, country_code='NL', code='OV', active=1, sort_order=10, state='Overijssel'";
	$sql[] = "insert into xlsws_state set id=166, country_id=152, country_code='NL', code='UT', active=1, sort_order=10, state='Utrecht'";
	$sql[] = "insert into xlsws_state set id=167, country_id=152, country_code='NL', code='ZE', active=1, sort_order=10, state='Zeeland'";
	$sql[] = "insert into xlsws_state set id=168, country_id=152, country_code='NL', code='ZH', active=1, sort_order=10, state='Zuid Holland'";
	$sql[] = "insert into xlsws_state set id=169, country_id=83, country_code='DE', code='BAW', active=1, sort_order=10, state='Baden-Württemberg'";
	$sql[] = "insert into xlsws_state set id=170, country_id=83, country_code='DE', code='BAY', active=1, sort_order=10, state='Bayern'";
	$sql[] = "insert into xlsws_state set id=171, country_id=83, country_code='DE', code='BER', active=1, sort_order=10, state='Berlin'";
	$sql[] = "insert into xlsws_state set id=172, country_id=83, country_code='DE', code='BRG', active=1, sort_order=10, state='Branderburg'";
	$sql[] = "insert into xlsws_state set id=173, country_id=83, country_code='DE', code='BRE', active=1, sort_order=10, state='Bremen'";
	$sql[] = "insert into xlsws_state set id=174, country_id=83, country_code='DE', code='HAM', active=1, sort_order=10, state='Hamburg'";
	$sql[] = "insert into xlsws_state set id=175, country_id=83, country_code='DE', code='HES', active=1, sort_order=10, state='Hessen'";
	$sql[] = "insert into xlsws_state set id=176, country_id=83, country_code='DE', code='MEC', active=1, sort_order=10, state='Mecklenburg-Vorpommern'";
	$sql[] = "insert into xlsws_state set id=177, country_id=83, country_code='DE', code='NDS', active=1, sort_order=10, state='Niedersachsen'";
	$sql[] = "insert into xlsws_state set id=178, country_id=83, country_code='DE', code='NRW', active=1, sort_order=10, state='Nordrhein-Westfalen'";
	$sql[] = "insert into xlsws_state set id=179, country_id=83, country_code='DE', code='RHE', active=1, sort_order=10, state='Rheinland-Pfalz'";
	$sql[] = "insert into xlsws_state set id=180, country_id=83, country_code='DE', code='SAR', active=1, sort_order=10, state='Saarland'";
	$sql[] = "insert into xlsws_state set id=181, country_id=83, country_code='DE', code='SAS', active=1, sort_order=10, state='Sachsen'";
	$sql[] = "insert into xlsws_state set id=182, country_id=83, country_code='DE', code='SAC', active=1, sort_order=10, state='Sachsen-Anhalt'";
	$sql[] = "insert into xlsws_state set id=183, country_id=83, country_code='DE', code='SCN', active=1, sort_order=10, state='Schleswig-Holstein'";
	$sql[] = "insert into xlsws_state set id=184, country_id=83, country_code='DE', code='THE', active=1, sort_order=10, state='Thüringen'";
	$sql[] = "insert into xlsws_state set id=185, country_id=223, country_code='GB', code='ABN', active=1, sort_order=10, state='Aberdeen'";
	$sql[] = "insert into xlsws_state set id=186, country_id=223, country_code='GB', code='ABNS', active=1, sort_order=10, state='Aberdeenshire'";
	$sql[] = "insert into xlsws_state set id=187, country_id=223, country_code='GB', code='ANG', active=1, sort_order=10, state='Anglesey'";
	$sql[] = "insert into xlsws_state set id=188, country_id=223, country_code='GB', code='AGS', active=1, sort_order=10, state='Angus'";
	$sql[] = "insert into xlsws_state set id=189, country_id=223, country_code='GB', code='ARY', active=1, sort_order=10, state='Argyll and Bute'";
	$sql[] = "insert into xlsws_state set id=190, country_id=223, country_code='GB', code='BEDS', active=1, sort_order=10, state='Bedfordshire'";
	$sql[] = "insert into xlsws_state set id=191, country_id=223, country_code='GB', code='BERKS', active=1, sort_order=10, state='Berkshire'";
	$sql[] = "insert into xlsws_state set id=192, country_id=223, country_code='GB', code='BLA', active=1, sort_order=10, state='Blaenau Gwent'";
	$sql[] = "insert into xlsws_state set id=193, country_id=223, country_code='GB', code='BRI', active=1, sort_order=10, state='Bridgend'";
	$sql[] = "insert into xlsws_state set id=194, country_id=223, country_code='GB', code='BSTL', active=1, sort_order=10, state='Bristol'";
	$sql[] = "insert into xlsws_state set id=195, country_id=223, country_code='GB', code='BUCKS', active=1, sort_order=10, state='Buckinghamshire'";
	$sql[] = "insert into xlsws_state set id=196, country_id=223, country_code='GB', code='CAE', active=1, sort_order=10, state='Caerphilly'";
	$sql[] = "insert into xlsws_state set id=197, country_id=223, country_code='GB', code='CAMBS', active=1, sort_order=10, state='Cambridgeshire'";
	$sql[] = "insert into xlsws_state set id=198, country_id=223, country_code='GB', code='CDF', active=1, sort_order=10, state='Cardiff'";
	$sql[] = "insert into xlsws_state set id=199, country_id=223, country_code='GB', code='CARM', active=1, sort_order=10, state='Carmarthenshire'";
	$sql[] = "insert into xlsws_state set id=200, country_id=223, country_code='GB', code='CDGN', active=1, sort_order=10, state='Ceredigion'";
	$sql[] = "insert into xlsws_state set id=201, country_id=223, country_code='GB', code='CHES', active=1, sort_order=10, state='Cheshire'";
	$sql[] = "insert into xlsws_state set id=202, country_id=223, country_code='GB', code='CLACK', active=1, sort_order=10, state='Clackmannanshire'";
	$sql[] = "insert into xlsws_state set id=203, country_id=223, country_code='GB', code='CON', active=1, sort_order=10, state='Conwy'";
	$sql[] = "insert into xlsws_state set id=204, country_id=223, country_code='GB', code='CORN', active=1, sort_order=10, state='Cornwall'";
	$sql[] = "insert into xlsws_state set id=205, country_id=223, country_code='GB', code='DNBG', active=1, sort_order=10, state='Denbighshire'";
	$sql[] = "insert into xlsws_state set id=206, country_id=223, country_code='GB', code='DERBY', active=1, sort_order=10, state='Derbyshire'";
	$sql[] = "insert into xlsws_state set id=207, country_id=223, country_code='GB', code='DVN', active=1, sort_order=10, state='Devon'";
	$sql[] = "insert into xlsws_state set id=208, country_id=223, country_code='GB', code='DOR', active=1, sort_order=10, state='Dorset'";
	$sql[] = "insert into xlsws_state set id=209, country_id=223, country_code='GB', code='DGL', active=1, sort_order=10, state='Dumfries and Galloway'";
	$sql[] = "insert into xlsws_state set id=210, country_id=223, country_code='GB', code='DUND', active=1, sort_order=10, state='Dundee'";
	$sql[] = "insert into xlsws_state set id=211, country_id=223, country_code='GB', code='DHM', active=1, sort_order=10, state='Durham'";
	$sql[] = "insert into xlsws_state set id=212, country_id=223, country_code='GB', code='ARYE', active=1, sort_order=10, state='East Ayrshire'";
	$sql[] = "insert into xlsws_state set id=213, country_id=223, country_code='GB', code='DUNBE', active=1, sort_order=10, state='East Dunbartonshire'";
	$sql[] = "insert into xlsws_state set id=214, country_id=223, country_code='GB', code='LOTE', active=1, sort_order=10, state='East Lothian'";
	$sql[] = "insert into xlsws_state set id=215, country_id=223, country_code='GB', code='RENE', active=1, sort_order=10, state='East Renfrewshire'";
	$sql[] = "insert into xlsws_state set id=216, country_id=223, country_code='GB', code='ERYS', active=1, sort_order=10, state='East Riding of Yorkshire'";
	$sql[] = "insert into xlsws_state set id=217, country_id=223, country_code='GB', code='SXE', active=1, sort_order=10, state='East Sussex'";
	$sql[] = "insert into xlsws_state set id=218, country_id=223, country_code='GB', code='EDIN', active=1, sort_order=10, state='Edinburgh'";
	$sql[] = "insert into xlsws_state set id=219, country_id=223, country_code='GB', code='ESX', active=1, sort_order=10, state='Essex'";
	$sql[] = "insert into xlsws_state set id=220, country_id=223, country_code='GB', code='FALK', active=1, sort_order=10, state='Falkirk'";
	$sql[] = "insert into xlsws_state set id=221, country_id=223, country_code='GB', code='FFE', active=1, sort_order=10, state='Fife'";
	$sql[] = "insert into xlsws_state set id=222, country_id=223, country_code='GB', code='FLINT', active=1, sort_order=10, state='Flintshire'";
	$sql[] = "insert into xlsws_state set id=223, country_id=223, country_code='GB', code='GLAS', active=1, sort_order=10, state='Glasgow'";
	$sql[] = "insert into xlsws_state set id=224, country_id=223, country_code='GB', code='GLOS', active=1, sort_order=10, state='Gloucestershire'";
	$sql[] = "insert into xlsws_state set id=225, country_id=223, country_code='GB', code='LDN', active=1, sort_order=10, state='Greater London'";
	$sql[] = "insert into xlsws_state set id=226, country_id=223, country_code='GB', code='MCH', active=1, sort_order=10, state='Greater Manchester'";
	$sql[] = "insert into xlsws_state set id=227, country_id=223, country_code='GB', code='GDD', active=1, sort_order=10, state='Gwynedd'";
	$sql[] = "insert into xlsws_state set id=228, country_id=223, country_code='GB', code='HANTS', active=1, sort_order=10, state='Hampshire'";
	$sql[] = "insert into xlsws_state set id=229, country_id=223, country_code='GB', code='HWR', active=1, sort_order=10, state='Herefordshire'";
	$sql[] = "insert into xlsws_state set id=230, country_id=223, country_code='GB', code='HERTS', active=1, sort_order=10, state='Hertfordshire'";
	$sql[] = "insert into xlsws_state set id=231, country_id=223, country_code='GB', code='HLD', active=1, sort_order=10, state='Highlands'";
	$sql[] = "insert into xlsws_state set id=232, country_id=223, country_code='GB', code='IVER', active=1, sort_order=10, state='Inverclyde'";
	$sql[] = "insert into xlsws_state set id=233, country_id=223, country_code='GB', code='IOW', active=1, sort_order=10, state='Isle of Wight'";
	$sql[] = "insert into xlsws_state set id=234, country_id=223, country_code='GB', code='KNT', active=1, sort_order=10, state='Kent'";
	$sql[] = "insert into xlsws_state set id=235, country_id=223, country_code='GB', code='LANCS', active=1, sort_order=10, state='Lancashire'";
	$sql[] = "insert into xlsws_state set id=236, country_id=223, country_code='GB', code='LEICS', active=1, sort_order=10, state='Leicestershire'";
	$sql[] = "insert into xlsws_state set id=237, country_id=223, country_code='GB', code='LINCS', active=1, sort_order=10, state='Lincolnshire'";
	$sql[] = "insert into xlsws_state set id=238, country_id=223, country_code='GB', code='MSY', active=1, sort_order=10, state='Merseyside'";
	$sql[] = "insert into xlsws_state set id=239, country_id=223, country_code='GB', code='MERT', active=1, sort_order=10, state='Merthyr Tydfil'";
	$sql[] = "insert into xlsws_state set id=240, country_id=223, country_code='GB', code='MLOT', active=1, sort_order=10, state='Midlothian'";
	$sql[] = "insert into xlsws_state set id=241, country_id=223, country_code='GB', code='MMOUTH', active=1, sort_order=10, state='Monmouthshire'";
	$sql[] = "insert into xlsws_state set id=242, country_id=223, country_code='GB', code='MORAY', active=1, sort_order=10, state='Moray'";
	$sql[] = "insert into xlsws_state set id=243, country_id=223, country_code='GB', code='NPRTAL', active=1, sort_order=10, state='Neath Port Talbot'";
	$sql[] = "insert into xlsws_state set id=244, country_id=223, country_code='GB', code='NEWPT', active=1, sort_order=10, state='Newport'";
	$sql[] = "insert into xlsws_state set id=245, country_id=223, country_code='GB', code='NOR', active=1, sort_order=10, state='Norfolk'";
	$sql[] = "insert into xlsws_state set id=246, country_id=223, country_code='GB', code='ARYN', active=1, sort_order=10, state='North Ayrshire'";
	$sql[] = "insert into xlsws_state set id=247, country_id=223, country_code='GB', code='LANN', active=1, sort_order=10, state='North Lanarkshire'";
	$sql[] = "insert into xlsws_state set id=248, country_id=223, country_code='GB', code='YSN', active=1, sort_order=10, state='North Yorkshire'";
	$sql[] = "insert into xlsws_state set id=249, country_id=223, country_code='GB', code='NHM', active=1, sort_order=10, state='Northamptonshire'";
	$sql[] = "insert into xlsws_state set id=250, country_id=223, country_code='GB', code='NLD', active=1, sort_order=10, state='Northumberland'";
	$sql[] = "insert into xlsws_state set id=251, country_id=223, country_code='GB', code='NOT', active=1, sort_order=10, state='Nottinghamshire'";
	$sql[] = "insert into xlsws_state set id=252, country_id=223, country_code='GB', code='ORK', active=1, sort_order=10, state='Orkney Islands'";
	$sql[] = "insert into xlsws_state set id=253, country_id=223, country_code='GB', code='OFE', active=1, sort_order=10, state='Oxfordshire'";
	$sql[] = "insert into xlsws_state set id=254, country_id=223, country_code='GB', code='PEM', active=1, sort_order=10, state='Pembrokeshire'";
	$sql[] = "insert into xlsws_state set id=255, country_id=223, country_code='GB', code='PERTH', active=1, sort_order=10, state='Perth and Kinross'";
	$sql[] = "insert into xlsws_state set id=256, country_id=223, country_code='GB', code='PWS', active=1, sort_order=10, state='Powys'";
	$sql[] = "insert into xlsws_state set id=257, country_id=223, country_code='GB', code='REN', active=1, sort_order=10, state='Renfrewshire'";
	$sql[] = "insert into xlsws_state set id=258, country_id=223, country_code='GB', code='RHON', active=1, sort_order=10, state='Rhondda Cynon Taff'";
	$sql[] = "insert into xlsws_state set id=259, country_id=223, country_code='GB', code='RUT', active=1, sort_order=10, state='Rutland'";
	$sql[] = "insert into xlsws_state set id=260, country_id=223, country_code='GB', code='BOR', active=1, sort_order=10, state='Scottish Borders'";
	$sql[] = "insert into xlsws_state set id=261, country_id=223, country_code='GB', code='SHET', active=1, sort_order=10, state='Shetland Islands'";
	$sql[] = "insert into xlsws_state set id=262, country_id=223, country_code='GB', code='SPE', active=1, sort_order=10, state='Shropshire'";
	$sql[] = "insert into xlsws_state set id=263, country_id=223, country_code='GB', code='SOM', active=1, sort_order=10, state='Somerset'";
	$sql[] = "insert into xlsws_state set id=264, country_id=223, country_code='GB', code='ARYS', active=1, sort_order=10, state='South Ayrshire'";
	$sql[] = "insert into xlsws_state set id=265, country_id=223, country_code='GB', code='LANS', active=1, sort_order=10, state='South Lanarkshire'";
	$sql[] = "insert into xlsws_state set id=266, country_id=223, country_code='GB', code='YSS', active=1, sort_order=10, state='South Yorkshire'";
	$sql[] = "insert into xlsws_state set id=267, country_id=223, country_code='GB', code='SFD', active=1, sort_order=10, state='Staffordshire'";
	$sql[] = "insert into xlsws_state set id=268, country_id=223, country_code='GB', code='STIR', active=1, sort_order=10, state='Stirling'";
	$sql[] = "insert into xlsws_state set id=269, country_id=223, country_code='GB', code='SFK', active=1, sort_order=10, state='Suffolk'";
	$sql[] = "insert into xlsws_state set id=270, country_id=223, country_code='GB', code='SRY', active=1, sort_order=10, state='Surrey'";
	$sql[] = "insert into xlsws_state set id=271, country_id=223, country_code='GB', code='SWAN', active=1, sort_order=10, state='Swansea'";
	$sql[] = "insert into xlsws_state set id=272, country_id=223, country_code='GB', code='TORF', active=1, sort_order=10, state='Torfaen'";
	$sql[] = "insert into xlsws_state set id=273, country_id=223, country_code='GB', code='TWR', active=1, sort_order=10, state='Tyne and Wear'";
	$sql[] = "insert into xlsws_state set id=274, country_id=223, country_code='GB', code='VGLAM', active=1, sort_order=10, state='Vale of Glamorgan'";
	$sql[] = "insert into xlsws_state set id=275, country_id=223, country_code='GB', code='WARKS', active=1, sort_order=10, state='Warwickshire'";
	$sql[] = "insert into xlsws_state set id=276, country_id=223, country_code='GB', code='WDUN', active=1, sort_order=10, state='West Dunbartonshire'";
	$sql[] = "insert into xlsws_state set id=277, country_id=223, country_code='GB', code='WLOT', active=1, sort_order=10, state='West Lothian'";
	$sql[] = "insert into xlsws_state set id=278, country_id=223, country_code='GB', code='WMD', active=1, sort_order=10, state='West Midlands'";
	$sql[] = "insert into xlsws_state set id=279, country_id=223, country_code='GB', code='SXW', active=1, sort_order=10, state='West Sussex'";
	$sql[] = "insert into xlsws_state set id=280, country_id=223, country_code='GB', code='YSW', active=1, sort_order=10, state='West Yorkshire'";
	$sql[] = "insert into xlsws_state set id=281, country_id=223, country_code='GB', code='WIL', active=1, sort_order=10, state='Western Isles'";
	$sql[] = "insert into xlsws_state set id=282, country_id=223, country_code='GB', code='WLT', active=1, sort_order=10, state='Wiltshire'";
	$sql[] = "insert into xlsws_state set id=283, country_id=223, country_code='GB', code='WORCS', active=1, sort_order=10, state='Worcestershire'";
	$sql[] = "insert into xlsws_state set id=284, country_id=223, country_code='GB', code='WRX', active=1, sort_order=10, state='Wrexham'";


	$sql[] = "insert into xlsws_credit_card set id=1, label='American Express', numeric_length='15', prefix='34,37', sort_order=3, enabled=0, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_credit_card set id=2, label='Carte Blanche', numeric_length='14', prefix='300,301,302,303,304,305,36,38', sort_order=0, enabled=0, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_credit_card set id=3, label='Diners Club', numeric_length='14', prefix='300,301,302,303,304,305,36,38', sort_order=0, enabled=0, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_credit_card set id=4, label='Discover', numeric_length='16', prefix='6011', sort_order=0, enabled=0, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_credit_card set id=5, label='Enroute', numeric_length='15', prefix='20142149', sort_order=0, enabled=0, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_credit_card set id=6, label='JCB', numeric_length='15,16', prefix='318002131', sort_order=0, enabled=0, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_credit_card set id=7, label='Maestro', numeric_length='16,18', prefix='5020,6', sort_order=1, enabled=0, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_credit_card set id=8, label='MasterCard', numeric_length='16', prefix='51,52,53,54,55', sort_order=0, enabled=1, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_credit_card set id=9, label='Solo', numeric_length='16,18,19', prefix='63346767', sort_order=0, enabled=0, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_credit_card set id=10, label='Switch', numeric_length='16,18,19', prefix='4.90349054911493E+35', sort_order=0, enabled=0, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_credit_card set id=11, label='Visa', numeric_length='13,16', prefix='4', sort_order=0, enabled=1, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_credit_card set id=12, label='Visa Electron', numeric_length='16', prefix='41750049174913', sort_order=0, enabled=0, modified='".date("Y-m-d H:i:s")."' ";


	$sql[] = "insert into xlsws_custom_page set id=1, page_key='top', title='Top Products', page='<p>Page coming soon...</p>', request_url='top-products', tab_position=12, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_custom_page set id=2, page_key='new', title='New Products', page='<p>Page coming soon...</p>', request_url='new-products', tab_position=11, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_custom_page set id=3, page_key='promo', title='Promotions', page='<p>Page coming soon...</p>', request_url='promotions', tab_position=13, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_custom_page set id=4, page_key='about', title='About Us', page='<p>Page coming soon...</p>', request_url='about-us', tab_position=21, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_custom_page set id=5, page_key='privacy', title='Privacy Policy', page='<p>Page coming soon...</p>', request_url='privacy-policy', tab_position=23, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_custom_page set id=6, page_key='tc', title='Terms and Conditions', page='<p>Page coming soon...</p>', request_url='terms-and-conditions', tab_position=22, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_custom_page set id=7, page_key='contactus', title='Contact Us', page='If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.', request_url='contact-us', tab_position=13, modified='".date("Y-m-d H:i:s")."' ";
	$sql[] = "insert into xlsws_custom_page set id=8, page_key='Welcome', title='Welcome', page='<p>Page coming soon...</p>', request_url='welcome', tab_position=0, modified='".date("Y-m-d H:i:s")."' ";


	$sql[] = "INSERT INTO `xlsws_modules` set active=1,module='wsborderlookup', category='sidebar', sort_order=2";
	$sql[] = "INSERT INTO `xlsws_modules` set active=1,module='wsbwishlist', category='sidebar', sort_order=3";
	$sql[] = "INSERT INTO `xlsws_modules` set active=0,module='wsbsidebar', category='sidebar', sort_order=4";

	$sql[] = "INSERT INTO `xlsws_modules` (`active`, `module`, `category`, `version`, `name`, `sort_order`, `configuration`, `modified`, `created`)
VALUES	(1, 'cashondelivery', 'payment', 1, 'Cash on Delivery', 14, 'a:1:{s:5:\"label\";s:16:\"Cash On Delivery\";}', '".date("Y-m-d H:i:s")."', NULL);";

	$sql[] = "INSERT INTO `xlsws_modules` (`active`, `module`, `category`, `version`, `name`, `sort_order`, `configuration`, `modified`, `created`)
VALUES	(1, 'storepickup', 'shipping', 1, 'Store Pickup', 21, 'a:4:{s:5:\"label\";s:12:\"Store Pickup\";s:3:\"msg\";s:71:\"Please quote order ID %s with photo ID at the reception for collection.\";s:7:\"product\";s:8:\"SHIPPING\";s:6:\"markup\";s:1:\"0\";}', '".date("Y-m-d H:i:s")."', NULL);";


	foreach ($sql as $s) {
		$db->query($s);
	}

}


function up217($db)
{
	//We make the assumption the customer is at least on 2.0.8, and start running updates from that point that apply. Anything redundant is ignored.

	$db->add_config_key('MATRIX_PRICE' , 'Hide price of matrix master product', '3', 'If you do not want to show the price of your master product in a size/color matrix, turn this option on', 8,9 ,'BOOL');

	$db->add_table('xlsws_promo_code' , "CREATE TABLE `xlsws_promo_code` (
			  `rowid` int(11) NOT NULL auto_increment,
			  `code` varchar(255) default NULL,
			  `type` int(11) default '0',
			  `amount` double NOT NULL,
			  `valid_from` tinytext NOT NULL,
			  `qty_remaining` int(11) NOT NULL default '-1',
			  `valid_until` tinytext,
			  `lscodes` longtext NOT NULL,
			  `threshold` double NOT NULL,
			  PRIMARY KEY  (`rowid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8" , '2.1');

	$db->add_table('xlsws_shipping_tiers' , "CREATE TABLE `xlsws_shipping_tiers` (
			  `rowid` int(11) NOT NULL auto_increment,
			  `start_price` double default '0',
			  `end_price` double default '0',
			  `rate` double default '0',
			  PRIMARY KEY  (`rowid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8" , '2.1');

	$db->add_table('xlsws_sessions' , "CREATE TABLE `xlsws_sessions` (
				  `intSessionId` int(10) NOT NULL auto_increment,
				  `vchName` varchar(255) NOT NULL default '',
				  `uxtExpires` int(10) unsigned NOT NULL default '0',
				  `txtData` longtext,
				  PRIMARY KEY  (`intSessionId`),
				  KEY `idxName` (`vchName`),
				  KEY `idxExpires` (`uxtExpires`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8" , '2.1');

	$db->add_column('xlsws_cart' , 'fk_promo_id' , "ALTER TABLE  `xlsws_cart` ADD  `fk_promo_id` int(5) DEFAULT  NULL " , '2.1');

	$db->add_config_key('CHILD_SEARCH' , 'Show child products in search results',  '','If you want child products from a size color matrix to show up in search results, enable this option',8,10,'BOOL');

	$db->add_config_key('EMAIL_SMTP_SECURITY_MODE' , 'Security mode for outbound SMTP',  '0',  'Automatic based on SMTP Port, or force security.',  '5',  '8', 'EMAIL_SMTP_SECURITY_MODE');

	$db->add_config_key('MAX_PRODUCTS_IN_SLIDER' , 'Maximum Products in Slider', '64',  'For a custom page, max products in slider.',  '8',  '11', 'PINT');

	$db->query("ALTER TABLE xlsws_family MODIFY COLUMN family varchar (255)");

	$db->query("ALTER TABLE xlsws_product MODIFY COLUMN family varchar (255)");

	$db->add_config_key('DATABASE_SCHEMA_VERSION' ,'Database Schema Version',
		'217',  'Used for tracking schema changes',  '',  '',  NULL);
	$db->query("UPDATE `xlsws_configuration` SET `value`='217' where `key`='DATABASE_SCHEMA_VERSION'");

}

function up250($db,$sqlline)
{
	if ($sqlline==4)
	{

		$db->add_column('xlsws_configuration' , 'template_specific',
			"ALTER TABLE xlsws_configuration ADD COLUMN `template_specific` tinyint (1) NULL DEFAULT 0 AFTER `options`");

		$db->add_column('xlsws_category' , 'google_id' ,
			"ALTER TABLE `xlsws_category` ADD `google_id` INT  DEFAULT NULL AFTER `image_id`");

		$db->query("ALTER TABLE `xlsws_cart` CHANGE `printed_notes` `printed_notes` TEXT  NULL");

		$db->add_config_key('DEBUG_LS_SOAP_CALL' ,
			'Debug SOAP Calls', '0',
			'Turn on soap debugging.',
			1, 17, 'BOOL',0);

		$db->add_config_key('FEATURED_KEYWORD' ,
			'Featured Keyword', 'featured',
			'If this keyword is one of your product keywords, the product will be featured on the Web Store homepage.',
			8, 6, NULL,0);

		$db->add_config_key('LIGHTSPEED_HOSTING' ,
			'LightSpeed Hosting',
			'0', 'Flag which indicates site is hosted by LightSpeed', 0, 0, 'BOOL',0);

		//Add debug keys
		$db->add_config_key('DEBUG_PAYMENTS' , 'Debug Payment Methods', '',
			'If selected, WS logs all activity for credit card processing and other payment methods.',
			1, 18, 'BOOL',0);
		$db->add_config_key('DEBUG_SHIPPING' ,'Debug Shipping Methods', '',
			'If selected, WS logs all activity for shipping processing.',
			1, 19,  'BOOL',0);
		$db->add_config_key('DEBUG_RESET' ,
			'Reset Without Flush', '',
			'If selected, WS will not perform a flush on content tables when doing a Reset Store Products.',
			1, 20,  'BOOL',0);
		$db->add_config_key('DEBUG_DISABLE_AJAX' ,
			'Disable Ajax Paging', '0',
			'If selected, WS will not page using AJAX but will use regular URLs.',
			1, 21,  'BOOL',0);
		$db->add_config_key('LOG_ROTATE_DAYS' ,'Log Rotate Days',
			'30', 'How many days System Log should be retained.', 1, 30,  'INT',0);
		$db->add_config_key('UPLOADER_TIMESTAMP' , 'Last timestamp uploader ran',
			'0', 'Internal', 0, 0,  'NULL',0);

		//Families menu labeling
		$db->query("UPDATE `xlsws_configuration` SET `title`='Show Families on Product Menu?',`configuration_type_id`=19,`sort_order`=3,
					`options`='ENABLE_FAMILIES' where `key`='ENABLE_FAMILIES'");

		$db->add_config_key('ENABLE_FAMILIES_MENU_LABEL' ,
			'Show Families Menu label',
			'By Manufacturer', '', 19, 4,  NULL,0);
	}

	if ($sqlline==5)
	{
		//Promo code table changes
		if ($db->add_column('xlsws_promo_code' , 'enabled' ,
			"ALTER TABLE xlsws_promo_code ADD COLUMN enabled tinyint (1) NOT NULL DEFAULT 1 AFTER rowid "))
			$db->query("UPDATE xlsws_promo_code SET enabled=1");

		if ($db->add_column('xlsws_promo_code' , 'except' ,
			"ALTER TABLE xlsws_promo_code ADD COLUMN except tinyint (1) NOT NULL DEFAULT 0 AFTER enabled "))
			$db->query("UPDATE xlsws_promo_code SET except=0");

		$db->add_column('xlsws_cart' , 'tracking_number',
			"ALTER TABLE xlsws_cart ADD COLUMN `tracking_number` VARCHAR(255) NULL DEFAULT NULL AFTER `payment_amount`");


		//Template section
		$db->query("UPDATE `xlsws_configuration` SET `configuration_type_id`=0,`sort_order`=1
					where `key`='DEFAULT_TEMPLATE'");
		$db->add_config_key('DEFAULT_TEMPLATE_THEME' ,
			'Template theme', '',
			'If supported, changeable colo(u)rs for template files.',
			0, 2,  'DEFAULT_TEMPLATE_THEME',1);
		$db->add_config_key('ENABLE_SLASHED_PRICES' ,
			'Enabled Slashed \"Original\" Prices', '',
			'If selected, will display original price slashed out and Web Price as a Sale Price.',
			19, 3,  'ENABLE_SLASHED_PRICES',0);


		//Fix some sequencing problems for Product options
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=1 where `key`='PRODUCT_COLOR_LABEL'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=2 where `key`='PRODUCT_SIZE_LABEL'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=3 where `key`='PRODUCTS_PER_PAGE'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=4 where `key`='PRODUCT_SORT_FIELD'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=5 where `key`='ENABLE_FAMILIES'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=6 where `key`='ENABLE_FAMILIES_MENU_LABEL'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=8 where `key`='MATRIX_PRICE'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=9 where `key`='ENABLE_SLASHED_PRICES'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=10 where `key`='CHILD_SEARCH'");

		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=12 where `key`='DISPLAY_EMPTY_CATEGORY'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=13 where `key`='FEATURED_KEYWORD'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=14 where `key`='SITEMAP_SHOW_PRODUCTS'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=16 where `key`='MAX_PRODUCTS_IN_SLIDER'");


		if ($db->add_column('xlsws_custom_page' , 'tab_position' ,
			"ALTER TABLE xlsws_custom_page ADD COLUMN tab_position int NULL"))
		{
			$db->query("UPDATE xlsws_custom_page SET tab_position=11 where `key`='new'");
			$db->query("UPDATE xlsws_custom_page SET tab_position=12 where `key`='top'");
			$db->query("UPDATE xlsws_custom_page SET tab_position=13 where `key`='promo'");
			$db->query("UPDATE xlsws_custom_page SET tab_position=14 where `key`='contactus'");
			$db->query("UPDATE xlsws_custom_page SET tab_position=21 where `key`='about'");
			$db->query("UPDATE xlsws_custom_page SET tab_position=22 where `key`='tc'");
			$db->query("UPDATE xlsws_custom_page SET tab_position=23 where `key`='privacy'");
		}


		$db->query("UPDATE `xlsws_country` SET `country`='Russia' WHERE `code`='RU'");


		//ReCaptcha Keys
		$db->add_config_key('RECAPTCHA_PUBLIC_KEY' ,
			'ReCaptcha Public Key',
			'', 'Sign up for an account at http://www.google.com/recaptcha', 18, 2,  NULL,0);
		$db->add_config_key('RECAPTCHA_PRIVATE_KEY' ,
			'ReCaptcha Private Key',
			'', 'Sign up for an account at http://www.google.com/recaptcha', 18, 3,  NULL,0);

		$db->add_config_key('CAPTCHA_STYLE' ,
			'Captcha Style',
			'0', 'Sign up for an account at http://www.google.com/recaptcha', 18, 1,  'CAPTCHA_STYLE',0);

		$db->add_config_key('CAPTCHA_CHECKOUT' ,
			'Use Captcha on Checkout',
			'1', '', 18, 6,  'CAPTCHA_CHECKOUT',0);
		$db->add_config_key('CAPTCHA_CONTACTUS' ,
			'Use Captcha on Contact Us',
			'1', '', 18, 7,  'CAPTCHA_CONTACTUS',0);
		$db->add_config_key('CAPTCHA_REGISTRATION' ,
			'Use Captcha on Registration',
			'1', '', 18, 8,  'CAPTCHA_REGISTRATION',0);
		$db->add_config_key('CAPTCHA_THEME' ,
			'ReCaptcha Theme',
			'white', '', 18, 4,  'CAPTCHA_THEME',1);


		//Email options
		$db->add_config_key('EMAIL_SMTP_AUTH_PLAIN' ,
			'Force AUTH PLAIN Authentication',
			'0', 'Force plain text password in rare circumstances', 5, 9,  'BOOL',0);
		$db->add_config_key('EMAIL_SEND_CUSTOMER' ,
			'Send Receipts to Customers',
			'1', 'Option whether to email order receipts to customers', 24, 1,  'BOOL',0);
		$db->add_config_key('EMAIL_SEND_STORE' ,
			'Send Order Alerts to Store',
			'1', 'Option to send Store Owner email when order is placed', 24, 2,  'BOOL',0);
		$db->add_config_key('EMAIL_SUBJECT_CUSTOMER' ,
			'Customer Email Subject Line',
			'%storename% Order Notification %orderid%', 'Configure Email Subject line with variables for Customer Email', 24, 10,  NULL,0);
		$db->add_config_key('EMAIL_SUBJECT_OWNER' ,
			'Owner Email Subject Line',
			'%storename% Order Notification %orderid%', 'Configure Email Subject line with variables for Owner email', 24, 11,  NULL,0);



		//Fix some sequencing problems for options
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=10 where `key`='EMAIL_SIGNATURE'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=11 where `key`='EMAIL_SMTP_SERVER'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=12 where `key`='EMAIL_SMTP_PORT'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=13 where `key`='EMAIL_SMTP_USERNAME'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=14,options='PASSWORD' where `key`='EMAIL_SMTP_PASSWORD'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=15 where `key`='EMAIL_SMTP_SECURITY_MODE'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=16 where `key`='EMAIL_SMTP_AUTH_PLAIN'");







		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=5 where `key`='INVENTORY_ZERO_NEG_TITLE'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=6 where `key`='INVENTORY_AVAILABLE'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=7 where `key`='INVENTORY_LOW_TITLE'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=8 where `key`='INVENTORY_LOW_THRESHOLD'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=9 where `key`='INVENTORY_NON_TITLE'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=10 where `key`='INVENTORY_OUT_ALLOW_ADD'");

		//Cleaning up some tooltips and descriptions to be clearer
		$db->query("UPDATE `xlsws_configuration` SET `title`='Enter relative URL (usually starting with /images)'
					where `key`='HEADER_IMAGE'");
		$db->query("UPDATE `xlsws_configuration` SET `helper_text`='Enter the location (relative to you Web Store install directory) to the header or logo image for your Web Store. Do not use a http:// prefix, this will interfere with SSL security. ' where `key`='HEADER_IMAGE'");
		$db->query("UPDATE `xlsws_configuration` SET `title`='New customers can purchase', `options`='ALLOW_GUEST_CHECKOUT', `helper_text`='Force customers to sign up with an account before shopping? Note this some customers will abandon a forced-signup process. Customer cards are created in LightSpeed based on all orders, not dependent on customer registrations.' where `key`='ALLOW_GUEST_CHECKOUT'");
	}

	if ($sqlline==6)
	{
		//Inventory handling changes
		$db->query("UPDATE `xlsws_configuration` SET `title`='Inventory should include Virtual Warehouses'
					where `key`='INVENTORY_FIELD_TOTAL'");
		$db->add_config_key('INVENTORY_RESERVED' ,
			'Deduct Pending Orders from Available Inventory',
			'1', 'This option will calculate Qty Available minus Pending Orders. Turning on Upload Orders in LightSpeed Tools->eCommerce->Documents is required to make this feature work properly.', 11, 4,  'BOOL',0);
		$db->add_column('xlsws_product' , 'inventory_reserved' ,
			"ALTER TABLE xlsws_product ADD COLUMN inventory_reserved float NOT NULL DEFAULT 0 AFTER inventory_total;");
	}

	if ($sqlline==7)
	{
		$db->add_column('xlsws_product' , 'inventory_avail' ,
			"ALTER TABLE xlsws_product ADD COLUMN inventory_avail float NOT NULL DEFAULT 0 AFTER inventory_reserved;");
		$db->query("UPDATE xlsws_product SET inventory_reserved=0");
		$db->query("UPDATE xlsws_product SET inventory_avail=0");
	}

	if ($sqlline==8)
	{
		$db->query("UPDATE `xlsws_configuration` SET `title`='When a product is Out of Stock',
					`options`='INVENTORY_OUT_ALLOW_ADD',`helper_text`='How should system treat products currently out of stock. Note: Turn OFF the checkbox for -Only Upload Products with Available Inventory- in Tools->eCommerce.' where `key`='INVENTORY_OUT_ALLOW_ADD'");
		//$db->query("ALTER TABLE `xlsws_product` ADD INDEX (`inventory`, `inventory_avail`);	//need to check if exists

		//Pricing Changes
		$db->query("UPDATE `xlsws_configuration` SET `title`='In Product Grid, when child product prices vary',
					`options`='MATRIX_PRICE',`value`=3,`helper_text`='How should system treat child products when different child products have different prices.' where `key`='MATRIX_PRICE'");
		$db->add_config_key('PRICE_REQUIRE_LOGIN' ,
			'Require login to view prices',
			'0', 'System will not display prices to anyone not logged in.', 3, 3,  'BOOL',0);
		//Fix some sequencing problems for options
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=4 where `key`='LANGUAGES'");
		$db->query("UPDATE `xlsws_configuration` SET `sort_order`=5 where `key`='MIN_PASSWORD_LEN'");

		$db->query("UPDATE `xlsws_configuration` SET `title`='SSL Security certificate should be used',
					`options`='SSL_NO_NEED_FORWARD',`helper_text`='Change when SSL secure mode is used.' where `key`='SSL_NO_NEED_FORWARD'");
	}

	if ($sqlline==9)
	{
		//SEO Changes
		$db->add_column('xlsws_category' , 'request_url' ,
			"ALTER TABLE xlsws_category ADD COLUMN `request_url` varchar (255) AFTER `child_count`");
		$db->add_column('xlsws_product' , 'request_url' ,
			"ALTER TABLE xlsws_product ADD COLUMN `request_url` varchar (255) AFTER `web_keyword3`");
		$db->add_column('xlsws_custom_page' , 'request_url' ,
			"ALTER TABLE xlsws_custom_page ADD COLUMN `request_url` varchar (255) AFTER `page`");
		$db->add_column('xlsws_family' , 'request_url' ,
			"ALTER TABLE xlsws_family ADD COLUMN `request_url` varchar (255) AFTER `family`");
	}
	if ($sqlline==10)
	{
		$db->add_index('xlsws_family','request_url');
		$db->add_index('xlsws_category','request_url');
		$db->add_index('xlsws_product','request_url');
		$db->add_index('xlsws_custom_page','request_url');
		$db->add_index('xlsws_product','image_id');
		$db->add_index('xlsws_images','image_path');
	}

	if ($sqlline==11)
	{
		$db->add_config_key('SHOW_TEMPLATE_CODE' ,
			'Show Product Code on Product Details',
			'1', 'Determines if the Product Code should be visible', 19, 20,  'BOOL',0);
		$db->add_config_key('SHOW_SHARING' ,
			'Show Sharing Buttons on Product Details',
			'1', 'Show Sharing buttons such as Facebook and Pinterest', 19, 21,  'BOOL',0);

		$db->add_config_key('SEO_URL_CODES' ,
			'Use Product Codes in Product URLs',
			'0', 'If your Product Codes are important (such as model numbers), this will include them when making SEO formatted URLs. If you generate your own Product Codes that are only internal, you can leave this off.', 21, 1,  'BOOL',0);
		$db->add_config_key('GOOGLE_ANALYTICS' ,
			'Google Analytics Code (format: UA-00000000-0)',
			'', 'Google Analytics code for tracking', 20, 1,  'NULL',0);
		$db->add_config_key('GOOGLE_MPN' ,
			'Product Codes are Manufacturer Part Numbers in Google Shopping', '0',
			'If your Product Codes are Manufacturer Part Numbers, turn this on to apply this to Google Shopping feed.',
			20, 4,  'BOOL', 0);
		$db->add_config_key('GOOGLE_ADWORDS' ,
			'Google AdWords ID (format: 000000000)',
			'', 'Google AdWords Conversion ID (found in line \'var google_conversion_id\' when viewing code from Google AdWords setup)', 20, 2,  'NULL',0);
		$db->add_config_key('GOOGLE_VERIFY' ,
			'Google Site Verify ID (format: _PRasdu8f9a8F9A..etc)',
			'', 'Google Verify Code (found in google-site-verification meta header)', 20, 3,  'NULL',0);



		$db->add_config_key('STORE_TAGLINE' ,
			'Store Tagline',
			'Amazing products available to order online!', 'Slogan which follows your store name on the Title bar', 2, 4,  'NULL',0);
		$db->add_config_key('STORE_ADDRESS1' ,
			'Store Address',
			'123 Main St.', 'Address line 1', 2, 5,  'NULL',0);
		$db->add_config_key('STORE_ADDRESS2' ,
			'Store City, State, Postal',
			'Anytown, NY 12345', 'Address line 2', 2, 6,  'NULL',0);
		$db->add_config_key('STORE_HOURS' ,
			'Store Operating Hours',
			'MON - SAT: 9AM-9PM', 'Store hours.', 2, 7,  'NULL',0);


		//URL and Description Formatting
		$db->add_config_key('SEO_PRODUCT_TITLE' ,
			'Product Title format',
			'%description% : %storename%', 'Which elements appear in the Title', 22, 2,  'NULL',0);
		$db->add_config_key('SEO_PRODUCT_DESCRIPTION' ,
			'Product Meta Description format',
			'%longdescription%', 'Which elements appear in the Meta Description', 22, 3,  'NULL',0);
		$db->add_config_key('SEO_CATEGORY_TITLE' ,
			'Category pages Title format',
			'%name% : %storename%', 'Which elements appear in the title of a category page', 23, 1,  'NULL',0);
		$db->add_config_key('SEO_CUSTOMPAGE_TITLE' ,
			'Custom pages Title format',
			'%name% : %storename%', 'Which elements appear in the title of a custom page', 23, 2,  'NULL',0);

		//Copy our category table since we will use this to handle uploads and SEO activities
		$db->add_table('xlsws_category_addl' , "CREATE TABLE `xlsws_category_addl` (
				  `rowid` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(64) DEFAULT NULL,
				  `parent` int(11) DEFAULT NULL,
				  `position` int(11) NOT NULL,
				  `created` datetime DEFAULT NULL,
				  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  PRIMARY KEY (`rowid`),
				  KEY `name` (`name`),
				  KEY `parent` (`parent`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;");



		$db->query("UPDATE `xlsws_configuration` SET `title`='Product Grid image width', `configuration_type_id`=29, `sort_order`=1,options='INT',template_specific=1
					where `key`='LISTING_IMAGE_WIDTH'");
		$db->query("UPDATE `xlsws_configuration` SET `title`='Product Grid image height', `configuration_type_id`=29, `sort_order`=2 ,options='INT' ,template_specific=1
					where `key`='LISTING_IMAGE_HEIGHT'");

		$db->query("UPDATE `xlsws_configuration` SET `title`='Shopping Cart image width', `configuration_type_id`=29, `sort_order`=3 ,options='INT',template_specific=1
					where `key`='MINI_IMAGE_WIDTH'");
		$db->query("UPDATE `xlsws_configuration` SET `title`='Shopping Cart image height', `configuration_type_id`=29, `sort_order`=4 ,options='INT',template_specific=1
					where `key`='MINI_IMAGE_HEIGHT'");

		$db->query("UPDATE `xlsws_configuration` SET `title`='Product Detail Image Width', `configuration_type_id`=29, `sort_order`=5 ,options='INT' ,template_specific=1
					where `key`='DETAIL_IMAGE_WIDTH'");
		$db->query("UPDATE `xlsws_configuration` SET `title`='Product Detail Image Height', `configuration_type_id`=29, `sort_order`=6 ,options='INT' ,template_specific=1
					where `key`='DETAIL_IMAGE_HEIGHT'");


		$db->add_config_key('CATEGORY_IMAGE_WIDTH' ,
			'Category Page Image Width',
			'180', 'if using a Category Page image', 29, 7,  'INT',1);
		$db->add_config_key('CATEGORY_IMAGE_HEIGHT' ,
			'Category Page Image Height',
			'180', 'if using a Category Page image', 29, 8,  'INT',1);
		$db->add_config_key('PREVIEW_IMAGE_WIDTH' ,
			'Preview Thumbnail (Product Detail Page) Width',
			'30', 'Preview Thumbnail image', 29, 9,  'INT',1);
		$db->add_config_key('PREVIEW_IMAGE_HEIGHT' ,
			'Preview Thumbnail (Product Detail Page) Height',
			'30', 'Preview Thumbnail image', 29, 10,  'INT',1);
		$db->add_config_key('SLIDER_IMAGE_WIDTH' ,
			'Slider Image Width',
			'90', 'Slider on custom pages', 29, 11,  'INT',1);
		$db->add_config_key('SLIDER_IMAGE_HEIGHT' ,
			'Slider Image Height',
			'90', 'Slider on custom pages', 29, 12,  'INT',1);
		$db->add_config_key('IMAGE_FORMAT' ,
			'Image Format',
			'jpg', 'Use .jpg or .png format for images. JPG files are smaller but slightly lower quality. PNG is higher quality and supports transparency, but has a larger file size.', 17, 18,  'IMAGE_FORMAT',0);

		$db->query("UPDATE `xlsws_configuration` SET `configuration_type_id`=17, `sort_order`=15
					where `key`='PRODUCT_ENLARGE_SHOW_LIGHTBOX'");

		$db->add_config_key('ENABLE_CATEGORY_IMAGE' ,
			'Display Image on Category Page (when set)',
			'0', 'Requires a defined Category image under SEO settings', 0, 13,  'BOOL',1);
		$db->query("update `xlsws_configuration` set template_specific=1,`configuration_type_id`=29 where `key` like '%_IMAGE_WIDTH'");
		$db->query("update `xlsws_configuration` set template_specific=1,`configuration_type_id`=29 where `key` like '%_IMAGE_HEIGHT'");
		$db->query("update `xlsws_configuration` set template_specific=1 where `key` = 'DEFAULT_TEMPLATE_THEME'");
		$db->query("update `xlsws_configuration` set template_specific=1 where `key` = 'PRODUCTS_PER_PAGE'");

		//Because of a change to the width display in Admin panel, make sure the option type is set so numbers aren't huge fields
		$db->query("UPDATE `xlsws_configuration` SET options='INT' where `key`='QUOTE_EXPIRY'");
		$db->query("UPDATE `xlsws_configuration` SET options='INT' where `key`='CART_LIFE'");
		$db->query("UPDATE `xlsws_configuration` SET options='INT' where `key`='RESET_GIFT_REGISTRY_PURCHASE_STATUS'");
		$db->query("UPDATE `xlsws_configuration` SET options='INT' where `key`='INVENTORY_LOW_THRESHOLD'");
		$db->query("UPDATE `xlsws_configuration` SET options='INT' where `key`='PRODUCTS_PER_PAGE'");
		$db->query("UPDATE `xlsws_configuration` SET options='INT' where `key`='MAX_PRODUCTS_IN_SLIDER'");
		$db->query("UPDATE `xlsws_configuration` SET options='INT' where `key`='EMAIL_SMTP_PORT'");
		$db->query("UPDATE `xlsws_configuration` SET options='INT' where `key`='MIN_PASSWORD_LEN'");

		if ($db->add_column('xlsws_modules' , 'active' ,
			"ALTER TABLE xlsws_modules ADD COLUMN `active` INT(11) DEFAULT NULL AFTER `rowid`"))
			$db->query("update xlsws_modules set active=1");


		$db->add_column('xlsws_customer' , 'check_same' ,
			"ALTER TABLE xlsws_customer ADD COLUMN `check_same` INT(11) DEFAULT NULL AFTER `zip2`");



		//Cart flash messages table
		$db->add_table('xlsws_cart_messages' , "CREATE TABLE `xlsws_cart_messages` (
				  `rowid` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `cart_id` bigint(20) DEFAULT NULL,
				  `message` text,
				  PRIMARY KEY (`rowid`),
				  KEY `cart_id` (`cart_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;");




		//Shipping options
		$db->add_config_key('SHIP_SAME_BILLSHIP' ,
			'Require Billing and Shipping Address to Match',
			'0', 'Locks the Shipping and Billing are same checkbox to not allow separate shipping address.', 25, 2,  'BOOL',0);
		$db->query("UPDATE `xlsws_configuration` SET `configuration_type_id`=25, `sort_order`=1
					where `key`='SHIP_RESTRICT_DESTINATION'");
		if ($db->add_column('xlsws_shipping_tiers' , 'class_name' ,
			"ALTER TABLE `xlsws_shipping_tiers` ADD `class_name` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `rate`;"))
			$db->query("update xlsws_shipping_tiers set `class_name`='tier_table'");



		//Drop some unused keys

		$db->query("UPDATE `xlsws_configuration` SET `title`='Display My Repairs (SROs) under My Account',
					helper_text='If your store uses SROs for repairs and uploads them to Web Store, turn this option on to allow customers to view pending repairs.'
					where `key`='ENABLE_SRO'");


		$db->query("UPDATE `xlsws_configuration` SET `value`='250' where `key`='DATABASE_SCHEMA_VERSION'");

	}
}

function up251($db)
{

	$db->query("UPDATE `xlsws_configuration` SET `title`='Product Detail Image Height' where `key`='DETAIL_IMAGE_HEIGHT'");
	$db->query("UPDATE `xlsws_configuration` SET `title`='Category Page Image Height' where `key`='CATEGORY_IMAGE_HEIGHT'");
	$db->query("UPDATE `xlsws_configuration` SET `value`='251' where `key`='DATABASE_SCHEMA_VERSION'");


}


function up252($db)
{

	$db->add_config_key('FACEBOOK_APPID' ,'Facebook App ID', '', 'Create Facebook AppID', 26, 1, NULL, 0);
	$db->query("UPDATE `xlsws_configuration` SET `value`='252' where `key`='DATABASE_SCHEMA_VERSION'");

}

function afterMigrationCleanup($db)
{
	$db->query("DELETE from xlsws_configuration WHERE `key`='ADMIN_EMAIL'");
	$db->query("DELETE FROM `xlsws_configuration` WHERE `key`='SRO_ADDITIONAL_ITEMS'");
	$db->query("DELETE FROM `xlsws_configuration` WHERE `key`='SRO_WARRANTY_OPTIONS'");
	$db->query("DELETE FROM `xlsws_configuration` where `key`='ENABLE_SEO_URL'");
	$db->query("DELETE FROM `xlsws_country` WHERE `code`='FX'");
	$db->query("DELETE from `xlsws_configuration` where `key`='CACHE_CATEGORY'");


}


function parse_php_info()
{
	if ($_SERVER['REQUEST_URI'] == "/install.php?phpinfo") {
		echo phpinfo();
		die();
	}
	ob_start();
	phpinfo();
	$phpinfotemp = array('phpinfotemp' => array());

	if (preg_match_all(
		'#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr>(.*?)\s*(?:(.*?)\s*(?:(.*?)\s*)?)?</tr>)#s',
		ob_get_clean(), $matches, PREG_SET_ORDER
	)
	) {
		foreach ($matches as $match) {
			$infotemp_keys = array_keys($phpinfotemp);
			if (strlen($match[1])) {
				$phpinfotemp[$match[1]] = array();
			} elseif (isset($match[3])) {
				$phpinfotemp[end($infotemp_keys)][] = isset($match[4]) ? array($match[3], $match[4])
					: $match[3];
			}
			else {
				$phpinfotemp[end($infotemp_keys)][] = $match[2];
			}
		}
	}
	$phpinfo = array();
	foreach ($phpinfotemp as $name => $section) {
		if ($name == "PHP Core") {
			$name = "Core";
		} //name change between 5.2 and 5.3

		foreach ($section as $key => $val) {
			preg_match_all('|<td.*>(.*)</td>|U', $val[1], $output);

			if (count($output[0])>1)
				$phpinfo[$name][trim(strip_tags($output[0][0]))] = trim(strip_tags($output[0][1]));

		}
	}
	ob_end_flush();
	return $phpinfo;
}

function xls_check_server_environment()
{
	$phpinfo = parse_php_info();
	//We check all the elements we need for a successful install and pass back the report
	$checked = array();

	$checked['MySQLi'] = isset($phpinfo['mysqli']) ? "pass" : "fail";
	$checked['PHP Session'] = ($phpinfo['session']['Session Support'] == "enabled" ? "pass" : "fail");
	$checked['cURL Support'] = isset($phpinfo['curl']) ? "pass" : "fail";
	if ($checked['cURL Support'] == "pass") {
		$checked['cURL SSL Support'] = (
		(stripos($phpinfo['curl']['cURL Information'], "OpenSSL") !== false
			|| stripos($phpinfo['curl']['cURL Information'], "NSS") !== false
			|| $phpinfo['curl']['SSL'] == "Yes") ? "pass" : "fail");
	}
	$checked['Multi-Byte String Library'] = (
	$phpinfo['mbstring']['Multibyte Support'] == "enabled" ? "pass" : "fail");
	$checked['GD Library'] = ($phpinfo['gd']['GD Support'] == "enabled" ? "pass" : "fail");
	$checked['GD Library GIF'] = ($phpinfo['gd']['GIF Create Support'] == "enabled" ? "pass" : "fail");
	if (isset($phpinfo['gd']['JPG Support']))
		$checked['GD Library JPG'] = ($phpinfo['gd']['JPG Support'] == "enabled" ? "pass" : "fail");
	else $checked['GD Library JPG']= "fail";
	if ($checked['GD Library JPG'] == "fail") {
		$checked['GD Library JPG'] = (
		$phpinfo['gd']['JPEG Support'] == "enabled" ? "pass" : "fail");
	}
	$checked['GD Library PNG'] = ($phpinfo['gd']['PNG Support'] == "enabled" ? "pass" : "fail");
//	$checked['GD Library Freetype Support'] = (
//	$phpinfo['gd']['FreeType Support'] == "enabled" ? "pass" : "fail");
	$checked['MCrypt Encryption Library'] = isset($phpinfo['mcrypt']) ? "pass" : "fail";
	$checked['session.use_cookies must be turned On'] = (
	$phpinfo['session']['session.use_cookies'] == "On" ? "pass" : "fail");
//	$checked['session.use_only_cookies must be turned Off'] = (
//	$phpinfo['session']['session.use_only_cookies'] == "Off" ? "pass" : "fail");
	$checked['PDO Library'] = isset($phpinfo['PDO']) ? "pass" : "fail";
	$checked['pdo_mysql Library'] = isset($phpinfo['pdo_mysql']) ? "pass" : "fail";
	$checked['Zip Library'] = isset($phpinfo['zip']) ? "pass" : "fail";
	$checked['Soap Library'] = ($phpinfo['soap']['Soap Client'] == "enabled" ? "pass" : "fail");
	$checked['OpenSSL'] = ($phpinfo['openssl']['OpenSSL support'] == "enabled" ? "pass" : "fail");

	//Check php.ini settings

	//Removed in 5.4.0 so just check if we're running an older version

	if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		$checked['magic_quotes_gpc in Php.ini must be turned Off'] = (
		$phpinfo['Core']['magic_quotes_gpc'] == "Off" ? "pass" : "fail");
		$checked['allow_call_time_pass_reference in Php.ini must be turned On'] = (
		$phpinfo['Core']['allow_call_time_pass_reference'] == "On" ? "pass" : "fail");
		$checked['register_globals in Php.ini must be turned Off'] = ($phpinfo['Core']['register_globals'] == "Off" ? "pass" : "fail");
		$checked['short_open_tag in Php.ini must be turned On'] = ($phpinfo['Core']['short_open_tag'] == "On" ? "pass" : "fail");
	}

	if (version_compare(PHP_VERSION, '5.4.0', '>'))
		$checked['Default timezone'] = ($phpinfo['date']['date.timezone'] == "no value" ? "fail" : "pass");


	//Check folder permissions
	if (file_exists('images'))
		$checked['/images folder must be writeable'] = (is_writable('images') ? "pass" : "fail");
	if (file_exists('assets'))
		$checked['/assets folder must be writeable'] = (is_writable('assets') ? "pass" : "fail");
	if (file_exists('runtime'))
		$checked['/runtime folder must be writeable'] = (is_writable('runtime') ? "pass" : "fail");
	if (file_exists('runtime/cache'))
		$checked['/runtime/cache folder must be writeable'] = (is_writable('runtime/cache') ? "pass" : "fail");
	if (file_exists('config'))
		$checked['/config folder must be writeable'] = (is_writable('config') ? "pass" : "fail");

	//If any of our items fail, be helpful and show them where the php.ini is. Otherwise, we hide it since working servers shouldn't advertise this
	if (in_array('fail',$checked))
		$checked = array_merge(array('<b>php.ini file is at</b> '.$phpinfo['phpinfotemp']['Loaded Configuration File']=>"pass"),$checked);
	return $checked;
}
function xls_check_upgrades()
{
	$checked = array();
	$strFolder =str_replace("/install.php","",$_SERVER['SCRIPT_NAME']);
	$strConfig = file_get_contents("includes/configuration.inc.php");
	$intSub = strpos($strConfig, '__SUBDIRECTORY__');
	$intBegin = strpos($strConfig, '\'',$intSub+18);
	$intEnd = strpos($strConfig, '\'',$intBegin+1);
	$subF = substr($strConfig,$intBegin+1,$intEnd-$intBegin-1);

	if ($strFolder != $subF) {
		$checked['SUBDIRECTORY in configuration.inc.php shows "'.$subF. '" but system detects "'.$strFolder."'"] = "fail";
	}

	$checked['<b>--Upgrade Check RESULTS BELOW--</b>'] = "pass";

	//Have we run the Upgrade Database to add new fields to the database?
	$result = _dbx_first_cell("select `key` from xlsws_configuration where `key`='DEFAULT_TEMPLATE_THEME'");
	$checked['Upgrade Database command has been run from Admin Panel'] = (
	$result == "DEFAULT_TEMPLATE_THEME" ? "pass" : "fail");
	//Have new 2.5 templates been added

	$checked['2.5 Templates added'] = file_exists("templates/brooklyn/index.tpl.php") ? "pass"
		: "fail";

	$checked['<b>Note: Specific template code changes are not checked.</b>'] = "pass";

	return $checked;
}

function arguments ( $args )
{
	$out = array();
	$last_arg = null;
	for($i = 1, $il = sizeof($args); $i < $il; $i++) {
		if( (bool)preg_match("/^--(.+)/", $args[$i], $match) ) {
			$parts = explode("=", $match[1]);
			$key = preg_replace("/[^a-z0-9]+/", "", $parts[0]);
			if(isset($parts[1])) {
				$out[$key] = $parts[1];
			}
			else {
				$out[$key] = true;
			}
			$last_arg = $key;
		}
		else if( (bool)preg_match("/^-([a-zA-Z0-9]+)/", $args[$i], $match) ) {
			for( $j = 0, $jl = strlen($match[1]); $j < $jl; $j++ ) {
				$key = $match[1]{$j};
				$out[$key] = true;
			}
			$last_arg = $key;
		}
		else if($last_arg !== null) {
			$out[$last_arg] = $args[$i];
		}
	}
	return $out;
}

function modifyArgs($arg)
{
	if(isset($arg['url']))
	{
		$url = str_replace("http://","",$arg['url']);
		$url = str_replace("https://","",$url);
		if($url[strlen($url)-1]=="/")
			$url = substr($url,0,-1);
		$arg['url'] = $url;

		if(stripos($url,"/")===false)
		{
			$_SERVER['SERVER_NAME'] = $arg['url'];
			$_SERVER['SCRIPT_NAME']=$_SERVER['PHP_SELF']="/install.php";
		} else
		{
			$marker = stripos($arg['url'],"/");
			$path=substr($arg['url'],$marker);
			$arg['url'] = substr($arg['url'],0,$marker);

			$_SERVER['SERVER_NAME'] = $arg['url'];
			$_SERVER['SCRIPT_NAME']=$_SERVER['PHP_SELF']=$path."/install.php";

		}


	}
	return $arg;
}