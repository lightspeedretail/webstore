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
 * MarketplaceWebServiceOrders_Model_PaymentMethodList
 * 
 * Properties:
 * <ul>
 * 
 * <li>Method: PaymentMethodEnum</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_PaymentMethodList extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_PaymentMethodList
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>Method: PaymentMethodEnum</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'Method' => array('FieldValue' => array(), 'FieldType' => array('PaymentMethodEnum')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the Method .
     * 
     * @return array of PaymentMethodEnum Method
     */
    public function getMethod() 
    {
        return $this->_fields['Method']['FieldValue'];
    }

    /**
     * Sets the value of the Method.
     * 
     * @param PaymentMethodEnum or an array of PaymentMethodEnum Method
     * @return this instance
     */
    public function setMethod($method) 
    {
        if (!$this->_isNumericArray($method)) {
            $method =  array ($method);    
        }
        $this->_fields['Method']['FieldValue'] = $method;
        return $this;
    }
  

    /**
     * Sets single or multiple values of Method list via variable number of arguments. 
     * For example, to set the list with two elements, simply pass two values as arguments to this function
     * <code>withMethod($method1, $method2)</code>
     * 
     * @param PaymentMethodEnum  $paymentMethodEnumArgs one or more Method
     * @return MarketplaceWebServiceOrders_Model_PaymentMethodList  instance
     */
    public function withMethod($paymentMethodEnumArgs)
    {
        foreach (func_get_args() as $method) {
            $this->_fields['Method']['FieldValue'][] = $method;
        }
        return $this;
    }  
      

    /**
     * Checks if Method list is non-empty
     * 
     * @return bool true if Method list is non-empty
     */
    public function isSetMethod()
    {
        return count ($this->_fields['Method']['FieldValue']) > 0;
    }




}