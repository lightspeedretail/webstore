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
 * MarketplaceWebServiceOrders_Model_ListOrderItemsResult
 * 
 * Properties:
 * <ul>
 * 
 * <li>NextToken: string</li>
 * <li>AmazonOrderId: string</li>
 * <li>OrderItems: MarketplaceWebServiceOrders_Model_OrderItemList</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_ListOrderItemsResult extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_ListOrderItemsResult
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>NextToken: string</li>
     * <li>AmazonOrderId: string</li>
     * <li>OrderItems: MarketplaceWebServiceOrders_Model_OrderItemList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'NextToken' => array('FieldValue' => null, 'FieldType' => 'string'),
        'AmazonOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),

        'OrderItems' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_OrderItemList'),

        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the NextToken property.
     * 
     * @return string NextToken
     */
    public function getNextToken() 
    {
        return $this->_fields['NextToken']['FieldValue'];
    }

    /**
     * Sets the value of the NextToken property.
     * 
     * @param string NextToken
     * @return this instance
     */
    public function setNextToken($value) 
    {
        $this->_fields['NextToken']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the NextToken and returns this instance
     * 
     * @param string $value NextToken
     * @return MarketplaceWebServiceOrders_Model_ListOrderItemsResult instance
     */
    public function withNextToken($value)
    {
        $this->setNextToken($value);
        return $this;
    }


    /**
     * Checks if NextToken is set
     * 
     * @return bool true if NextToken  is set
     */
    public function isSetNextToken()
    {
        return !is_null($this->_fields['NextToken']['FieldValue']);
    }

    /**
     * Gets the value of the AmazonOrderId property.
     * 
     * @return string AmazonOrderId
     */
    public function getAmazonOrderId() 
    {
        return $this->_fields['AmazonOrderId']['FieldValue'];
    }

    /**
     * Sets the value of the AmazonOrderId property.
     * 
     * @param string AmazonOrderId
     * @return this instance
     */
    public function setAmazonOrderId($value) 
    {
        $this->_fields['AmazonOrderId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the AmazonOrderId and returns this instance
     * 
     * @param string $value AmazonOrderId
     * @return MarketplaceWebServiceOrders_Model_ListOrderItemsResult instance
     */
    public function withAmazonOrderId($value)
    {
        $this->setAmazonOrderId($value);
        return $this;
    }


    /**
     * Checks if AmazonOrderId is set
     * 
     * @return bool true if AmazonOrderId  is set
     */
    public function isSetAmazonOrderId()
    {
        return !is_null($this->_fields['AmazonOrderId']['FieldValue']);
    }

    /**
     * Gets the value of the OrderItems.
     * 
     * @return OrderItemList OrderItems
     */
    public function getOrderItems() 
    {
        return $this->_fields['OrderItems']['FieldValue'];
    }

    /**
     * Sets the value of the OrderItems.
     * 
     * @param OrderItemList OrderItems
     * @return void
     */
    public function setOrderItems($value) 
    {
        $this->_fields['OrderItems']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the OrderItems  and returns this instance
     * 
     * @param OrderItemList $value OrderItems
     * @return MarketplaceWebServiceOrders_Model_ListOrderItemsResult instance
     */
    public function withOrderItems($value)
    {
        $this->setOrderItems($value);
        return $this;
    }


    /**
     * Checks if OrderItems  is set
     * 
     * @return bool true if OrderItems property is set
     */
    public function isSetOrderItems()
    {
        return !is_null($this->_fields['OrderItems']['FieldValue']);

    }




}