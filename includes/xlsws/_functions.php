<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
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
	if(_xls_get_conf('DEBUG_TEMPLATE' , false) && stristr($name , ".tpl")) {
		_xls_stack_add('template_used' , $name);
	}

	$file = 'templates/' . _xls_get_conf('DEFAULT_TEMPLATE' , 'xsilva') . '/'.$name;
	if(!file_exists($file) && stristr($name , ".tpl")) {
		QApplication::Log(E_USER_NOTICE,"Template ".$file." not found - site cannot continue");
		die("Template file missing. Check System Log for details.");
	}
	else return $file;
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
 * Determine whether a string is a properly formated email address
 *
 * @param string $email :: The string to test
 * @return int(bool)
 */
function isValidEmail($email) {
	$qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
	$dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
	$atom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c'.
		'\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
	$quoted_pair = '\\x5c[\\x00-\\x7f]';
	$domain_literal = "\\x5b($dtext|$quoted_pair)*\\x5d";
	$quoted_string = "\\x22($qtext|$quoted_pair)*\\x22";
	$domain_ref = $atom;
	$sub_domain = "($domain_ref|$domain_literal)";
	$word = "($atom|$quoted_string)";
	$domain = "$sub_domain(\\x2e$sub_domain)*";
	$local_part = "$word(\\x2e$word)*";
	$addr_spec = "$local_part\\x40$domain";

	return preg_match("!^$addr_spec$!", $email) ? 1 : 0;
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
function values_as_keys($arr) {
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
function _xls_log($msg) {
	QApplication::Log(E_NOTICE, 'unknown', $msg);
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
	return $hname;
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
 * Get configuration value
 * Note : This is a shortcut to the global ConfigurationManager
 *
 * @param string $key
 * @param string $default
 * @return string
 */
function _xls_get_conf($strKey, $mixDefault = "") {
	return Configuration::$Manager->GetValue($strKey, $mixDefault);
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
	$config_type, $options, $sort_order = NULL) {

	$conf = Configuration::LoadByKey($key);

	if(!$conf)
		$conf = new Configuration();

	$conf->Key = $key;
	$conf->Title = $title;
	$conf->Value = $value;
	$conf->HelperText = $helper_text;
	$conf->ConfigType = $config_type;

	$conf->Options = $options;

	$query = <<<EOS
		SELECT IFNULL(MAX(sort_order),0)+1
		FROM xlsws_configuration
		WHERE configuration_type_id = '{$config_type}';
EOS;
	if(!$sort_order)
		$sort_order = _dbx_first_cell($query);

	$conf->SortOrder = $sort_order;

	$conf->Save();
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
* If the key already exists, we add to it instead of replacing
*
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
function _xls_display_msg($msg, $redirect = "index.php") {
	_xls_stack_add('msg', _sp($msg));

	if($redirect)
		_xls_add_meta_redirect($redirect);

	_rd('index.php?xlspg=msg');
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

	_rd('index.php?xlspg=login');
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

/**
 * Send an email
 *
 * @param string $to
 * @param string $subject
 * @param string $body
 * @param string $from
 */
function _xls_mail($strAddTo, $strSubject, $strBody, $strAddFrom = false) {
    // Ensure the Email Server is configured
    if (!QEmailServer::$SmtpServer) {
        QApplication::Log(E_ERROR, 'smtp', 
            _sp('SMTP Server is not defined'));
        return false;
    }

    // Set default values
    $strAddFrom = _xls_get_conf('ORDER_FROM', $strAddFrom);
    $strAddBcc = _xls_get_conf('EMAIL_BCC', false);

    // Ensure the From address is set
    if (!$strAddFrom) { 
        QApplication::Log(E_ERROR, 'smtp', 
            _sp('Order From email address is not defined'));
        return false;
    }

    // Strip HTML tags from Template generated emails
    $strText = strip_tags($strBody);
    $strTextArray = explode('\n', $strText);

    foreach($strTextArray as $strKey => $strLine)
        $strTextArray[$strKey] = trim($strLine);

    $strText = implode('\n', $strTextArray);

    // Determine whether we should send HTML emails
    $blnHtmlEmail = _xls_get_conf('HTML_EMAIL', true);

    $objCustomer = Customer::LoadByEmail(trim(strtolower($strAddTo)));
    if ($objCustomer) $blnHtmlEmail = $objCustomer->HtmlEmail;

    // Define the email message
    $objMessage = new QEmailMessage();
    $objMessage->To = $strAddTo;
    $objMessage->From = $strAddFrom;
    if ($strAddBcc)
        $objMessage->Bcc = $strAddBcc;
    $objMessage->Subject = $strSubject;
    $objMessage->Body = $strText;
    if ($blnHtmlEmail)
        $objMessage->HtmlBody = $strBody;

    $intSocketTimeout = ini_get('default_socket_timeout');
    // Send the email
    try { 
        if (ini_get('default_socket_timeout') > ini_get('max_execution_time'))
            ini_set('default_socket_timeout', 
                ini_get('max_execution_time') * 80 / 100);

        QEmailServer::Send($objMessage);

        ini_set('default_socket_timeout', $intSocketTimeout);

    }
    catch (QCallerException $objExc) {
        QApplication::Log(E_ERROR, 'smtp', 
            $objExc->getMessage());
        return false;
    }

	return true;
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
 * Get the image for verification
 *
 * @return string
 */
function _xls_verify_img() {
	if (Customer::GetCurrent()) {
		// TODO :: Disable captcha after login
	}

	$imgEl = '<img id="captcha-verif-img" src="verify-img.php"' .
		' alt="Verification Code" />';
	$refreshEl = '<div id="captcha-verif-refresh"' .
		' alt="Select a new sequence"' .
		' onclick="javascript:verfimg=\'verify-img.php?rnd=\' +' .
		' Math.random();setCaptchaImage(verfimg);"><img src="' .
		__CAPTCHA_ASSETS__ .
		'/images/refresh.gif" alt="Select a new sequence" /></div>';
	$playAudioEl = '<div id="captcha-verif-audio"><a href="' .
		__CAPTCHA_ASSETS__ .
		'/securimage_play.php"><img alt="Listen to letter sequence" src="' .
		__CAPTCHA_ASSETS__ .
		'/images/audio_icon.gif" /></a></div>';

	return "$imgEl $refreshEl $playAudioEl";
}

/**
 * Return the current verification image text
 *
 * @return string
 */
function _xls_verify_img_txt() {
	if (Customer::GetCurrent()) {
	}

	require_once(SECIMG_DIR . "/securimage.php");

	$secimg = new Securimage();
	return $secimg->getCode();
}

/**
 * Trims all feilds in an array
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
function _xls_currency($num) {
	if(function_exists('_custom_currency'))
		return _custom_currency($num);

	if (!is_numeric($num))
		return $num;

	$format = _xls_get_conf('CURRENCY_FORMAT' , '%i');
	return money_format($format , $num);
}

/**
 * Return how long after (in second current page will expire)
 * @return int
 */
function _xls_page_session_expiry_duration() {
	// Synchronize page expiry with session expiry minus 60 seconds
	$intLifetime = XLSSessionHandler::GetSessionLifetime() - 60;
	return $intLifetime;
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
 * Return the Base URL for the site
 * Also perform http/https conversion if need be.
 *
 * @param boolean ssl_attempt
 * @return string url
 */
function _xls_site_dir($ssl_attempt = true) {
	$strUrlPfx = '';
	if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on'))
		$strUrlPfx = 'https://';
	else
		$strUrlPfx = 'http://';

	$strUrlPath = '';
	if (dirname($_SERVER['PHP_SELF']) == '/')
		$strUrlPath = '';
	else
		$strUrlPath = dirname($_SERVER['PHP_SELF']);

	return $strUrlPfx . $_SERVER['HTTP_HOST'] . $strUrlPath;
}

/**
 * Get the current customer object
 *
 * @param boolean $fallbackonStackTemp
 * @return obj customer
 */
function _xls_get_current_customer($fallbackOnStackTemp=false) {
	QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
	return Customer::GetCurrent($fallbackonStackTemp);
}

/**
 * Get the ID of the current customer object
 * @return int
 */
function _xls_get_current_customer_id() {
	$customer = Customer::GetCurrent();
	if($customer)
		return $customer->Rowid;

	return null;
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
 * Update a QFormObject to provide an onfocus dhtml popup
 *
 * @param obj $objControl :: QForm Control
 * @param string $strDefault :: String to display as default
 */
function _xls_helpertextbox($objControl , $strDefault = '') {
	$objControl->Text = $strDefault;
	$objControl->SetCustomAttribute("onfocus",
		"if(this.value=='{$strDefault}'){ this.value='';" .
		" this.className='';}");
	$objControl->SetCustomAttribute("onblur",
		"if(this.value==''){ this.value='{$strDefault}';" .
		" this.className='helper';}");
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
}

/**
 * Add meta escription to the stack_vars stack
 * @param string $desc
 */
function _xls_add_meta_desc($desc) {
	_xls_stack_add('xls_meta_desc', strip_tags($desc));
}

/**
 * Add meta keyword to the stack_vars stack
 * @param string $desc
 */
function _xls_add_meta_keyword($words) {
	if(is_array($words))
		$words = implode("," , $words);
	_xls_stack_add('xls_meta_keywords', strip_tags($words));
}

/**
 * Return the Web Store's version
 * @return string
 */
function _xls_version() { // LEGACY
	return XLSWS_VERSION;
}

/**
* Initialize a new language.
*
* @param string $strLangCode
* @param string $strCountryCode (optional)
*/
function _xls_lang_init($strLangCode , $strCountryCode = '') {
	// Do not process if language isn't changing
	if((QApplication::$LanguageCode == $strLangCode) &&
	   (QApplication::$CountryCode == $strCountryCode))
		return;

	$_SESSION['language_code'] = $strLangCode;
	if (!empty($strCountryCode))
		$_SESSION['country_code'] = $strCountryCode;

	QI18n::Initialize();
}

/**
 * Return amount of tax fields
 * @return int
 */
function _xls_tax_count() {
	return 5;
}

/**
 * Return the default tax code
 * @return obj TaxCode
 */
// TODO :: This should be part of the TaxCode object
function _xls_tax_default_taxcode() {
	$taxcodes = TaxCode::LoadAll(
		QQ::Clause(QQ::OrderBy(QQN::TaxCode()->ListOrder))
	);
	if (!($taxcodes && is_array($taxcodes) && count($taxcodes)))
		return NULL;

	return $taxcodes[0];
}

/**
 * Return tax to be charged on a given price, tax code and tax status
 *     $code and $status can either be respective objects or database id
 *
 * @param float $price
 * @param TaxCode|int $code
 * @param TaxStatus|int $status
 * @return
 */

// Todo move caching to a manager
function _xls_calculate_price_tax_price($price , $code , $status) {
	static $taxes; // Cached for better performance

	$newprice = $price;
	$rtaxamount = array(1=>0 , 2=>0 , 3=>0 , 4=> 0 , 5=>0);

	$rtaxamount = array(1=>0 , 2=>0 , 3=>0 , 4=> 0 , 5=>0);
	$newprice = $price;

	if($code instanceof TaxCode )
		$tcode = $code;
	else
		$tcode = TaxCode::Load($code);

	if(!$tcode) {
		if($code!= -1)  // Only complain about things that are not -1
			_xls_log(_sp("Unknown tax code passed") . " $code");
		return array($newprice , $rtaxamount);
	}

	if($status instanceof TaxStatus)
		$tstatus = $status;
	elseif($status >= 0)
		$tstatus = TaxStatus::Load($status);
	else
		$tstatus = false;

	if(!$taxes)
		$taxes = Tax::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Tax()->Rowid)));

	$taxtypes = _xls_tax_count(); // Number of taxes in LS

	// for each exempt, reset the code to 0
	if($tstatus) {
		if($tstatus->Tax1Status) $tcode->Tax1Rate =0;
		if($tstatus->Tax2Status) $tcode->Tax2Rate =0;
		if($tstatus->Tax3Status) $tcode->Tax3Rate =0;
		if($tstatus->Tax4Status) $tcode->Tax4Rate =0;
		if($tstatus->Tax5Status) $tcode->Tax5Rate =0;
	}

	$i = 0;
	foreach($taxes as $tax) {
		$rate = "Tax" . ($i+1) . "Rate";

		if($tax->Compounded)
			$tamount = $newprice * ($tcode->$rate/100);
		else
			$tamount = $price * ($tcode->$rate/100);

		if(($tax->Max > 0) && ($tamount >= $tax->Max))
			$tamount = $tax->Max;

		$rtaxamount[$i+1] = $tamount;

		$newprice = $newprice + $tamount;

		$i++;
		if($i >= $taxtypes) $i = $taxtypes;
	}

	return array($newprice , $rtaxamount);
}

/**
 * Return tax charged on a given price, tax code and tax status -
 * Assuming that $price is already tax inclusive $code and $status can
 * either be respective objects or database id
 * This uses the  _xls_calculate_price_tax_price function by first
 * calculating the original price and then building up the tax comments.
 *
 * @param float $price
 * @param TaxCode|int $code
 * @param TaxStatus|int $status
 * @return
 */
// TODO :: Move caching to manager
function _xls_calculate_price_tax_price_tax_inclusive( // LEGACY
	$price , $code , $status) {
	static $taxes;

	$origprice = $price;

	if($code instanceof TaxCode )
		$tcode = $code;
	else
		$tcode = TaxCode::Load($code);

	if(!$tcode) {
		if($code!= -1)  // Only complain about things that are not -1
			_xls_log(_sp("Unknown tax code passed") . " $code ");
		return array($newprice , $rtaxamount);
	}

	if($status instanceof TaxStatus)
		$tstatus = $status;
	elseif($status >= 0)
		$tstatus = TaxStatus::Load($status);
	else
		$tstatus = false;

	if(!$taxes)
		$taxes = Tax::LoadAll(QQ::Clause(QQ::OrderBy(
			QQN::Tax()->Rowid , false)));

	$taxtypes = _xls_tax_count(); // Number of taxes in LS

	// for each exempt, reset the code to 0!
	if($tstatus) {
		if($tstatus->Tax1Status) $tcode->Tax1Rate = 0;
		if($tstatus->Tax2Status) $tcode->Tax2Rate = 0;
		if($tstatus->Tax3Status) $tcode->Tax3Rate = 0;
		if($tstatus->Tax4Status) $tcode->Tax4Rate = 0;
		if($tstatus->Tax5Status) $tcode->Tax5Rate = 0;
	}

	$ttotal = 0;

	$i = $taxtypes;
	foreach($taxes as $tax) {
		$rate = "Tax" . ($i) . "Rate";

		if($tax->Compounded) {
			$origprice =  ($origprice  / ((100+$tcode->$rate)/100));
		} else {
			$ttotal +=  $tcode->$rate;
		}

		$i--;
		if($i <= 0) break;
	}

	$origprice =  ($origprice  / ((100+$ttotal)/100));

	return _xls_calculate_price_tax_price($origprice , $code ,$status);
}

/**
 * Match a given address to the most accurate Destination
 * @param string $country
 * @param string $state
 * @param string $zip
 * @return obj :: The matching destination
 */
function _xls_match_destination($country , $state , $zip) {
	QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
	return Destination::LoadMatching($country, $state, $zip);
}

/**
 * Merge arguments into an array
 * @param array (variable amount of array arguments)
 * @param boolean $preserve_keys (last argument should be a boolean)
 * @return array
 */
function _xls_array_merge() {
	$arg_list = func_get_args();
	$preserve_keys = true;
	$result = array();
	if (is_bool(end($arg_list))) {
		$preserve_keys = array_pop($arg_list);
	}

	foreach((array)$arg_list as $arg) {
		foreach((array)$arg as $k => $v) {
			if ($preserve_keys == true) {
				$result[$k]=$v;
			} else {
				$result[]=$v;
			}
		}
	}

	return $result;
}

/**
 * Build HTTP GET style query string
 * @param array $array
 * @param string $query
 * @param string $prefix
 * @return string
 */
function _xls_build_query($array, $query = '', $prefix = '') {
	if (!is_array($array))
		return false;

	foreach ($array as $k => $v) {
		if (is_array($v))
			$query = _xls_build_query($v, $query,
				urlencode(empty($prefix) ? "$k" : $prefix . "[$k]"));
		else
			$query .= (!empty($query) ? '&' : '') .
				(empty($prefix) ? $k : $prefix . urlencode("[$k]")) .
				'=' . urlencode($v);
	}
	return $query;
}

/**
 * Return the URL for a Custom Page
 * @param string $page :: The page key
 * @return string
 */
function _xls_custom_page_url($page) {
	if(!_xls_get_conf('ENABLE_SEO_URL' , false))
		return "index.php?cpage=$page";

	$cpage = CustomPage::LoadByKey($page);

	if($cpage)
		return $cpage->Link;

	return "index.php";
}

/**
 * Return code to load the XML sitemap
 * @param string $url
 * @param boolean $lastmod
 * @param boolean $priority
 */

function _xls_sitemap_xml_url($url , $lastmod = false , $priority = false) {
	$sitedir = _xls_site_dir();
	$result = <<<EOS
<url>
<loc> {$sitedir}/{$url} </loc>
EOS;
	$result .= ($lastmod?' <lastmod>' .  $lastmod  . '</lastmod>':'') .
		($priority?'    <priority>' .  $priority  . '</priority>':'') .
		'</url>' . "\n";

	return $result;
}

/**
 * Generate the sitemap
 * @param string $file
 */

function _xls_generate_sitemap($file = "sitemap.xml") {
	$ret = '';

	if(is_resource($file))
		$fp = $file;
	elseif($file != '')
		$fp = _xls_fopen_w($file);
	else
		$fp= fopen('php://stdout' , 'w');

	if(!$fp) {
		_xls_log(_sp("Could not open sitemap file") . " $file " .
				 _sp("for writing"));
		$ret .= _sp("Could not open sitemap file $file for writing");
		return;
	}

	fwrite($fp, '<?xml version="1.0" encoding="UTF-8"?>' . "\n");
	fwrite($fp, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n");

	// index page
	fwrite($fp , _xls_sitemap_xml_url('index.php' ));
	// sitemap page
	fwrite($fp , _xls_sitemap_xml_url('index.php?xlspg=sitemap' ));

	$categories = Category::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Category()->Rowid)));

	foreach($categories as $category){
		$ret .=  _sp("Generating URL for category") .
			$category->Name . "\n";

		fwrite($fp, _xls_sitemap_xml_url($category->Link,
			QDateTime::FromTimestamp(strtotime(
				$category->Modified))->__toString('YYYY-MM-DD')));
	}

	$products = Product::QueryArray(
		QQ::AndCondition(QQ::Equal(QQN::Product()->Web, 1),
			QQ::OrCondition(
				QQ::Equal(QQN::Product()->MasterModel, 1),
				QQ::AndCondition(
					QQ::Equal(QQN::Product()->MasterModel, 0),
					QQ::Equal(QQN::Product()->FkProductMasterId, 0)
				)
			)
		),
		QQ::Clause(QQ::OrderBy(QQN::Product()->Code))
	);

	foreach($products as $product) {
		$ret .=  _sp("Generating URL for product") . " $product->Code " . "\n";

		if (_xls_get_conf('ENABLE_SEO_URL',0) == '1') {
			fwrite($fp, _xls_sitemap_xml_url(
				urlencode($product->Code) . ".html",
				QDateTime::FromTimestamp(strtotime(
					$product->Modified))->__toString('YYYY-MM-DD'),
					($product->Featured?'0.8':'0.5')));
		} else {
			fwrite($fp, _xls_sitemap_xml_url(
				"index.php?product=" . urlencode($product->Code),
				QDateTime::FromTimestamp(strtotime(
					$product->Modified))->__toString('YYYY-MM-DD'),
					($product->Featured?'0.8':'0.5'))  );
		}
	}

	$pages = CustomPage::LoadAll();

	foreach($pages as $page) {
		$ret .=  _sp("Generating url for page ") . $page->Title . "\n";

		if (_xls_get_conf('ENABLE_SEO_URL',0) == '1') {
			fwrite($fp , _xls_sitemap_xml_url(
				urlencode($page->Key) . ".html",
				QDateTime::FromTimestamp(strtotime(
					$page->Modified))->__toString('YYYY-MM-DD')));
		} else {
			fwrite($fp , _xls_sitemap_xml_url(
				$page->Link,
				QDateTime::FromTimestamp(strtotime(
					$page->Modified))->__toString('YYYY-MM-DD')));
		}
	}

	fwrite($fp , '</urlset>' . "\n");

	$ret .=  "Done!";
	if(is_string($file))
		fclose($fp);

	return $ret;
}

/**
 * Encrypt the Web Store key
 * @param string $text
 * @param boolean $key
 */
function _xls_key_encrypt($text , $key = false) {
	if(!$key)
		$key = _xls_get_conf('LSKEY' , 'password');

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

	return $crypttext;
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
	return $results;
}

/**
 * Mimic mysql_real_escape_String
 */
function _xls_escape($strText) {
	if (!empty($strText) && is_string($strText))
		return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"),
			array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'),
			$strText);
	return $strText;
}
