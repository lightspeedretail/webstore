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
 * MarketplaceWebServiceOrders_Model_InvoiceData
 * 
 * Properties:
 * <ul>
 * 
 * <li>InvoiceRequirement: string</li>
 * <li>BuyerSelectedInvoiceCategory: string</li>
 * <li>InvoiceTitle: string</li>
 * <li>InvoiceInformation: string</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_InvoiceData extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_InvoiceData
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>InvoiceRequirement: string</li>
     * <li>BuyerSelectedInvoiceCategory: string</li>
     * <li>InvoiceTitle: string</li>
     * <li>InvoiceInformation: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'InvoiceRequirement' => array('FieldValue' => null, 'FieldType' => 'string'),
        'BuyerSelectedInvoiceCategory' => array('FieldValue' => null, 'FieldType' => 'string'),
        'InvoiceTitle' => array('FieldValue' => null, 'FieldType' => 'string'),
        'InvoiceInformation' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the InvoiceRequirement property.
     * 
     * @return string InvoiceRequirement
     */
    public function getInvoiceRequirement() 
    {
        return $this->_fields['InvoiceRequirement']['FieldValue'];
    }

    /**
     * Sets the value of the InvoiceRequirement property.
     * 
     * @param string InvoiceRequirement
     * @return this instance
     */
    public function setInvoiceRequirement($value) 
    {
        $this->_fields['InvoiceRequirement']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the InvoiceRequirement and returns this instance
     * 
     * @param string $value InvoiceRequirement
     * @return MarketplaceWebServiceOrders_Model_InvoiceData instance
     */
    public function withInvoiceRequirement($value)
    {
        $this->setInvoiceRequirement($value);
        return $this;
    }


    /**
     * Checks if InvoiceRequirement is set
     * 
     * @return bool true if InvoiceRequirement  is set
     */
    public function isSetInvoiceRequirement()
    {
        return !is_null($this->_fields['InvoiceRequirement']['FieldValue']);
    }

    /**
     * Gets the value of the BuyerSelectedInvoiceCategory property.
     * 
     * @return string BuyerSelectedInvoiceCategory
     */
    public function getBuyerSelectedInvoiceCategory() 
    {
        return $this->_fields['BuyerSelectedInvoiceCategory']['FieldValue'];
    }

    /**
     * Sets the value of the BuyerSelectedInvoiceCategory property.
     * 
     * @param string BuyerSelectedInvoiceCategory
     * @return this instance
     */
    public function setBuyerSelectedInvoiceCategory($value) 
    {
        $this->_fields['BuyerSelectedInvoiceCategory']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the BuyerSelectedInvoiceCategory and returns this instance
     * 
     * @param string $value BuyerSelectedInvoiceCategory
     * @return MarketplaceWebServiceOrders_Model_InvoiceData instance
     */
    public function withBuyerSelectedInvoiceCategory($value)
    {
        $this->setBuyerSelectedInvoiceCategory($value);
        return $this;
    }


    /**
     * Checks if BuyerSelectedInvoiceCategory is set
     * 
     * @return bool true if BuyerSelectedInvoiceCategory  is set
     */
    public function isSetBuyerSelectedInvoiceCategory()
    {
        return !is_null($this->_fields['BuyerSelectedInvoiceCategory']['FieldValue']);
    }

    /**
     * Gets the value of the InvoiceTitle property.
     * 
     * @return string InvoiceTitle
     */
    public function getInvoiceTitle() 
    {
        return $this->_fields['InvoiceTitle']['FieldValue'];
    }

    /**
     * Sets the value of the InvoiceTitle property.
     * 
     * @param string InvoiceTitle
     * @return this instance
     */
    public function setInvoiceTitle($value) 
    {
        $this->_fields['InvoiceTitle']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the InvoiceTitle and returns this instance
     * 
     * @param string $value InvoiceTitle
     * @return MarketplaceWebServiceOrders_Model_InvoiceData instance
     */
    public function withInvoiceTitle($value)
    {
        $this->setInvoiceTitle($value);
        return $this;
    }


    /**
     * Checks if InvoiceTitle is set
     * 
     * @return bool true if InvoiceTitle  is set
     */
    public function isSetInvoiceTitle()
    {
        return !is_null($this->_fields['InvoiceTitle']['FieldValue']);
    }

    /**
     * Gets the value of the InvoiceInformation property.
     * 
     * @return string InvoiceInformation
     */
    public function getInvoiceInformation() 
    {
        return $this->_fields['InvoiceInformation']['FieldValue'];
    }

    /**
     * Sets the value of the InvoiceInformation property.
     * 
     * @param string InvoiceInformation
     * @return this instance
     */
    public function setInvoiceInformation($value) 
    {
        $this->_fields['InvoiceInformation']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the InvoiceInformation and returns this instance
     * 
     * @param string $value InvoiceInformation
     * @return MarketplaceWebServiceOrders_Model_InvoiceData instance
     */
    public function withInvoiceInformation($value)
    {
        $this->setInvoiceInformation($value);
        return $this;
    }


    /**
     * Checks if InvoiceInformation is set
     * 
     * @return bool true if InvoiceInformation  is set
     */
    public function isSetInvoiceInformation()
    {
        return !is_null($this->_fields['InvoiceInformation']['FieldValue']);
    }




}