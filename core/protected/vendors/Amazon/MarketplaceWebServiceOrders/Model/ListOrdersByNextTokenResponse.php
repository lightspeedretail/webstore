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
 *  @see MarketplaceWebServiceOrders_Model
 */
require_once ('MarketplaceWebServiceOrders/Model.php');  

    

/**
 * MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse
 * 
 * Properties:
 * <ul>
 * 
 * <li>ListOrdersByNextTokenResult: MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResult</li>
 * <li>ResponseMetadata: MarketplaceWebServiceOrders_Model_ResponseMetadata</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>ListOrdersByNextTokenResult: MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResult</li>
     * <li>ResponseMetadata: MarketplaceWebServiceOrders_Model_ResponseMetadata</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (

        'ListOrdersByNextTokenResult' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResult'),


        'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_ResponseMetadata'),

        );
        parent::__construct($data);
    }

       
    /**
     * Construct MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse from XML string
     * 
     * @param string $xml XML string to construct from
     * @return MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse 
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'https://mws.amazonservices.com/Orders/2011-01-01');
        $response = $xpath->query('//a:ListOrdersByNextTokenResponse');
        if ($response->length == 1) {
            return new MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse(($response->item(0))); 
        } else {
            throw new Exception ("Unable to construct MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse from provided XML. 
                                  Make sure that ListOrdersByNextTokenResponse is a root element");
        }
          
    }
    
    /**
     * Gets the value of the ListOrdersByNextTokenResult.
     * 
     * @return ListOrdersByNextTokenResult ListOrdersByNextTokenResult
     */
    public function getListOrdersByNextTokenResult() 
    {
        return $this->_fields['ListOrdersByNextTokenResult']['FieldValue'];
    }

    /**
     * Sets the value of the ListOrdersByNextTokenResult.
     * 
     * @param ListOrdersByNextTokenResult ListOrdersByNextTokenResult
     * @return void
     */
    public function setListOrdersByNextTokenResult($value) 
    {
        $this->_fields['ListOrdersByNextTokenResult']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ListOrdersByNextTokenResult  and returns this instance
     * 
     * @param ListOrdersByNextTokenResult $value ListOrdersByNextTokenResult
     * @return MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse instance
     */
    public function withListOrdersByNextTokenResult($value)
    {
        $this->setListOrdersByNextTokenResult($value);
        return $this;
    }


    /**
     * Checks if ListOrdersByNextTokenResult  is set
     * 
     * @return bool true if ListOrdersByNextTokenResult property is set
     */
    public function isSetListOrdersByNextTokenResult()
    {
        return !is_null($this->_fields['ListOrdersByNextTokenResult']['FieldValue']);

    }

    /**
     * Gets the value of the ResponseMetadata.
     * 
     * @return ResponseMetadata ResponseMetadata
     */
    public function getResponseMetadata() 
    {
        return $this->_fields['ResponseMetadata']['FieldValue'];
    }

    /**
     * Sets the value of the ResponseMetadata.
     * 
     * @param ResponseMetadata ResponseMetadata
     * @return void
     */
    public function setResponseMetadata($value) 
    {
        $this->_fields['ResponseMetadata']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ResponseMetadata  and returns this instance
     * 
     * @param ResponseMetadata $value ResponseMetadata
     * @return MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse instance
     */
    public function withResponseMetadata($value)
    {
        $this->setResponseMetadata($value);
        return $this;
    }


    /**
     * Checks if ResponseMetadata  is set
     * 
     * @return bool true if ResponseMetadata property is set
     */
    public function isSetResponseMetadata()
    {
        return !is_null($this->_fields['ResponseMetadata']['FieldValue']);

    }



    /**
     * XML Representation for this object
     * 
     * @return string XML for this object
     */
    public function toXML() 
    {
        $xml = "";
        $xml .= "<ListOrdersByNextTokenResponse xmlns=\"https://mws.amazonservices.com/Orders/2011-01-01\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</ListOrdersByNextTokenResponse>";
        return $xml;
    }

    private $_responseHeaderMetadata = null;

    public function getResponseHeaderMetadata() {
        return $this->_responseHeaderMetadata;
    }

    public function setResponseHeaderMetadata($responseHeaderMetadata) {
        return $this->_responseHeaderMetadata = $responseHeaderMetadata;
    }

}