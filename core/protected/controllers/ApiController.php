<?php
/**
 * ApiController class file
 */

class ApiController extends CController
{
	/**
	 * Key which has to be in HTTP USERNAME and PASSWORD headers
	 */
	Const APPLICATION_ID = 'WEBSTORE';
	const NOT_FOUND = "NOT_FOUND";

	// API key names
	const SERVER_WS_API_KEY = 'WS_API_KEY';
	const CLIENT_WS_API_KEY = 'HTTP_X_WS_API_KEY';

	private $format = 'json';


	/**
	 * List of exposed configuration keys.
	 * We can add to this list as necessary
	 *
	 * @var array
	 */

	private $arrExposedConfigKeys = array(
		'LIGHTSPEED_CID',
		'LIGHTSPEED_SHOW_RELEASENOTES'
	);


	/**
	 * @return array action filters
	 */

	public function init()
	{
		Controller::initParams();
	}

	/**
	 * @return array action filters
	 */

	public function filters()
	{
		return array();
	}

	public function actionList()
	{
		self::_checkApiKey();

		switch($_GET['model'])
		{
			case 'pendingorders':
				$pendingOrders = Cart::getPendingOrders();
				if ($pendingOrders)
				{
					self::_sendResponse(200, CJSON::encode($pendingOrders));
				} else {
					self::_sendResponse(404, "No pending orders were found.");
				}
				break;
			case 'configuration':
				$key = Yii::app()->getRequest()->getQuery('key');
				$keyval = self::_getConfig($key);
				if ($keyval == self::NOT_FOUND)
				{
					self::_sendResponse(501, sprintf('key not found'));
					exit;
				} else {
					self::_sendResponse(200, CJSON::encode($keyval));
				}
				break;
			default:
				self::_sendResponse(
					501,
					sprintf('Mode <b>get</b> is not implemented for model <b>%s</b>', $_GET['model'])
				);
				exit;
		}
	}

	/**
	 * Creates a new item
	 *
	 * @access public
	 * @return void
	 */

	public function actionCreate()
	{
		$this->_checkAuth();

		switch($_GET['model'])
		{
			case 'configuration':
				//Configuration keys are handled a bit differently than normal records
				$json = file_get_contents('php://input');
				$obj = json_decode($json);
				foreach ($obj as $var => $value)
				{
					if ($var != 'Testing')
					{
						_xls_set_conf($var, $value);
					}
				}

				$arrobj = (array) $obj;
				// if we're doing testing, don't push or register
				if (isset($arrobj['Testing']) == false)
				{
					_upload_default_header_to_s3();
					_xls_check_version(); //Register ourselves to stat server
				}
				break;
			// Get an instance of the respective model
			default:
				$this->_sendResponse(
					501,
					sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>', $_GET['model'])
				);
				exit;
		}

		$this->_sendResponse(200, json_encode(array('status' => 'success')));
	}


	/**
	 * Update an existing item(s).
	 *
	 * @return void
	 */

	public function actionUpdate()
	{
		self::_checkApiKey();

		switch($_GET['model'])
		{
			case 'configuration':
				//Configuration keys are handled a bit differently than normal records
				$boolUpdate = false;
				$arrBadKeys = array();
				$json = file_get_contents('php://input');

				$obj = json_decode($json);
				foreach($obj as $var => $value)
					if (in_array($var, $this->arrExposedConfigKeys))
					{
						_xls_set_conf($var,$value);
						$boolUpdate = true;
					}
					else
						$arrBadKeys[] = $var;
				break;
			// Get an instance of the respective model
			default:
				$this->_sendResponse(
					501,
					sprintf('Mode <b>update</b> is not implemented for model <b>%s</b>',$_GET['model'])
				);
				exit;
		}
		if (!empty($arrBadKeys))
			$this->_sendResponse(
				$boolUpdate ? 200 : 400,
				json_encode(
					array(
						'badkeys' => implode(',', $arrBadKeys),
						'message' => 'You have included keys that are either invalid or not exposed at this time.'
					)
				)
			);
		else
			$this->_sendResponse(200, json_encode(array('status' => 'success')));

	}

	/**
	 * Get configuration
	 *
	 * @param string $passkey
	 * @param string $confkey
	 * @return string
	 */
	private function _getConfig($confkey)
	{
		$conf = Configuration::LoadByKey($confkey);

		if(!$conf)
		{
			return self::NOT_FOUND;
		}

		return $conf->key_value;
	}

