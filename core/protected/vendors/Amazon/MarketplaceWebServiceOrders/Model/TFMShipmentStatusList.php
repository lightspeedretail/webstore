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
 * MarketplaceWebServiceOrders_Model_TFMShipmentStatusList
 * 
 * Properties:
 * <ul>
 * 
 * <li>Status: string</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_TFMShipmentStatusList extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_TFMShipmentStatusList
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>Status: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'Status' => array('FieldValue' => array(), 'FieldType' => array('string')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the Status .
     * 
     * @return array of string Status
     */
    public function getStatus() 
    {
        return $this->_fields['Status']['FieldValue'];
    }

    /**
     * Sets the value of the Status.
     * 
     * @param string or an array of string Status
     * @return this instance
     */
    public function setStatus($status) 
    {
        if (!$this->_isNumericArray($status)) {
            $status =  array ($status);    
        }
        $this->_fields['Status']['FieldValue'] = $status;
        return $this;
    }
  

    /**
     * Sets single or multiple values of Status list via variable number of arguments. 
     * For example, to set the list with two elements, simply pass two values as arguments to this function
     * <code>withStatus($status1, $status2)</code>
     * 
     * @param string  $stringArgs one or more Status
     * @return MarketplaceWebServiceOrders_Model_TFMShipmentStatusList  instance
     */
    public function withStatus($stringArgs)
    {
        foreach (func_get_args() as $status) {
            $this->_fields['Status']['FieldValue'][] = $status;
        }
        return $this;
    }  
      

    /**
     * Checks if Status list is non-empty
     * 
     * @return bool true if Status list is non-empty
     */
    public function isSetStatus()
    {
        return count ($this->_fields['Status']['FieldValue']) > 0;
    }




}