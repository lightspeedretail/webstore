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
 * MarketplaceWebServiceOrders_Model_OrderList
 * 
 * Properties:
 * <ul>
 * 
 * <li>Order: MarketplaceWebServiceOrders_Model_Order</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_OrderList extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_OrderList
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>Order: MarketplaceWebServiceOrders_Model_Order</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'Order' => array('FieldValue' => array(), 'FieldType' => array('MarketplaceWebServiceOrders_Model_Order')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the Order.
     * 
     * @return array of Order Order
     */
    public function getOrder() 
    {
        return $this->_fields['Order']['FieldValue'];
    }

    /**
     * Sets the value of the Order.
     * 
     * @param mixed Order or an array of Order Order
     * @return this instance
     */
    public function setOrder($order) 
    {
        if (!$this->_isNumericArray($order)) {
            $order =  array ($order);    
        }
        $this->_fields['Order']['FieldValue'] = $order;
        return $this;
    }


    /**
     * Sets single or multiple values of Order list via variable number of arguments. 
     * For example, to set the list with two elements, simply pass two values as arguments to this function
     * <code>withOrder($order1, $order2)</code>
     * 
     * @param Order  $orderArgs one or more Order
     * @return MarketplaceWebServiceOrders_Model_OrderList  instance
     */
    public function withOrder($orderArgs)
    {
        foreach (func_get_args() as $order) {
            $this->_fields['Order']['FieldValue'][] = $order;
        }
        return $this;
    }   



    /**
     * Checks if Order list is non-empty
     * 
     * @return bool true if Order list is non-empty
     */
    public function isSetOrder()
    {
        return count ($this->_fields['Order']['FieldValue']) > 0;
    }




}