<?php
/**
 * The helper functions are globally accessible throughout the Web Store.
 *
 * @category  Controller
 * @package   Global helpers
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @copyright  Copyright &copy; 2013 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @version   3.0
 * @since     2013-05-14
 */

/**
 * Loads configuration key. Uses a manger to not load keys twice.
 * @param string key to look up
 * @param mix optional default in case key is not found
 * @return string key value
*/
// @codingStandardsIgnoreStart
function _xls_get_conf($strKey, $mixDefault = "")
// @codingStandardsIgnoreEnd
{

	if (isset(Yii::app()->params[$strKey]))
	{
		return Yii::app()->params[$strKey];
	} else {
		$objKey = Configuration::model()->find('key_name=?', array($strKey));

		if (!$objKey)
		{
			return $mixDefault;
		} else {
			return $objKey->key_value;
		}
	}
}

/**
 * Returns fully qualified URL, based on sitedir. Used to generate a link.
 *
 * @param string $strUrlPath optional
 * @return string url
 */
// @codingStandardsIgnoreStart
function _xls_site_url($strUrlPath = '')
// @codingStandardsIgnoreEnd
{
	Yii::log("Function deprecated, should use \$this->createAbsoluteUrl instead", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
	return Yii::app()->createAbsoluteUrl($strUrlPath);
}

// @codingStandardsIgnoreStart
function _xls_theme_config($strThemeName)
// @codingStandardsIgnoreEnd
{
	if(Theme::hasAdminForm($strThemeName))
	{
		return Yii::app()->getComponent('wstheme')->getAdminModel($strThemeName);
	}

	$fnOptions = YiiBase::getPathOfAlias('webroot')."/themes/".$strThemeName."/config.xml";
	if (file_exists($fnOptions))
	{
		$strXml = file_get_contents($fnOptions);
		return new SimpleXMLElement($strXml);
	} else {
		return null;
	}
}


/**
 * Get a file via cURL.
 * @param $url
 * @return bool|mixed
 */
function getFile($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$resp = curl_exec($ch);
	curl_close($ch);
	return $resp;
}

/**
 * Download latest theme. We call this during install and also on the off chance that the default suddenly
 * goes missing.
 */
function downloadTheme($strTheme)
{
	$jLatest = getFile("http://"._xls_get_conf('LIGHTSPEED_UPDATER', 'updater.lightspeedretail.com')."/site/latesttheme/".XLSWS_VERSIONBUILD."/".$strTheme);
	$result = json_decode($jLatest);
	if(empty($result))
	{
		Yii::log(
			"ERROR attempting to locate latesttheme ".$strTheme." from Lightspeed",
			'error',
			'application.'.__CLASS__.".".__FUNCTION__
		);
		return false;
	}

	$strWebstoreInstall = "http://cdn.lightspeedretail.com/webstore/themes/".$result->latest->filename;

	$data = getFile($strWebstoreInstall);
	if (stripos($data, "404 - Not Found") > 0 || empty($data))
	{
		Yii::log("ERROR downloading theme ".$strTheme." from Lightspeed CDN", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		return false;
	}

	$f = file_put_contents("themes/".$result->latest->title.".zip", $data);
	if ($f)
	{
		require_once( YiiBase::getPathOfAlias('application.components'). '/zip.php');
		extractZip($result->latest->title.".zip", '', YiiBase::getPathOfAlias('webroot.themes'));
		@unlink("themes/".$result->latest->title.".zip");
	} else {
		Yii::log("ERROR saving themes/".$result->latest->title.".zip", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		return false;
	}

	return true;
}

// @codingStandardsIgnoreStart
function _xls_regionalize($str)
// @codingStandardsIgnoreEnd
{
	$c = Yii::app()->params['DEFAULT_COUNTRY'];
	switch ($str)
	{
		case 'color':
			if ($c == 224)
			{
				return 'color';
			} else {
				return 'colour';
			}

		case 'check':
			if ($c == 224)
			{
				return 'check';
			} else {
				return 'cheque';
			}

		default:
			return $str;
	}
}


/**
 * Return the custom url version of the passed url
 *
 * @param $url
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_url_common_to_custom($url)
// @codingStandardsIgnoreEnd
{
	return str_replace(
		"https://".Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'],
		"http://".Yii::app()->params['LIGHTSPEED_HOSTING_CUSTOM_URL'],
		$url
	);
}

/**
 * Return the commom/shared url version of the passed url
 *
 * @param $url
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_url_custom_to_common($url)
// @codingStandardsIgnoreEnd
{
	return str_replace(
		"http://".Yii::app()->params['LIGHTSPEED_HOSTING_CUSTOM_URL'],
		"https://".Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'],
		$url
	);
}

/**
 * Return the Base URL for the site
 * Also perform http/https conversion if need be.
 *
 * @param boolean ssl_attempt
 * @return string url
 */
// @codingStandardsIgnoreStart
function _xls_site_dir($sslAttempt = false)
// @codingStandardsIgnoreEnd
{
	$strSsl = 'http://';
	$strHost = $_SERVER['HTTP_HOST'] . Yii::app()->getBaseUrl(false);

	if ($sslAttempt ||
		(isset($_SERVER['HTTPS']) &&
		($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == '1')))
	{
		$strSsl = 'https://';
	}

	if (substr($strHost, -1) == "/")
	{
		$strHost = substr($strHost, 0, -1);
	}

	return $strSsl . $strHost;
}

/**
 * Translate string that includes print.
 * @param string key to look up
 * @param mix optional default in case key is not found
 * @return string key value
 */
function _sp($strString, $strLocation = "global")
{
	return Yii::t($strLocation, $strString);

}

function _qalert($strString)
{
	error_log($strString);
}

// @codingStandardsIgnoreStart
function _xls_parse_name($strName)
// @codingStandardsIgnoreEnd
{
	Yii::import('ext.HumanNameParser.*');
	require_once('Name.php');
	require_once('Parser.php');

	$parser = new HumanNameParser_Parser($strName);
	return $parser;
}

/*
 * Get all active event handles for a given event
 */
// @codingStandardsIgnoreStart
function _xls_get_events($strEventHandler)
// @codingStandardsIgnoreEnd
{
	$obj = new Modules();
	$obj->category = $strEventHandler;
	$obj->active = 1;
	$dataProvider = $obj->searchEvents();
	$objModules = $dataProvider->getData();

	foreach ($objModules as $module)
	{
		// See if the extension actually exists either in custom file or our core
		if(file_exists(Yii::getPathOfAlias('custom.extensions.'.$module->module.".".$module->module).".php"))
		{
			Yii::import('custom.extensions.'.$module->module.".".$module->module);
		} elseif(file_exists(Yii::getPathOfAlias('ext.'.$module->module.".".$module->module).".php"))
		{
			Yii::import('ext.'.$module->module.".".$module->module);
		}
	}

	return $objModules;
}

// @codingStandardsIgnoreStart
function _xls_raise_events($strEvent, $objEvent)
// @codingStandardsIgnoreEnd
{
	// Attach event handlers
	$objModules = _xls_get_events($strEvent);
	foreach ($objModules as $objModule)
	{
		$objModule->instanceHandle = new $objModule->module;
		$objModule->instanceHandle->attachEventHandler($objEvent->onAction, array($objModule->instanceHandle,$objEvent->onAction));
	}

	// Raise events
	foreach ($objModules as $objModule)
	{
		Yii::log('Running event '.$strEvent.' '.$objModule->module, 'trace', 'application.'.__CLASS__.".".__FUNCTION__);
		$objModule->instanceHandle->raiseEvent($objEvent->onAction, $objEvent);
	}
}

// @codingStandardsIgnoreStart
function _xls_facebook_login()
// @codingStandardsIgnoreEnd
{
	if (!empty(Yii::app()->params['FACEBOOK_APPID']) && !empty(Yii::app()->params['FACEBOOK_SECRET']))
	{
		return true;
	} else {
		return false;
	}
}

/**
 * Converts an array of errors as generated by Yii's model getErrors() to a
 * flattened list of messages for displaying to the user.
 *
 * Duplicated messages are returned once (there would be no point in displaying
 * exactly the same message more than once).
 *
 * @param array An associative array mapping the attribute name to an an
 * indexed array of error messages (strings) associated with that attribute.
 * See below.
 * @return array An indexed array of error messages.
 *
 * Example of $arrErrors:
 * Array
 * (
 *     [first_name] => Array
 *         (
 *             [0] => First Name cannot be blank.
 *         )
 *     [last_name] => Array
 *         (
 *             [0] => Last Name cannot be blank.
 *         )
 * )
 *
 * Expected return:
 * Array
 * (
 *		[0] => First Name cannot be blank.
 *		[1] => Last Name cannot be blank.
 * )
 */
// @codingStandardsIgnoreStart
function _xls_convert_errors($arrErrors)
// @codingStandardsIgnoreEnd
{
	return array_reduce(
		$arrErrors,
		function ($collectedErrors, $attributeErrors)
		{
			return array_unique(array_merge($collectedErrors, $attributeErrors));
		},
		array()
	);
}

// @codingStandardsIgnoreStart
function _xls_convert_errors_display($arrErrors)
// @codingStandardsIgnoreEnd
{
	$strReturn = "\n";
	foreach ($arrErrors as $value)
	{
		$strReturn .= $value."\n";
	}

	return $strReturn;
}

// from http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
function hex2rgb($hex)
{
	$hex = str_replace("#", "", $hex);

	if(strlen($hex) == 3)
	{
		$r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
		$g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
		$b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
	} else {
		$r = hexdec(substr($hex, 0, 2));
		$g = hexdec(substr($hex, 2, 2));
		$b = hexdec(substr($hex, 4, 2));
	}

	$rgb = array($r, $g, $b);
// return implode(",", $rgb); // returns the rgb values separated by commas
	return $rgb; // returns an array with the rgb values
}

// @codingStandardsIgnoreStart
function json_encode_with_relations(array $models, $attributeNames)
// @codingStandardsIgnoreEnd
{
	$attributeNames = explode(',', $attributeNames);

	$rows = array(); //the rows to output
	foreach ($models as $model)
	{
		$row = array(); //you will be copying in model attribute values to this array
		foreach ($attributeNames as $name)
		{
			$name = trim($name); //in case of spaces around commas
			$row[$name] = CHtml::value($model, $name); //this function walks the relations
		}

		$rows[] = $row;
	}

	return CJSON::encode($rows);
}


// @codingStandardsIgnoreStart
function _xls_get_sort_order()
// @codingStandardsIgnoreEnd
{
	$strProperty = _xls_get_conf('PRODUCT_SORT_FIELD', 'Name');
	$blnAscend = true;

	if ($strProperty[0] == '-')
	{
		$strProperty = substr($strProperty, 1);
		$blnAscend = false;
	}

	return $strProperty . ($blnAscend ? "" : " DESC");
}

/***************
 * Below this are copied from _functions.php on the old system
 * To be verified that they're still needed and tweaked for Yii
 */


/**
 * Open a file for writing
 *
 * @param string $filename
 * @return pointer
 */
// @codingStandardsIgnoreStart
function _xls_fopen_w($filename)
// @codingStandardsIgnoreEnd
{
	$dir = dirname($filename);

	if (!file_exists($filename) && !is_writable($dir))
	{
		return false;
	}

	if (file_exists($filename) && !is_writable($filename))
	{
		return false;
	}

	$fp = fopen($filename, 'w');
	return $fp;
}

/**
 * Convert a path relative to template/ to relative to __SITEROOT__
 *
 * @param string $name :: Path relative to the template folder
 * @return string :: Converted path relative to __SITEROOT__
 */
function templateNamed($name)
{
	$file = Yii::app()->theme->baseUrl . '/'.$name;
	return $file;
}

/**
 * Convert an upper camel cased string to a database field string
 *
 * @param string $camel :: String you wish to convert
 * @return string :: Converted string corresponding to a table field
 */
// @codingStandardsIgnoreStart
function _xls_convert_camel($camel)
// @codingStandardsIgnoreEnd
{
	$output = "";
	preg_match_all('/[A-Z][^A-Z]*/', $camel, $results);

	for ($i = 0; $i < count($results[0]); $i++)
	{
		if ($i)
		{
			$output .= "_" . $results[0][$i];
		} else {
			$output .= $results[0][$i];
		}
	}

	return strtolower($output);
}

/**
 * Convert an string to camel cased string
 *
 * @param string $string :: String you wish to convert
 * @param bool $pascalCase :: Capitalize First Letter
 * @return string :: Converted string corresponding to a table field
 */

function camelize($string, $pascalCase = true)
{
	$string = str_replace(array('-', '_'), ' ', $string);
	$string = ucwords($string);
	$string = str_replace(' ', '', $string);

	if (!$pascalCase)
	{
		return lcfirst($string);
	}

	return $string;
}

/**
 * Determine whether a string is a properly formated email address
 *
 * @param string $email :: The string to test
 * @return int(bool)
 */
function isValidEmail($email) {
	$validator = new CEmailValidator;

	if(!$validator->validateValue($email))
	{
		return false;
	}

	$isValid = true;
	$atIndex = strrpos($email, "@");
	if (is_bool($atIndex) && !$atIndex)
	{
		$isValid = false;
	} else {
		$domain = substr($email, $atIndex + 1);
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);
		if ($localLen < 1 || $localLen > 64)
		{
			// local part length exceeded
			$isValid = false;
		}
		elseif ($domainLen < 1 || $domainLen > 255)
		{
			// domain part length exceeded
			$isValid = false;
		}
		elseif ($local[0] == '.' || $local[$localLen - 1] == '.')
		{
			// local part starts or ends with '.'
			$isValid = false;
		}
		elseif (preg_match('/\\.\\./', $local))
		{
			// local part has two consecutive dots
			$isValid = false;
		}
		elseif (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
		{
			// character not valid in domain part
			$isValid = false;
		}
		elseif (preg_match('/\\.\\./', $domain))
		{
			// domain part has two consecutive dots
			$isValid = false;
		}
		elseif (!preg_match(
			'/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
			str_replace("\\\\", "", $local)
		)) {
			// character not valid in local part unless
			// local part is quoted
			if (!preg_match(
				'/^"(\\\\"|[^"])+"$/',
				str_replace("\\\\", "", $local)
			))
			{
				$isValid = false;
			}
		}

		if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A")))
		{
			// domain not found in DNS
			$isValid = false;
		}
	}

	return $isValid;
}

/**
 * Convert a string into an array
 *
 * @param string $input
 * @return array
 */
function toCharArray($input) {
	$len = strlen($input);

	for ($j = 0; $j < $len; $j++)
	{
		$char[$j] = substr($input, $j, 1);
	}

	return ($char);
}

/**
 * Return values of an array as value and key (itself)
 *
 * @param array $arr The array
 * @return array
 */
// @codingStandardsIgnoreStart
function values_as_keys($arr)
// @codingStandardsIgnoreEnd
{
	// TODO: only used in Admin panel
	$ret = array();
	reset($arr);

	foreach($arr as $val)
	{
		$ret[$val] = $val;
	}

	reset($ret);
	$arr = $ret;
	return $ret;
}

/**
 * Convert a string to array broken in commas
 *
 * @param string $val
 * @return array
 */
// @codingStandardsIgnoreStart
function _xls_comma_to_array($val)
// @codingStandardsIgnoreEnd
{
	return _xls_delim_to_array($val, ',');
}

/**
 * Convert a string to array broken in commas
 *
 * @param string $val
 * @return array
 */
// @codingStandardsIgnoreStart
function _xls_delim_to_array($val, $delim = ',')
// @codingStandardsIgnoreEnd
{
	$arr = explode($delim, trim($val));
	$ret = array();
	while(list( , $item) = each($arr))
	{
		if(trim($item) != '')
		{
			$ret[$item] = $item;
		}
	}

	return $ret;
}

/**
 * Convert a string or 1 dimensional array
 * to a 2 dimensional array.
 * Primarily meant to format an error(s)
 * to mimic Yii validation error structure
 *
 * @param $arr
 * @return array
 */
// @codingStandardsIgnoreStart
function _xls_make2dimArray($arr)
// @codingStandardsIgnoreEnd
{
	if (is_array($arr) === false)
	{
		// string
		return array(array($arr));
	}

	if (is_array(current($arr)) === false)
	{
		// 1 dimension array
		$newArr = array();
		foreach ($arr as $key => $item)
		{
			$newArr[$key] = array($item);
		}

		return $newArr;
	}

	return $arr;
}

/**
 * Create a hidden for input our of a name and value
 *
 * @param string $name :: The form input's Name attr
 * @param string $value :: The value to be contained by the input
 * @return string :: An input HTML tag
 */
// @codingStandardsIgnoreStart
function _xls_make_hidden($name, $value)
// @codingStandardsIgnoreEnd
{
	return "<input type=\"hidden\" name=\"$name\" value=\"$value\">\n";
}

/**
 * Do a log entry
 *
 * @param unknown_type $msg
 */
// @codingStandardsIgnoreStart
function _xls_log($msg, $blnSysLogOnly = false)
// @codingStandardsIgnoreEnd
{
	Yii::log($msg, CLogger::LEVEL_ERROR, 'application');
}

/**
 * Get Host and IP Address
 *
 * @return string "hostname ; ipaddress"
 */
// @codingStandardsIgnoreStart
function _xls_get_ip()
// @codingStandardsIgnoreEnd
{
	$hname = @gethostbyaddr($_SERVER["REMOTE_ADDR"]);

	if(strcmp($hname, $_SERVER["REMOTE_ADDR"]) != 0)
	{
		$hname .= " ( " . $_SERVER["REMOTE_ADDR"] . " ) ";
	}

	if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
	{
		$pname = @gethostbyaddr($_SERVER["HTTP_X_FORWARDED_FOR"]);
		if(strcmp($pname, $_SERVER["HTTP_X_FORWARDED_FOR"]) != 0)
		{
			$pname .= " ( " . $_SERVER["HTTP_X_FORWARDED_FOR"] . " ) ";
		}

		$hname = $pname . " ; Proxy " . $hname;
	}

	return trim($hname);
}

/**
 * Return values of an array as value and key (itself)
 *
 * @param array $arr The array
 * @return array
 */
// @codingStandardsIgnoreStart
function _xls_values_as_keys($arr)
// @codingStandardsIgnoreEnd
{
	$ret = array();
	reset($arr);

	foreach($arr as $val)
	{
		$ret[$val] = $val;
	}

	reset($ret);
	$arr = $ret;
	return $ret;
}

/**
 * Set existing configuration value
 * @param $strKey
 * @param string $mixDefault
 * @return bool
 */
// @codingStandardsIgnoreStart
function _xls_set_conf($strKey, $mixDefault = "")
// @codingStandardsIgnoreEnd
{
	$conf = Configuration::LoadByKey($strKey);
	Yii::app()->params[$strKey] = $mixDefault;

	if(!$conf)
	{
		return false;
	}

	$conf->key_value = $mixDefault;
	$conf->modified = new CDbExpression('NOW()');
	$conf->save();
	return true;
}

/**
 * Enter a configuration into
 *
 * @param string $key
 * @param string $title
 * @param string $value
 * @param string $helper_text
 * @param int $config_type
 * @param string $options
 * @param int $sort_order
 */
// @codingStandardsIgnoreStart
function _xls_insert_conf(
// @codingStandardsIgnoreEnd
	$key,
	$title,
	$value,
	$helperText,
	$configType,
	$options,
	$sortOrder = NULL,
	$templateSpecific = 0
) {
	$conf = Configuration::LoadByKey($key);

	if(!$conf)
	{
		$conf = new Configuration();
	}

	$conf->key_name = $key;
	$conf->title = $title;
	$conf->key_value = $value;
	$conf->helper_text = $helperText;
	$conf->configuration_type_id = $configType;

	$conf->options = $options;

	$query = <<<EOS
		SELECT IFNULL(MAX(sortOrder),0)+1
		FROM xlsws_configuration
		WHERE configuration_type_id = '{$configType}';
EOS;
	if(!$sortOrder)
	{
		$sortOrder = Configuration::model()->findBySql($query);
	}

	$conf->sort_order = $sortOrder;
	$conf->template_specific = $templateSpecific;

	$conf->created = new CDbExpression('NOW()');
	$conf->modified = new CDbExpression('NOW()');

	if (!$conf->save())
	{
		print_r($conf->getErrors());
	}
}

/**
 * Standardize the ZIP format for compatibility with shipping calculators
 *
 * @param string $zip
 * @return $zip
 */
// @codingStandardsIgnoreStart
function _xls_zip_fix($zip)
// @codingStandardsIgnoreEnd
{
	$zip = trim($zip);
	$zip = str_replace(" ", "", $zip);
	$zip = strtoupper($zip);
	$zip = preg_replace('/\-[0-9][0-9][0-9][0-9]/', '', $zip);

	return $zip;
}

/**
 * Validate a ZIP against a regex
 *
 * @param string $zip
 * @param string $zippreg
 * @return boolean
 */
// @codingStandardsIgnoreStart
function _xls_validate_zip($zip, $zippreg = '')
// @codingStandardsIgnoreEnd
{
	if(!$zippreg)
	{
		return true;
	}

	$zip = _xls_zip_fix($zip);

	if(preg_match($zippreg, $zip))
	{
		return true;
	}
	else {
		return false;
	}
}

/**
 * List files within directory
 *
 * @param string $dir
 * @param string $ext :: Filter to a given file extension
 * @return array
 */
// @codingStandardsIgnoreStart
function _xls_read_dir($dir, $ext = FALSE) {
// @codingStandardsIgnoreEnd
	$ret = array();

	$handle = opendir($dir);

	while (false !== ($file = readdir($handle)))
	{
		if ($file != "." && $file != ".." &&
			($ext ? stristr($file, $ext) : TRUE))
		{
			$ret[$file] = $file;
		}
	}

	closedir($handle);
	return $ret;
}

/**
 * Add variables to the stack_vars array within the _SESSION
 * Stacks multiple if already exists
 * @param string $key
 * @param mix $value
 * @return void
 */
// @codingStandardsIgnoreStart
function _xls_stack_add($key, $value)
// @codingStandardsIgnoreEnd
{
	if(!isset($_SESSION['stack_vars'][$key]))
	{
		$_SESSION['stack_vars'][$key] = array();
	}

	$_SESSION['stack_vars'][$key][] = $value;
}

/**
 * Add variables to the stack_vars array within the _SESSION
 * Overwrites any previous value
 * @param string $key
 * @param mix $value
 * @return void
 */
// @codingStandardsIgnoreStart
function _xls_stack_put($key, $value)
// @codingStandardsIgnoreEnd
{
	$_SESSION['stack_vars'][$key] = array($value);

}
/**
 * Get a variable from the stack_vars array within the _SESSION
 * Returns the last item in the array
 *
 * @param string $key
 * return mix or false
 */
// @codingStandardsIgnoreStart
function _xls_stack_get($key)
// @codingStandardsIgnoreEnd
{
	if(isset($_SESSION['stack_vars'][$key]))
	{
		$intItemCount = count($_SESSION['stack_vars'][$key]);

		if ($intItemCount > 0)
		{
			return $_SESSION['stack_vars'][$key][$intItemCount - 1];
		}
	} else {
		return false;
	}
}

/**
 * Pop a variable from the stack_vars array within the _SESSION
 *
 * @param string $key
 * return mix or false
 */
// @codingStandardsIgnoreStart
function _xls_stack_pop($key)
// @codingStandardsIgnoreEnd
{
	if(isset($_SESSION['stack_vars'][$key]) &&
		(count($_SESSION['stack_vars'][$key]) > 0))
	{
		end($_SESSION['stack_vars'][$key]);
		$index = key($_SESSION['stack_vars'][$key]);

		$val = $_SESSION['stack_vars'][$key][$index];
		unset($_SESSION['stack_vars'][$key][$index]);

		if(count($_SESSION['stack_vars'][$key]) == 0)
		{
			$_SESSION['stack_vars'][$key] = array();
		}

		return $val;
	}else {
		return false;
	}
}

// @codingStandardsIgnoreStart
function _xls_stack_remove($key)
// @codingStandardsIgnoreEnd
{

	while (_xls_stack_pop($key))
	{
	}

	unset($_SESSION['stack_vars'][$key]);
	return true;
}
/**
 * Clear $_SESSION['stack_vars']
 */
// @codingStandardsIgnoreStart
function _xls_stack_removeall()
// @codingStandardsIgnoreEnd
{
	unset($_SESSION['stack_vars']);
}

/**
 * Display a message within the page's content section
 *
 * @param string $msg
 * @param string $redirect
 */
// @codingStandardsIgnoreStart
function _xls_display_msg($msg)
// @codingStandardsIgnoreEnd
{
	_xls_stack_add('msg', _sp($msg));

	_rd(_xls_site_url('msg/'.XLSURL::KEY_PAGE));
}

/**
 * Set last viewed page within _SESSION
 *
 * @param string $key
 * return mix or false
 */
// @codingStandardsIgnoreStart
function _xls_remember_url($strUrl)
// @codingStandardsIgnoreEnd
{ //Yii::app()->session['crumbtrail']
	if (empty($strUrl))
	{
		unset(Yii::app()->session['last_url']);
	}
	else {
		Yii::app()->session['last_url'] = $strUrl;
	}
}

/**
 * Set last viewed page within _SESSION
 *
 * @param string $key
 * return mix or false
 */
// @codingStandardsIgnoreStart
function _xls_get_remembered_url()
// @codingStandardsIgnoreEnd
{
	if (isset(Yii::app()->session['last_url']))
	{
		return (Yii::app()->session['last_url']);
	}
	else {
		return null;
	}
}

/**
 * Ensure that a client is logged in before accessing this page.
 *
 * @param string $msg
 */
// @codingStandardsIgnoreStart
function _xls_require_login($msg = false)
// @codingStandardsIgnoreEnd
{
	$uri = $_SERVER['REQUEST_URI'];

	if(!$msg)
	{
		$msg = "You are required to login to access this page.";
	}

	_xls_stack_add('login_msg', _sp($msg));
	_xls_stack_add('login_redirect_uri', $uri);

	_rd(_xls_site_url('login/'.XLSURL::KEY_PAGE.'/'));
}

/**
 * Format the name and address to be used with _xls_mail
 *
 * @param string $name
 * @param string $adde
 * @return array
 */
// @codingStandardsIgnoreStart
function _xls_mail_name($name, $adde)
// @codingStandardsIgnoreEnd
{
	return $name . ' <' . $adde . '>';
}

// @codingStandardsIgnoreStart
function _xls_send_email($id, $hideJson = false)
// @codingStandardsIgnoreEnd
{
	$objMail = EmailQueue::model()->findByPk($id);
	if ($objMail instanceof EmailQueue)
	{
		$orderEmail = _xls_get_conf('ORDER_FROM', '');
		$from = empty($orderEmail) ? _xls_get_conf('EMAIL_FROM') : $orderEmail;

		Yii::app()->setComponent('Smtpmail', null);
		$mail = Yii::app()->Smtpmail;
		//$mail->CharSet="utf-8";
		$mail->Debugoutput = "error_log";
		$mail->IsSMTP();
		$mail->Username = Yii::app()->params['EMAIL_SMTP_USERNAME'];
		$mail->Password = _xls_decrypt(Yii::app()->params['EMAIL_SMTP_PASSWORD']);
		$mail->Mailer = 'smtp';
		$mail->Port = Yii::app()->params['EMAIL_SMTP_PORT'];

		$SMTPSecure = "";
		if(Yii::app()->params['EMAIL_SMTP_SECURITY_MODE'] == '0')
		{
			if (Yii::app()->params['EMAIL_SMTP_PORT'] == "465")
			{
				$SMTPSecure = "ssl";
			}

			if (Yii::app()->params['EMAIL_SMTP_PORT'] == "587")
			{
				$SMTPSecure = "tls";
			}
		}

		if(_xls_get_conf('EMAIL_SMTP_SECURITY_MODE') == '1')
		{
			$SMTPSecure = "";
		}

		if(_xls_get_conf('EMAIL_SMTP_SECURITY_MODE') == '2')
		{
			$SMTPSecure = "ssl";
		}

		if(_xls_get_conf('EMAIL_SMTP_SECURITY_MODE') == '3')
		{
			$SMTPSecure = "tls";
		}

		$mail->SMTPAuth = true;
		$mail->AuthType = "LOGIN";
		if(_xls_get_conf('EMAIL_SMTP_AUTH_PLAIN', '0') == '1')
		{
			$mail->AuthType = "PLAIN";
		}

		if(empty(Yii::app()->params['EMAIL_SMTP_PASSWORD']))
		{
			Yii::log("Password for SMTP blank, turning off SMTP Authentication", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			$mail->SMTPAuth = false;
			$mail->Username = '';
			$mail->Password = '';
		}

		$mail->SMTPDebug = 1;
		$mail->SMTPSecure = $SMTPSecure;
		$mail->Host = Yii::app()->params['EMAIL_SMTP_SERVER'];

		$mail->SetFrom($from, Yii::app()->params['STORE_NAME']);
		$mail->Subject = $objMail->subject;
		$mail->ClearAllRecipients();
		$mail->AddAddress($objMail->to);
		if(!empty(Yii::app()->params['EMAIL_BCC']))
		{
			if($objMail->to != Yii::app()->params['EMAIL_BCC'] && $objMail->to == $from)
			{
				$mail->AddCC(Yii::app()->params['EMAIL_BCC']);
			}
		}

		$mail->MsgHTML($objMail->htmlbody);
		$blnResult = $mail->Send();

		$mail->Password = '*password removed for logging*'; //replace the real password before logging
		Yii::log("Contents of mail ".print_r($mail, true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		if($blnResult)
		{
			Yii::log("Sent email to ".$objMail->to." successfully.", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			$objMail->delete();
			Yii::log("Email removed from queue", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			if (!$hideJson)
			{
				echo json_encode("success");
			}
		} else {
			$objMail->sent_attempts += 1;
			$objMail->save();
			Yii::log(
				"Sending email failed ID ".$id." ".$objMail->to." ".
				print_r($mail->ErrorInfo, true),
				'error',
				'application.'.__CLASS__.".".__FUNCTION__
			);

			if (!$hideJson)
			{
				echo json_encode("failure");
			}
		}
	}

	return $blnResult;
}


/**
 * Make email body from templates
 *
 * @param string $templatefile
 * @param mixed $vars
 */
// @codingStandardsIgnoreStart
function _xls_mail_body_from_template($templatefile, $vars)
// @codingStandardsIgnoreEnd
{
	if(!file_exists($templatefile))
	{
		_xls_log(
			_sp("FATAL ERROR : e-mail template file not found") .
			$templatefile
		);

		return "";
	}

	try {
		extract($vars);
	} catch (Exception $exc) {
		_xls_log(
			_sp(
				"FATAL ERROR : problem extracting e-mail" .
				" variables in"
			) . " " . print_r($vars, true)
		);

		return "";
	}

	ob_start();
	include(templateNamed('email_header.tpl.php'));
	include($templatefile);
	include(templateNamed('email_footer.tpl.php'));

	$content = ob_get_contents();

	ob_end_clean();

	return $content;
}

/**
 * Trims all fields in an array
 *
 * @param memory reference to array value
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_trim(&$value)
// @codingStandardsIgnoreEnd
{
	$value = trim($value);
}

/**
 * Does a partial array search within an array for a given value
 *
 * @param string partial value to search for
 * @param array to search in
 * @return boolean true or false
 */
// @codingStandardsIgnoreStart
function _xls_array_search($needle, $haystack)
// @codingStandardsIgnoreEnd
{
	foreach($haystack as $elem)
	{
		if(preg_match('/' . $elem.'/', $needle))
		{
			return true;
		}
	}

	return false;
}

/**
 * Does a partial array search within an array that begins with a given value
 *
 * @param string partial value to search for
 * @param array to search in
 * @return boolean true or false
 */
// @codingStandardsIgnoreStart
function _xls_array_search_begin($needle, $haystack)
// @codingStandardsIgnoreEnd
{
	foreach($haystack as $elem)
	{
		if(preg_match('/^' . $elem .'/', $needle))
		{
			return true;
		}
	}

	return false;
}

// @codingStandardsIgnoreStart
function _xls_array_search_restrict_begin($needle, $haystack)
// @codingStandardsIgnoreEnd
{
	foreach($haystack as $elem)
	{
		$elem = preg_quote($elem, '/');
		$elem = substr_replace($elem, '', strpos($elem, "\\"), (strpos($elem, "\\") - strlen($elem) + 1));
		if(preg_match('/^' . $elem .'/', $needle))
		{
			return true;
		}
	}

	return false;
}

/**
 * Format a number as currency.
 * Note : This function can be overridden with _custom_currency
 *
 * @param float $num
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_currency($num, $strCountry = null)
// @codingStandardsIgnoreEnd
{
	if(function_exists('_custom_currency'))
	{
		return _custom_currency($num);
	}

	if (!is_numeric($num))
	{
		return $num;
	}

	if (is_null($strCountry))
	{
		$strCountry = _xls_get_conf('CURRENCY_DEFAULT', 'USD');
	}

	return Yii::app()->numberFormatter->formatCurrency($num, $strCountry);

}

/**
 * Remove the leading slash
 *
 * @param string $path
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_remove_leading_slash($path)
// @codingStandardsIgnoreEnd
{
	if(substr($path, 0, 1) == '/')
	{
		return substr($path, 1);
	}
	else {
		return $path;
	}
}

/**
 * Return the URL Parser Object
 * Useful if we need to find out URL properties to make decisions somewhere
 *
 * @return object
 */
// @codingStandardsIgnoreStart
function _xls_url_object()
// @codingStandardsIgnoreEnd
{
	$objUrl = XLSURL::getInstance();
	return $objUrl;
}

// Makes our SEO hyphenated string from passed string
// Used to build anything that will be in a URL.
// Same as seo_name plus lower case conversion and removing spaces
// @codingStandardsIgnoreStart
function _xls_seo_url($string)
// @codingStandardsIgnoreEnd
{
	return mb_strtolower(trim(_xls_seo_name($string), '-'), 'UTF-8');
}

// Makes our SEO hyphenated string from passed string
// Used to build anything that will be in a Name.
// @codingStandardsIgnoreStart
function _xls_seo_name($string)
// @codingStandardsIgnoreEnd
{
	$string = str_replace(array('\n', '\r', chr(13), '%'), '', $string);
	$string = str_replace('\'', '', $string);
	$string = str_replace('"', '', $string);
	$string = str_replace(array(',', '?', '!', '.'), '', $string);
	$string = str_replace("&", "and", $string);
	$string = str_replace("%", "pct", $string);
	$string = str_replace("#", "No", $string);
	$string = str_replace("+", "and", $string);
	$string = str_replace(array(" ", '/'), "-", $string);
	$string = preg_replace("`\[.*\]`U", "", $string);
	$string = preg_replace('`&(amp;)?#?[A-Za-z0-9]+;`i', '-', $string);
	$string = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i", "\\1", $string);
	$string = str_replace('-amp-', '-and-', $string);
	$string = preg_replace(array("`[^a-z0-9{Cyrillic}{Greek}{Japanese}{Chinese}{Korean}{Hebrew}{Arabic}]/u`i", "`[-]+`"), "-", $string);
	return trim($string, '- ');
}

/**
 * Escape backslashes, used for Google Conversion item description
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_jssafe_name($string)
// @codingStandardsIgnoreEnd
{
	$string = str_replace('\'', '\\\'', $string);
	$string = str_replace('&', '&amp;', $string);
	return trim($string);
}

/**
 * Escape backslashes, used for Google Conversion item description
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_ajaxclean($string)
// @codingStandardsIgnoreEnd
{
	$string = trim($string);
	$string = str_replace('\'', '\\\'', $string);
	$string = str_replace(array("\r", "\n", "\t"), '', $string);
	return trim($string);
}

/**
 * Replace double quote with single quote for Meta tags
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_meta_safe($string)
// @codingStandardsIgnoreEnd
{
	$string = str_replace('"', '\'', $string);
	//$string = str_replace("&","and",$string);
	return trim($string);
}


/**
 * Get the ID of the current customer object
 * @return int
 */
// @codingStandardsIgnoreStart
function _xls_get_current_customer_id()
// @codingStandardsIgnoreEnd
{

	$blnLoggedIn = !Yii::app()->user->isGuest;
	if ($blnLoggedIn)
	{
		return Yii::app()->user->GetId();
	} else {
		return null;
	}
}

/**
 * Get the Full Name of the current customer object
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_get_current_customer_name()
// @codingStandardsIgnoreEnd
{
	$blnLoggedIn = !Yii::app()->user->isGuest;
	if ($blnLoggedIn)
	{
		if (strlen(Yii::app()->user->fullname) < 13)
		{
			return Yii::app()->user->fullname;
		} else {
			return _xls_truncate(Yii::app()->user->firstname, 13);
		}
	} else {
		return "My Account";
	}
}

/**
 * Convert a filesystem path to a URL
 *
 * @param string $filename (path)
 * @return string $filename (fqdn path)
 */
// @codingStandardsIgnoreStart
function _xls_get_url_resource($filename)
// @codingStandardsIgnoreEnd
{
	$fl = mb_strtolower($filename);
	if(substr($fl, 0, 7) == 'http://' ||
		substr($fl, 0, 8) == 'https://')
	{
		return $filename;
	}

	$filename = str_replace(__DOCROOT__, '', $filename);
	$filename = str_replace(__SUBDIRECTORY__, '', $filename);
	$filename = __VIRTUAL_DIRECTORY__ . __SUBDIRECTORY__ . $filename;

	return $filename;
}

/**
 * Do a permanent 301 redirect
 *
 */
// @codingStandardsIgnoreStart
function _xls_301($strUrl)
// @codingStandardsIgnoreEnd
{
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: ".$strUrl);
	exit();
}

/**
 * Do a 404 fail
 *
 */
// @codingStandardsIgnoreStart
function _xls_404()
// @codingStandardsIgnoreEnd
{
	throw new CHttpException(404, 'The requested page does not exist.');
}

/**
 * Redirect to a given URL
 * @param string $url
 */
function _rd($url = '')
{

	if(empty($url))
	{
		$url = $_SERVER["REQUEST_URI"];
		//_xls_site_url will append subfolder again so get rid of it here
		if (__SUBDIRECTORY__)
		{
			$url = substr($url, strlen(__SUBDIRECTORY__), 999);
		}
	}

	header("Location: "._xls_site_url($url));
	exit();
}


/**
 * Return a customer address as a formatted string
 *
 * @param CustomerAddress $objAddress
 * @return null|string
 */
// @codingStandardsIgnoreStart
function _xls_string_address($objAddress)
// @codingStandardsIgnoreEnd
{
	if ($objAddress instanceof CustomerAddress === false)
	{
		Yii::log('Invalid CustomerAddress object', 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
		return null;
	}

	$str = '';
	$str .= $objAddress->address1 . ', ';
	$str .= $objAddress->address2 ? $objAddress->address2 . ', ' : '';
	$str .= $objAddress->city . ', ';
	$str .= $objAddress->state_id ? State::CodeById($objAddress->state_id) . ', ' : '';
	$str .= Country::CodeById($objAddress->country_id) . ', ';
	$str .= $objAddress->postal;

	return $str;
}

/**
 * Return an html formatted string of the Store Address
 *
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_html_storeaddress()
// @codingStandardsIgnoreEnd
{
	$str = '';
	$str .= Yii::app()->params['STORE_ADDRESS1'] . '<br>';
	$str .= Yii::app()->params['STORE_ADDRESS2'] ? Yii::app()->params['STORE_ADDRESS2'] . '<br>' : '';
	$str .= Yii::app()->params['STORE_CITY'] ? Yii::app()->params['STORE_CITY'] . ', ' : '';
	$str .= Yii::app()->params['STORE_STATE'] ? State::CodeById(Yii::app()->params['STORE_STATE']) . '<br>' : '';
	$intIdCountry = Yii::app()->params['STORE_COUNTRY'];
	if (is_null($intIdCountry) === false)
	{
		if (Country::CodeById($intIdCountry) !== _xls_country())
		{
			$str .= Yii::app()->params['STORE_COUNTRY'] ? Country::CountryById(Yii::app()->params['STORE_COUNTRY']) . '<br>' : '';
		}
	}

	$str .= Yii::app()->params['STORE_ZIP'] ? Yii::app()->params['STORE_ZIP'] : '';

	return $str;
}

/**
 * Return an html formatted string of store pickup details
 *
 * @param ShoppingCart $objCart
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_html_storepickupdetails($objCart)
// @codingStandardsIgnoreEnd
{
	if ($objCart === null)
	{
		$objCart = Yii::app()->shoppingcart;
	}

	$str = '';
	if ($objCart->shipping->isStorePickup)
	{
		$str .= $objCart->shipaddress->first_name . ' ' . $objCart->shipaddress->last_name . '<br>';
		$str .= $objCart->shipaddress->store_pickup_email ? $objCart->shipaddress->store_pickup_email : $objCart->customer->email;
		$str .= $objCart->shipaddress->phone ? '<br>' . $objCart->shipaddress->phone : '';
	}
	else
	{
		Yii::log(
			sprintf('CartShipping for cart id: %d is not store pickup', $objCart->id),
			'error',
			'application.'.__CLASS__.'.'.__FUNCTION__
		);
	}

	return $str;
}


/**
 * Return an html formatted string version of the Cart's shipping address
 *
 * @param ShoppingCart $objCart
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_html_shippingaddress($objCart)
// @codingStandardsIgnoreEnd
{
	if ($objCart === null)
	{
		$objCart = Yii::app()->shoppingcart;
	}

	$str = '';
	$str .= $objCart->shipaddress->address1 . '<br>';
	$str .= $objCart->shipaddress->address2 ? $objCart->shipaddress->address2 . '<br>' : '';
	$str .= $objCart->shipaddress->city . ', ';
	$str .= $objCart->shipaddress->state_id ? State::CodeById($objCart->shipaddress->state_id) . ', ' : '';
	$str .= $objCart->shipaddress->postal ? $objCart->shipaddress->postal . '<br>' : '';

	if (_xls_get_conf('DEFAULT_COUNTRY') != $objCart->shipaddress->country_id)
	{
		$str .= Country::CountryById($objCart->shipaddress->country_id);
	}

	return $str;

}

/**
 * Return an html formatted string version of the Cart's billing address
 *
 * @param ShoppingCart $objCart
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_html_billingaddress($objCart)
// @codingStandardsIgnoreEnd
{
	if ($objCart === null)
	{
		$objCart = Yii::app()->shoppingcart;
	}

	if ($objCart->billaddress_id === null)
	{
		return '';
	}

	if ($objCart->shipaddress_id == $objCart->billaddress_id)
	{
		return _xls_html_shippingaddress($objCart);
	}

	$str = '';
	$str .= $objCart->billaddress->address1 . '<br>';
	$str .= $objCart->billaddress->address2 ? $objCart->billaddress->address2 . '<br>' : '';
	$str .= $objCart->billaddress->city . ', ';
	$str .= $objCart->billaddress->state_id ? State::CodeById($objCart->billaddress->state_id) . ', ' : '';
	$str .= $objCart->billaddress->postal ? $objCart->billaddress->postal . '<br>' : '';

	if (_xls_get_conf('DEFAULT_COUNTRY') != $objCart->billaddress->country_id)
	{
		$str .= Country::CountryById($objCart->billaddress->country_id);
	}

	return $str;
}



/**
 * Truncate a string to a given length
 * This function can be extended with _custom_truncate
 *
 * @param string $text
 * @param int $len
 * @param string $etc
 */
// @codingStandardsIgnoreStart
function _xls_truncate($text, $len, $etc = "&hellip;")
// @codingStandardsIgnoreEnd
{
	if(function_exists('_custom_truncate'))
	{
		return _custom_truncate($text, $len, $etc);
	}

	return _xls_string_smart_truncate($text, $len, $etc);
}

/**
 * Truncate a string
 *
 * @param mb_string $strText
 * @param integer $intLimit
 * @param string $strEncoding
 * @return mb_string $strOut
 */
// @codingStandardsIgnoreStart
function _xls_string_smart_truncate($strText, $intLimit, $etc,
// @codingStandardsIgnoreEnd
	$strEncoding = "utf-8")
{

	$strOut = rtrim($strText);

	// if the text length is longer than the limit set
	if (mb_strlen($strOut, $strEncoding) > $intLimit)
	{
		$strStop = XLS_TRUNCATE_PUNCTUATIONS;
		$strStop .= html_entity_decode("&raquo;", ENT_COMPAT, "utf-8");

		$strOut = mb_substr($strText, 0, $intLimit, $strEncoding);
		$charArray = _xls_string_split($strOut, $strEncoding);

		$posStop = null;
		$blnFlagStop = false;

		$intChars = 0;

		for ($i = $intLimit - 1; $i >= 0; $i--)
		{
			if (mb_strpos($strStop, $charArray[$i]) ||
				ctype_space($charArray[$i]))
			{
				$posStop = $i;
			} else {
				if ($intChars > 0)
				{
					break;
				}

				$intChars++;
			}
		}

		if (!is_null($posStop) && ($posStop != 0))
		{
			$intLimit = $posStop;
		}

		$strOut = mb_substr($strOut, 0, $intLimit, $strEncoding);
		$strOut .= html_entity_decode($etc, ENT_COMPAT, "utf-8");
	}

	return $strOut;
}

/**
 * Split a string and get an array with its characters
 *
 * @param mb_string $strText
 * @param string $strEncoding
 * @return mb_string $strOut
 */
// @codingStandardsIgnoreStart
function _xls_string_split($strText, $strEncoding = "utf-8")
// @codingStandardsIgnoreEnd
{
	$intLength = mb_strlen($strText, $strEncoding);
	$charArray = array();

	// start from the left and chop off one character a time
	while ($intLength)
	{
		$charArray[] = mb_substr($strText, 0, 1, $strEncoding);
		$strText = mb_substr($strText, 1, $intLength, $strEncoding);
		$intLength = mb_strlen($strText, $strEncoding);
	}

	return $charArray;
}

/**
 * Return a number out of a string only
 * @param string $string
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_number_only($string)
// @codingStandardsIgnoreEnd
{
	return preg_replace('/[^0-9]/', '', $string);
}

/**
 * Return a number out of a string only
 * @param string $string
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_letters_only($string)
// @codingStandardsIgnoreEnd
{
	return preg_replace('/[^A-Za-z]/', '', $string);
}

/**
 * Return a currency string removing anything not allowed
 * Note this is not handling European-formatted currency (, separator)
 * @param string $string
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_clean_currency($string)
// @codingStandardsIgnoreEnd
{
	return preg_replace('/[^0-9\.\-]/', '', $string);
}

/**
 * Add meta redirect to the stack_vars stack
 * @param string $url
 * @param int $delay
 */
// @codingStandardsIgnoreStart
function _xls_add_meta_redirect($url, $delay = 60)
// @codingStandardsIgnoreEnd
{
	_xls_stack_add(
		'xls_meta_redirect',
		array('url' => $url , 'delay' => $delay)
	);
}

/**
 * Set the page title
 * @param string $title
 */
// @codingStandardsIgnoreStart
function _xls_add_page_title($title)
// @codingStandardsIgnoreEnd
{
	global $strPageTitle;
	$strPageTitle = $title;
	_xls_stack_put('xls_page_title', $title);
}

/**
 * Set the page title combined with storename (or other wildcard pattern)
 * @param string $title
 */
// @codingStandardsIgnoreStart
function _xls_add_formatted_page_title($title)
// @codingStandardsIgnoreEnd
{
	_xls_stack_put(
		'xls_page_title',
		_xls_get_formatted_page_title($title)
	);
}

/**
 * Set the page title combined with storename (or other wildcard pattern)
 * @param string $title
 */
// @codingStandardsIgnoreStart
function _xls_get_formatted_page_title($title, $meta = null)
// @codingStandardsIgnoreEnd
{
	if(is_null($meta))
	{
		$meta = _xls_get_conf('SEO_CUSTOMPAGE_TITLE');
	}

	return
		Yii::t(
			'global',
			$meta,
			array(
				'{name}' => $title,
				'{storename}' => _xls_get_conf('STORE_NAME', 'Lightspeed Web Store'),
				'{storetagline}' => _xls_get_conf('STORE_TAGLINE', 'Amazing products available to order online!'),
			)
		);
}

/**
 * Return Email Subject
 * Note that this doesn't populate orderid or customername, that has to be done in skeleton
 * @param string $title
 */
// @codingStandardsIgnoreStart
function _xls_format_email_subject($key = 'EMAIL_SUBJECT_CUSTOMER', $customer = "", $orderid = "")
// @codingStandardsIgnoreEnd
{
	$strPattern = _xls_get_conf($key);

	return
		Yii::t(
			'email',
			$strPattern,
			array(
				'{customername}' => $customer,
				'{orderid}' => $orderid,
				'{storename}' => _xls_get_conf('STORE_NAME', 'Lightspeed Web Store'),
			)
		);
}

/**
 * Add meta description to the stack_vars stack
 * @param string $desc
 */
// @codingStandardsIgnoreStart
function _xls_add_meta_desc($desc)
// @codingStandardsIgnoreEnd
{
	_xls_stack_put('xls_meta_desc', strip_tags($desc));
}

/**
 * Save crumbtrail to session
 * @param array $arrCrumbs
 */
// @codingStandardsIgnoreStart
function _xls_set_crumbtrail($arrCrumbs = null)
// @codingStandardsIgnoreEnd
{

	if($arrCrumbs)
	{
		Yii::app()->session['crumbtrail'] = $arrCrumbs;
	} else {
		unset(Yii::app()->session['crumbtrail']);
	}
}

/**
 * Retrieve crumbtrail, either full array with links or just names
 * @param string $type
 * @return $array
 */
// @codingStandardsIgnoreStart
function _xls_get_crumbtrail($type = 'full')
// @codingStandardsIgnoreEnd
{

	if (!isset(Yii::app()->session['crumbtrail']))
	{
		return array();
	}

	if ($type == 'full')
	{
		return Yii::app()->session['crumbtrail'];
	}

	$arrCrumbs = Yii::app()->session['crumbtrail'];
	$retArray = array();
	foreach ($arrCrumbs as $crumb)
	{
		$retArray[] = $crumb['name'];
	}

	return $retArray;

}

/**
 * Retrieve Google Category based on Product RowId
 * @param int $intProductRowid
 * @return $string
 */
// @codingStandardsIgnoreStart
function _xls_get_googlecategory($intProductRowid)
// @codingStandardsIgnoreEnd
{

	$objGoogle = Yii::app()->db->createCommand(
		"SELECT d.name0, extra
		FROM ".ProductCategoryAssn::model()->tableName()." AS a
		LEFT JOIN ".Category::model()->tableName()." AS b ON a.category_id=b.id
		LEFT JOIN ".CategoryIntegration::model()->tableName()." AS c ON a.category_id=c.category_id
		LEFT JOIN ".CategoryGoogle::model()->tableName()." as d ON c.foreign_id=d.id
		WHERE c.module='google' AND a.product_id=".$intProductRowid
	)->queryRow();

	$strLine = $objGoogle['name0'];
	$strLine = str_replace("&", "&amp;", $strLine);
	$strLine = str_replace(">", "&gt;", $strLine);

	$arrGoogle = array();
	$arrGoogle['Category'] = trim($strLine);
	if (!empty($objGoogle['extra']))
	{
		$arrX = explode(",", $objGoogle['extra']);
		$arrGoogle['Gender'] = $arrX[0];
		$arrGoogle['Age'] = $arrX[1];
	}

	return $arrGoogle;
}

// @codingStandardsIgnoreStart
function _xls_get_googleparentcategory($intProductRowid)
// @codingStandardsIgnoreEnd
{
	$arrTrailFull = Category::GetTrailByProductId($intProductRowid);
	$objCat = Category::model()->findbyPk($arrTrailFull[0]['key']);
	$objPar = $objCat->integration->google;

	if ($objPar)
	{
		$strLine = $objPar->name0;
		$strMeta = $objPar->extra;
	} else {
		$strLine = "";
	}

	$strLine = str_replace("&", "&amp;", $strLine);
	$strLine = str_replace(">", "&gt;", $strLine);

	$arrGoogle = array();
	$arrGoogle['Category'] = trim($strLine);
	if (!empty($strMeta))
	{
		$arrX = explode(",", $strMeta);
		$arrGoogle['Gender'] = $arrX[0];
		$arrGoogle['Age'] = $arrX[1];
	}

	return $arrGoogle;

}


/**
 * Return the Web Store's version
 * @return string
 */
// @codingStandardsIgnoreStart
function _xls_version()
// @codingStandardsIgnoreEnd
{ // LEGACY
	return XLSWS_VERSION;
}

/**
 * Are we being browsed on an iDevice (checks for both devices)
 * @return bool
 */
// @codingStandardsIgnoreStart
function _xls_is_idevice()
// @codingStandardsIgnoreEnd
{
	if (_xls_is_ipad() || _xls_is_iphone())
	{
		return true;
	} else {
		return false;
	}
}

/**
 * Are we being browsed on an iPad
 * @return bool
 */
// @codingStandardsIgnoreStart
function _xls_is_ipad()
// @codingStandardsIgnoreEnd
{
	if(isset($_SERVER['HTTP_USER_AGENT']))
	{
		return (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'iPad');
	} else {
		return false;
	}
}

/**
 * Are we being browsed on an iPhone/Ipod Touch
 * @return bool
 */
// @codingStandardsIgnoreStart
function _xls_is_iphone()
// @codingStandardsIgnoreEnd
{
	if(isset($_SERVER['HTTP_USER_AGENT']) &&
		(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') ||
		strpos($_SERVER['HTTP_USER_AGENT'], 'iPod')))
	{
		return true;
	}

	return false;
}

/**
 * Initialize a new language.
 *
 * @param string $strLangCode
 * @param string $strCountryCode (optional)
 */
// @codingStandardsIgnoreStart
function _xls_lang_init($strLangCode, $strCountryCode = '')
// @codingStandardsIgnoreEnd
{
	if (Yii::app()->language != $strLangCode)
	{
		Yii::app()->language = $strLangCode;
		if (!empty($strCountryCode))
		{
			Yii::app()->session['country_code'] = $strCountryCode;
		}

		return;
	}
}

// @codingStandardsIgnoreStart
function _xls_avail_languages()
// @codingStandardsIgnoreEnd
{
	$data = array();
	foreach (explode(",", _xls_get_conf('LANG_OPTIONS', 'en_us')) as $cLine)
	{
		list ($cKey, $cValue) = explode(':', $cLine, 2);
		$data[$cKey] = $cValue;
	}

	return $data;
}

// @codingStandardsIgnoreStart
function _xls_check_version()
// @codingStandardsIgnoreEnd
{
	if(!Yii::app()->theme)
	{
		return false;
	}

	$url = "http://"._xls_get_conf('LIGHTSPEED_UPDATER', 'updater.lightspeedretail.com');

	Yii::log("Checking Version (and reporting stats) to $url", 'info', 'application.'.__CLASS__.".".__FUNCTION__);

	$storeurl = Yii::app()->createAbsoluteUrl("/");
	$storeurl = str_replace("http://", "", $storeurl);
	$storeurl = str_replace("https://", "", $storeurl);

	$strTheme = Yii::app()->theme->name;
	$strThemeVersion = (Yii::app()->theme->info->noupdate ? "noupdate" : Yii::app()->theme->info->version);

	if(isset($_SERVER['SERVER_SOFTWARE']))
	{
		$serversoftware = $_SERVER['SERVER_SOFTWARE'];
	}
	else {
		$serversoftware = "";
	}

	$data['webstore'] = array(
		'version'       => XLSWS_VERSIONBUILD,
		'customer'      => $storeurl,
		'type'          => (_xls_get_conf('LIGHTSPEED_HOSTING') == 1 ? "hosted" : "self"),
		'track'         => (_xls_get_conf('AUTO_UPDATE_TRACK', '0') == 1 ? "beta" : "release"),
		'autoupdate'    => (_xls_get_conf('AUTO_UPDATE', '1') == 1 ? "1" : "0"),
		'theme'         => $strTheme,
		'serversoftware' => $serversoftware,
		'themeversion'  => $strThemeVersion,
		'schema'  => _xls_get_conf('DATABASE_SCHEMA_VERSION', '447'),
		'cid'  => _xls_get_conf('LIGHTSPEED_CID'),
		'phpversion'  => PHP_VERSION,
		'themefiles' => _xls_theme_report(),
		'configuration' => _xls_configuration_report()

	);
	if(Yii::app()->params['LIGHTSPEED_MT'] == '1')
	{
		// Since we could have two urls on multitenant, just grab the original one
		$data['webstore']['customer'] = Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'];
		$data['webstore']['type'] = "mt-pro";
		if(Yii::app()->params['LIGHTSPEED_CLOUD'] > 0)
		{
			$data['webstore']['type'] = "mt-cloud";
			$data['webstore']['cid'] = Yii::app()->params['LIGHTSPEED_CLOUD'];
		}
	}

	Yii::log("sending to stats ".print_r($data, true), 'trace', 'application.'.__CLASS__.".".__FUNCTION__);
	$json = json_encode($data);

	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_VERBOSE, 0);

	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt(
		$ch,
		CURLOPT_HTTPHEADER,
		array("Content-type: application/json")
	);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

	$resp = curl_exec($ch);
	curl_close($ch);

	if(Yii::app()->params['LIGHTSPEED_HOSTING'] != 1 && _xls_check_migrations())
	{
		$oXML = json_decode($resp);
		if (!empty($oXML) && isset($oXML->webstore))
		{
			$oXML->webstore->schema = "update";
		}

		$resp = json_encode($oXML);
	}

	return $resp;
}

/**
 * Check for pending migrations.
 *
 * @return bool
 */
// @codingStandardsIgnoreStart
function _xls_check_migrations()
// @codingStandardsIgnoreEnd
{

	$arrFiles = glob(Yii::getPathOfAlias('application').'/migrations/*.php');
	$arrMigrations = preg_grep('/(m(\d{6}_\d{6})_.*?)\.php$/', $arrFiles);
	$intCount = Yii::app()->db->createCommand("select count(*) from xlsws_migrations")->queryScalar();

	return ((count($arrMigrations) + 1) > $intCount);
}


// In order to evaluate view layer changes impact, we need to know
// what files have changed in a customer theme. (This is useful for
// judging risk during the development process)
// We simply take an md5 hash of each file in the theme and compare
// it to the hash of our original shipping file. Contents of the file
// are not sent.
// @codingStandardsIgnoreStart
function _xls_theme_report()
// @codingStandardsIgnoreEnd
{
	$retVal = getThemeFiles(YiiBase::getPathOfAlias('webroot.themes').'/'.Yii::app()->theme->name);
	return serialize($retVal);
}

// @codingStandardsIgnoreStart
function getThemeFiles($dir)
// @codingStandardsIgnoreEnd
{
	$files = array();
	if ($handle = opendir($dir))
	{
		while (false !== ($file = readdir($handle)))
		{
			if ($file != "." && $file != "..")
			{
				if(is_dir($dir.'/'.$file))
				{
					$dir2 = $dir.'/'.$file;
					$files[] = getThemeFiles($dir2);
				}
				else {
					// We only care about php and css files
					if(substr($file, -4) == ".php" || substr($file, -4) == ".css")
					{
						$files[] = str_replace(
							YiiBase::getPathOfAlias('webroot.themes').'/'.Yii::app()->theme->name."/",
							"",
							$dir
						) . '/'.$file.",".md5_file($dir.'/'.$file);
					}
				}
			}
		}

		closedir($handle);
	}

	return array_flat($files);
}

// @codingStandardsIgnoreStart
function array_flat($array)
// @codingStandardsIgnoreEnd
{
	$tmp = array();
	foreach($array as $a)
	{
		if(is_array($a))
		{
			$tmp = array_merge($tmp, array_flat($a));
		}
		else {
			$tmp[] = $a;
		}
	}

	return $tmp;
}


// @codingStandardsIgnoreStart
function _xls_configuration_report()
// @codingStandardsIgnoreEnd
{
	// In order to make impact decisions, report how these configuration keys are set
	// This array will report either the value or simply if they are set (in cases of sensitive data)
	$arrKeysToReport = array(
		'EMAIL_FROM' => 'value',
		'CURRENCY_DEFAULT' => 'value',
		'FACEBOOK_APPID' => 'boolean',
		'GOOGLE_ADWORDS' => 'boolean',
		'GOOGLE_ANALYTICS' => 'boolean',
		'ENABLE_SSL' => 'value',
		'ADMIN_PANEL' => 'value',
		'LANGUAGES' => 'value',
		'LANG_MENU' => 'value'
	);

	// Report active modules
	$retVal = CHtml::listData(Modules::model()->findAllByAttributes(array('active' => 1)), 'module', 'active');

	// How big are some of these tables, affects upgrade processing time when we have to add indexes, etc. Trying to avoid timeouts.
	$retVal['CART_LIFE'] = Yii::app()->db->createCommand("SELECT count(*) as thecount FROM xlsws_cart WHERE cart_type=4 AND modified > '".date("Y-m-d", strtotime("-30 DAYS"))."'")->queryScalar();
	$retVal['WEB_PRODUCTS'] = Yii::app()->db->createCommand("SELECT count(*) as thecount FROM xlsws_product WHERE web=1")->queryScalar();
	$retVal['TOTAL_PRODUCTS'] = Yii::app()->db->createCommand("SELECT count(*) as thecount FROM xlsws_product")->queryScalar();
	$retVal['TOTAL_CATEGORIES'] = Yii::app()->db->createCommand("SELECT count(*) as thecount FROM xlsws_category")->queryScalar();
	foreach ($arrKeysToReport as $key => $value)
	{
		$retVal[$key] = ($value == "boolean" ? (empty(Yii::app()->params[$key]) ? 0 : 1) : Yii::app()->params[$key]);
	}

	return serialize($retVal);
}

// @codingStandardsIgnoreStart
function _xls_parse_language($string)
// @codingStandardsIgnoreEnd
{
	$pattern = "|<".Yii::app()->language.".*>(.*)</".Yii::app()->language.">|U";

	preg_match_all($pattern, $string, $output);
	if (is_array($output) && count($output) > 0 && count($output[1]) > 0)
	{
		return $output[1][0];
	} else {
		$patternDefaultLang = "/^(.*?)<\b(".str_replace(",", "|", _xls_get_conf('LANGUAGES', 'en')).")/";
		preg_match_all($patternDefaultLang, $string, $output);

		if (is_array($output) && count($output) > 0 && count($output[1]) > 0)
		{
			return $output[1][0];
		}
	}

	return $string;
}

// @codingStandardsIgnoreStart
function _xls_parse_language_serialized($string)
// @codingStandardsIgnoreEnd
{
	$output = @unserialize($string);
	if (empty($output) || !is_array($output))
	{
		$output = array(Yii::app()->language => $string);
	}

	return $output;
}

/**
 * Encrypt the Web Store key
 * @param string $text
 * @param boolean $key
 */
// @codingStandardsIgnoreStart
function _xls_key_encrypt($text, $key = false)
// @codingStandardsIgnoreEnd
{
	if(!$key)
	{
		$key = _xls_get_conf('LSKEY', 'password');
	}

	$text = trim($text);
	$ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
	$enc = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);

	return $enc;
}

/**
 * Decrypt the Web Store key
 * @param string $enc
 * @param boolean $key
 */
// @codingStandardsIgnoreStart
function _xls_key_decrypt($enc, $key = false)
// @codingStandardsIgnoreEnd
{
	if(!$key)
	{
		$key = _xls_get_conf('LSKEY', 'password');
	}

	$ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
	$crypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $enc, MCRYPT_MODE_ECB, $iv);

	return trim($crypttext);
}


// @codingStandardsIgnoreStart
function _xls_encrypt($msg)
// @codingStandardsIgnoreEnd
{
	if(file_exists(YiiBase::getPathOfAlias('config')."/wskeys.php"))
	{
		$existingKeys = require(YiiBase::getPathOfAlias('config')."/wskeys.php");
		$pass = $existingKeys['key'];
		$salt = $existingKeys['salt'];
		$cryptastic = new cryptastic;

		$key = $cryptastic->pbkdf2($pass, $salt, 30000, 32);
		$encrypted = $cryptastic->encrypt($msg, $key, true);

		return $encrypted;
	}
	else {
		die("missing wskeys");
	}
}

// @codingStandardsIgnoreStart
function _xls_decrypt($msg)
// @codingStandardsIgnoreEnd
{
	if(file_exists(YiiBase::getPathOfAlias('config')."/wskeys.php"))
	{
		$existingKeys = require(YiiBase::getPathOfAlias('config')."/wskeys.php");
		$pass = $existingKeys['key'];
		$salt = $existingKeys['salt'];

		$cryptastic = new cryptastic;

		$key = $cryptastic->pbkdf2($pass, $salt, 30000, 32);

		$decrypted = $cryptastic->decrypt($msg, $key, true);

		return $decrypted;
	}
	else {
		die("missing wskeys");
	}
}

/**
 * Return an array containing a list of timezone names
 */
// @codingStandardsIgnoreStart
function _xls_timezones()
// @codingStandardsIgnoreEnd
{
	$zones = array('Africa','America','Antarctica','Arctic','Asia',
		'Atlantic','Australia','Europe','Indian','Pacific','Canada','US');
	$results = array();

	foreach (timezone_identifiers_list() as $zone)
	{
		// Split the value into 0=>Continent, 1=>City
		$zone = explode('/', $zone);

		if (in_array($zone[0], $zones))
		{
			if (isset($zone[1]) != '')
			{
				$results[] = implode('/', $zone);
			}
		}
	}

	$results = array_combine($results, $results);
	return $results;
}

/**
 * Determine whether to show the captcha to the user
 */
// @codingStandardsIgnoreStart
function _xls_show_captcha($strPage = "checkout")
// @codingStandardsIgnoreEnd
{
	switch ($strPage)
	{
		case 'register':
			$strKey = "CAPTCHA_REGISTRATION";
			break;
		case 'contactus':
			$strKey = "CAPTCHA_CONTACTUS";
			break;
		case 'checkout':
		default:
			$strKey = "CAPTCHA_CHECKOUT";
			break;
	}

	if (_xls_get_conf($strKey, '0') == '2' || (Yii::app()->user->isGuest && _xls_get_conf($strKey, '0') == '1'))
	{
		return true;
	} else {
		return false;
	}
}

// @codingStandardsIgnoreStart
function _xls_country()
// @codingStandardsIgnoreEnd
{
	$objCountry = Country::Load(_xls_get_conf('DEFAULT_COUNTRY', 39));
	return $objCountry->code;
}


// @codingStandardsIgnoreStart
function _xls_recalculate_inventory()
// @codingStandardsIgnoreEnd
{
	$strField = (_xls_get_conf('INVENTORY_FIELD_TOTAL', '') == 1 ? "inventory_total" : "inventory");

	$dbC = Yii::app()->db->createCommand();
	$dbC->setFetchMode(PDO::FETCH_OBJ);//fetch each row as Object

	$dbC->select()->from(Product::model()->tableName())->where('web=1 AND '.$strField.'>0 AND
		inventory_reserved=0 AND inventory_avail=0 AND
		master_model=0')->order('id')->limit(1000);

	foreach ($dbC->queryAll() as $item)
	{
		$objProduct = Product::model()->findByPk($item->id);
		$objProduct->inventory_reserved = $objProduct->CalculateReservedInventory();
		$objProduct->inventory_avail = $objProduct->inventory;
		$objProduct->save();
	}

	$ctPic = Yii::app()->db->createCommand("SELECT count(*) as thecount FROM xlsws_product WHERE web=1 AND ".$strField.">0 AND inventory_reserved=0 AND inventory_avail=0 AND master_model=0")->queryScalar();
	return $ctPic;
}

// @codingStandardsIgnoreStart
function mb_pathinfo($filepath, $portion = null)
// @codingStandardsIgnoreEnd
{
	preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im', $filepath, $m);
	if(isset($m[1]))
	{
		$ret['dirname'] = $m[1];
	}

	if(isset($m[2]))
	{
		$ret['basename'] = $m[2];
	}

	if(isset($m[5]))
	{
		$ret['extension'] = $m[5];
	}

	if(isset($m[3]))
	{
		$ret['filename'] = $m[3];
	}

	if ($portion == PATHINFO_DIRNAME)
	{
		return $ret['dirname'];
	}

	if ($portion == PATHINFO_BASENAME)
	{
		return $ret['basename'];
	}

	if ($portion == PATHINFO_EXTENSION)
	{
		return $ret['extension'];
	}

	if ($portion == PATHINFO_FILENAME)
	{
		return $ret['filename'];
	}

	return $ret;
}

// @codingStandardsIgnoreStart
function convert_number_to_words($number)
// @codingStandardsIgnoreEnd
{
	$hyphen = $negative = "-";
	$conjunction = "and";
	$separator = ",";
	$decimal = ".";
	$dictionary = array(
		0					=> 'Zero',
		1                   => 'One',
		2                   => 'Two',
		3                   => 'Three',
		4                   => 'Four',
		5                   => 'Five',
		6                   => 'Six',
		7                   => 'Seven',
		8                   => 'Eight',
		9                   => 'Nine',
		10                  => 'Ten',
		11                  => 'Eleven',
		12                  => 'Twelve',
		13                  => 'Thirteen',
		14                  => 'Fourteen',
		15                  => 'Fifteen',
		16                  => 'Sixteen',
		17                  => 'Seventeen',
		18                  => 'Eighteen',
		19                  => 'Nineteen',
		20                  => 'Twenty',
		30                  => 'Thirty',
		40                  => 'Fourty',
		50                  => 'Fifty',
		60                  => 'Sixty',
		70                  => 'Seventy',
		80                  => 'Eighty',
		90                  => 'Ninety',
		100                 => 'Hundred',
		1000                => 'Thousand',
		1000000             => 'Million',
		1000000000          => 'Billion',
		1000000000000       => 'Trillion',
		1000000000000000    => 'Quadrillion',
		1000000000000000000 => 'Quintillion'
	);

	if (!is_numeric($number))
	{
		return false;
	}

	if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX)
	{
		// overflow
		trigger_error(
			'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
			E_USER_WARNING
		);
		return false;
	}

	if ($number < 0)
	{
		return $negative . convert_number_to_words(abs($number));
	}

	$string = $fraction = null;

	if (strpos($number, '.') !== false)
	{
		list($number, $fraction) = explode('.', $number);
	}

	switch (true)
	{
		case $number < 21:
			$string = $dictionary[$number];
			break;
		case $number < 100:
			$tens   = ((int) ($number / 10)) * 10;
			$units  = $number % 10;
			$string = $dictionary[$tens];
			if ($units)
			{
				$string .= $hyphen . $dictionary[$units];
			}
			break;
		case $number < 1000:
			$hundreds  = $number / 100;
			$remainder = $number % 100;
			$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
			if ($remainder)
			{
				$string .= $conjunction . convert_number_to_words($remainder);
			}
			break;
		default:
			$baseUnit = pow(1000, floor(log($number, 1000)));
			$numBaseUnits = (int) ($number / $baseUnit);
			$remainder = $number % $baseUnit;
			$string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
			if ($remainder)
			{
				$string .= $remainder < 100 ? $conjunction : $separator;
				$string .= convert_number_to_words($remainder);
			}
			break;
	}

	if (null !== $fraction && is_numeric($fraction))
	{
		$string .= $decimal;
		$words = array();
		foreach (str_split((string) $fraction) as $number)
		{
			$words[] = $dictionary[$number];
		}

		$string .= implode(' ', $words);
	}

	return strtolower($string);
}

/**
 * Function for displaying what called function, useful for debugging
 */
// @codingStandardsIgnoreStart
function _xls_whereCalled($level = 1)
// @codingStandardsIgnoreEnd
{
	$trace = debug_backtrace();
	$file   = isset($trace[$level]['file']) ? $trace[$level]['file'] : "file?";
	$line   = isset($trace[$level]['line']) ? $trace[$level]['line'] : "line?";
	$object = isset($trace[$level]['object']) ? $trace[$level]['object'] : "object?";
	if (is_object($object))
	{
		$object = get_class($object);
	}

	return "Where called: class $object was called on line $line of $file";
}

function _dbx($sql)
{
	// Run SQL query directly
	return Yii::app()->db->createCommand($sql)->execute();
}

function _xt($strToTranslate)
{
	echo Yii::t('global', $strToTranslate);
	Yii::log("Called outdated _xt() function for string ".$strToTranslate, CLogger::LEVEL_WARNING, 'application.'.__CLASS__.".".__FUNCTION__);
}

// @codingStandardsIgnoreStart
function _xls_convert_date_to_js($strFormat)
// @codingStandardsIgnoreEnd
{
	$strFormat = str_replace("y", "yy", $strFormat);
	$strFormat = str_replace("Y", "yyyy", $strFormat);
	$strFormat = str_replace("d", "dd", $strFormat);
	$strFormat = str_replace("m", "mm", $strFormat);
	return $strFormat;
}

// @codingStandardsIgnoreStart
function recurse_copy($src, $dst)
// @codingStandardsIgnoreEnd
{
	$dir = opendir($src);
	@mkdir($dst);
	while(false !== ( $file = readdir($dir)))
	{
		if (( $file != '.' ) && ( $file != '..' ))
		{
			if (is_dir($src . '/' . $file))
			{
				recurse_copy($src . '/' . $file, $dst . '/' . $file);
			}
			else {
				copy($src . '/' . $file, $dst . '/' . $file);
			}
		}
	}

	closedir($dir);
}

function rrmdir($dir)
{
	if (is_dir($dir))
	{
		$files = scandir($dir);
		foreach ($files as $file)
		{
			if ($file != "." && $file != "..")
			{
				rrmdir("$dir/$file");
			}
		}

		rmdir($dir);
	}
	elseif (file_exists($dir))
	{
		unlink($dir);
	}
}

// Function to Copy folders and files
function rcopy($src, $dst)
{
	if (file_exists($dst))
	{
		rrmdir($dst);
	}

	if (is_dir($src))
	{
		mkdir($dst);
		$files = scandir($src);
		foreach ($files as $file)
		{
			if ($file != "." && $file != "..")
			{
				rcopy("$src/$file", "$dst/$file");
			}
		}
	} elseif (file_exists($src)) {
		copy($src, $dst);
	}
}

// @codingStandardsIgnoreStart
function RemoveEmptySubFolders($path)
// @codingStandardsIgnoreEnd
{
	$empty = true;
	foreach (glob($path.DIRECTORY_SEPARATOR."*") as $file)
	{
		$empty &= is_dir($file) && RemoveEmptySubFolders($file);
	}

	return $empty && @rmdir($path);
}

// @codingStandardsIgnoreStart
function _xls_custom_css_folder($local = false)
// @codingStandardsIgnoreEnd
{
	if(Yii::app()->params['LIGHTSPEED_MT'] == "1")
	{
		return "http://lightspeedwebstore.s3.amazonaws.com/".
			Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL']."/themes/";
	} else {
		$strCustomFolder = Yii::app()->theme->info->useCustomFolderForCustomcss ? '/custom' : '';
		return ($local ? YiiBase::getPathOfAlias('custom') : Yii::app()->baseUrl . $strCustomFolder) . "/themes/";
	}
}

// @codingStandardsIgnoreStart
function _upload_default_header_to_s3()
// @codingStandardsIgnoreEnd
{
	Gallery::LoadGallery(1);
	$d = dir(YiiBase::getPathOfAlias('webroot')."/images/header");
	while (false !== ($filename = $d->read()))
	{
		if ($filename == "defaultheader.png")
		{
			$model = new GalleryPhoto();
			$model->gallery_id = 1;
			$model->file_name = $filename;
			$model->name = '';
			$model->description = '';
			$model->thumb_ext = 'png';
			$model->save();
			$arrImages["/images/header/".$filename] = CHtml::image(Yii::app()->request->baseUrl."/images/header/".$filename);

			$src = YiiBase::getPathOfAlias('webroot')."/images/header/".$filename;

			$fileinfo = mb_pathinfo($filename);

			$imageFile = new CUploadedFile(
				$filename,
				$src,
				"image/".$fileinfo['extension'],
				getimagesize($src),
				null
			);

			if(Yii::app()->params['LIGHTSPEED_MT'] == '1')
			{
				$model->setS3Image($imageFile);
			}

			_xls_set_conf(
				'HEADER_IMAGE',
				"//lightspeedwebstore.s3.amazonaws.com/".
				_xls_get_conf('LIGHTSPEED_HOSTING_LIGHTSPEED_URL').
				"/gallery/1/".$model->id.".png"
			);
		}
	}
}

/**
 * Indents a flat JSON string to make it more human-readable.
 *
 * @param string $json The original JSON string to process.
 *
 * @return string Indented version of the original JSON string.
 */
function prettyjson($json)
{

	$result      = '';
	$pos         = 0;
	$strLen      = strlen($json);
	$indentStr   = '  ';
	$newLine     = "\n";
	$prevChar    = '';
	$outOfQuotes = true;

	for ($i = 0; $i <= $strLen; $i++)
	{
		// Grab the next character in the string.
		$char = substr($json, $i, 1);

		// Are we inside a quoted string?
		if ($char == '"' && $prevChar != '\\')
		{
			$outOfQuotes = !$outOfQuotes;

			// If this character is the end of an element,
			// output a new line and indent the next line.
		} elseif(($char == '}' || $char == ']') && $outOfQuotes) {
			$result .= $newLine;
			$pos --;
			for ($j = 0; $j < $pos; $j++)
			{
				$result .= $indentStr;
			}
		}

		// Add the character to the result string.
		$result .= $char;

		// If the last character was the beginning of an element,
		// output a new line and indent the next line.
		if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes)
		{
			$result .= $newLine;
			if ($char == '{' || $char == '[')
			{
				$pos ++;
			}

			for ($j = 0; $j < $pos; $j++)
			{
				$result .= $indentStr;
			}
		}

		$prevChar = $char;
	}

	return $result;
}

function _runMigrationTool($steps = null)
{
	$commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
	$runner = new CConsoleCommandRunner();
	$runner->addCommands($commandPath);
	$commandPath = Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands';
	$runner->addCommands($commandPath);
	$commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'SingleMigrateCommand';
	$runner->addCommands($commandPath);
	if(is_null($steps))
	{
		$strCommand = 'migrate';
	} elseif($steps == 'set') {
		$strCommand = 'setmigrate';
	} elseif($steps == 'upgrade') {
		$strCommand = 'upgrademigrate';
	} else {
		$strCommand = 'singlemigrate';
	}

	Yii::log("Migrating with $strCommand", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
	$args = array('yiic', $strCommand, '--interactive=0','--migrationTable=xlsws_migrations');

	ob_start();
	$runner->run($args);
	return htmlentities(ob_get_clean(), null, Yii::app()->charset);
}


/**
 * Implementation of Underscore.js findWhere.
 * Looks through the $arr and returns the first value that matches all of the
 * key-value pairs listed in properties.
 * @param array $arr An array of containing associative arrays.
 * @param array $properties An associative array of properties to match against
 * each element in $arr.
 * @return mixed The first matching element of $arr.
 */
function findWhere($arr, $properties)
{
	if (is_array($arr) === false && $arr instanceof Traversable === false)
	{
		return null;
	}

	if (is_array($properties) === false)
	{
		return null;
	}

	foreach ($arr as $element)
	{
		if (sizeof(array_intersect_assoc($element, $properties)) === sizeof($properties))
		{
			return $element;
		}
	}

	return null;
}

/**
 * This method will take two keys from an array
 * and swap their position in the array.
 *
 * @param array $array An associative array
 * @param string $key1 A key for the swapping
 * @param string $key2 A key for the swapping
 * @return array[]
 */
function arraySwap($array, $key1, $key2)
{
	if (is_array($array) === false || isset($array[$key1]) === false || isset($array[$key2]) === false)
	{
		return $array;
	}

	$swappedArray = array();
	foreach($array as $key => $value)
	{
		if ($key == $key1)
		{
			$swappedArray[$key2] = $array[$key2];
		}
		elseif ($key == $key2)
		{
			$swappedArray[$key1] = $array[$key1];
		}
		else
		{
			$swappedArray[$key] = $value;
		}
	}

	return $swappedArray;
}
