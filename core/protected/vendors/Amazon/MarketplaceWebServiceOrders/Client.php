<?php
/** 
 *  PHP Version 5
 *
 *  @category    Amazon
 *  @package     MarketplaceWebServiceOrders
 *  @copyright   Copyright 2008-2012 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *  @link        http://aws.amazon.com
 *  @license     http://aws.amazon.com/apache2.0  Apache License, Version 2.0
 *  @version     2011-01-01
 */
/******************************************************************************* 
 * 
 *  Marketplace Web Service Orders PHP5 Library
 * 
 */

/**
 *  @see MarketplaceWebServiceOrders_Interface
 */
require_once ('MarketplaceWebServiceOrders/Interface.php');

/**
 * This contains the Order Retrieval API section of the Marketplace Web Service.
 * 
 * MarketplaceWebServiceOrders_Client is an implementation of MarketplaceWebServiceOrders
 *
 */
class MarketplaceWebServiceOrders_Client implements MarketplaceWebServiceOrders_Interface
{

    const SERVICE_VERSION = '2011-01-01';
    const MWS_CLIENT_VERSION = '2012-05-01';

    /** @var string */
    private  $_awsAccessKeyId = null;

    /** @var string */
    private  $_awsSecretAccessKey = null;

    /** @var array */
    private  $_config = array ('ServiceURL' => null,
                               'UserAgent' => 'MarketplaceWebServiceOrders PHP5 Library',
                               'SignatureVersion' => 2,
                               'SignatureMethod' => 'HmacSHA256',
                               'ProxyHost' => null,
                               'ProxyPort' => -1,
                               'MaxErrorRetry' => 3
                               );

    /**
     * Construct new Client
     *
     * @param string $awsAccessKeyId AWS Access Key ID
     * @param string $awsSecretAccessKey AWS Secret Access Key
     * @param array $config configuration options.
     * Valid configuration options are:
     * <ul>
     * <li>ServiceURL</li>
     * <li>UserAgent</li>
     * <li>SignatureVersion</li>
     * <li>TimesRetryOnError</li>
     * <li>ProxyHost</li>
     * <li>ProxyPort</li>
     * <li>MaxErrorRetry</li>
     * </ul>
     */
    public function __construct($awsAccessKeyId, $awsSecretAccessKey, $applicationName, $applicationVersion, $config = null)
    {
        iconv_set_encoding('output_encoding', 'UTF-8');
        iconv_set_encoding('input_encoding', 'UTF-8');
        iconv_set_encoding('internal_encoding', 'UTF-8');

        $this->_awsAccessKeyId = $awsAccessKeyId;
        $this->_awsSecretAccessKey = $awsSecretAccessKey;
        if (!is_null($config)) $this->_config = array_merge($this->_config, $config);
        $this->setUserAgentHeader($applicationName, $applicationVersion);
    }
    
    private function setUserAgentHeader(
    	$applicationName,
    	$applicationVersion,
    	$attributes = null) {

        if (is_null($attributes)) {
        	$attributes = array ();
        }
        
        $this->_config['UserAgent'] = 
        	$this->constructUserAgentHeader($applicationName, $applicationVersion, $attributes);
    }
    
    private function constructUserAgentHeader($applicationName, $applicationVersion, $attributes = null) {
    	if (is_null($applicationName) || $applicationName === "") {
    		throw new InvalidArgumentException('$applicationName cannot be null');
    	}
    	
    	if (is_null($applicationVersion) || $applicationVersion === "") {
    		throw new InvalidArgumentException('$applicationVersion cannot be null');
    	}
    	
    	$userAgent = 
    		$this->quoteApplicationName($applicationName)
    		. '/'
    		. $this->quoteApplicationVersion($applicationVersion);
    		
        $userAgent .= ' (';
        $userAgent .= 'Language=PHP/' . phpversion();
        $userAgent .= '; ';
        $userAgent .= 'Platform=' . php_uname('s') . '/' . php_uname('m') . '/' . php_uname('r');
        $userAgent .= '; ';
        $userAgent .= 'MWSClientVersion=' . self::MWS_CLIENT_VERSION;
        
        foreach ($attributes as $key => $value) {
        	if (empty($value)) {
        		throw new InvalidArgumentException("Value for $key cannot be null or empty.");
        	}
        	
        	$userAgent .= '; '
        	    . $this->quoteAttributeName($key)
        	    . '='
        	    . $this->quoteAttributeValue($value);
        }
        
        $userAgent .= ')';
        
        return $userAgent;
    }
    
