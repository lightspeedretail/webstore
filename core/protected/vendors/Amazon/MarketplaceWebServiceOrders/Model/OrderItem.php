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
 * MarketplaceWebServiceOrders_Model_OrderItem
 * 
 * Properties:
 * <ul>
 * 
 * <li>ASIN: string</li>
 * <li>SellerSKU: string</li>
 * <li>OrderItemId: string</li>
 * <li>Title: string</li>
 * <li>QuantityOrdered: int</li>
 * <li>QuantityShipped: int</li>
 * <li>ItemPrice: MarketplaceWebServiceOrders_Model_Money</li>
 * <li>ShippingPrice: MarketplaceWebServiceOrders_Model_Money</li>
 * <li>GiftWrapPrice: MarketplaceWebServiceOrders_Model_Money</li>
 * <li>ItemTax: MarketplaceWebServiceOrders_Model_Money</li>
 * <li>ShippingTax: MarketplaceWebServiceOrders_Model_Money</li>
 * <li>GiftWrapTax: MarketplaceWebServiceOrders_Model_Money</li>
 * <li>ShippingDiscount: MarketplaceWebServiceOrders_Model_Money</li>
 * <li>PromotionDiscount: MarketplaceWebServiceOrders_Model_Money</li>
 * <li>PromotionIds: MarketplaceWebServiceOrders_Model_PromotionIdList</li>
 * <li>CODFee: MarketplaceWebServiceOrders_Model_Money</li>
 * <li>CODFeeDiscount: MarketplaceWebServiceOrders_Model_Money</li>
 * <li>GiftMessageText: string</li>
 * <li>GiftWrapLevel: string</li>
 * <li>InvoiceData: MarketplaceWebServiceOrders_Model_InvoiceData</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_OrderItem extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_OrderItem
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>ASIN: string</li>
     * <li>SellerSKU: string</li>
     * <li>OrderItemId: string</li>
     * <li>Title: string</li>
     * <li>QuantityOrdered: int</li>
     * <li>QuantityShipped: int</li>
     * <li>ItemPrice: MarketplaceWebServiceOrders_Model_Money</li>
     * <li>ShippingPrice: MarketplaceWebServiceOrders_Model_Money</li>
     * <li>GiftWrapPrice: MarketplaceWebServiceOrders_Model_Money</li>
     * <li>ItemTax: MarketplaceWebServiceOrders_Model_Money</li>
     * <li>ShippingTax: MarketplaceWebServiceOrders_Model_Money</li>
     * <li>GiftWrapTax: MarketplaceWebServiceOrders_Model_Money</li>
     * <li>ShippingDiscount: MarketplaceWebServiceOrders_Model_Money</li>
     * <li>PromotionDiscount: MarketplaceWebServiceOrders_Model_Money</li>
     * <li>PromotionIds: MarketplaceWebServiceOrders_Model_PromotionIdList</li>
     * <li>CODFee: MarketplaceWebServiceOrders_Model_Money</li>
     * <li>CODFeeDiscount: MarketplaceWebServiceOrders_Model_Money</li>
     * <li>GiftMessageText: string</li>
     * <li>GiftWrapLevel: string</li>
     * <li>InvoiceData: MarketplaceWebServiceOrders_Model_InvoiceData</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'ASIN' => array('FieldValue' => null, 'FieldType' => 'string'),
        'SellerSKU' => array('FieldValue' => null, 'FieldType' => 'string'),
        'OrderItemId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Title' => array('FieldValue' => null, 'FieldType' => 'string'),
        'QuantityOrdered' => array('FieldValue' => null, 'FieldType' => 'int'),
        'QuantityShipped' => array('FieldValue' => null, 'FieldType' => 'int'),

        'ItemPrice' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_Money'),


        'ShippingPrice' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_Money'),


        'GiftWrapPrice' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_Money'),


        'ItemTax' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_Money'),


        'ShippingTax' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_Money'),


        'GiftWrapTax' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_Money'),


        'ShippingDiscount' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_Money'),


        'PromotionDiscount' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_Money'),


        'PromotionIds' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_PromotionIdList'),


        'CODFee' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_Money'),


        'CODFeeDiscount' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_Money'),

        'GiftMessageText' => array('FieldValue' => null, 'FieldType' => 'string'),
        'GiftWrapLevel' => array('FieldValue' => null, 'FieldType' => 'string'),

        'InvoiceData' => array('FieldValue' => null, 'FieldType' => 'MarketplaceWebServiceOrders_Model_InvoiceData'),

        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the ASIN property.
     * 
     * @return string ASIN
     */
    public function getASIN() 
    {
        return $this->_fields['ASIN']['FieldValue'];
    }

    /**
     * Sets the value of the ASIN property.
     * 
     * @param string ASIN
     * @return this instance
     */
    public function setASIN($value) 
    {
        $this->_fields['ASIN']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ASIN and returns this instance
     * 
     * @param string $value ASIN
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withASIN($value)
    {
        $this->setASIN($value);
        return $this;
    }


    /**
     * Checks if ASIN is set
     * 
     * @return bool true if ASIN  is set
     */
    public function isSetASIN()
    {
        return !is_null($this->_fields['ASIN']['FieldValue']);
    }

    /**
     * Gets the value of the SellerSKU property.
     * 
     * @return string SellerSKU
     */
    public function getSellerSKU() 
    {
        return $this->_fields['SellerSKU']['FieldValue'];
    }

    /**
     * Sets the value of the SellerSKU property.
     * 
     * @param string SellerSKU
     * @return this instance
     */
    public function setSellerSKU($value) 
    {
        $this->_fields['SellerSKU']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerSKU and returns this instance
     * 
     * @param string $value SellerSKU
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withSellerSKU($value)
    {
        $this->setSellerSKU($value);
        return $this;
    }


    /**
     * Checks if SellerSKU is set
     * 
     * @return bool true if SellerSKU  is set
     */
    public function isSetSellerSKU()
    {
        return !is_null($this->_fields['SellerSKU']['FieldValue']);
    }

    /**
     * Gets the value of the OrderItemId property.
     * 
     * @return string OrderItemId
     */
    public function getOrderItemId() 
    {
        return $this->_fields['OrderItemId']['FieldValue'];
    }

    /**
     * Sets the value of the OrderItemId property.
     * 
     * @param string OrderItemId
     * @return this instance
     */
    public function setOrderItemId($value) 
    {
        $this->_fields['OrderItemId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the OrderItemId and returns this instance
     * 
     * @param string $value OrderItemId
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withOrderItemId($value)
    {
        $this->setOrderItemId($value);
        return $this;
    }


    /**
     * Checks if OrderItemId is set
     * 
     * @return bool true if OrderItemId  is set
     */
    public function isSetOrderItemId()
    {
        return !is_null($this->_fields['OrderItemId']['FieldValue']);
    }

    /**
     * Gets the value of the Title property.
     * 
     * @return string Title
     */
    public function getTitle() 
    {
        return $this->_fields['Title']['FieldValue'];
    }

    /**
     * Sets the value of the Title property.
     * 
     * @param string Title
     * @return this instance
     */
    public function setTitle($value) 
    {
        $this->_fields['Title']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Title and returns this instance
     * 
     * @param string $value Title
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withTitle($value)
    {
        $this->setTitle($value);
        return $this;
    }


    /**
     * Checks if Title is set
     * 
     * @return bool true if Title  is set
     */
    public function isSetTitle()
    {
        return !is_null($this->_fields['Title']['FieldValue']);
    }

    /**
     * Gets the value of the QuantityOrdered property.
     * 
     * @return int QuantityOrdered
     */
    public function getQuantityOrdered() 
    {
        return $this->_fields['QuantityOrdered']['FieldValue'];
    }

    /**
     * Sets the value of the QuantityOrdered property.
     * 
     * @param int QuantityOrdered
     * @return this instance
     */
    public function setQuantityOrdered($value) 
    {
        $this->_fields['QuantityOrdered']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the QuantityOrdered and returns this instance
     * 
     * @param int $value QuantityOrdered
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withQuantityOrdered($value)
    {
        $this->setQuantityOrdered($value);
        return $this;
    }


    /**
     * Checks if QuantityOrdered is set
     * 
     * @return bool true if QuantityOrdered  is set
     */
    public function isSetQuantityOrdered()
    {
        return !is_null($this->_fields['QuantityOrdered']['FieldValue']);
    }

    /**
     * Gets the value of the QuantityShipped property.
     * 
     * @return int QuantityShipped
     */
    public function getQuantityShipped() 
    {
        return $this->_fields['QuantityShipped']['FieldValue'];
    }

    /**
     * Sets the value of the QuantityShipped property.
     * 
     * @param int QuantityShipped
     * @return this instance
     */
    public function setQuantityShipped($value) 
    {
        $this->_fields['QuantityShipped']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the QuantityShipped and returns this instance
     * 
     * @param int $value QuantityShipped
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withQuantityShipped($value)
    {
        $this->setQuantityShipped($value);
        return $this;
    }


    /**
     * Checks if QuantityShipped is set
     * 
     * @return bool true if QuantityShipped  is set
     */
    public function isSetQuantityShipped()
    {
        return !is_null($this->_fields['QuantityShipped']['FieldValue']);
    }

    /**
     * Gets the value of the ItemPrice.
     * 
     * @return Money ItemPrice
     */
    public function getItemPrice() 
    {
        return $this->_fields['ItemPrice']['FieldValue'];
    }

    /**
     * Sets the value of the ItemPrice.
     * 
     * @param Money ItemPrice
     * @return void
     */
    public function setItemPrice($value) 
    {
        $this->_fields['ItemPrice']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ItemPrice  and returns this instance
     * 
     * @param Money $value ItemPrice
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withItemPrice($value)
    {
        $this->setItemPrice($value);
        return $this;
    }


    /**
     * Checks if ItemPrice  is set
     * 
     * @return bool true if ItemPrice property is set
     */
    public function isSetItemPrice()
    {
        return !is_null($this->_fields['ItemPrice']['FieldValue']);

    }

    /**
     * Gets the value of the ShippingPrice.
     * 
     * @return Money ShippingPrice
     */
    public function getShippingPrice() 
    {
        return $this->_fields['ShippingPrice']['FieldValue'];
    }

    /**
     * Sets the value of the ShippingPrice.
     * 
     * @param Money ShippingPrice
     * @return void
     */
    public function setShippingPrice($value) 
    {
        $this->_fields['ShippingPrice']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ShippingPrice  and returns this instance
     * 
     * @param Money $value ShippingPrice
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withShippingPrice($value)
    {
        $this->setShippingPrice($value);
        return $this;
    }


    /**
     * Checks if ShippingPrice  is set
     * 
     * @return bool true if ShippingPrice property is set
     */
    public function isSetShippingPrice()
    {
        return !is_null($this->_fields['ShippingPrice']['FieldValue']);

    }

    /**
     * Gets the value of the GiftWrapPrice.
     * 
     * @return Money GiftWrapPrice
     */
    public function getGiftWrapPrice() 
    {
        return $this->_fields['GiftWrapPrice']['FieldValue'];
    }

    /**
     * Sets the value of the GiftWrapPrice.
     * 
     * @param Money GiftWrapPrice
     * @return void
     */
    public function setGiftWrapPrice($value) 
    {
        $this->_fields['GiftWrapPrice']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the GiftWrapPrice  and returns this instance
     * 
     * @param Money $value GiftWrapPrice
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withGiftWrapPrice($value)
    {
        $this->setGiftWrapPrice($value);
        return $this;
    }


    /**
     * Checks if GiftWrapPrice  is set
     * 
     * @return bool true if GiftWrapPrice property is set
     */
    public function isSetGiftWrapPrice()
    {
        return !is_null($this->_fields['GiftWrapPrice']['FieldValue']);

    }

    /**
     * Gets the value of the ItemTax.
     * 
     * @return Money ItemTax
     */
    public function getItemTax() 
    {
        return $this->_fields['ItemTax']['FieldValue'];
    }

    /**
     * Sets the value of the ItemTax.
     * 
     * @param Money ItemTax
     * @return void
     */
    public function setItemTax($value) 
    {
        $this->_fields['ItemTax']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ItemTax  and returns this instance
     * 
     * @param Money $value ItemTax
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withItemTax($value)
    {
        $this->setItemTax($value);
        return $this;
    }


    /**
     * Checks if ItemTax  is set
     * 
     * @return bool true if ItemTax property is set
     */
    public function isSetItemTax()
    {
        return !is_null($this->_fields['ItemTax']['FieldValue']);

    }

    /**
     * Gets the value of the ShippingTax.
     * 
     * @return Money ShippingTax
     */
    public function getShippingTax() 
    {
        return $this->_fields['ShippingTax']['FieldValue'];
    }

    /**
     * Sets the value of the ShippingTax.
     * 
     * @param Money ShippingTax
     * @return void
     */
    public function setShippingTax($value) 
    {
        $this->_fields['ShippingTax']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ShippingTax  and returns this instance
     * 
     * @param Money $value ShippingTax
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withShippingTax($value)
    {
        $this->setShippingTax($value);
        return $this;
    }


    /**
     * Checks if ShippingTax  is set
     * 
     * @return bool true if ShippingTax property is set
     */
    public function isSetShippingTax()
    {
        return !is_null($this->_fields['ShippingTax']['FieldValue']);

    }

    /**
     * Gets the value of the GiftWrapTax.
     * 
     * @return Money GiftWrapTax
     */
    public function getGiftWrapTax() 
    {
        return $this->_fields['GiftWrapTax']['FieldValue'];
    }

    /**
     * Sets the value of the GiftWrapTax.
     * 
     * @param Money GiftWrapTax
     * @return void
     */
    public function setGiftWrapTax($value) 
    {
        $this->_fields['GiftWrapTax']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the GiftWrapTax  and returns this instance
     * 
     * @param Money $value GiftWrapTax
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withGiftWrapTax($value)
    {
        $this->setGiftWrapTax($value);
        return $this;
    }


    /**
     * Checks if GiftWrapTax  is set
     * 
     * @return bool true if GiftWrapTax property is set
     */
    public function isSetGiftWrapTax()
    {
        return !is_null($this->_fields['GiftWrapTax']['FieldValue']);

    }

    /**
     * Gets the value of the ShippingDiscount.
     * 
     * @return Money ShippingDiscount
     */
    public function getShippingDiscount() 
    {
        return $this->_fields['ShippingDiscount']['FieldValue'];
    }

    /**
     * Sets the value of the ShippingDiscount.
     * 
     * @param Money ShippingDiscount
     * @return void
     */
    public function setShippingDiscount($value) 
    {
        $this->_fields['ShippingDiscount']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ShippingDiscount  and returns this instance
     * 
     * @param Money $value ShippingDiscount
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withShippingDiscount($value)
    {
        $this->setShippingDiscount($value);
        return $this;
    }


    /**
     * Checks if ShippingDiscount  is set
     * 
     * @return bool true if ShippingDiscount property is set
     */
    public function isSetShippingDiscount()
    {
        return !is_null($this->_fields['ShippingDiscount']['FieldValue']);

    }

    /**
     * Gets the value of the PromotionDiscount.
     * 
     * @return Money PromotionDiscount
     */
    public function getPromotionDiscount() 
    {
        return $this->_fields['PromotionDiscount']['FieldValue'];
    }

    /**
     * Sets the value of the PromotionDiscount.
     * 
     * @param Money PromotionDiscount
     * @return void
     */
    public function setPromotionDiscount($value) 
    {
        $this->_fields['PromotionDiscount']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the PromotionDiscount  and returns this instance
     * 
     * @param Money $value PromotionDiscount
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withPromotionDiscount($value)
    {
        $this->setPromotionDiscount($value);
        return $this;
    }


    /**
     * Checks if PromotionDiscount  is set
     * 
     * @return bool true if PromotionDiscount property is set
     */
    public function isSetPromotionDiscount()
    {
        return !is_null($this->_fields['PromotionDiscount']['FieldValue']);

    }

    /**
     * Gets the value of the PromotionIds.
     * 
     * @return PromotionIdList PromotionIds
     */
    public function getPromotionIds() 
    {
        return $this->_fields['PromotionIds']['FieldValue'];
    }

    /**
     * Sets the value of the PromotionIds.
     * 
     * @param PromotionIdList PromotionIds
     * @return void
     */
    public function setPromotionIds($value) 
    {
        $this->_fields['PromotionIds']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the PromotionIds  and returns this instance
     * 
     * @param PromotionIdList $value PromotionIds
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withPromotionIds($value)
    {
        $this->setPromotionIds($value);
        return $this;
    }


    /**
     * Checks if PromotionIds  is set
     * 
     * @return bool true if PromotionIds property is set
     */
    public function isSetPromotionIds()
    {
        return !is_null($this->_fields['PromotionIds']['FieldValue']);

    }

    /**
     * Gets the value of the CODFee.
     * 
     * @return Money CODFee
     */
    public function getCODFee() 
    {
        return $this->_fields['CODFee']['FieldValue'];
    }

    /**
     * Sets the value of the CODFee.
     * 
     * @param Money CODFee
     * @return void
     */
    public function setCODFee($value) 
    {
        $this->_fields['CODFee']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the CODFee  and returns this instance
     * 
     * @param Money $value CODFee
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withCODFee($value)
    {
        $this->setCODFee($value);
        return $this;
    }


    /**
     * Checks if CODFee  is set
     * 
     * @return bool true if CODFee property is set
     */
    public function isSetCODFee()
    {
        return !is_null($this->_fields['CODFee']['FieldValue']);

    }

    /**
     * Gets the value of the CODFeeDiscount.
     * 
     * @return Money CODFeeDiscount
     */
    public function getCODFeeDiscount() 
    {
        return $this->_fields['CODFeeDiscount']['FieldValue'];
    }

    /**
     * Sets the value of the CODFeeDiscount.
     * 
     * @param Money CODFeeDiscount
     * @return void
     */
    public function setCODFeeDiscount($value) 
    {
        $this->_fields['CODFeeDiscount']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the CODFeeDiscount  and returns this instance
     * 
     * @param Money $value CODFeeDiscount
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withCODFeeDiscount($value)
    {
        $this->setCODFeeDiscount($value);
        return $this;
    }


    /**
     * Checks if CODFeeDiscount  is set
     * 
     * @return bool true if CODFeeDiscount property is set
     */
    public function isSetCODFeeDiscount()
    {
        return !is_null($this->_fields['CODFeeDiscount']['FieldValue']);

    }

    /**
     * Gets the value of the GiftMessageText property.
     * 
     * @return string GiftMessageText
     */
    public function getGiftMessageText() 
    {
        return $this->_fields['GiftMessageText']['FieldValue'];
    }

    /**
     * Sets the value of the GiftMessageText property.
     * 
     * @param string GiftMessageText
     * @return this instance
     */
    public function setGiftMessageText($value) 
    {
        $this->_fields['GiftMessageText']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the GiftMessageText and returns this instance
     * 
     * @param string $value GiftMessageText
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withGiftMessageText($value)
    {
        $this->setGiftMessageText($value);
        return $this;
    }


    /**
     * Checks if GiftMessageText is set
     * 
     * @return bool true if GiftMessageText  is set
     */
    public function isSetGiftMessageText()
    {
        return !is_null($this->_fields['GiftMessageText']['FieldValue']);
    }

    /**
     * Gets the value of the GiftWrapLevel property.
     * 
     * @return string GiftWrapLevel
     */
    public function getGiftWrapLevel() 
    {
        return $this->_fields['GiftWrapLevel']['FieldValue'];
    }

    /**
     * Sets the value of the GiftWrapLevel property.
     * 
     * @param string GiftWrapLevel
     * @return this instance
     */
    public function setGiftWrapLevel($value) 
    {
        $this->_fields['GiftWrapLevel']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the GiftWrapLevel and returns this instance
     * 
     * @param string $value GiftWrapLevel
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withGiftWrapLevel($value)
    {
        $this->setGiftWrapLevel($value);
        return $this;
    }


    /**
     * Checks if GiftWrapLevel is set
     * 
     * @return bool true if GiftWrapLevel  is set
     */
    public function isSetGiftWrapLevel()
    {
        return !is_null($this->_fields['GiftWrapLevel']['FieldValue']);
    }

    /**
     * Gets the value of the InvoiceData.
     * 
     * @return InvoiceData InvoiceData
     */
    public function getInvoiceData() 
    {
        return $this->_fields['InvoiceData']['FieldValue'];
    }

    /**
     * Sets the value of the InvoiceData.
     * 
     * @param InvoiceData InvoiceData
     * @return void
     */
    public function setInvoiceData($value) 
    {
        $this->_fields['InvoiceData']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the InvoiceData  and returns this instance
     * 
     * @param InvoiceData $value InvoiceData
     * @return MarketplaceWebServiceOrders_Model_OrderItem instance
     */
    public function withInvoiceData($value)
    {
        $this->setInvoiceData($value);
        return $this;
    }


    /**
     * Checks if InvoiceData  is set
     * 
     * @return bool true if InvoiceData property is set
     */
    public function isSetInvoiceData()
    {
        return !is_null($this->_fields['InvoiceData']['FieldValue']);

    }




}