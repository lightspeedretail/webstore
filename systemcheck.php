<?php

const DEFAULT_UPDATER_URL = 'http://updater.lightspeedretail.com';

function displayHeader()
{
	?>
	<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Web Store System Check</title>
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
			.header-new .logo { float: left; padding: 5px 0 0; width: 268px;}
			.header-new .welcome { float: left; padding: 30px 20px 20px 10px; margin-left: 470px; font-size: 28px; }
			.table { width: 700px; margin: 0 auto; }
			.hero-unit { padding: 20px; }
			.hero-unit p { font-size: 0.9em; }
			#stats { font-size: 0.7em; }
		</style>
		<script src="http://cdn.lightspeedretail.com/bootstrap/js/jquery.min.js"></script>
		<script src="http://cdn.lightspeedretail.com/bootstrap/js/bootstrap.js"></script>
	</head>

	<body>

	<div class="header-new">
		<div class="header-inner">
			<div class="logo"><img src="//www.lightspeedpos.com/wp-content/themes/lightspeed/sharedmenu/imgs/logo-red-bl.png" alt="Lightspeed"></div>
		</div>
	</div>
	<div class="container">

<?php
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
		$checked['GD Library JPG'] = ($phpinfo['gd']['JPEG Support'] == "enabled" ? "pass" : "fail");
	}
	$checked['GD Library PNG'] = ($phpinfo['gd']['PNG Support'] == "enabled" ? "pass" : "fail");
	$checked['MCrypt Encryption Library'] = isset($phpinfo['mcrypt']) ? "pass" : "fail";
	$checked['session.use_cookies must be turned On'] = ($phpinfo['session']['session.use_cookies'] == "On" ? "pass" : "fail");
	$checked['PDO Library'] = isset($phpinfo['PDO']) ? "pass" : "fail";
	$checked['pdo_mysql Library'] = isset($phpinfo['pdo_mysql']) ? "pass" : "fail";
	$checked['Php_xml library'] = isset($phpinfo['xml']) ? "pass" : "fail";
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

	if (version_compare(PHP_VERSION, '5.3.27', '>='))
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

function xls_check_file_signatures($complete = false)
{
	$url = DEFAULT_UPDATER_URL;

	if(isset($_SERVER['WS_UPDATER_URL']))
	{
		$url = 'http://' . $_SERVER['WS_UPDATER_URL'];
		if (filter_var($url, FILTER_VALIDATE_URL) === false)
		{
			$url = DEFAULT_UPDATER_URL;
		}
	}

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
			// we delete install.php after installation.
			// it can be ignored in this check.
			if ($key !== './install.php')
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

function displaySystemCheckResult($checkenv)
{
	$warning_text = "<table id='header' class='table table-striped'>";
	?><h2>System Check</h2><?php
	$warning_text .= "<tr><td colspan='2'><b>SYSTEM CHECK for " . _ws_version() . "</b></td></tr>";
	$warning_text .= "<tr><td colspan='2'>The chart below shows the results of the system check and if upgrades have been performed.</td></tr></table>";

	$warning_text .=  "<table id='checklist' class='table table-striped'>";

	$checkenv = array_merge($checkenv, xls_check_file_signatures());

	$warning_text .= "<tr><td colspan='2'><hr></td></tr>";
	$curver = _ws_version();
	foreach ($checkenv as $key => $value) {
		$warning_text
			.= "<tr><td>$key</td><td>" . (($value == "pass" || $value == $curver) ? "$value"
				: "<font color='#cc0000'><b>$value</b></font>") . "</td></tr>";
	}


	$warning_text .= "</table>";
	?>




	<div>
		<?php echo $warning_text; ?>
	</div>
	<p>&nbsp;</p>
	</div>
	</body>

	<?php
}

function parse_php_info()
{
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

function  _ws_version()
{
	if(file_exists("core/protected/config/wsver.php"))
	{
		include_once("core/protected/config/wsver.php");

		return XLSWS_VERSION;
	}
	else return 3;
}

displayHeader();

$checkenv = xls_check_server_environment();

displaySystemCheckResult($checkenv);

