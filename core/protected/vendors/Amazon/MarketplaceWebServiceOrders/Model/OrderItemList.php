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
 * MarketplaceWebServiceOrders_Model_OrderItemList
 * 
 * Properties:
 * <ul>
 * 
 * <li>OrderItem: MarketplaceWebServiceOrders_Model_OrderItem</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_OrderItemList extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_OrderItemList
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>OrderItem: MarketplaceWebServiceOrders_Model_OrderItem</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'OrderItem' => array('FieldValue' => array(), 'FieldType' => array('MarketplaceWebServiceOrders_Model_OrderItem')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the OrderItem.
     * 
     * @return array of OrderItem OrderItem
     */
    public function getOrderItem() 
    {
        return $this->_fields['OrderItem']['FieldValue'];
    }

    /**
     * Sets the value of the OrderItem.
     * 
     * @param mixed OrderItem or an array of OrderItem OrderItem
     * @return this instance
     */
    public function setOrderItem($orderItem) 
    {
        if (!$this->_isNumericArray($orderItem)) {
            $orderItem =  array ($orderItem);    
        }
        $this->_fields['OrderItem']['FieldValue'] = $orderItem;
        return $this;
    }


    /**
     * Sets single or multiple values of OrderItem list via variable number of arguments. 
     * For example, to set the list with two elements, simply pass two values as arguments to this function
     * <code>withOrderItem($orderItem1, $orderItem2)</code>
     * 
     * @param OrderItem  $orderItemArgs one or more OrderItem
     * @return MarketplaceWebServiceOrders_Model_OrderItemList  instance
     */
    public function withOrderItem($orderItemArgs)
    {
        foreach (func_get_args() as $orderItem) {
            $this->_fields['OrderItem']['FieldValue'][] = $orderItem;
        }
        return $this;
    }   



    /**
     * Checks if OrderItem list is non-empty
     * 
     * @return bool true if OrderItem list is non-empty
     */
    public function isSetOrderItem()
    {
        return count ($this->_fields['OrderItem']['FieldValue']) > 0;
    }




}