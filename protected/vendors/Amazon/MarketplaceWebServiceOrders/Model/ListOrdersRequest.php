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
 * MarketplaceWebServiceOrders_Model_ListOrdersRequest
 * 
 * Properties:
 * <ul>
 * 
 * <li>SellerId: string</li>
 * <li>CreatedAfter: string</li>
 * <li>CreatedBefore: string</li>
 * <li>LastUpdatedAfter: string</li>
 * <li>LastUpdatedBefore: string</li>
 * <li>OrderStatus: MarketplaceWebServiceOrders_Model_OrderStatusList</li>
 * <li>MarketplaceId: MarketplaceWebServiceOrders_Model_MarketplaceIdList</li>
 * <li>FulfillmentChannel: MarketplaceWebServiceOrders_Model_FulfillmentChannelList</li>
 * <li>PaymentMethod: MarketplaceWebServiceOrders_Model_PaymentMethodList</li>
 * <li>BuyerEmail: string</li>
 * <li>SellerOrderId: string</li>
 * <li>MaxResultsPerPage: MaxResults</li>
 * <li>TFMShipmentStatus: MarketplaceWebServiceOrders_Model_TFMShipmentStatusList</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_ListOrdersRequest extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_ListOrdersRequest
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>SellerId: string</li>
     * <li>CreatedAfter: string</li>
     * <li>CreatedBefore: string</li>
     * <li>LastUpdatedAfter: string</li>
     * <li>LastUpdatedBefore: string</li>
     * <li>OrderStatus: MarketplaceWebServiceOrders_Model_OrderStatusList</li>
     * <li>MarketplaceId: MarketplaceWebServiceOrders_Model_MarketplaceIdList</li>
     * <li>FulfillmentChannel: MarketplaceWebServiceOrders_Model_FulfillmentChannelList</li>
     * <li>PaymentMethod: MarketplaceWebServiceOrders_Model_PaymentMethodList</li>
     * <li>BuyerEmail: string</li>
     * <li>SellerOrderId: string</li>
     * <li>MaxResultsPerPage: MaxResults</li>
     * <li>TFMShipmentStatus: MarketplaceWebServiceOrders_Model_TFMShipmentStatusList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'CreatedAfter' => array('FieldValue' => null, 'FieldType' => 'string'),
        'CreatedBefore' => array('FieldValue' => null, 'FieldType' => 'string'),
        'LastUpdatedAfter' => array('FieldValue' => null, 'FieldType' => 'string'),
        'LastUpdatedBefore' => array('FieldValue' => null, 'FieldType' => 'string'),

        'OrderStatus' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_OrderStatusList'),


        'MarketplaceId' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_MarketplaceIdList'),


        'FulfillmentChannel' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_FulfillmentChannelList'),


        'PaymentMethod' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_PaymentMethodList'),

        'BuyerEmail' => array('FieldValue' => null, 'FieldType' => 'string'),
        'SellerOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'MaxResultsPerPage' => array('FieldValue' => null, 'FieldType' => 'MaxResults'),

        'TFMShipmentStatus' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_TFMShipmentStatusList'),

        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the SellerId property.
     * 
     * @return string SellerId
     */
    public function getSellerId() 
    {
        return $this->_fields['SellerId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerId property.
     * 
     * @param string SellerId
     * @return this instance
     */
    public function setSellerId($value) 
    {
        $this->_fields['SellerId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerId and returns this instance
     * 
     * @param string $value SellerId
     * @return MarketplaceWebServiceOrders_Model_ListOrdersRequest instance
     */
    public function withSellerId($value)
    {
        $this->setSellerId($value);
        return $this;
    }


    /**
     * Checks if SellerId is set
     * 
     * @return bool true if SellerId  is set
     */
    public function isSetSellerId()
    {
        return !is_null($this->_fields['SellerId']['FieldValue']);
    }

    /**
     * Gets the value of the CreatedAfter property.
     * 
     * @return string CreatedAfter
     */
    public function getCreatedAfter() 
    {
        return $this->_fields['CreatedAfter']['FieldValue'];
    }

    /**
     * Sets the value of the CreatedAfter property.
     * 
     * @param string CreatedAfter
     * @return this instance
     */
    public function setCreatedAfter($value) 
    {
        $this->_fields['CreatedAfter']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the CreatedAfter and returns this instance
     * 
     * @param string $value CreatedAfter
     * @return MarketplaceWebServiceOrders_Model_ListOrdersRequest instance
     */
    public function withCreatedAfter($value)
    {
        $this->setCreatedAfter($value);
        return $this;
    }


    /**
     * Checks if CreatedAfter is set
     * 
     * @return bool true if CreatedAfter  is set
     */
    public function isSetCreatedAfter()
    {
        return !is_null($this->_fields['CreatedAfter']['FieldValue']);
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
     * @return MarketplaceWebServiceOrders_Model_ListOrdersRequest instance
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
     * Gets the value of the LastUpdatedAfter property.
     * 
     * @return string LastUpdatedAfter
     */
    public function getLastUpdatedAfter() 
    {
        return $this->_fields['LastUpdatedAfter']['FieldValue'];
    }

    /**
     * Sets the value of the LastUpdatedAfter property.
     * 
     * @param string LastUpdatedAfter
     * @return this instance
     */
    public function setLastUpdatedAfter($value) 
    {
        $this->_fields['LastUpdatedAfter']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the LastUpdatedAfter and returns this instance
     * 
     * @param string $value LastUpdatedAfter
     * @return MarketplaceWebServiceOrders_Model_ListOrdersRequest instance
     */
    public function withLastUpdatedAfter($value)
    {
        $this->setLastUpdatedAfter($value);
        return $this;
    }


    /**
     * Checks if LastUpdatedAfter is set
     * 
     * @return bool true if LastUpdatedAfter  is set
     */
    public function isSetLastUpdatedAfter()
    {
        return !is_null($this->_fields['LastUpdatedAfter']['FieldValue']);
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
     * @return MarketplaceWebServiceOrders_Model_ListOrdersRequest instance
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
     * Gets the value of the OrderStatus.
     * 
     * @return OrderStatusList OrderStatus
     */
    public function getOrderStatus() 
    {
        return $this->_fields['OrderStatus']['FieldValue'];
    }

    /**
     * Sets the value of the OrderStatus.
     * 
     * @param OrderStatusList OrderStatus
     * @return void
     */
    public function setOrderStatus($value) 
    {
        $this->_fields['OrderStatus']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the OrderStatus  and returns this instance
     * 
     * @param OrderStatusList $value OrderStatus
     * @return MarketplaceWebServiceOrders_Model_ListOrdersRequest instance
     */
    public function withOrderStatus($value)
    {
        $this->setOrderStatus($value);
        return $this;
    }


    /**
     * Checks if OrderStatus  is set
     * 
     * @return bool true if OrderStatus property is set
     */
    public function isSetOrderStatus()
    {
        return !is_null($this->_fields['OrderStatus']['FieldValue']);

    }

    /**
     * Gets the value of the MarketplaceId.
     * 
     * @return MarketplaceIdList MarketplaceId
     */
    public function getMarketplaceId() 
    {
        return $this->_fields['MarketplaceId']['FieldValue'];
    }

    /**
     * Sets the value of the MarketplaceId.
     * 
     * @param MarketplaceIdList MarketplaceId
     * @return void
     */
    public function setMarketplaceId($value) 
    {
        $this->_fields['MarketplaceId']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the MarketplaceId  and returns this instance
     * 
     * @param MarketplaceIdList $value MarketplaceId
     * @return MarketplaceWebServiceOrders_Model_ListOrdersRequest instance
     */
    public function withMarketplaceId($value)
    {
        $this->setMarketplaceId($value);
        return $this;
    }


    /**
     * Checks if MarketplaceId  is set
     * 
     * @return bool true if MarketplaceId property is set
     */
    public function isSetMarketplaceId()
    {
        return !is_null($this->_fields['MarketplaceId']['FieldValue']);

    }

    /**
     * Gets the value of the FulfillmentChannel.
     * 
     * @return FulfillmentChannelList FulfillmentChannel
     */
    public function getFulfillmentChannel() 
    {
        return $this->_fields['FulfillmentChannel']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentChannel.
     * 
     * @param FulfillmentChannelList FulfillmentChannel
     * @return void
     */
    public function setFulfillmentChannel($value) 
    {
        $this->_fields['FulfillmentChannel']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the FulfillmentChannel  and returns this instance
     * 
     * @param FulfillmentChannelList $value FulfillmentChannel
     * @return MarketplaceWebServiceOrders_Model_ListOrdersRequest instance
     */
    public function withFulfillmentChannel($value)
    {
        $this->setFulfillmentChannel($value);
        return $this;
    }


    /**
     * Checks if FulfillmentChannel  is set
     * 
     * @return bool true if FulfillmentChannel property is set
     */
    public function isSetFulfillmentChannel()
    {
        return !is_null($this->_fields['FulfillmentChannel']['FieldValue']);

    }

    /**
     * Gets the value of the PaymentMethod.
     * 
     * @return PaymentMethodList PaymentMethod
     */
    public function getPaymentMethod() 
    {
        return $this->_fields['PaymentMethod']['FieldValue'];
    }

    /**
     * Sets the value of the PaymentMethod.
     * 
     * @param PaymentMethodList PaymentMethod
     * @return void
     */
    public function setPaymentMethod($value) 
    {
        $this->_fields['PaymentMethod']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the PaymentMethod  and returns this instance
     * 
     * @param PaymentMethodList $value PaymentMethod
     * @return MarketplaceWebServiceOrders_Model_ListOrdersRequest instance
     */
    public function withPaymentMethod($value)
    {
        $this->setPaymentMethod($value);
        return $this;
    }


    /**
     * Checks if PaymentMethod  is set
     * 
     * @return bool true if PaymentMethod property is set
     */
    public function isSetPaymentMethod()
    {
        return !is_null($this->_fields['PaymentMethod']['FieldValue']);

    }

    /**
     * Gets the value of the BuyerEmail property.
     * 
     * @return string BuyerEmail
     */
    public function getBuyerEmail() 
    {
        return $this->_fields['BuyerEmail']['FieldValue'];
    }

    /**
     * Sets the value of the BuyerEmail property.
     * 
     * @param string BuyerEmail
     * @return this instance
     */
    public function setBuyerEmail($value) 
    {
        $this->_fields['BuyerEmail']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the BuyerEmail and returns this instance
     * 
     * @param string $value BuyerEmail
     * @return MarketplaceWebServiceOrders_Model_ListOrdersRequest instance
     */
    public function withBuyerEmail($value)
    {
        $this->setBuyerEmail($value);
        return $this;
    }


    /**
     * Checks if BuyerEmail is set
     * 
     * @return bool true if BuyerEmail  is set
     */
    public function isSetBuyerEmail()
    {
        return !is_null($this->_fields['BuyerEmail']['FieldValue']);
    }

    /**
     * Gets the value of the SellerOrderId property.
     * 
     * @return string SellerOrderId
     */
    public function getSellerOrderId() 
    {
        return $this->_fields['SellerOrderId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerOrderId property.
     * 
     * @param string SellerOrderId
     * @return this instance
     */
    public function setSellerOrderId($value) 
    {
        $this->_fields['SellerOrderId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerOrderId and returns this instance
     * 
     * @param string $value SellerOrderId
     * @return MarketplaceWebServiceOrders_Model_ListOrdersRequest instance
     */
    public function withSellerOrderId($value)
    {
        $this->setSellerOrderId($value);
        return $this;
    }


    /**
     * Checks if SellerOrderId is set
     * 
     * @return bool true if SellerOrderId  is set
     */
    public function isSetSellerOrderId()
    {
        return !is_null($this->_fields['SellerOrderId']['FieldValue']);
    }

    /**
     * Gets the value of the MaxResultsPerPage property.
     * 
     * @return MaxResults MaxResultsPerPage
     */
    public function getMaxResultsPerPage() 
    {
        return $this->_fields['MaxResultsPerPage']['FieldValue'];
    }

    /**
     * Sets the value of the MaxResultsPerPage property.
     * 
     * @param MaxResults MaxResultsPerPage
     * @return this instance
     */
    public function setMaxResultsPerPage($value) 
    {
        $this->_fields['MaxResultsPerPage']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the MaxResultsPerPage and returns this instance
     * 
     * @param MaxResults $value MaxResultsPerPage
     * @return MarketplaceWebServiceOrders_Model_ListOrdersRequest instance
     */
    public function withMaxResultsPerPage($value)
    {
        $this->setMaxResultsPerPage($value);
        return $this;
    }


    /**
     * Checks if MaxResultsPerPage is set
     * 
     * @return bool true if MaxResultsPerPage  is set
     */
    public function isSetMaxResultsPerPage()
    {
        return !is_null($this->_fields['MaxResultsPerPage']['FieldValue']);
    }

    /**
     * Gets the value of the TFMShipmentStatus.
     * 
     * @return TFMShipmentStatusList TFMShipmentStatus
     */
    public function getTFMShipmentStatus() 
    {
        return $this->_fields['TFMShipmentStatus']['FieldValue'];
    }

    /**
     * Sets the value of the TFMShipmentStatus.
     * 
     * @param TFMShipmentStatusList TFMShipmentStatus
     * @return void
     */
    public function setTFMShipmentStatus($value) 
    {
        $this->_fields['TFMShipmentStatus']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the TFMShipmentStatus  and returns this instance
     * 
     * @param TFMShipmentStatusList $value TFMShipmentStatus
     * @return MarketplaceWebServiceOrders_Model_ListOrdersRequest instance
     */
    public function withTFMShipmentStatus($value)
    {
        $this->setTFMShipmentStatus($value);
        return $this;
    }


    /**
     * Checks if TFMShipmentStatus  is set
     * 
     * @return bool true if TFMShipmentStatus property is set
     */
    public function isSetTFMShipmentStatus()
    {
        return !is_null($this->_fields['TFMShipmentStatus']['FieldValue']);

    }




}