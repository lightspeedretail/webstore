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
 * MarketplaceWebServiceOrders_Model_GetOrderRequest
 * 
 * Properties:
 * <ul>
 * 
 * <li>SellerId: string</li>
 * <li>AmazonOrderId: MarketplaceWebServiceOrders_Model_OrderIdList</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_GetOrderRequest extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_GetOrderRequest
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>SellerId: string</li>
     * <li>AmazonOrderId: MarketplaceWebServiceOrders_Model_OrderIdList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),

        'AmazonOrderId' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_OrderIdList'),

        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the SellerId property.
     * 
     * @return string SellerId
     */
    public function getSellerId() 
    {
        return $this->_fields['SellerId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerId property.
     * 
     * @param string SellerId
     * @return this instance
     */
    public function setSellerId($value) 
    {
        $this->_fields['SellerId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerId and returns this instance
     * 
     * @param string $value SellerId
     * @return MarketplaceWebServiceOrders_Model_GetOrderRequest instance
     */
    public function withSellerId($value)
    {
        $this->setSellerId($value);
        return $this;
    }


    /**
     * Checks if SellerId is set
     * 
     * @return bool true if SellerId  is set
     */
    public function isSetSellerId()
    {
        return !is_null($this->_fields['SellerId']['FieldValue']);
    }

    /**
     * Gets the value of the AmazonOrderId.
     * 
     * @return OrderIdList AmazonOrderId
     */
    public function getAmazonOrderId() 
    {
        return $this->_fields['AmazonOrderId']['FieldValue'];
    }

    /**
     * Sets the value of the AmazonOrderId.
     * 
     * @param OrderIdList AmazonOrderId
     * @return void
     */
    public function setAmazonOrderId($value) 
    {
        $this->_fields['AmazonOrderId']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the AmazonOrderId  and returns this instance
     * 
     * @param OrderIdList $value AmazonOrderId
     * @return MarketplaceWebServiceOrders_Model_GetOrderRequest instance
     */
    public function withAmazonOrderId($value)
    {
        $this->setAmazonOrderId($value);
        return $this;
    }


    /**
     * Checks if AmazonOrderId  is set
     * 
     * @return bool true if AmazonOrderId property is set
     */
    public function isSetAmazonOrderId()
    {
        return !is_null($this->_fields['AmazonOrderId']['FieldValue']);

    }




}