	// {{{ Other Methods
	// {{{ _sendResponse
	/**
	 * Sends the API response
	 *
	 * @param int $status
	 * @param string $body
	 * @param string $content_type
	 * @access private
	 * @return void
	 */
	private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
	{
		$status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
		// set the status
		header($status_header);
		// set the content type
		header('Content-type: ' . $content_type);

		// pages with body are easy
		if($body != '')
		{
			// send the body
			echo $body;
			exit;
		}
		// we need to create the body if none is passed
		else
		{
			// create some body messages
			$message = '';

			// this is purely optional, but makes the pages a little nicer to read
			// for your users.  Since you won't likely send a lot of different status codes,
			// this also shouldn't be too ponderous to maintain
			switch($status)
			{
				case 401:
					$message = 'You must be authorized to view this page.';
					break;
				case 404:
					$message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
					break;
				case 500:
					$message = 'The server encountered an error processing your request.';
					break;
				case 501:
					$message = 'The requested method is not implemented.';
					break;
			}

			// servers don't always have a signature turned on (this is an apache directive "ServerSignature On")
			$signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

			// this should be templatized in a real-world solution
			$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
                        <html>
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                                <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
                            </head>
                            <body>
                                <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
                                <p>' . $message . '</p>
                                <hr />
                                <address>' . $signature . '</address>
                            </body>
                        </html>';

			echo $body;
			exit;
		}
	} // }}}
	// {{{ _getStatusCodeMessage
	/**
	 * Gets the message for a status code
	 *
	 * @param mixed $status
	 * @access private
	 * @return string
	 */
	private function _getStatusCodeMessage($status)
	{
		// these could be stored in a .ini file and loaded
		// via parse_ini_file()... however, this will suffice
		// for an example
		$codes = Array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
		);

		return (isset($codes[$status])) ? $codes[$status] : '';
	} // }}}
	// {{{ _checkAuth
	/**
	 * Checks if a request is authorized
	 *
	 * @access private
	 * @return void
	 */
	private function _checkAuth()
	{
		// Check if we have the USERNAME and PASSWORD HTTP headers set?
		if(!(isset($_SERVER['HTTP_X_'.self::APPLICATION_ID.'_PASSWORD'])))
		{
			// Error: Unauthorized
			$this->_sendResponse(401);
		}
		$password = $_SERVER['HTTP_X_'.self::APPLICATION_ID.'_PASSWORD'];
		$conf = _xls_get_conf('LSKEY','notset');

		$bln= ($conf == strtolower(md5($password)) ? 1 : 0);
		if($bln != true)
			$this->_sendResponse(401);
	}

	private function _checkApiKey()
	{
		if (!(isset($_SERVER[self::CLIENT_WS_API_KEY])))
		{
			// Error: Unauthorized
			Yii::log("CLIENT_WS_API_KEY was not found in the request from "  . $_SERVER['REMOTE_ADDR'],
				CLogger::LEVEL_ERROR, 'application.'.__CLASS__.".".__FUNCTION__);
			$this->_sendResponse(401);
		}

		if (!(isset($_SERVER[self::SERVER_WS_API_KEY])))
		{
			// WS_API_KEY environment variable
			// is not set on the server
			Yii::log("SERVER_WS_API_KEY was not found on the server",
				CLogger::LEVEL_ERROR, 'application.'.__CLASS__.".".__FUNCTION__);
			$this->_sendResponse(500);
		}

		$apiKey = $_SERVER[self::CLIENT_WS_API_KEY];
		$serverApiKey = $_SERVER[self::SERVER_WS_API_KEY];

		if ($apiKey !== $serverApiKey)
		{
			Yii::log("Incorrect API key from " . $_SERVER['REMOTE_ADDR'],
				CLogger::LEVEL_ERROR, 'application.'.__CLASS__.".".__FUNCTION__);
			$this->_sendResponse(412);
		}
	}

	/**
	 * Returns the json or xml encoded array
	 *
	 * @param mixed $model
	 * @param mixed $array Data to be encoded
	 * @access private
	 * @return void
	 */
	private function _getObjectEncoded($model, $array)
	{
		if(isset($_GET['format']))
			$this->format = $_GET['format'];

		if($this->format=='json')
		{
			return CJSON::encode($array);
		}
		elseif($this->format=='xml')
		{
			$result = '<?xml version="1.0">';
			$result .= "\n<$model>\n";
			foreach($array as $key => $value)
				$result .= "    <$key>".utf8_encode($value)."</$key>\n";
			$result .= '</'.$model.'>';
			return $result;
		}
		else
		{
			return;
		}
	}
}
