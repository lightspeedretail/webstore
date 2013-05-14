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
 * MarketplaceWebServiceOrders_Model_ListOrderItemsResponse
 * 
 * Properties:
 * <ul>
 * 
 * <li>ListOrderItemsResult: MarketplaceWebServiceOrders_Model_ListOrderItemsResult</li>
 * <li>ResponseMetadata: MarketplaceWebServiceOrders_Model_ResponseMetadata</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_ListOrderItemsResponse extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_ListOrderItemsResponse
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>ListOrderItemsResult: MarketplaceWebServiceOrders_Model_ListOrderItemsResult</li>
     * <li>ResponseMetadata: MarketplaceWebServiceOrders_Model_ResponseMetadata</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (

        'ListOrderItemsResult' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_ListOrderItemsResult'),


        'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_ResponseMetadata'),

        );
        parent::__construct($data);
    }

       
    /**
     * Construct MarketplaceWebServiceOrders_Model_ListOrderItemsResponse from XML string
     * 
     * @param string $xml XML string to construct from
     * @return MarketplaceWebServiceOrders_Model_ListOrderItemsResponse 
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'https://mws.amazonservices.com/Orders/2011-01-01');
        $response = $xpath->query('//a:ListOrderItemsResponse');
        if ($response->length == 1) {
            return new MarketplaceWebServiceOrders_Model_ListOrderItemsResponse(($response->item(0))); 
        } else {
            throw new Exception ("Unable to construct MarketplaceWebServiceOrders_Model_ListOrderItemsResponse from provided XML. 
                                  Make sure that ListOrderItemsResponse is a root element");
        }
          
    }
    
    /**
     * Gets the value of the ListOrderItemsResult.
     * 
     * @return ListOrderItemsResult ListOrderItemsResult
     */
    public function getListOrderItemsResult() 
    {
        return $this->_fields['ListOrderItemsResult']['FieldValue'];
    }

    /**
     * Sets the value of the ListOrderItemsResult.
     * 
     * @param ListOrderItemsResult ListOrderItemsResult
     * @return void
     */
    public function setListOrderItemsResult($value) 
    {
        $this->_fields['ListOrderItemsResult']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ListOrderItemsResult  and returns this instance
     * 
     * @param ListOrderItemsResult $value ListOrderItemsResult
     * @return MarketplaceWebServiceOrders_Model_ListOrderItemsResponse instance
     */
    public function withListOrderItemsResult($value)
    {
        $this->setListOrderItemsResult($value);
        return $this;
    }


    /**
     * Checks if ListOrderItemsResult  is set
     * 
     * @return bool true if ListOrderItemsResult property is set
     */
    public function isSetListOrderItemsResult()
    {
        return !is_null($this->_fields['ListOrderItemsResult']['FieldValue']);

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
     * @return MarketplaceWebServiceOrders_Model_ListOrderItemsResponse instance
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
        $xml .= "<ListOrderItemsResponse xmlns=\"https://mws.amazonservices.com/Orders/2011-01-01\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</ListOrderItemsResponse>";
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