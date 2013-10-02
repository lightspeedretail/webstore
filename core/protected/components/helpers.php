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
function _xls_get_conf($strKey, $mixDefault = ""){

	if (isset(Yii::app()->params[$strKey]))
		return Yii::app()->params[$strKey];
	else
	{
		$objKey = Configuration::model()->find('key_name=?', array($strKey));
		if (!$objKey) return $mixDefault;
		else return $objKey->key_value;
	}


}

/**
 * Returns fully qualified URL, based on sitedir. Used to generate a link.
 *
 * @param string $strUrlPath optional
 * @return string url
 */
function _xls_site_url($strUrlPath =  '') {
	if (substr($strUrlPath,0,4)=="http") return $strUrlPath; //we've passed through twice, don't double up
	if (substr($strUrlPath,0,1)=="/") $strUrlPath = substr($strUrlPath,1,999); //remove a leading / so we don't // by accident

	$usessl=false;

	if (_xls_get_conf('ENABLE_SSL','0')==1) {
		if (_xls_get_conf('SSL_NO_NEED_FORWARD',0) != 1)
			$usessl = true;
		elseif (	strstr($strUrlPath,"checkout") !== false ||
			strstr($strUrlPath,"customer-register") !== false

		) $usessl = true;
	}

	return _xls_site_dir($usessl) . '/' . $strUrlPath;
}

function _xls_theme_config($theme)
{
	$fnOptions = YiiBase::getPathOfAlias('webroot')."/themes/".$theme."/config.xml";

	if (file_exists($fnOptions))
	{
		$strXml = file_get_contents($fnOptions);
		return new SimpleXMLElement($strXml);
	} else return null;

}


/**
 * Get a file from our CDN network.
 * @param $url
 * @return bool|mixed
 */
function getFile($url)
{
	if(stripos($url,".lightspeedretail.com")>0)
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
	} else return false;


}


/**
 * Download the Brooklyn template. We call this during install and also on the off chance that Brooklyn suddenly
 * goes missing.
 */