   /**
    * Collapse multiple whitespace characters into a single ' ' character.
    * @param $s
    * @return string
    */
   private function collapseWhitespace($s) {
       return preg_replace('/ {2,}|\s/', ' ', $s);
   }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
     * and '/' characters from a string.
     * @param $s
     * @return string
     */
    private function quoteApplicationName($s) {
	    $quotedString = $this->collapseWhitespace($s);
	    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
	    $quotedString = preg_replace('/\//', '\\/', $quotedString);
	
	    return $quotedString;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
     * and '(' characters from a string.
     *
     * @param $s
     * @return string
     */
    private function quoteApplicationVersion($s) {
	    $quotedString = $this->collapseWhitespace($s);
	    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
	    $quotedString = preg_replace('/\\(/', '\\(', $quotedString);
	
	    return $quotedString;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
     * and '=' characters from a string.
     *
     * @param $s
     * @return unknown_type
     */
    private function quoteAttributeName($s) {
	    $quotedString = $this->collapseWhitespace($s);
	    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
	    $quotedString = preg_replace('/\\=/', '\\=', $quotedString);
	
	    return $quotedString;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape ';', '\',
     * and ')' characters from a string.
     *
     * @param $s
     * @return unknown_type
     */
    private function quoteAttributeValue($s) {
	    $quotedString = $this->collapseWhitespace($s);
	    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
	    $quotedString = preg_replace('/\\;/', '\\;', $quotedString);
	    $quotedString = preg_replace('/\\)/', '\\)', $quotedString);
	
	    return $quotedString;
	}

    // Public API ------------------------------------------------------------//


            
    /**
     * List Orders By Next Token 
     * If ListOrders returns a nextToken, thus indicating that there are more orders
     * than returned that matched the given filter criteria, ListOrdersByNextToken
     * can be used to retrieve those other orders using that nextToken.
     * 
     * @see http://docs.amazonwebservices.com/${docPath}ListOrdersByNextToken.html
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenRequest request
     * or MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenRequest object itself
     * @see MarketplaceWebServiceOrders_Model_ListOrdersByNextToken
     * @return MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function listOrdersByNextToken($request)
    {
        if (!$request instanceof MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenRequest) {
            require_once ('MarketplaceWebServiceOrders/Model/ListOrdersByNextTokenRequest.php');
            $request = new MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenRequest($request);
        }
        require_once ('MarketplaceWebServiceOrders/Model/ListOrdersByNextTokenResponse.php');
        $httpResponse = $this->_invoke($this->_convertListOrdersByNextToken($request));
        $response = MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


            
    /**
     * List Order Items By Next Token 
     * If ListOrderItems cannot return all the order items in one go, it will
     * provide a nextToken. That nextToken can be used with this operation to
     * retrive the next batch of items for that order.
     * 
     * @see http://docs.amazonwebservices.com/${docPath}ListOrderItemsByNextToken.html
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenRequest request
     * or MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenRequest object itself
     * @see MarketplaceWebServiceOrders_Model_ListOrderItemsByNextToken
     * @return MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenResponse MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function listOrderItemsByNextToken($request)
    {
        if (!$request instanceof MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenRequest) {
            require_once ('MarketplaceWebServiceOrders/Model/ListOrderItemsByNextTokenRequest.php');
            $request = new MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenRequest($request);
        }
        require_once ('MarketplaceWebServiceOrders/Model/ListOrderItemsByNextTokenResponse.php');
        $httpResponse = $this->_invoke($this->_convertListOrderItemsByNextToken($request));
        $response = MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


            
    /**
     * Get Order 
     * This operation takes up to 50 order ids and returns the corresponding orders.
     * 
     * @see http://docs.amazonwebservices.com/${docPath}GetOrder.html
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_GetOrderRequest request
     * or MarketplaceWebServiceOrders_Model_GetOrderRequest object itself
     * @see MarketplaceWebServiceOrders_Model_GetOrder
     * @return MarketplaceWebServiceOrders_Model_GetOrderResponse MarketplaceWebServiceOrders_Model_GetOrderResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function getOrder($request)
    {
        if (!$request instanceof MarketplaceWebServiceOrders_Model_GetOrderRequest) {
            require_once ('MarketplaceWebServiceOrders/Model/GetOrderRequest.php');
            $request = new MarketplaceWebServiceOrders_Model_GetOrderRequest($request);
        }
        require_once ('MarketplaceWebServiceOrders/Model/GetOrderResponse.php');
        $httpResponse = $this->_invoke($this->_convertGetOrder($request));
        $response = MarketplaceWebServiceOrders_Model_GetOrderResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


            
    /**
     * List Order Items 
     * This operation can be used to list the items of the order indicated by the
     * given order id (only a single Amazon order id is allowed).
     * 
     * @see http://docs.amazonwebservices.com/${docPath}ListOrderItems.html
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_ListOrderItemsRequest request
     * or MarketplaceWebServiceOrders_Model_ListOrderItemsRequest object itself
     * @see MarketplaceWebServiceOrders_Model_ListOrderItems
     * @return MarketplaceWebServiceOrders_Model_ListOrderItemsResponse MarketplaceWebServiceOrders_Model_ListOrderItemsResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function listOrderItems($request)
    {
        if (!$request instanceof MarketplaceWebServiceOrders_Model_ListOrderItemsRequest) {
            require_once ('MarketplaceWebServiceOrders/Model/ListOrderItemsRequest.php');
            $request = new MarketplaceWebServiceOrders_Model_ListOrderItemsRequest($request);
        }
        require_once ('MarketplaceWebServiceOrders/Model/ListOrderItemsResponse.php');
        $httpResponse = $this->_invoke($this->_convertListOrderItems($request));
        $response = MarketplaceWebServiceOrders_Model_ListOrderItemsResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


            
    /**
     * List Orders 
     * ListOrders can be used to find orders that meet the specified criteria.
     * 
     * @see http://docs.amazonwebservices.com/${docPath}ListOrders.html
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_ListOrdersRequest request
     * or MarketplaceWebServiceOrders_Model_ListOrdersRequest object itself
     * @see MarketplaceWebServiceOrders_Model_ListOrders
     * @return MarketplaceWebServiceOrders_Model_ListOrdersResponse MarketplaceWebServiceOrders_Model_ListOrdersResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function listOrders($request)
    {
        if (!$request instanceof MarketplaceWebServiceOrders_Model_ListOrdersRequest) {
            require_once ('MarketplaceWebServiceOrders/Model/ListOrdersRequest.php');
            $request = new MarketplaceWebServiceOrders_Model_ListOrdersRequest($request);
        }
        require_once ('MarketplaceWebServiceOrders/Model/ListOrdersResponse.php');
        $httpResponse = $this->_invoke($this->_convertListOrders($request));
        $response = MarketplaceWebServiceOrders_Model_ListOrdersResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


            
    /**
     * Get Service Status 
     * Returns the service status of a particular MWS API section. The operation
     * takes no input.
     * 
     * @see http://docs.amazonwebservices.com/${docPath}GetServiceStatus.html
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_GetServiceStatusRequest request
     * or MarketplaceWebServiceOrders_Model_GetServiceStatusRequest object itself
     * @see MarketplaceWebServiceOrders_Model_GetServiceStatus
     * @return MarketplaceWebServiceOrders_Model_GetServiceStatusResponse MarketplaceWebServiceOrders_Model_GetServiceStatusResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function getServiceStatus($request)
    {
        if (!$request instanceof MarketplaceWebServiceOrders_Model_GetServiceStatusRequest) {
            require_once ('MarketplaceWebServiceOrders/Model/GetServiceStatusRequest.php');
            $request = new MarketplaceWebServiceOrders_Model_GetServiceStatusRequest($request);
        }
        require_once ('MarketplaceWebServiceOrders/Model/GetServiceStatusResponse.php');
        $httpResponse = $this->_invoke($this->_convertGetServiceStatus($request));
        $response = MarketplaceWebServiceOrders_Model_GetServiceStatusResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }

        // Private API ------------------------------------------------------------//

    /**
     * Invoke request and return response
     */
    private function _invoke(array $parameters)
    {
        $actionName = $parameters["Action"];
        $response = array();
        $responseBody = null;
        $statusCode = 200;

        /* Submit the request and read response body */
        try {

        	if (empty($this->_config['ServiceURL'])) {
        		throw new MarketplaceWebServiceOrders_Exception(
        			array ('ErrorCode' => 'InvalidServiceURL',
        				   'Message' => "Missing serviceUrl configuration value. You may obtain a list of valid MWS URLs by consulting the MWS Developer's Guide, or reviewing the sample code published along side this library."));
        	}

            /* Add required request parameters */
            $parameters = $this->_addRequiredParameters($parameters);

            $shouldRetry = true;
            $retries = 0;
            do {
                try {
                        $response = $this->_httpPost($parameters);
                        if ($response['Status'] === 200) {
                            $shouldRetry = false;
                        } else {
                            if ($response['Status'] === 500 || $response['Status'] === 503) {
                            	
                            	require_once('MarketplaceWebServiceOrders/Model/ErrorResponse.php');
                            	$errorResponse = MarketplaceWebServiceOrders_Model_ErrorResponse::fromXML($response['ResponseBody']);
                            	
                            	$errors = $errorResponse->getError();
                            	$shouldRetry = ($errors[0]->getCode() === 'RequestThrottled') ? false : true;
                            	
                            	if ($shouldRetry) {
                            		$this->_pauseOnRetry(++$retries, $response['Status']);
                            	} else {
                            		throw $this->_reportAnyErrors($response['ResponseBody'], $response['Status'], $response['ResponseHeaderMetadata']);
                            	}
                            } else {
                                throw $this->_reportAnyErrors($response['ResponseBody'], $response['Status'], $response['ResponseHeaderMetadata']);
                            }
                       }
                /* Rethrow on deserializer error */
                } catch (Exception $e) {
                    require_once ('MarketplaceWebServiceOrders/Exception.php');
                    if ($e instanceof MarketplaceWebServiceOrders_Exception) {
                        throw $e;
                    } else {
                        require_once ('MarketplaceWebServiceOrders/Exception.php');
                        throw new MarketplaceWebServiceOrders_Exception(array('Exception' => $e, 'Message' => $e->getMessage()));
                    }
                }

            } while ($shouldRetry);

        } catch (MarketplaceWebServiceOrders_Exception $se) {
            throw $se;
        } catch (Exception $t) {
            throw new MarketplaceWebServiceOrders_Exception(array('Exception' => $t, 'Message' => $t->getMessage()));
        }

        return array ('ResponseBody' => $response['ResponseBody'], 'ResponseHeaderMetadata' => $response['ResponseHeaderMetadata']);
    }

    /**
     * Look for additional error strings in the response and return formatted exception
     */
    private function _reportAnyErrors($responseBody, $status, $responseHeaderMetadata, Exception $e =  null)
    {
        $exProps = array();
        $exProps["StatusCode"] = $status;
        $exProps["ResponseHeaderMetadata"] = $responseHeaderMetadata;
        
        libxml_use_internal_errors(true);  // Silence XML parsing errors
        $xmlBody = simplexml_load_string($responseBody);
        
        if ($xmlBody !== false) {  // Check XML loaded without errors
            $exProps["XML"] = $responseBody;
            $exProps["ErrorCode"] = $xmlBody->Error->Code;
            $exProps["Message"] = $xmlBody->Error->Message;
            $exProps["ErrorType"] = !empty($xmlBody->Error->Type) ? $xmlBody->Error->Type : "Unknown";
            $exProps["RequestId"] = !empty($xmlBody->RequestID) ? $xmlBody->RequestID : $xmlBody->RequestId; // 'd' in RequestId is sometimes capitalized
        } else { // We got bad XML in response, just throw a generic exception
            $exProps["Message"] = "Internal Error";
        }
        
        require_once ('MarketplaceWebServiceOrders/Exception.php');
        return new MarketplaceWebServiceOrders_Exception($exProps);
    }



    /**
     * Perform HTTP post with exponential retries on error 500 and 503
     *
     */
    private function _httpPost(array $parameters)
    {

        $query = $this->_getParametersAsString($parameters);
        $url = parse_url ($this->_config['ServiceURL']);
	    $uri = array_key_exists('path', $url) ? $url['path'] : null;
	    $port = null;
        if (!isset ($uri)) {
                $uri = "/";
        }
        $scheme = '';

        switch ($url['scheme']) {
            case 'https':
                $scheme = 'https://';
                $port = $port === null ? 443 : $port;
                break;
            default:
                $scheme = 'http://';
                $port = $port === null ? 80 : $port;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $scheme . $url['host'] . $uri);
        curl_setopt($ch, CURLOPT_PORT, $port);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->_config['UserAgent']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_HEADER, true); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($this->_config['ProxyHost'] != null && $this->_config['ProxyPort'] != -1)
        {
            curl_setopt($ch, CURLOPT_PROXY, $this->_config['ProxyHost'] . ':' . $this->_config['ProxyPort']);
        }

        $response = "";
        $response = curl_exec($ch);

        curl_close($ch);

        list($other, $responseBody) = explode("\r\n\r\n", $response, 2);
        $other = preg_split("/\r\n|\n|\r/", $other);

        $headers = array();
        foreach ($other as $value) {
          if (stripos($value,": ")>0)
          {
	          list ($k, $v) = explode (': ', $value);
	          if (array_key_exists($k, $headers)) {
	          $headers[$k] = $headers[$k] . "," . $v;
	          } else {
		          $headers[$k] = $v;
	          }
          }
        }
        require_once('MarketplaceWebServiceOrders/Model/ResponseHeaderMetadata.php');
        $responseHeaderMetadata = new MarketplaceWebServiceOrders_Model_ResponseHeaderMetadata(
          $headers['x-mws-request-id'],
          $headers['x-mws-response-context'],
          $headers['x-mws-timestamp']);

        list($protocol, $code, $text) = explode(' ', trim(array_shift($other)), 3);

        return array ('Status' => (int)$code, 'ResponseBody' => $responseBody, 'ResponseHeaderMetadata' => $responseHeaderMetadata);
    }

    /**
     * Exponential sleep on failed request
     * @param retries current retry
     * @throws MarketplaceWebServiceOrders_Exception if maximum number of retries has been reached
     */
    private function _pauseOnRetry($retries, $status)
    {
        if ($retries <= $this->_config['MaxErrorRetry']) {
            $delay = (int) (pow(4, $retries) * 100000) ;
            usleep($delay);
        } else {
            require_once ('MarketplaceWebServiceOrders/Exception.php');
            throw new MarketplaceWebServiceOrders_Exception (array ('Message' => "Maximum number of retry attempts reached :  $retries", 'StatusCode' => $status));
        }
    }

    /**
     * Add authentication related and version parameters
     */
    private function _addRequiredParameters(array $parameters)
    {
        $parameters['AWSAccessKeyId'] = $this->_awsAccessKeyId;
        $parameters['Timestamp'] = $this->_getFormattedTimestamp();
        $parameters['Version'] = self::SERVICE_VERSION;
        $parameters['SignatureVersion'] = $this->_config['SignatureVersion'];
        if ($parameters['SignatureVersion'] > 1) {
            $parameters['SignatureMethod'] = $this->_config['SignatureMethod'];
        }
        $parameters['Signature'] = $this->_signParameters($parameters, $this->_awsSecretAccessKey);

        return $parameters;
    }

    /**
     * Convert paremeters to Url encoded query string
     */
    private function _getParametersAsString(array $parameters)
    {
        $queryParameters = array();
        foreach ($parameters as $key => $value) {
            $queryParameters[] = $key . '=' . $this->_urlencode($value);
        }
        return implode('&', $queryParameters);
    }


    /**
     * Computes RFC 2104-compliant HMAC signature for request parameters
     * Implements AWS Signature, as per following spec:
     *
     * If Signature Version is 0, it signs concatenated Action and Timestamp
     *
     * If Signature Version is 1, it performs the following:
     *
     * Sorts all  parameters (including SignatureVersion and excluding Signature,
     * the value of which is being created), ignoring case.
     *
     * Iterate over the sorted list and append the parameter name (in original case)
     * and then its value. It will not URL-encode the parameter values before
     * constructing this string. There are no separators.
     *
     * If Signature Version is 2, string to sign is based on following:
     *
     *    1. The HTTP Request Method followed by an ASCII newline (%0A)
     *    2. The HTTP Host header in the form of lowercase host, followed by an ASCII newline.
     *    3. The URL encoded HTTP absolute path component of the URI
     *       (up to but not including the query string parameters);
     *       if this is empty use a forward '/'. This parameter is followed by an ASCII newline.
     *    4. The concatenation of all query string components (names and values)
     *       as UTF-8 characters which are URL encoded as per RFC 3986
     *       (hex characters MUST be uppercase), sorted using lexicographic byte ordering.
     *       Parameter names are separated from their values by the '=' character
     *       (ASCII character 61), even if the value is empty.
     *       Pairs of parameter and values are separated by the '&' character (ASCII code 38).
     *
     */
    private function _signParameters(array $parameters, $key) {
        $signatureVersion = $parameters['SignatureVersion'];
        $algorithm = "HmacSHA1";
        $stringToSign = null;
        if (2 === $signatureVersion) {
            $algorithm = $this->_config['SignatureMethod'];
            $parameters['SignatureMethod'] = $algorithm;
            $stringToSign = $this->_calculateStringToSignV2($parameters);
        } else {
            throw new Exception("Invalid Signature Version specified");
        }
        return $this->_sign($stringToSign, $key, $algorithm);
    }

    /**
     * Calculate String to Sign for SignatureVersion 2
     * @param array $parameters request parameters
     * @return String to Sign
     */
    private function _calculateStringToSignV2(array $parameters) {
        $data = 'POST';
        $data .= "\n";
        $endpoint = parse_url ($this->_config['ServiceURL']);
        $data .= $endpoint['host'];
        $data .= "\n";
        $uri = array_key_exists('path', $endpoint) ? $endpoint['path'] : null;
        if (!isset ($uri)) {
        	$uri = "/";
        }
		$uriencoded = implode("/", array_map(array($this, "_urlencode"), explode("/", $uri)));
        $data .= $uriencoded;
        $data .= "\n";
        uksort($parameters, 'strcmp');
        $data .= $this->_getParametersAsString($parameters);
        return $data;
    }

    private function _urlencode($value) {
		return str_replace('%7E', '~', rawurlencode($value));
    }


    /**
     * Computes RFC 2104-compliant HMAC signature.
     */
    private function _sign($data, $key, $algorithm)
    {
        if ($algorithm === 'HmacSHA1') {
            $hash = 'sha1';
        } else if ($algorithm === 'HmacSHA256') {
            $hash = 'sha256';
        } else {
            throw new Exception ("Non-supported signing method specified");
        }
        return base64_encode(
            hash_hmac($hash, $data, $key, true)
        );
    }


    /**
     * Formats date as ISO 8601 timestamp
     */
    private function _getFormattedTimestamp()
    {
        return gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
    }
    
    /**
     * Formats date as ISO 8601 timestamp
     */
    private function getFormattedTimestamp($dateTime)
    {
	    return $dateTime->format(DATE_ISO8601);
    }



                                                
    /**
     * Convert ListOrdersByNextTokenRequest to name value pairs
     */
    private function _convertListOrdersByNextToken($request) {
        
        $parameters = array();
        $parameters['Action'] = 'ListOrdersByNextToken';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetNextToken()) {
            $parameters['NextToken'] =  $request->getNextToken();
        }

        return $parameters;
    }
        
                                                
    /**
     * Convert ListOrderItemsByNextTokenRequest to name value pairs
     */
    private function _convertListOrderItemsByNextToken($request) {
        
        $parameters = array();
        $parameters['Action'] = 'ListOrderItemsByNextToken';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetNextToken()) {
            $parameters['NextToken'] =  $request->getNextToken();
        }

        return $parameters;
    }
        
                                                
    /**
     * Convert GetOrderRequest to name value pairs
     */
    private function _convertGetOrder($request) {
        
        $parameters = array();
        $parameters['Action'] = 'GetOrder';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetAmazonOrderId()) {
            $amazonOrderIdgetOrderRequest = $request->getAmazonOrderId();
            foreach  ($amazonOrderIdgetOrderRequest->getId() as $idamazonOrderIdIndex => $idamazonOrderId) {
                $parameters['AmazonOrderId' . '.' . 'Id' . '.'  . ($idamazonOrderIdIndex + 1)] =  $idamazonOrderId;
            }
        }

