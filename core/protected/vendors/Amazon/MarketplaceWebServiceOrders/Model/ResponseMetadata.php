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
 * MarketplaceWebServiceOrders_Model_ResponseMetadata
 * 
 * Properties:
 * <ul>
 * 
 * <li>RequestId: string</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_ResponseMetadata extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_ResponseMetadata
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>RequestId: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'RequestId' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the RequestId property.
     * 
     * @return string RequestId
     */
    public function getRequestId() 
    {
        return $this->_fields['RequestId']['FieldValue'];
    }

    /**
     * Sets the value of the RequestId property.
     * 
     * @param string RequestId
     * @return this instance
     */
    public function setRequestId($value) 
    {
        $this->_fields['RequestId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the RequestId and returns this instance
     * 
     * @param string $value RequestId
     * @return MarketplaceWebServiceOrders_Model_ResponseMetadata instance
     */
    public function withRequestId($value)
    {
        $this->setRequestId($value);
        return $this;
    }


    /**
     * Checks if RequestId is set
     * 
     * @return bool true if RequestId  is set
     */
    public function isSetRequestId()
    {
        return !is_null($this->_fields['RequestId']['FieldValue']);
    }




}