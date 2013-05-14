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
 * MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItemList
 * 
 * Properties:
 * <ul>
 * 
 * <li>PaymentExecutionDetailItem: MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItem</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItemList extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItemList
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>PaymentExecutionDetailItem: MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItem</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'PaymentExecutionDetailItem' => array('FieldValue' => array(), 'FieldType' => array('MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItem')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the PaymentExecutionDetailItem.
     * 
     * @return array of PaymentExecutionDetailItem PaymentExecutionDetailItem
     */
    public function getPaymentExecutionDetailItem() 
    {
        return $this->_fields['PaymentExecutionDetailItem']['FieldValue'];
    }

    /**
     * Sets the value of the PaymentExecutionDetailItem.
     * 
     * @param mixed PaymentExecutionDetailItem or an array of PaymentExecutionDetailItem PaymentExecutionDetailItem
     * @return this instance
     */
    public function setPaymentExecutionDetailItem($paymentExecutionDetailItem) 
    {
        if (!$this->_isNumericArray($paymentExecutionDetailItem)) {
            $paymentExecutionDetailItem =  array ($paymentExecutionDetailItem);    
        }
        $this->_fields['PaymentExecutionDetailItem']['FieldValue'] = $paymentExecutionDetailItem;
        return $this;
    }


    /**
     * Sets single or multiple values of PaymentExecutionDetailItem list via variable number of arguments. 
     * For example, to set the list with two elements, simply pass two values as arguments to this function
     * <code>withPaymentExecutionDetailItem($paymentExecutionDetailItem1, $paymentExecutionDetailItem2)</code>
     * 
     * @param PaymentExecutionDetailItem  $paymentExecutionDetailItemArgs one or more PaymentExecutionDetailItem
     * @return MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItemList  instance
     */
    public function withPaymentExecutionDetailItem($paymentExecutionDetailItemArgs)
    {
        foreach (func_get_args() as $paymentExecutionDetailItem) {
            $this->_fields['PaymentExecutionDetailItem']['FieldValue'][] = $paymentExecutionDetailItem;
        }
        return $this;
    }   



    /**
     * Checks if PaymentExecutionDetailItem list is non-empty
     * 
     * @return bool true if PaymentExecutionDetailItem list is non-empty
     */
    public function isSetPaymentExecutionDetailItem()
    {
        return count ($this->_fields['PaymentExecutionDetailItem']['FieldValue']) > 0;
    }




}