        return $parameters;
    }
        
                                                
    /**
     * Convert ListOrderItemsRequest to name value pairs
     */
    private function _convertListOrderItems($request) {
        
        $parameters = array();
        $parameters['Action'] = 'ListOrderItems';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetAmazonOrderId()) {
            $parameters['AmazonOrderId'] =  $request->getAmazonOrderId();
        }

        return $parameters;
    }
        
                                                
    /**
     * Convert ListOrdersRequest to name value pairs
     */
    private function _convertListOrders($request) {
        
        $parameters = array();
        $parameters['Action'] = 'ListOrders';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetCreatedAfter()) {
            $parameters['CreatedAfter'] =  $this->getFormattedTimestamp($request->getCreatedAfter());
        }
        if ($request->isSetCreatedBefore()) {
            $parameters['CreatedBefore'] =  $this->getFormattedTimestamp($request->getCreatedBefore());
        }
        if ($request->isSetLastUpdatedAfter()) {
            $parameters['LastUpdatedAfter'] =  $this->getFormattedTimestamp($request->getLastUpdatedAfter());
        }
        if ($request->isSetLastUpdatedBefore()) {
            $parameters['LastUpdatedBefore'] =  $this->getFormattedTimestamp($request->getLastUpdatedBefore());
        }
        if ($request->isSetOrderStatus()) {
            $orderStatuslistOrdersRequest = $request->getOrderStatus();
            foreach  ($orderStatuslistOrdersRequest->getStatus() as $statusorderStatusIndex => $statusorderStatus) {
                $parameters['OrderStatus' . '.' . 'Status' . '.'  . ($statusorderStatusIndex + 1)] =  $statusorderStatus;
            }
        }
        if ($request->isSetMarketplaceId()) {
            $marketplaceIdlistOrdersRequest = $request->getMarketplaceId();
            foreach  ($marketplaceIdlistOrdersRequest->getId() as $idmarketplaceIdIndex => $idmarketplaceId) {
                $parameters['MarketplaceId' . '.' . 'Id' . '.'  . ($idmarketplaceIdIndex + 1)] =  $idmarketplaceId;
            }
        }
        if ($request->isSetFulfillmentChannel()) {
            $fulfillmentChannellistOrdersRequest = $request->getFulfillmentChannel();
            foreach  ($fulfillmentChannellistOrdersRequest->getChannel() as $channelfulfillmentChannelIndex => $channelfulfillmentChannel) {
                $parameters['FulfillmentChannel' . '.' . 'Channel' . '.'  . ($channelfulfillmentChannelIndex + 1)] =  $channelfulfillmentChannel;
            }
        }
        if ($request->isSetPaymentMethod()) {
            $paymentMethodlistOrdersRequest = $request->getPaymentMethod();
            foreach  ($paymentMethodlistOrdersRequest->getMethod() as $methodpaymentMethodIndex => $methodpaymentMethod) {
                $parameters['PaymentMethod' . '.' . 'Method' . '.'  . ($methodpaymentMethodIndex + 1)] =  $methodpaymentMethod;
            }
        }
        if ($request->isSetBuyerEmail()) {
            $parameters['BuyerEmail'] =  $request->getBuyerEmail();
        }
        if ($request->isSetSellerOrderId()) {
            $parameters['SellerOrderId'] =  $request->getSellerOrderId();
        }
        if ($request->isSetMaxResultsPerPage()) {
            $parameters['MaxResultsPerPage'] =  $request->getMaxResultsPerPage();
        }
        if ($request->isSetTFMShipmentStatus()) {
            $TFMShipmentStatuslistOrdersRequest = $request->getTFMShipmentStatus();
            foreach  ($TFMShipmentStatuslistOrdersRequest->getStatus() as $statusTFMShipmentStatusIndex => $statusTFMShipmentStatus) {
                $parameters['TFMShipmentStatus' . '.' . 'Status' . '.'  . ($statusTFMShipmentStatusIndex + 1)] =  $statusTFMShipmentStatus;
            }
        }

        return $parameters;
    }
        
                                                
    /**
     * Convert GetServiceStatusRequest to name value pairs
     */
    private function _convertGetServiceStatus($request) {
        
        $parameters = array();
        $parameters['Action'] = 'GetServiceStatus';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }

        return $parameters;
    }
        
                                                                                                                                                                                                                
}
