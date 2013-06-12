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
 * MarketplaceWebServiceOrders_Model_Order
 * 
 * Properties:
 * <ul>
 * 
 * <li>AmazonOrderId: string</li>
 * <li>SellerOrderId: string</li>
 * <li>PurchaseDate: string</li>
 * <li>LastUpdateDate: string</li>
 * <li>OrderStatus: OrderStatusEnum</li>
 * <li>FulfillmentChannel: FulfillmentChannelEnum</li>
 * <li>SalesChannel: string</li>
 * <li>OrderChannel: string</li>
 * <li>ShipServiceLevel: string</li>
 * <li>ShippingAddress: MarketplaceWebServiceOrders_Model_Address</li>
 * <li>OrderTotal: MarketplaceWebServiceOrders_Model_Money</li>
 * <li>NumberOfItemsShipped: int</li>
 * <li>NumberOfItemsUnshipped: int</li>
 * <li>PaymentExecutionDetail: MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItemList</li>
 * <li>PaymentMethod: PaymentMethodEnum</li>
 * <li>MarketplaceId: string</li>
 * <li>BuyerEmail: string</li>
 * <li>BuyerName: string</li>
 * <li>ShipmentServiceLevelCategory: string</li>
 * <li>ShippedByAmazonTFM: bool</li>
 * <li>TFMShipmentStatus: string</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_Order extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_Order
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>AmazonOrderId: string</li>
     * <li>SellerOrderId: string</li>
     * <li>PurchaseDate: string</li>
     * <li>LastUpdateDate: string</li>
     * <li>OrderStatus: OrderStatusEnum</li>
     * <li>FulfillmentChannel: FulfillmentChannelEnum</li>
     * <li>SalesChannel: string</li>
     * <li>OrderChannel: string</li>
     * <li>ShipServiceLevel: string</li>
     * <li>ShippingAddress: MarketplaceWebServiceOrders_Model_Address</li>
     * <li>OrderTotal: MarketplaceWebServiceOrders_Model_Money</li>
     * <li>NumberOfItemsShipped: int</li>
     * <li>NumberOfItemsUnshipped: int</li>
     * <li>PaymentExecutionDetail: MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItemList</li>
     * <li>PaymentMethod: PaymentMethodEnum</li>
     * <li>MarketplaceId: string</li>
     * <li>BuyerEmail: string</li>
     * <li>BuyerName: string</li>
     * <li>ShipmentServiceLevelCategory: string</li>
     * <li>ShippedByAmazonTFM: bool</li>
     * <li>TFMShipmentStatus: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'AmazonOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'SellerOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'PurchaseDate' => array('FieldValue' => null, 'FieldType' => 'string'),
        'LastUpdateDate' => array('FieldValue' => null, 'FieldType' => 'string'),
        'OrderStatus' => array('FieldValue' => null, 'FieldType' => 'OrderStatusEnum'),
        'FulfillmentChannel' => array('FieldValue' => null, 'FieldType' => 'FulfillmentChannelEnum'),
        'SalesChannel' => array('FieldValue' => null, 'FieldType' => 'string'),
        'OrderChannel' => array('FieldValue' => null, 'FieldType' => 'string'),
        'ShipServiceLevel' => array('FieldValue' => null, 'FieldType' => 'string'),

        'ShippingAddress' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_Address'),


        'OrderTotal' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_Money'),

        'NumberOfItemsShipped' => array('FieldValue' => null, 'FieldType' => 'int'),
        'NumberOfItemsUnshipped' => array('FieldValue' => null, 'FieldType' => 'int'),

        'PaymentExecutionDetail' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_PaymentExecutionDetailItemList'),

        'PaymentMethod' => array('FieldValue' => null, 'FieldType' => 'PaymentMethodEnum'),
        'MarketplaceId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'BuyerEmail' => array('FieldValue' => null, 'FieldType' => 'string'),
        'BuyerName' => array('FieldValue' => null, 'FieldType' => 'string'),
        'ShipmentServiceLevelCategory' => array('FieldValue' => null, 'FieldType' => 'string'),
        'ShippedByAmazonTFM' => array('FieldValue' => null, 'FieldType' => 'bool'),
        'TFMShipmentStatus' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the AmazonOrderId property.
     * 
     * @return string AmazonOrderId
     */
    public function getAmazonOrderId() 
    {
        return $this->_fields['AmazonOrderId']['FieldValue'];
    }

    /**
     * Sets the value of the AmazonOrderId property.
     * 
     * @param string AmazonOrderId
     * @return this instance
     */
    public function setAmazonOrderId($value) 
    {
        $this->_fields['AmazonOrderId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the AmazonOrderId and returns this instance
     * 
     * @param string $value AmazonOrderId
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withAmazonOrderId($value)
    {
        $this->setAmazonOrderId($value);
        return $this;
    }


    /**
     * Checks if AmazonOrderId is set
     * 
     * @return bool true if AmazonOrderId  is set
     */
    public function isSetAmazonOrderId()
    {
        return !is_null($this->_fields['AmazonOrderId']['FieldValue']);
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
     * @return MarketplaceWebServiceOrders_Model_Order instance
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
     * Gets the value of the PurchaseDate property.
     * 
     * @return string PurchaseDate
     */
    public function getPurchaseDate() 
    {
        return $this->_fields['PurchaseDate']['FieldValue'];
    }

    /**
     * Sets the value of the PurchaseDate property.
     * 
     * @param string PurchaseDate
     * @return this instance
     */
    public function setPurchaseDate($value) 
    {
        $this->_fields['PurchaseDate']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the PurchaseDate and returns this instance
     * 
     * @param string $value PurchaseDate
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withPurchaseDate($value)
    {
        $this->setPurchaseDate($value);
        return $this;
    }


    /**
     * Checks if PurchaseDate is set
     * 
     * @return bool true if PurchaseDate  is set
     */
    public function isSetPurchaseDate()
    {
        return !is_null($this->_fields['PurchaseDate']['FieldValue']);
    }

    /**
     * Gets the value of the LastUpdateDate property.
     * 
     * @return string LastUpdateDate
     */
    public function getLastUpdateDate() 
    {
        return $this->_fields['LastUpdateDate']['FieldValue'];
    }

    /**
     * Sets the value of the LastUpdateDate property.
     * 
     * @param string LastUpdateDate
     * @return this instance
     */
    public function setLastUpdateDate($value) 
    {
        $this->_fields['LastUpdateDate']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the LastUpdateDate and returns this instance
     * 
     * @param string $value LastUpdateDate
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withLastUpdateDate($value)
    {
        $this->setLastUpdateDate($value);
        return $this;
    }


    /**
     * Checks if LastUpdateDate is set
     * 
     * @return bool true if LastUpdateDate  is set
     */
    public function isSetLastUpdateDate()
    {
        return !is_null($this->_fields['LastUpdateDate']['FieldValue']);
    }

    /**
     * Gets the value of the OrderStatus property.
     * 
     * @return OrderStatusEnum OrderStatus
     */
    public function getOrderStatus() 
    {
        return $this->_fields['OrderStatus']['FieldValue'];
    }

    /**
     * Sets the value of the OrderStatus property.
     * 
     * @param OrderStatusEnum OrderStatus
     * @return this instance
     */
    public function setOrderStatus($value) 
    {
        $this->_fields['OrderStatus']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the OrderStatus and returns this instance
     * 
     * @param OrderStatusEnum $value OrderStatus
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withOrderStatus($value)
    {
        $this->setOrderStatus($value);
        return $this;
    }


    /**
     * Checks if OrderStatus is set
     * 
     * @return bool true if OrderStatus  is set
     */
    public function isSetOrderStatus()
    {
        return !is_null($this->_fields['OrderStatus']['FieldValue']);
    }

    /**
     * Gets the value of the FulfillmentChannel property.
     * 
     * @return FulfillmentChannelEnum FulfillmentChannel
     */
    public function getFulfillmentChannel() 
    {
        return $this->_fields['FulfillmentChannel']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentChannel property.
     * 
     * @param FulfillmentChannelEnum FulfillmentChannel
     * @return this instance
     */
    public function setFulfillmentChannel($value) 
    {
        $this->_fields['FulfillmentChannel']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the FulfillmentChannel and returns this instance
     * 
     * @param FulfillmentChannelEnum $value FulfillmentChannel
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withFulfillmentChannel($value)
    {
        $this->setFulfillmentChannel($value);
        return $this;
    }


    /**
     * Checks if FulfillmentChannel is set
     * 
     * @return bool true if FulfillmentChannel  is set
     */
    public function isSetFulfillmentChannel()
    {
        return !is_null($this->_fields['FulfillmentChannel']['FieldValue']);
    }

    /**
     * Gets the value of the SalesChannel property.
     * 
     * @return string SalesChannel
     */
    public function getSalesChannel() 
    {
        return $this->_fields['SalesChannel']['FieldValue'];
    }

    /**
     * Sets the value of the SalesChannel property.
     * 
     * @param string SalesChannel
     * @return this instance
     */
    public function setSalesChannel($value) 
    {
        $this->_fields['SalesChannel']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SalesChannel and returns this instance
     * 
     * @param string $value SalesChannel
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withSalesChannel($value)
    {
        $this->setSalesChannel($value);
        return $this;
    }


    /**
     * Checks if SalesChannel is set
     * 
     * @return bool true if SalesChannel  is set
     */
    public function isSetSalesChannel()
    {
        return !is_null($this->_fields['SalesChannel']['FieldValue']);
    }

    /**
     * Gets the value of the OrderChannel property.
     * 
     * @return string OrderChannel
     */
    public function getOrderChannel() 
    {
        return $this->_fields['OrderChannel']['FieldValue'];
    }

    /**
     * Sets the value of the OrderChannel property.
     * 
     * @param string OrderChannel
     * @return this instance
     */
    public function setOrderChannel($value) 
    {
        $this->_fields['OrderChannel']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the OrderChannel and returns this instance
     * 
     * @param string $value OrderChannel
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withOrderChannel($value)
    {
        $this->setOrderChannel($value);
        return $this;
    }


    /**
     * Checks if OrderChannel is set
     * 
     * @return bool true if OrderChannel  is set
     */
    public function isSetOrderChannel()
    {
        return !is_null($this->_fields['OrderChannel']['FieldValue']);
    }

    /**
     * Gets the value of the ShipServiceLevel property.
     * 
     * @return string ShipServiceLevel
     */
    public function getShipServiceLevel() 
    {
        return $this->_fields['ShipServiceLevel']['FieldValue'];
    }

    /**
     * Sets the value of the ShipServiceLevel property.
     * 
     * @param string ShipServiceLevel
     * @return this instance
     */
    public function setShipServiceLevel($value) 
    {
        $this->_fields['ShipServiceLevel']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ShipServiceLevel and returns this instance
     * 
     * @param string $value ShipServiceLevel
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withShipServiceLevel($value)
    {
        $this->setShipServiceLevel($value);
        return $this;
    }


    /**
     * Checks if ShipServiceLevel is set
     * 
     * @return bool true if ShipServiceLevel  is set
     */
    public function isSetShipServiceLevel()
    {
        return !is_null($this->_fields['ShipServiceLevel']['FieldValue']);
    }

    /**
     * Gets the value of the ShippingAddress.
     * 
     * @return Address ShippingAddress
     */
    public function getShippingAddress() 
    {
        return $this->_fields['ShippingAddress']['FieldValue'];
    }

    /**
     * Sets the value of the ShippingAddress.
     * 
     * @param Address ShippingAddress
     * @return void
     */
    public function setShippingAddress($value) 
    {
        $this->_fields['ShippingAddress']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ShippingAddress  and returns this instance
     * 
     * @param Address $value ShippingAddress
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withShippingAddress($value)
    {
        $this->setShippingAddress($value);
        return $this;
    }


    /**
     * Checks if ShippingAddress  is set
     * 
     * @return bool true if ShippingAddress property is set
     */
    public function isSetShippingAddress()
    {
        return !is_null($this->_fields['ShippingAddress']['FieldValue']);

    }

    /**
     * Gets the value of the OrderTotal.
     * 
     * @return Money OrderTotal
     */
    public function getOrderTotal() 
    {
        return $this->_fields['OrderTotal']['FieldValue'];
    }

    /**
     * Sets the value of the OrderTotal.
     * 
     * @param Money OrderTotal
     * @return void
     */
    public function setOrderTotal($value) 
    {
        $this->_fields['OrderTotal']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the OrderTotal  and returns this instance
     * 
     * @param Money $value OrderTotal
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withOrderTotal($value)
    {
        $this->setOrderTotal($value);
        return $this;
    }


    /**
     * Checks if OrderTotal  is set
     * 
     * @return bool true if OrderTotal property is set
     */
    public function isSetOrderTotal()
    {
        return !is_null($this->_fields['OrderTotal']['FieldValue']);

    }

    /**
     * Gets the value of the NumberOfItemsShipped property.
     * 
     * @return int NumberOfItemsShipped
     */
    public function getNumberOfItemsShipped() 
    {
        return $this->_fields['NumberOfItemsShipped']['FieldValue'];
    }

    /**
     * Sets the value of the NumberOfItemsShipped property.
     * 
     * @param int NumberOfItemsShipped
     * @return this instance
     */
    public function setNumberOfItemsShipped($value) 
    {
        $this->_fields['NumberOfItemsShipped']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the NumberOfItemsShipped and returns this instance
     * 
     * @param int $value NumberOfItemsShipped
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withNumberOfItemsShipped($value)
    {
        $this->setNumberOfItemsShipped($value);
        return $this;
    }


    /**
     * Checks if NumberOfItemsShipped is set
     * 
     * @return bool true if NumberOfItemsShipped  is set
     */
    public function isSetNumberOfItemsShipped()
    {
        return !is_null($this->_fields['NumberOfItemsShipped']['FieldValue']);
    }

    /**
     * Gets the value of the NumberOfItemsUnshipped property.
     * 
     * @return int NumberOfItemsUnshipped
     */
    public function getNumberOfItemsUnshipped() 
    {
        return $this->_fields['NumberOfItemsUnshipped']['FieldValue'];
    }

    /**
     * Sets the value of the NumberOfItemsUnshipped property.
     * 
     * @param int NumberOfItemsUnshipped
     * @return this instance
     */
    public function setNumberOfItemsUnshipped($value) 
    {
        $this->_fields['NumberOfItemsUnshipped']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the NumberOfItemsUnshipped and returns this instance
     * 
     * @param int $value NumberOfItemsUnshipped
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withNumberOfItemsUnshipped($value)
    {
        $this->setNumberOfItemsUnshipped($value);
        return $this;
    }


    /**
     * Checks if NumberOfItemsUnshipped is set
     * 
     * @return bool true if NumberOfItemsUnshipped  is set
     */
    public function isSetNumberOfItemsUnshipped()
    {
        return !is_null($this->_fields['NumberOfItemsUnshipped']['FieldValue']);
    }

    /**
     * Gets the value of the PaymentExecutionDetail.
     * 
     * @return PaymentExecutionDetailItemList PaymentExecutionDetail
     */
    public function getPaymentExecutionDetail() 
    {
        return $this->_fields['PaymentExecutionDetail']['FieldValue'];
    }

    /**
     * Sets the value of the PaymentExecutionDetail.
     * 
     * @param PaymentExecutionDetailItemList PaymentExecutionDetail
     * @return void
     */
    public function setPaymentExecutionDetail($value) 
    {
        $this->_fields['PaymentExecutionDetail']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the PaymentExecutionDetail  and returns this instance
     * 
     * @param PaymentExecutionDetailItemList $value PaymentExecutionDetail
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withPaymentExecutionDetail($value)
    {
        $this->setPaymentExecutionDetail($value);
        return $this;
    }


    /**
     * Checks if PaymentExecutionDetail  is set
     * 
     * @return bool true if PaymentExecutionDetail property is set
     */
    public function isSetPaymentExecutionDetail()
    {
        return !is_null($this->_fields['PaymentExecutionDetail']['FieldValue']);

    }

    /**
     * Gets the value of the PaymentMethod property.
     * 
     * @return PaymentMethodEnum PaymentMethod
     */
    public function getPaymentMethod() 
    {
        return $this->_fields['PaymentMethod']['FieldValue'];
    }

    /**
     * Sets the value of the PaymentMethod property.
     * 
     * @param PaymentMethodEnum PaymentMethod
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
     * @param PaymentMethodEnum $value PaymentMethod
     * @return MarketplaceWebServiceOrders_Model_Order instance
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

    /**
     * Gets the value of the MarketplaceId property.
     * 
     * @return string MarketplaceId
     */
    public function getMarketplaceId() 
    {
        return $this->_fields['MarketplaceId']['FieldValue'];
    }

    /**
     * Sets the value of the MarketplaceId property.
     * 
     * @param string MarketplaceId
     * @return this instance
     */
    public function setMarketplaceId($value) 
    {
        $this->_fields['MarketplaceId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the MarketplaceId and returns this instance
     * 
     * @param string $value MarketplaceId
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withMarketplaceId($value)
    {
        $this->setMarketplaceId($value);
        return $this;
    }


    /**
     * Checks if MarketplaceId is set
     * 
     * @return bool true if MarketplaceId  is set
     */
    public function isSetMarketplaceId()
    {
        return !is_null($this->_fields['MarketplaceId']['FieldValue']);
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
     * @return MarketplaceWebServiceOrders_Model_Order instance
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
     * Gets the value of the BuyerName property.
     * 
     * @return string BuyerName
     */
    public function getBuyerName() 
    {
        return $this->_fields['BuyerName']['FieldValue'];
    }

    /**
     * Sets the value of the BuyerName property.
     * 
     * @param string BuyerName
     * @return this instance
     */
    public function setBuyerName($value) 
    {
        $this->_fields['BuyerName']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the BuyerName and returns this instance
     * 
     * @param string $value BuyerName
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withBuyerName($value)
    {
        $this->setBuyerName($value);
        return $this;
    }


    /**
     * Checks if BuyerName is set
     * 
     * @return bool true if BuyerName  is set
     */
    public function isSetBuyerName()
    {
        return !is_null($this->_fields['BuyerName']['FieldValue']);
    }

    /**
     * Gets the value of the ShipmentServiceLevelCategory property.
     * 
     * @return string ShipmentServiceLevelCategory
     */
    public function getShipmentServiceLevelCategory() 
    {
        return $this->_fields['ShipmentServiceLevelCategory']['FieldValue'];
    }

    /**
     * Sets the value of the ShipmentServiceLevelCategory property.
     * 
     * @param string ShipmentServiceLevelCategory
     * @return this instance
     */
    public function setShipmentServiceLevelCategory($value) 
    {
        $this->_fields['ShipmentServiceLevelCategory']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ShipmentServiceLevelCategory and returns this instance
     * 
     * @param string $value ShipmentServiceLevelCategory
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withShipmentServiceLevelCategory($value)
    {
        $this->setShipmentServiceLevelCategory($value);
        return $this;
    }


    /**
     * Checks if ShipmentServiceLevelCategory is set
     * 
     * @return bool true if ShipmentServiceLevelCategory  is set
     */
    public function isSetShipmentServiceLevelCategory()
    {
        return !is_null($this->_fields['ShipmentServiceLevelCategory']['FieldValue']);
    }

    /**
     * Gets the value of the ShippedByAmazonTFM property.
     * 
     * @return bool ShippedByAmazonTFM
     */
    public function getShippedByAmazonTFM() 
    {
        return $this->_fields['ShippedByAmazonTFM']['FieldValue'];
    }

    /**
     * Sets the value of the ShippedByAmazonTFM property.
     * 
     * @param bool ShippedByAmazonTFM
     * @return this instance
     */
    public function setShippedByAmazonTFM($value) 
    {
        $this->_fields['ShippedByAmazonTFM']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ShippedByAmazonTFM and returns this instance
     * 
     * @param bool $value ShippedByAmazonTFM
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withShippedByAmazonTFM($value)
    {
        $this->setShippedByAmazonTFM($value);
        return $this;
    }


    /**
     * Checks if ShippedByAmazonTFM is set
     * 
     * @return bool true if ShippedByAmazonTFM  is set
     */
    public function isSetShippedByAmazonTFM()
    {
        return !is_null($this->_fields['ShippedByAmazonTFM']['FieldValue']);
    }

    /**
     * Gets the value of the TFMShipmentStatus property.
     * 
     * @return string TFMShipmentStatus
     */
    public function getTFMShipmentStatus() 
    {
        return $this->_fields['TFMShipmentStatus']['FieldValue'];
    }

    /**
     * Sets the value of the TFMShipmentStatus property.
     * 
     * @param string TFMShipmentStatus
     * @return this instance
     */
    public function setTFMShipmentStatus($value) 
    {
        $this->_fields['TFMShipmentStatus']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the TFMShipmentStatus and returns this instance
     * 
     * @param string $value TFMShipmentStatus
     * @return MarketplaceWebServiceOrders_Model_Order instance
     */
    public function withTFMShipmentStatus($value)
    {
        $this->setTFMShipmentStatus($value);
        return $this;
    }


    /**
     * Checks if TFMShipmentStatus is set
     * 
     * @return bool true if TFMShipmentStatus  is set
     */
    public function isSetTFMShipmentStatus()
    {
        return !is_null($this->_fields['TFMShipmentStatus']['FieldValue']);
    }




}