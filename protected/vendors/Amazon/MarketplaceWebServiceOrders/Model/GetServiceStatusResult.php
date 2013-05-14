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
 * MarketplaceWebServiceOrders_Model_GetServiceStatusResult
 * 
 * Properties:
 * <ul>
 * 
 * <li>Status: ServiceStatusEnum</li>
 * <li>Timestamp: string</li>
 * <li>MessageId: string</li>
 * <li>Messages: MarketplaceWebServiceOrders_Model_MessageList</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_GetServiceStatusResult extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_GetServiceStatusResult
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>Status: ServiceStatusEnum</li>
     * <li>Timestamp: string</li>
     * <li>MessageId: string</li>
     * <li>Messages: MarketplaceWebServiceOrders_Model_MessageList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'Status' => array('FieldValue' => null, 'FieldType' => 'ServiceStatusEnum'),
        'Timestamp' => array('FieldValue' => null, 'FieldType' => 'string'),
        'MessageId' => array('FieldValue' => null, 'FieldType' => 'string'),

        'Messages' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_MessageList'),

        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the Status property.
     * 
     * @return ServiceStatusEnum Status
     */
    public function getStatus() 
    {
        return $this->_fields['Status']['FieldValue'];
    }

    /**
     * Sets the value of the Status property.
     * 
     * @param ServiceStatusEnum Status
     * @return this instance
     */
    public function setStatus($value) 
    {
        $this->_fields['Status']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Status and returns this instance
     * 
     * @param ServiceStatusEnum $value Status
     * @return MarketplaceWebServiceOrders_Model_GetServiceStatusResult instance
     */
    public function withStatus($value)
    {
        $this->setStatus($value);
        return $this;
    }


    /**
     * Checks if Status is set
     * 
     * @return bool true if Status  is set
     */
    public function isSetStatus()
    {
        return !is_null($this->_fields['Status']['FieldValue']);
    }

    /**
     * Gets the value of the Timestamp property.
     * 
     * @return string Timestamp
     */
    public function getTimestamp() 
    {
        return $this->_fields['Timestamp']['FieldValue'];
    }

    /**
     * Sets the value of the Timestamp property.
     * 
     * @param string Timestamp
     * @return this instance
     */
    public function setTimestamp($value) 
    {
        $this->_fields['Timestamp']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Timestamp and returns this instance
     * 
     * @param string $value Timestamp
     * @return MarketplaceWebServiceOrders_Model_GetServiceStatusResult instance
     */
    public function withTimestamp($value)
    {
        $this->setTimestamp($value);
        return $this;
    }


    /**
     * Checks if Timestamp is set
     * 
     * @return bool true if Timestamp  is set
     */
    public function isSetTimestamp()
    {
        return !is_null($this->_fields['Timestamp']['FieldValue']);
    }

    /**
     * Gets the value of the MessageId property.
     * 
     * @return string MessageId
     */
    public function getMessageId() 
    {
        return $this->_fields['MessageId']['FieldValue'];
    }

    /**
     * Sets the value of the MessageId property.
     * 
     * @param string MessageId
     * @return this instance
     */
    public function setMessageId($value) 
    {
        $this->_fields['MessageId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the MessageId and returns this instance
     * 
     * @param string $value MessageId
     * @return MarketplaceWebServiceOrders_Model_GetServiceStatusResult instance
     */
    public function withMessageId($value)
    {
        $this->setMessageId($value);
        return $this;
    }


    /**
     * Checks if MessageId is set
     * 
     * @return bool true if MessageId  is set
     */
    public function isSetMessageId()
    {
        return !is_null($this->_fields['MessageId']['FieldValue']);
    }

    /**
     * Gets the value of the Messages.
     * 
     * @return MessageList Messages
     */
    public function getMessages() 
    {
        return $this->_fields['Messages']['FieldValue'];
    }

    /**
     * Sets the value of the Messages.
     * 
     * @param MessageList Messages
     * @return void
     */
    public function setMessages($value) 
    {
        $this->_fields['Messages']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the Messages  and returns this instance
     * 
     * @param MessageList $value Messages
     * @return MarketplaceWebServiceOrders_Model_GetServiceStatusResult instance
     */
    public function withMessages($value)
    {
        $this->setMessages($value);
        return $this;
    }


    /**
     * Checks if Messages  is set
     * 
     * @return bool true if Messages property is set
     */
    public function isSetMessages()
    {
        return !is_null($this->_fields['Messages']['FieldValue']);

    }




}