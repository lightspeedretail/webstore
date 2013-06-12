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
 * MarketplaceWebServiceOrders_Model_GetOrderResult
 * 
 * Properties:
 * <ul>
 * 
 * <li>Orders: MarketplaceWebServiceOrders_Model_OrderList</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_GetOrderResult extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_GetOrderResult
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>Orders: MarketplaceWebServiceOrders_Model_OrderList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (

        'Orders' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_OrderList'),

        );
        parent::__construct($data);
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
     * @return MarketplaceWebServiceOrders_Model_GetOrderResult instance
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