function downloadBrooklyn()
{
	$jLatest= getFile("http://updater.lightspeedretail.com/site/latestbrooklyn");
	$result = json_decode($jLatest);
	$strWebstoreInstall = "http://cdn.lightspeedretail.com/webstore/themes/".$result->latest->filename;

	$data = getFile($strWebstoreInstall);
	if (stripos($data,"404 - Not Found")>0 || empty($data)){
		Yii::log("ERROR downloading themes/brooklyn.zip from LightSpeed", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		return false;
	}

	$f=file_put_contents("themes/brooklyn.zip", $data);
	if ($f)
	{
		require_once( YiiBase::getPathOfAlias('application.components'). '/zip.php');
		extractZip("brooklyn.zip",'',YiiBase::getPathOfAlias('webroot.themes'));
		@unlink("themes/brooklyn.zip");
	}
	else {
		Yii::log("ERROR downloading themes/brooklyn.zip from LightSpeed", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		return false;
	}
	return true;
}
function _xls_regionalize($str)
{
	$c = Yii::app()->params['DEFAULT_COUNTRY'];
	switch ($str)
	{
		case 'color':
			if ($c==224) return 'color'; else return 'colour';

		case 'check':
			if ($c==224) return 'check'; else return 'cheque';

		default:
			return $str;
	}
}

/**
 * Return the Base URL for the site
 * Also perform http/https conversion if need be.
 *
 * @param boolean ssl_attempt
 * @return string url
 */
function _xls_site_dir($ssl_attempt = false) {
	$strSsl = 'http://';
	$strHost = $_SERVER['HTTP_HOST'] . Yii::app()->getBaseUrl(false);

	if ($ssl_attempt ||
		(isset($_SERVER['HTTPS']) &&
			($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == '1')))
		$strSsl = 'https://';

	if (substr($strHost,-1)=="/") $strHost = substr($strHost, 0, -1);

	return $strSsl . $strHost;
}

/**
 * Translate string that includes print.
 * @param string key to look up
 * @param mix optional default in case key is not found
 * @return string key value
 */
function _sp($strString, $strLocation = "global"){
	return Yii::t($strLocation, $strString);

}

function _qalert($strString) { error_log($strString);
//	$cs = Yii::app()->clientScript;
//	$cs->registerScript('my_script', 'alert("'.$strString.'");', CClientScript::POS_READY);
//	$this->render('any_view');

}

function _xls_parse_name($strName)
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
function _xls_get_events($strEventHandler)
{

	$obj = new Modules();
	$obj->category = $strEventHandler;
	$obj->active=1;
	$dataProvider = $obj->searchEvents();
	$objModules = $dataProvider->getData();

	foreach ($objModules as $module)
	{
		//See if the extension actually exists either in custom file or our core
		if(file_exists(Yii::getPathOfAlias('custom.extensions.'.$module->module.".".$module->module).".php"))
			Yii::import('custom.extensions.'.$module->module.".".$module->module);
		elseif(file_exists(Yii::getPathOfAlias('ext.'.$module->module.".".$module->module).".php"))
			Yii::import('ext.'.$module->module.".".$module->module);
	}

	return $objModules;
}

function _xls_raise_events($strEvent,$objEvent)
{

	//Attach event handlers
	$objModules = _xls_get_events($strEvent);
	foreach ($objModules as $objModule)
	{
		$objModule->instanceHandle = new $objModule->module;
		$objModule->instanceHandle->attachEventHandler($objEvent->onAction,array($objModule->instanceHandle,$objEvent->onAction));
	}

	//Raise events
	foreach ($objModules as $objModule) {
		Yii::log('Running event '.$strEvent.' '.$objModule->module, 'trace', 'application.'.__CLASS__.".".__FUNCTION__);
		$objModule->instanceHandle->raiseEvent($objEvent->onAction,$objEvent);
	}
}

function _xls_facebook_login()
{
	if (_xls_get_conf('FACEBOOK_APPID',0)>0 && _xls_get_conf('FACEBOOK_SECRET',0)>0)
		return true;
	else return false;
}

function _xls_convert_errors($arrErrors)
{
	$newArray = array();
	foreach ($arrErrors as $key=>$value)
	{
		$newArray[$key] = $value[0];

	}
	return $newArray;
}
function _xls_convert_errors_display($arrErrors)
{
	$strReturn = "\n";
	foreach ($arrErrors as $key=>$value)
	{
		$strReturn .= $value."\n";

	}
	return $strReturn;
}

//from http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
function hex2rgb($hex) {
	$hex = str_replace("#", "", $hex);

	if(strlen($hex) == 3) {
		$r = hexdec(substr($hex,0,1).substr($hex,0,1));
		$g = hexdec(substr($hex,1,1).substr($hex,1,1));
		$b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
	}
	$rgb = array($r, $g, $b);
	//return implode(",", $rgb); // returns the rgb values separated by commas
	return $rgb; // returns an array with the rgb values
}

function _xls_get_sort_order() {
	$strProperty = _xls_get_conf('PRODUCT_SORT_FIELD' , 'Name');
	$blnAscend = true;

	if ($strProperty[0] == '-') {
		$strProperty = substr($strProperty,1);
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
function _xls_fopen_w($filename) {
	$dir = dirname($filename);

	if (!file_exists($filename) && !is_writable($dir))
		return false;

	if (file_exists($filename) && !is_writable($filename))
		return false;

	$fp = fopen($filename , 'w');
	return $fp;
}

/**
 * Convert a path relative to template/ to relative to __SITEROOT__
 *
 * @param string $name :: Path relative to the template folder
 * @return string :: Converted path relative to __SITEROOT__
 */
function templateNamed($name) {
	$file = Yii::app()->theme->baseUrl . '/'.$name;
	return $file;
//
//	if(!file_exists($file)) {
//		QApplication::Log(E_ERROR,"Template ".$file." not found - site cannot continue");
//		die("Template file missing. Check System Log for details.");
//	}
//	else return $file;
}

/**
 * Convert an upper camel cased string to a database field string
 *
 * @param string $camel :: String you wish to convert
 * @return string :: Converted string corresponding to a table field
 */
function _xls_convert_camel($camel) {
	$output = "";
	preg_match_all('/[A-Z][^A-Z]*/',$camel,$results);
	for ($i=0; $i < count($results[0]);$i++) {
		if ($i)
			$output .= "_" . $results[0][$i];
		else
			$output .= $results[0][$i];
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

	if (!$pascalCase) {
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
	$isValid = true;
	$atIndex = strrpos($email, "@");
	if (is_bool($atIndex) && !$atIndex)
	{
		$isValid = false;
	}
	else
	{
		$domain = substr($email, $atIndex+1);
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);
		if ($localLen < 1 || $localLen > 64)
		{
			// local part length exceeded
			$isValid = false;
		}
		else if ($domainLen < 1 || $domainLen > 255)
		{
			// domain part length exceeded
			$isValid = false;
		}
		else if ($local[0] == '.' || $local[$localLen-1] == '.')
		{
			// local part starts or ends with '.'
			$isValid = false;
		}
		else if (preg_match('/\\.\\./', $local))
		{
			// local part has two consecutive dots
			$isValid = false;
		}
		else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
		{
			// character not valid in domain part
			$isValid = false;
		}
		else if (preg_match('/\\.\\./', $domain))
		{
			// domain part has two consecutive dots
			$isValid = false;
		}
		else if
		(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
				str_replace("\\\\","",$local)))
		{
			// character not valid in local part unless
			// local part is quoted
			if (!preg_match('/^"(\\\\"|[^"])+"$/',
				str_replace("\\\\","",$local)))
			{
				$isValid = false;
			}
		}
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
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

	for ($j=0;$j<$len;$j++) {
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
function values_as_keys($arr) { //ToDo: only used in Admin panel
	$ret = array();
	reset($arr);

	foreach($arr as $val) {
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
function _xls_comma_to_array($val) {
	return _xls_delim_to_array($val, ',');
}

/**
 * Convert a string to array broken in commas
 *
 * @param string $val
 * @return array
 */
function _xls_delim_to_array($val , $delim = ',') {
	$arr = explode($delim , trim($val));
	$ret = array();
	while(list( , $item) = each($arr)) {
		if(trim($item) != '')
			$ret[$item] = $item;
	}
	return $ret;
}

/**
 * Create a hidden for input our of a name and value
 *
 * @param string $name :: The form input's Name attr
 * @param string $value :: The value to be contained by the input
 * @return string :: An input HTML tag
 */
function _xls_make_hidden($name , $value) {
	return "<input type=\"hidden\" name=\"$name\" value=\"$value\">\n";
}

/**
 * Do a log entry
 *
 * @param unknown_type $msg
 */
function _xls_log($msg,$blnSysLogOnly = false) {

		Yii::log($msg, CLogger::LEVEL_ERROR, 'application');
}

/**
 * Get Host and IP Address
 *
 * @return string "hostname ; ipaddress"
 */
function _xls_get_ip() {
	$hname = @gethostbyaddr($_SERVER["REMOTE_ADDR"]);

	if(strcmp($hname , $_SERVER["REMOTE_ADDR"]) != 0) {
		$hname .= " ( " . $_SERVER["REMOTE_ADDR"] . " ) ";
	}

	if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$pname = @gethostbyaddr($_SERVER["HTTP_X_FORWARDED_FOR"]);
		if( strcmp($pname , $_SERVER["HTTP_X_FORWARDED_FOR"]) != 0) {
			$pname .= " ( " . $_SERVER["HTTP_X_FORWARDED_FOR"] . " ) ";
		}
		$hname = $pname . " ; Proxy " . $hname ;
	}
	return trim($hname);
}


/**
 * Return values of an array as value and key (itself)
 *
 * @param array $arr The array
 * @return array
 */
function _xls_values_as_keys($arr) {
	$ret = array();
	reset($arr);

	foreach($arr as $val) {
		$ret[$val] = $val;
	}

	reset($ret);
	$arr = $ret;
	return $ret;
}

/**
 * Set existing configuration value
 * @param string $key
 * @param string $mixDefault
 * @return bool success
 */
function _xls_set_conf($strKey, $mixDefault = "") {
	$conf = Configuration::LoadByKey($strKey);
	if(!$conf) return false;
	$conf->key_value = $mixDefault;
	$conf->modified = new CDbExpression('NOW()');
	$conf->save();
	Yii::app()->params[$strKey] = $mixDefault;
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
function _xls_insert_conf($key , $title, $value, $helper_text,
                          $config_type, $options, $sort_order = NULL,$template_specific = 0) {

	$conf = Configuration::LoadByKey($key);

	if(!$conf)
		$conf = new Configuration();

	$conf->key_name = $key;
	$conf->title = $title;
	$conf->key_value = $value;
	$conf->helper_text = $helper_text;
	$conf->configuration_type_id = $config_type;

	$conf->options = $options;

	$query = <<<EOS
		SELECT IFNULL(MAX(sort_order),0)+1
		FROM xlsws_configuration
		WHERE configuration_type_id = '{$config_type}';
EOS;
	if(!$sort_order)
		$sort_order = Configuration::model()->findBySql($query);

	$conf->sort_order = $sort_order;
	$conf->template_specific = $template_specific;

	$conf->created = new CDbExpression('NOW()');
	$conf->modified = new CDbExpression('NOW()');

	if (!$conf->save())
		print_r($conf->getErrors());

	Configuration::exportConfig();
}

/**
 * Standardize the ZIP format for compatibility with shipping calculators
 *
 * @param string $zip
 * @return $zip
 */
function _xls_zip_fix($zip) {
	$zip = trim($zip);
	$zip = str_replace(" " , "" , $zip);
	$zip = strtoupper($zip);
	$zip = preg_replace('/\-[0-9][0-9][0-9][0-9]/','',$zip);

	return $zip;
}

/**
 * Validate a ZIP against a regex
 *
 * @param string $zip
 * @param string $zippreg
 * @return boolean
 */
function _xls_validate_zip($zip , $zippreg = '') {
	if(!$zippreg)
		return true;

	$zip = _xls_zip_fix($zip);

	if(preg_match($zippreg, $zip))
		return true;
	else
		return false;
}

/**
 * List files within directory
 *
 * @param string $dir
 * @param string $ext :: Filter to a given file extension
 * @return array
 */
function _xls_read_dir($dir , $ext = FALSE) {
	$ret = array();

	$handle=opendir($dir);

	while (false !== ($file = readdir($handle)))
		if ($file != "." && $file != ".."  &&
			($ext ? stristr($file , $ext) : TRUE))
			$ret[$file] = $file;

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
function _xls_stack_add($key, $value) {
	if(!isset($_SESSION['stack_vars'][$key]))
		$_SESSION['stack_vars'][$key] = array();
	$_SESSION['stack_vars'][$key][]=$value;
}

/**
 * Add variables to the stack_vars array within the _SESSION
 * Overwrites any previous value
 * @param string $key
 * @param mix $value
 * @return void
 */
function _xls_stack_put($key, $value) {
	$_SESSION['stack_vars'][$key] = array($value);

}
/**
 * Get a variable from the stack_vars array within the _SESSION
 * Returns the last item in the array
 *
 * @param string $key
 * return mix or false
 */
function _xls_stack_get($key) {
	if(isset($_SESSION['stack_vars'][$key])) {
		$intItemCount = count($_SESSION['stack_vars'][$key]);

		if ($intItemCount > 0)
			return $_SESSION['stack_vars'][$key][$intItemCount - 1];
	}

	else
		return false;
}

/**
 * Pop a variable from the stack_vars array within the _SESSION
 *
 * @param string $key
 * return mix or false
 */
function _xls_stack_pop($key) {
	if(isset($_SESSION['stack_vars'][$key]) &&
		(count($_SESSION['stack_vars'][$key])>0)) {

		end($_SESSION['stack_vars'][$key]);
		$index = key($_SESSION['stack_vars'][$key]);

		$val = $_SESSION['stack_vars'][$key][$index];
		unset($_SESSION['stack_vars'][$key][$index]);

		if(count($_SESSION['stack_vars'][$key]) == 0)
			$_SESSION['stack_vars'][$key] = array();

		return $val;
	}else
		return false;
}

function _xls_stack_remove($key) {

	while (_xls_stack_pop($key)) { }
	unset($_SESSION['stack_vars'][$key]);
	return true;
}
/**
 * Clear $_SESSION['stack_vars']
 */
function _xls_stack_removeall() {
	unset($_SESSION['stack_vars']);
}

/**
 * Display a message within the page's content section
 *
 * @param string $msg
 * @param string $redirect
 */
function _xls_display_msg($msg) {
	_xls_stack_add('msg', _sp($msg));

	_rd(_xls_site_url('msg/'.XLSURL::KEY_PAGE));
}

/**
 * Set last viewed page within _SESSION
 *
 * @param string $key
 * return mix or false
 */
function _xls_remember_url($strUrl) { //Yii::app()->session['crumbtrail']
	if (empty($strUrl))
		unset(Yii::app()->session['last_url']);
	else
		Yii::app()->session['last_url'] =  $strUrl;
}

/**
 * Set last viewed page within _SESSION
 *
 * @param string $key
 * return mix or false
 */
function _xls_get_remembered_url() {
	if (isset(Yii::app()->session['last_url']))
		return (Yii::app()->session['last_url']);
	else
		return null;
}

/**
 * Ensure that a client is logged in before accessing this page.
 *
 * @param string $msg
 */
function _xls_require_login($msg = false) {
	$uri = $_SERVER['REQUEST_URI'];

	if(!$msg)
		$msg = "You are required to login to access this page.";

	_xls_stack_add('login_msg' , _sp($msg));
	_xls_stack_add('login_redirect_uri' , $uri);

	_rd(_xls_site_url('login/'.XLSURL::KEY_PAGE.'/'));
}

/**
 * Format the name and address to be used with _xls_mail
 *
 * @param string $name
 * @param string $adde
 * @return array
 */
function _xls_mail_name($name , $adde) {
	return $name . ' <' . $adde . '>';
}

function _xls_send_email($id, $hideJson = false)
{

	$headers = array(
		'MIME-Version: 1.0',
		'Content-type: text/html; charset=utf8'
	);

	$objMail = EmailQueue::model()->findByPk($id);
	if ($objMail instanceof EmailQueue) {

		Yii::import("ext.KEmail.KEmail");
		$orderEmail = _xls_get_conf('ORDER_FROM','');

		$blnResult = Yii::app()->email->send(
			empty($orderEmail) ? _xls_get_conf('EMAIL_FROM') : $orderEmail,
			$objMail->to,
			$objMail->subject,
			$objMail->htmlbody,
			$headers);

		if($blnResult)
		{
			$objMail->delete();
			if (!$hideJson) echo json_encode("success");
		}
		else
		{
			$objMail->sent_attempts += 1;
			$objMail->save();
			Yii::log("Sending email failed ID ".$id, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			if (!$hideJson) echo json_encode("failure");
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
function _xls_mail_body_from_template($templatefile, $vars) {
	if(!file_exists($templatefile)) {
		_xls_log(_sp("FATAL ERROR : e-mail template file not found") .
			$templatefile);
		return "";
	}

	try {
		extract($vars);
	} catch (Exception $exc) {
		_xls_log(_sp("FATAL ERROR : problem extracting e-mail" .
			" variables in") . " " . print_r($vars , true));
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
function _xls_trim(&$value) {
	$value = trim($value);
}

/**
 * Does a partial array search within an array for a given value
 *
 * @param string partial value to search for
 * @param array to search in
 * @return boolean true or false
 */
function _xls_array_search($needle, $haystack) {
	foreach($haystack as $elem) {
		if(preg_match('/' . $elem.'/',$needle))
			return true;
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
function _xls_array_search_begin($needle, $haystack) {
	foreach($haystack as $elem) {
		if(preg_match('/^' . $elem.'/',$needle))
			return true;
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
function _xls_currency($num, $strCountry = null) {
	if(function_exists('_custom_currency'))
		return _custom_currency($num);

	if (!is_numeric($num))
		return $num;

	if (is_null($strCountry))
		$strCountry=_xls_get_conf('CURRENCY_DEFAULT','USD');
	return Yii::app()->numberFormatter->formatCurrency($num,$strCountry);

}

/**
 * Remove the leading slash
 *
 * @param string $path
 * @return string
 */
function _xls_remove_leading_slash($path) {
	if(substr($path , 0 , 1) == '/')
		return substr($path , 1);
	else
		return $path;
}


/**
 * Return the URL Parser Object
 * Useful if we need to find out URL properties to make decisions somewhere
 *
 * @return object
 */
function _xls_url_object() {
	$objUrl = XLSURL::getInstance();
	return $objUrl;
}





//Makes our SEO hyphenated string from passed string
//Used to build anything that will be in a URL.
//Same as seo_name plus lower case conversion and removing spaces
function _xls_seo_url($string) {
	return strtolower(trim(_xls_seo_name($string), '-'));
}

//Makes our SEO hyphenated string from passed string
//Used to build anything that will be in a Name.
function _xls_seo_name($string) {
	$string = str_replace(array('\n','\r',chr(13),'%'),'',$string);
	$string = str_replace('\'','',$string);
	$string = str_replace('"','',$string);
	$string = str_replace(array(',','?','!','.'),'',$string);
	$string = str_replace("&","and",$string);
	$string = str_replace("%","pct",$string);
	$string = str_replace("#","No",$string);
	$string = str_replace("+","and",$string);
	$string = str_replace(array(" ",'/'),"-",$string);
	$string = preg_replace("`\[.*\]`U","",$string);
	$string = preg_replace('`&(amp;)?#?[A-Za-z0-9]+;`i','-',$string);
	//$string = htmlentities($string, ENT_COMPAT, 'utf-8');
	$string = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i","\\1", $string);
	$string = str_replace('-amp-','-and-',$string);
	$string = preg_replace( array("`[^a-z0-9{Cyrillic}{Greek}{Japanese}{Chinese}{Korean}{Hebrew}{Arabic}]/u`i","`[-]+`") , "-", $string);
	return trim($string, '- ');
}


/**
 * Escape backslashes, used for Google Conversion item description
 * @return string
 */
function _xls_jssafe_name($string) {
	$string = str_replace('\'','\\\'',$string);
	$string = str_replace('&','&amp;',$string);
	return trim($string);
}

/**
 * Escape backslashes, used for Google Conversion item description
 * @return string
 */
function _xls_ajaxclean($string) {
	$string = trim($string);
	$string = str_replace('\'','\\\'',$string);
	$string = str_replace(array("\r", "\n", "\t"), '', $string);
	return trim($string);
}

/**
 * Replace double quote with single quote for Meta tags
 * @return string
 */
function _xls_meta_safe($string) {
	$string = str_replace('"','\'',$string);
	//$string = str_replace("&","and",$string);
	return trim($string);
}


/**
 * Get the ID of the current customer object
 * @return int
 */
function _xls_get_current_customer_id() {

	$blnLoggedIn = !Yii::app()->user->isGuest;
	if ($blnLoggedIn)
		return Yii::app()->user->GetId();
	else return null;
}

/**
 * Get the Full Name of the current customer object
 * @return string
 */
function _xls_get_current_customer_name() {
	$blnLoggedIn = !Yii::app()->user->isGuest;
	if ($blnLoggedIn) {
		if (strlen(Yii::app()->user->fullname)<13) return Yii::app()->user->fullname;
		else return _xls_truncate(Yii::app()->user->firstname,13);

	}
	else return "My Account";
}

/**
 * Convert a filesystem path to a URL
 *
 * @param string $filename (path)
 * @return string $filename (fqdn path)
 */
function _xls_get_url_resource($filename) {
	$fl = mb_strtolower($filename);
	if(substr($fl , 0 , 7) == 'http://' ||
		substr($fl , 0 , 8) == 'https://')
		return $filename;

	$filename = str_replace( __DOCROOT__ , '' , $filename);
	$filename = str_replace( __SUBDIRECTORY__ , '' , $filename);
	$filename = __VIRTUAL_DIRECTORY__ . __SUBDIRECTORY__ . $filename;

	return $filename;
}

/**
 * Do a permanent 301 redirect
 *
 */
function _xls_301($strUrl) {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: ".$strUrl);
	exit();
}

/**
 * Do a 404 fail
 *
 */
function _xls_404() {
	header('HTTP/1.1 404 Not Found');
	$strFile = "404.php";
	if(file_exists(CUSTOM_INCLUDES . $strFile)) {
		include(CUSTOM_INCLUDES . $strFile);
		exit(); }
	elseif(file_exists('xlsws_includes/'.$strFile)) {
		include('xlsws_includes/'.$strFile);
	}
	exit();
}

/**
 * Redirect to a given URL
 * @param string $url
 */
function _rd($url = '') {

	if(empty($url)) {
		$url = $_SERVER["REQUEST_URI"];
		//_xls_site_url will append subfolder again so get rid of it here
		if (__SUBDIRECTORY__) $url = substr($url,strlen(__SUBDIRECTORY__),999);
	}
	header("Location: "._xls_site_url($url));
	exit();

}

/**
 * Truncate a string to a given length
 * This function can be extended with _custom_truncate
 *
 * @param string $text
 * @param int $len
 * @param string $etc
 */
function _xls_truncate($text,$len,$etc = "...") {
	if(function_exists('_custom_truncate'))
		return _custom_truncate($text,$len,$etc);

	return _xls_string_smart_truncate($text , $len , $etc);
}

/**
 * Truncate a string
 *
 * @param mb_string $strText
 * @param integer $intLimit
 * @param string $strEncoding
 * @return mb_string $strOut
 */
function _xls_string_smart_truncate($strText, $intLimit, $etc,
                                    $strEncoding = "utf-8") {

	$strOut = rtrim($strText);

	// if the text length is longer than the limit set
	if (mb_strlen($strOut, $strEncoding) > $intLimit) {
		$strStop = XLS_TRUNCATE_PUNCTUATIONS;
		$strStop .= html_entity_decode("&raquo;", ENT_COMPAT, "utf-8");

		$strOut = mb_substr($strText, 0, $intLimit, $strEncoding);
		$charArray = _xls_string_split($strOut, $strEncoding);

		$posStop = null;
		$blnFlagStop = false;

		$intChars = 0;

		for ($i = $intLimit-1; $i >= 0; $i--) {
			if (mb_strpos($strStop, $charArray[$i]) ||
				ctype_space($charArray[$i]))
				$posStop = $i;
			else {
				if ($intChars > 0)
					break;
				$intChars++;
			}
		}

		if (!is_null($posStop) && ($posStop != 0))
			$intLimit = $posStop;

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
function _xls_string_split($strText, $strEncoding = "utf-8") {
	$intLength = mb_strlen($strText, $strEncoding);
	$charArray = array();

	// start from the left and chop off one character a time
	while ($intLength) {
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
function _xls_number_only($string ) {
	return preg_replace('/[^0-9]/', '', $string);
}

/**
 * Return a number out of a string only
 * @param string $string
 * @return string
 */
function _xls_letters_only($string ) {
	return preg_replace('/[^A-Za-z]/', '', $string);
}

/**
 * Return a currency string removing anything not allowed
 * Note this is not handling European-formatted currency (, separator)
 * @param string $string
 * @return string
 */
function _xls_clean_currency($string) {
	return preg_replace('/[^0-9\.\-]/', '', $string);
}

/**
 * Add meta redirect to the stack_vars stack
 * @param string $url
 * @param int $delay
 */
function _xls_add_meta_redirect($url , $delay = 60) {
	_xls_stack_add(
		'xls_meta_redirect',
		array('url' => $url , 'delay' => $delay)
	);
}

/**
 * Set the page title
 * @param string $title
 */
function _xls_add_page_title($title) {
	global $strPageTitle;
	$strPageTitle = $title;
	_xls_stack_put('xls_page_title',$title);
}

/**
 * Set the page title combined with storename (or other wildcard pattern)
 * @param string $title
 */
function _xls_add_formatted_page_title($title) {

	_xls_stack_put('xls_page_title',
		_xls_get_formatted_page_title($title));

}

/**
 * Set the page title combined with storename (or other wildcard pattern)
 * @param string $title
 */
function _xls_get_formatted_page_title($title,$meta = null) {

	if(is_null($meta)) $meta = _xls_get_conf('SEO_CUSTOMPAGE_TITLE');

	return
		Yii::t('global',$meta,
		array(
			'{name}'=>$title,
			'{storename}'=>_xls_get_conf('STORE_NAME','LightSpeed Web Store'),
			'{storetagline}'=>_xls_get_conf('STORE_TAGLINE','Amazing products available to order online!'),
		));

}

/**
 * Return Email Subject
 * Note that this doesn't populate orderid or customername, that has to be done in skeleton
 * @param string $title
 */
function _xls_format_email_subject($key='EMAIL_SUBJECT_CUSTOMER',$customer="", $orderid="") {
	$strPattern = _xls_get_conf($key);

	return Yii::t('email',$strPattern,
		array(
			'{customername}'=>$customer,
			'{orderid}'=>$orderid,
			'{storename}'=>_xls_get_conf('STORE_NAME','LightSpeed Web Store'),
		));
}

/**
 * Add meta description to the stack_vars stack
 * @param string $desc
 */
function _xls_add_meta_desc($desc) {
	_xls_stack_put('xls_meta_desc', strip_tags($desc));
}

/**
 * Save crumbtrail to session
 * @param array $arrCrumbs
 */
function _xls_set_crumbtrail($arrCrumbs = null) {

	if($arrCrumbs)
		Yii::app()->session['crumbtrail'] = $arrCrumbs;
	else
		unset(Yii::app()->session['crumbtrail']);
}

/**
 * Retrieve crumbtrail, either full array with links or just names
 * @param string $type
 * @return $array
 */
function _xls_get_crumbtrail($type = 'full') {

	if (!isset(Yii::app()->session['crumbtrail'])) return array();

	if ($type=='full') return Yii::app()->session['crumbtrail'];

	$arrCrumbs = Yii::app()->session['crumbtrail'];
	$retArray = array();
	foreach ($arrCrumbs as $crumb)
		$retArray[] =  $crumb['name'];
	return $retArray;

}

/**
 * Retrieve Google Category based on Product RowId
 * @param int $intProductRowid
 * @return $string
 */
function _xls_get_googlecategory($intProductRowid) {

	$objGoogle = Yii::app()->db->createCommand(
		"SELECT d.name0, extra
		FROM ".ProductCategoryAssn::model()->tableName()." AS a
		LEFT JOIN ".Category::model()->tableName()." AS b ON a.category_id=b.id
		LEFT JOIN ".CategoryIntegration::model()->tableName()." AS c ON a.category_id=c.category_id
		LEFT JOIN ".CategoryGoogle::model()->tableName()." as d ON c.foreign_id=d.id
		WHERE c.module='google' AND a.product_id=".$intProductRowid)->queryRow();


	$strLine = $objGoogle['name0'];
	$strLine = str_replace("&","&amp;",$strLine);
	$strLine = str_replace(">","&gt;",$strLine);

	$arrGoogle = array();
	$arrGoogle['Category'] = trim($strLine);
	if (!empty($objGoogle['extra']))
	{
		$arrX = explode(",",$objGoogle['extra']);
		$arrGoogle['Gender'] = $arrX[0];
		$arrGoogle['Age'] = $arrX[1];
	}

	return $arrGoogle;

}

function _xls_get_googleparentcategory($intProductRowid) {

	$arrTrailFull = Category::GetTrailByProductId($intProductRowid);
	$objCat = Category::model()->findbyPk($arrTrailFull[0]['key']);
	$objPar = $objCat->integration->google;


	if ($objPar) {
		$strLine = $objPar->name0;
		$strMeta = $objPar->extra;
	} else $strLine="";
	$strLine = str_replace("&","&amp;",$strLine);
	$strLine = str_replace(">","&gt;",$strLine);


	$arrGoogle = array();
	$arrGoogle['Category'] = trim($strLine);
	if (!empty($strMeta))
	{
		$arrX = explode(",",$strMeta);
		$arrGoogle['Gender'] = $arrX[0];
		$arrGoogle['Age'] = $arrX[1];
	}

	return $arrGoogle;

}


/**
 * Return the Web Store's version
 * @return string
 */
function _xls_version() { // LEGACY
	return XLSWS_VERSION;
}

/**
 * Are we being browsed on an iDevice (checks for both devices)
 * @return bool
 */
function _xls_is_idevice() {
	if (_xls_is_ipad() || _xls_is_iphone()) return true;
	else return false;
}

/**
 * Are we being browsed on an iPad
 * @return bool
 */
function _xls_is_ipad() {

	if(isset($_SERVER['HTTP_USER_AGENT']))
	return (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
	else return false;
}

/**
 * Are we being browsed on an iPhone/Ipod Touch
 * @return bool
 */
function _xls_is_iphone() {

	if(isset($_SERVER['HTTP_USER_AGENT']) &&
		strpos($_SERVER['HTTP_USER_AGENT'],'iPhone') ||
		strpos($_SERVER['HTTP_USER_AGENT'],'iPod'))
		return true;
	else return false;
}

/**
 * Initialize a new language.
 *
 * @param string $strLangCode
 * @param string $strCountryCode (optional)
 */
function _xls_lang_init($strLangCode, $strCountryCode = '')
{
	if (Yii::app()->language != $strLangCode) {

		Yii::app()->language = $strLangCode;
		if (!empty($strCountryCode))
			Yii::app()->session['country_code'] = $strCountryCode;

		return;
	}
}

function _xls_avail_languages()
{

	$data = array();
	foreach (explode(",",  _xls_get_conf('LANG_OPTIONS','en_us')) as $cLine) {
		list ($cKey, $cValue) = explode(':', $cLine, 2);
		$data[$cKey] = $cValue;
	}
	return $data;

}

function _xls_check_version($releasenotes = false)
{
	if(!Yii::app()->theme) return false;

	$url = "http://updater.lightspeedretail.com";
	//$url = "http://www.lsvercheck.site";


	$storeurl = Yii::app()->createAbsoluteUrl("/");
	$storeurl = str_replace("http://","",$storeurl);
	$storeurl = str_replace("https://","",$storeurl);

	$oXML = _xls_theme_config(Yii::app()->theme->name);

	if(!is_null($oXML))
	{
		$strTheme = Yii::app()->theme->name;
		$strThemeVersion = _xls_number_only((string)$oXML->version);
		if(isset($oXML->noupdate) && $oXML->noupdate=='true' && $strTheme != "brooklyn")
			$strThemeVersion="noupdate";

	} else {
		$strTheme = "unknown";
		$strThemeVersion="noupdate";
	}

	if(isset($_SERVER['SERVER_SOFTWARE']))
		$serversoftware=$_SERVER['SERVER_SOFTWARE'];
	else
		$serversoftware="";

	$data['webstore'] = array(
		'version'       => XLSWS_VERSIONBUILD,
		'customer'      => $storeurl,
		'type'          => (_xls_get_conf('LIGHTSPEED_HOSTING')==1 ? "hosted" : "self"),
		'track'         => (_xls_get_conf('AUTO_UPDATE_TRACK','0')==1 ? "beta" : "release"),
		'autoupdate'    => (_xls_get_conf('AUTO_UPDATE','1')==1 ? "1" : "0"),
		'theme'         => $strTheme,
		'serversoftware'=> $serversoftware,
		'releasenotes'  => $releasenotes,
		'themeversion'  => $strThemeVersion,
		'schema'  => _xls_get_conf('DATABASE_SCHEMA_VERSION')

	);
	$json = json_encode($data);

	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_VERBOSE, 0);

	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER,
		array("Content-type: application/json"));
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

	$resp = curl_exec($ch);
	curl_close($ch);
	return $resp;
}

function _xls_parse_language($string)
{
	$pattern = "|<".Yii::app()->language.".*>(.*)</".Yii::app()->language.">|U";


	preg_match_all($pattern, $string, $output);
	if (is_array($output) && count($output)>0 && count($output[1])>0)
		return $output[1][0];
	else
	{
		$patternDefaultLang = "/^(.*?)<\b(".str_replace(",","|",_xls_get_conf('LANGUAGES','en')).")/";
		preg_match_all($patternDefaultLang, $string, $output);
		if (is_array($output) && count($output)>0 && count($output[1])>0)
			return $output[1][0];
	}

	return $string;
}

/**
 * Encrypt the Web Store key
 * @param string $text
 * @param boolean $key
 */
function _xls_key_encrypt($text , $key = false) {
	if(!$key)
		$key = _xls_get_conf('LSKEY' , 'password');

	$text = trim($text);
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$enc = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);

	return $enc;
}

/**
 * Decrypt the Web Store key
 * @param string $enc
 * @param boolean $key
 */
function _xls_key_decrypt($enc , $key = false) {
	if(!$key)
		$key = _xls_get_conf('LSKEY' , 'password');

	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$crypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $enc, MCRYPT_MODE_ECB, $iv);

	return trim($crypttext);
}


function _xls_encrypt($msg)
{
	if(file_exists(YiiBase::getPathOfAlias('config')."/wskeys.php")) {
		$existingKeys = require(YiiBase::getPathOfAlias('config')."/wskeys.php");
		$pass = $existingKeys['key'];
		$salt = $existingKeys['salt'];
		$cryptastic = new cryptastic;

		$key = $cryptastic->pbkdf2($pass, $salt, 30000, 32);
		$encrypted = $cryptastic->encrypt($msg, $key, true);

		return $encrypted;
	}
	else die("missing wskeys");


}

function _xls_decrypt($msg)
{
	if(file_exists(YiiBase::getPathOfAlias('config')."/wskeys.php")) {
		$existingKeys = require(YiiBase::getPathOfAlias('config')."/wskeys.php");
		$pass = $existingKeys['key'];
		$salt = $existingKeys['salt'];

		$cryptastic = new cryptastic;

		$key = $cryptastic->pbkdf2($pass, $salt, 30000, 32);

		$decrypted = $cryptastic->decrypt($msg, $key, true);

		return $decrypted;
	}
	else die("missing wskeys");
}

/**
 * Return an array containing a list of timezone names
 */
function _xls_timezones() {
	$zones = array('Africa','America','Antarctica','Arctic','Asia',
		'Atlantic','Australia','Europe','Indian','Pacific','Canada','US');
	$results = array();

	foreach (timezone_identifiers_list() as $zone) {
		// Split the value into 0=>Continent, 1=>City
		$zone = explode('/', $zone);

		if (in_array($zone[0],$zones))
			if (isset($zone[1]) != '')
				$results[] = implode('/', $zone);
	}
	$results = array_combine($results, $results);
	return $results;
}
/** Determine whether to show the captcha to the user
 */
function _xls_show_captcha($strPage = "checkout") {
	switch ($strPage) {

		case 'register': $strKey = "CAPTCHA_REGISTRATION"; break;
		case 'contactus':  $strKey = "CAPTCHA_CONTACTUS"; break;
		case 'checkout':
		default: $strKey = "CAPTCHA_CHECKOUT"; break;
	}

	if (_xls_get_conf($strKey , '0')=='2' || (Yii::app()->user->isGuest && _xls_get_conf($strKey , '0')=='1'))
		return true;
	else return false;

}

function _xls_country()
{
	$objCountry = Country::Load(_xls_get_conf('DEFAULT_COUNTRY',39));
	return $objCountry->code;

}


function _xls_recalculate_inventory() {

	$strField = (_xls_get_conf('INVENTORY_FIELD_TOTAL','')==1 ? "inventory_total" : "inventory");


	$dbC = Yii::app()->db->createCommand();
	$dbC->setFetchMode(PDO::FETCH_OBJ);//fetch each row as Object

	$dbC->select()->from(Product::model()->tableName())->where('web=1 AND '.$strField.'>0 AND
			inventory_reserved=0 AND inventory_avail=0 AND
			master_model=0')->order('id')->limit(1000);

	foreach ($dbC->queryAll() as $item) {

			$objProduct = Product::model()->findByPk($item->id);
			$objProduct->inventory_reserved=$objProduct->CalculateReservedInventory();
			$objProduct->inventory_avail=$objProduct->inventory;
			$objProduct->save();
		}

	$ctPic=Yii::app()->db->createCommand("SELECT count(*) as thecount FROM xlsws_product WHERE web=1 AND ".$strField.">0 AND inventory_reserved=0 AND inventory_avail=0 AND master_model=0")->queryScalar();
	return $ctPic;




}

function mb_pathinfo($filepath,$portion = null) {
	preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im',$filepath,$m);
	if(isset($m[1])) $ret['dirname']=$m[1];
	if(isset($m[2])) $ret['basename']=$m[2];
	if(isset($m[5])) $ret['extension']=$m[5];
	if(isset($m[3])) $ret['filename']=$m[3];
	if ($portion==PATHINFO_DIRNAME) return $ret['dirname'];
	if ($portion==PATHINFO_BASENAME) return $ret['basename'];
	if ($portion==PATHINFO_EXTENSION) return $ret['extension'];
	if ($portion==PATHINFO_FILENAME) return $ret['filename'];
	return $ret;
}
function convert_number_to_words($number)
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

    if (!is_numeric($number)) {
	    return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
	    // overflow
	    trigger_error(
		    'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
		    E_USER_WARNING
	    );
	    return false;
    }

    if ($number < 0) {
	    return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
	    list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
	    case $number < 21:
		    $string = $dictionary[$number];
		    break;
	    case $number < 100:
		    $tens   = ((int) ($number / 10)) * 10;
		    $units  = $number % 10;
		    $string = $dictionary[$tens];
		    if ($units) {
			    $string .= $hyphen . $dictionary[$units];
		    }
		    break;
	    case $number < 1000:
		    $hundreds  = $number / 100;
		    $remainder = $number % 100;
		    $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
		    if ($remainder) {
			    $string .= $conjunction . convert_number_to_words($remainder);
		    }
		    break;
	    default:
		    $baseUnit = pow(1000, floor(log($number, 1000)));
		    $numBaseUnits = (int) ($number / $baseUnit);
		    $remainder = $number % $baseUnit;
		    $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
		    if ($remainder) {
			    $string .= $remainder < 100 ? $conjunction : $separator;
			    $string .= convert_number_to_words($remainder);
		    }
		    break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
	    $string .= $decimal;
	    $words = array();
	    foreach (str_split((string) $fraction) as $number) {
		    $words[] = $dictionary[$number];
	    }
	    $string .= implode(' ', $words);
    }

    return strtolower($string);
}

/**
 * Function for displaying what called function, useful for debugging
 */
function _xls_whereCalled( $level = 1 ) {
	$trace = debug_backtrace();
	$file   = isset($trace[$level]['file']) ? $trace[$level]['file'] : "file?";
	$line   = isset($trace[$level]['line']) ? $trace[$level]['line'] : "line?";
	$object = isset($trace[$level]['object']) ? $trace[$level]['object'] : "object?";
	if (is_object($object)) { $object = get_class($object); }

	return "Where called: class $object was called on line $line of $file";

}

function _dbx($sql) {
	//Run SQL query directly
	return Yii::app()->db->createCommand($sql)->execute();
}
function _xt($strToTranslate)
{
	echo Yii::t('global',$strToTranslate);
	Yii::log("Called outdated _xt() function for string ".$strToTranslate, CLogger::LEVEL_WARNING, 'application.'.__CLASS__.".".__FUNCTION__);
}

function _xls_convert_date_to_js($strFormat)
{

	$strFormat = str_replace("y","yy",$strFormat);
	$strFormat = str_replace("Y","yyyy",$strFormat);
	$strFormat = str_replace("d","dd",$strFormat);
	$strFormat = str_replace("m","mm",$strFormat);
	return $strFormat;

}

function recurse_copy($src,$dst) {
	$dir = opendir($src);
	@mkdir($dst);
	while(false !== ( $file = readdir($dir)) ) {
		if (( $file != '.' ) && ( $file != '..' )) {
			if ( is_dir($src . '/' . $file) ) {
				recurse_copy($src . '/' . $file,$dst . '/' . $file);
			}
			else {
				copy($src . '/' . $file,$dst . '/' . $file);
			}
		}
	}
	closedir($dir);
}
function rrmdir($dir) {
	if (is_dir($dir)) {
		$files = scandir($dir);
		foreach ($files as $file)
			if ($file != "." && $file != "..") rrmdir("$dir/$file");
		rmdir($dir);
	}
	else if (file_exists($dir)) unlink($dir);
}

// Function to Copy folders and files
function rcopy($src, $dst) {
	if (file_exists ( $dst ))
		rrmdir ( $dst );
	if (is_dir ( $src )) {
		mkdir ( $dst );
		$files = scandir ( $src );
		foreach ( $files as $file )
			if ($file != "." && $file != "..")
				rcopy ( "$src/$file", "$dst/$file" );
	} else if (file_exists ( $src ))
		copy ( $src, $dst );
}