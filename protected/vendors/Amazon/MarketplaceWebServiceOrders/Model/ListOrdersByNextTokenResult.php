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
 * MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResult
 * 
 * Properties:
 * <ul>
 * 
 * <li>NextToken: string</li>
 * <li>CreatedBefore: string</li>
 * <li>LastUpdatedBefore: string</li>
 * <li>Orders: MarketplaceWebServiceOrders_Model_OrderList</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResult extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResult
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>NextToken: string</li>
     * <li>CreatedBefore: string</li>
     * <li>LastUpdatedBefore: string</li>
     * <li>Orders: MarketplaceWebServiceOrders_Model_OrderList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'NextToken' => array('FieldValue' => null, 'FieldType' => 'string'),
        'CreatedBefore' => array('FieldValue' => null, 'FieldType' => 'string'),
        'LastUpdatedBefore' => array('FieldValue' => null, 'FieldType' => 'string'),

        'Orders' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_OrderList'),

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
     * @return MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResult instance
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
     * Gets the value of the CreatedBefore property.
     * 
     * @return string CreatedBefore
     */
    public function getCreatedBefore() 
    {
        return $this->_fields['CreatedBefore']['FieldValue'];
    }

    /**
     * Sets the value of the CreatedBefore property.
     * 
     * @param string CreatedBefore
     * @return this instance
     */
    public function setCreatedBefore($value) 
    {
        $this->_fields['CreatedBefore']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the CreatedBefore and returns this instance
     * 
     * @param string $value CreatedBefore
     * @return MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResult instance
     */
    public function withCreatedBefore($value)
    {
        $this->setCreatedBefore($value);
        return $this;
    }


    /**
     * Checks if CreatedBefore is set
     * 
     * @return bool true if CreatedBefore  is set
     */
    public function isSetCreatedBefore()
    {
        return !is_null($this->_fields['CreatedBefore']['FieldValue']);
    }

    /**
     * Gets the value of the LastUpdatedBefore property.
     * 
     * @return string LastUpdatedBefore
     */
    public function getLastUpdatedBefore() 
    {
        return $this->_fields['LastUpdatedBefore']['FieldValue'];
    }

    /**
     * Sets the value of the LastUpdatedBefore property.
     * 
     * @param string LastUpdatedBefore
     * @return this instance
     */
    public function setLastUpdatedBefore($value) 
    {
        $this->_fields['LastUpdatedBefore']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the LastUpdatedBefore and returns this instance
     * 
     * @param string $value LastUpdatedBefore
     * @return MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResult instance
     */
    public function withLastUpdatedBefore($value)
    {
        $this->setLastUpdatedBefore($value);
        return $this;
    }


    /**
     * Checks if LastUpdatedBefore is set
     * 
     * @return bool true if LastUpdatedBefore  is set
     */
    public function isSetLastUpdatedBefore()
    {
        return !is_null($this->_fields['LastUpdatedBefore']['FieldValue']);
    }

    /**
     * Gets the value of the Orders.
     * 
     * @return OrderList Orders
     */
    public function getOrders() 
    {
        return $this->_fields['Orders']['FieldValue'];
    }

    /**
     * Sets the value of the Orders.
     * 
     * @param OrderList Orders
     * @return void
     */
    public function setOrders($value) 
    {
        $this->_fields['Orders']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the Orders  and returns this instance
     * 
     * @param OrderList $value Orders
     * @return MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResult instance
     */
    public function withOrders($value)
    {
        $this->setOrders($value);
        return $this;
    }


    /**
     * Checks if Orders  is set
     * 
     * @return bool true if Orders property is set
     */
    public function isSetOrders()
    {
        return !is_null($this->_fields['Orders']['FieldValue']);

    }




}