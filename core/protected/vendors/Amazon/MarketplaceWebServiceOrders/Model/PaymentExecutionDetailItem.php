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
 * MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItem
 * 
 * Properties:
 * <ul>
 * 
 * <li>Payment: MarketplaceWebServiceOrders_Model_Money</li>
 * <li>PaymentMethod: string</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItem extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItem
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>Payment: MarketplaceWebServiceOrders_Model_Money</li>
     * <li>PaymentMethod: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (

        'Payment' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_Money'),

        'PaymentMethod' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the Payment.
     * 
     * @return Money Payment
     */
    public function getPayment() 
    {
        return $this->_fields['Payment']['FieldValue'];
    }

    /**
     * Sets the value of the Payment.
     * 
     * @param Money Payment
     * @return void
     */
    public function setPayment($value) 
    {
        $this->_fields['Payment']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the Payment  and returns this instance
     * 
     * @param Money $value Payment
     * @return MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItem instance
     */
    public function withPayment($value)
    {
        $this->setPayment($value);
        return $this;
    }


    /**
     * Checks if Payment  is set
     * 
     * @return bool true if Payment property is set
     */
    public function isSetPayment()
    {
        return !is_null($this->_fields['Payment']['FieldValue']);

    }

    /**
     * Gets the value of the PaymentMethod property.
     * 
     * @return string PaymentMethod
     */
    public function getPaymentMethod() 
    {
        return $this->_fields['PaymentMethod']['FieldValue'];
    }

    /**
     * Sets the value of the PaymentMethod property.
     * 
     * @param string PaymentMethod
     * @return this instance
     */
    public function setPaymentMethod($value) 
    {
        $this->_fields['PaymentMethod']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the PaymentMethod and returns this instance
     * 
     * @param string $value PaymentMethod
     * @return MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItem instance
     */
    public function withPaymentMethod($value)
    {
        $this->setPaymentMethod($value);
        return $this;
    }


    /**
     * Checks if PaymentMethod is set
     * 
     * @return bool true if PaymentMethod  is set
     */
    public function isSetPaymentMethod()
    {
        return !is_null($this->_fields['PaymentMethod']['FieldValue']);
    }




}