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
 * List Order Items By Next Token  Sample
 */

include_once ('.config.inc.php'); 

/************************************************************************
 * Instantiate Implementation of MarketplaceWebServiceOrders
 * 
 * AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY constants 
 * are defined in the .config.inc.php located in the same 
 * directory as this sample
 ***********************************************************************/
// United States:
//$serviceUrl = "https://mws.amazonservices.com/Orders/2011-01-01";
// Europe
//$serviceUrl = "https://mws-eu.amazonservices.com/Orders/2011-01-01";
// Japan
//$serviceUrl = "https://mws.amazonservices.jp/Orders/2011-01-01";
// China
//$serviceUrl = "https://mws.amazonservices.com.cn/Orders/2011-01-01";
// Canada
//$serviceUrl = "https://mws.amazonservices.ca/Orders/2011-01-01";

 $config = array (
   'ServiceURL' => $serviceUrl,
   'ProxyHost' => null,
   'ProxyPort' => -1,
   'MaxErrorRetry' => 3,
 );

 $service = new MarketplaceWebServiceOrders_Client(
        AWS_ACCESS_KEY_ID,
        AWS_SECRET_ACCESS_KEY,
        APPLICATION_NAME,
        APPLICATION_VERSION,
        $config);
 
 
 
/************************************************************************
 * Uncomment to try out Mock Service that simulates MarketplaceWebServiceOrders
 * responses without calling MarketplaceWebServiceOrders service.
 *
 * Responses are loaded from local XML files. You can tweak XML files to
 * experiment with various outputs during development
 *
 * XML files available under MarketplaceWebServiceOrders/Mock tree
 *
 ***********************************************************************/
 // $service = new MarketplaceWebServiceOrders_Mock();

/************************************************************************
 * Setup request parameters and uncomment invoke to try out 
 * sample for List Order Items By Next Token Action
 ***********************************************************************/
 // @TODO: set request. Action can be passed as MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenRequest
 $request = new MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenRequest();
 $request->setSellerId(MERCHANT_ID);
 // object or array of parameters
 invokeListOrderItemsByNextToken($service, $request);

                            
/**
  * List Order Items By Next Token Action Sample
  * If ListOrderItems cannot return all the order items in one go, it will
  * provide a nextToken. That nextToken can be used with this operation to
  * retrive the next batch of items for that order.
  *   
  * @param MarketplaceWebServiceOrders_Interface $service instance of MarketplaceWebServiceOrders_Interface
  * @param mixed $request MarketplaceWebServiceOrders_Model_ListOrderItemsByNextToken or array of parameters
  */
  function invokeListOrderItemsByNextToken(MarketplaceWebServiceOrders_Interface $service, $request) 
  {
      try {
              $response = $service->listOrderItemsByNextToken($request);
              
                echo ("Service Response\n");
                echo ("=============================================================================\n");

                echo("        ListOrderItemsByNextTokenResponse\n");
                if ($response->isSetListOrderItemsByNextTokenResult()) { 
                    echo("            ListOrderItemsByNextTokenResult\n");
                    $listOrderItemsByNextTokenResult = $response->getListOrderItemsByNextTokenResult();
                    if ($listOrderItemsByNextTokenResult->isSetNextToken()) 
                    {
                        echo("                NextToken\n");
                        echo("                    " . $listOrderItemsByNextTokenResult->getNextToken() . "\n");
                    }
                    if ($listOrderItemsByNextTokenResult->isSetAmazonOrderId()) 
                    {
                        echo("                AmazonOrderId\n");
                        echo("                    " . $listOrderItemsByNextTokenResult->getAmazonOrderId() . "\n");
                    }
                    if ($listOrderItemsByNextTokenResult->isSetOrderItems()) { 
                        echo("                OrderItems\n");
                        $orderItems = $listOrderItemsByNextTokenResult->getOrderItems();
                        $orderItemList = $orderItems->getOrderItem();
                        foreach ($orderItemList as $orderItem) {
                            echo("                    OrderItem\n");
                            if ($orderItem->isSetASIN()) 
                            {
                                echo("                        ASIN\n");
                                echo("                            " . $orderItem->getASIN() . "\n");
                            }
                            if ($orderItem->isSetSellerSKU()) 
                            {
                                echo("                        SellerSKU\n");
                                echo("                            " . $orderItem->getSellerSKU() . "\n");
                            }
                            if ($orderItem->isSetOrderItemId()) 
                            {
                                echo("                        OrderItemId\n");
                                echo("                            " . $orderItem->getOrderItemId() . "\n");
                            }
                            if ($orderItem->isSetTitle()) 
                            {
                                echo("                        Title\n");
                                echo("                            " . $orderItem->getTitle() . "\n");
                            }
                            if ($orderItem->isSetQuantityOrdered()) 
                            {
                                echo("                        QuantityOrdered\n");
                                echo("                            " . $orderItem->getQuantityOrdered() . "\n");
                            }
                            if ($orderItem->isSetQuantityShipped()) 
                            {
                                echo("                        QuantityShipped\n");
                                echo("                            " . $orderItem->getQuantityShipped() . "\n");
                            }
                            if ($orderItem->isSetItemPrice()) { 
                                echo("                        ItemPrice\n");
                                $itemPrice = $orderItem->getItemPrice();
                                if ($itemPrice->isSetCurrencyCode()) 
                                {
                                    echo("                            CurrencyCode\n");
                                    echo("                                " . $itemPrice->getCurrencyCode() . "\n");
                                }
                                if ($itemPrice->isSetAmount()) 
                                {
                                    echo("                            Amount\n");
                                    echo("                                " . $itemPrice->getAmount() . "\n");
                                }
                            } 
                            if ($orderItem->isSetShippingPrice()) { 
                                echo("                        ShippingPrice\n");
                                $shippingPrice = $orderItem->getShippingPrice();
                                if ($shippingPrice->isSetCurrencyCode()) 
                                {
                                    echo("                            CurrencyCode\n");
                                    echo("                                " . $shippingPrice->getCurrencyCode() . "\n");
                                }
                                if ($shippingPrice->isSetAmount()) 
                                {
                                    echo("                            Amount\n");
                                    echo("                                " . $shippingPrice->getAmount() . "\n");
                                }
                            } 
                            if ($orderItem->isSetGiftWrapPrice()) { 
                                echo("                        GiftWrapPrice\n");
                                $giftWrapPrice = $orderItem->getGiftWrapPrice();
                                if ($giftWrapPrice->isSetCurrencyCode()) 
                                {
                                    echo("                            CurrencyCode\n");
                                    echo("                                " . $giftWrapPrice->getCurrencyCode() . "\n");
                                }
                                if ($giftWrapPrice->isSetAmount()) 
                                {
                                    echo("                            Amount\n");
                                    echo("                                " . $giftWrapPrice->getAmount() . "\n");
                                }
                            } 
                            if ($orderItem->isSetItemTax()) { 
                                echo("                        ItemTax\n");
                                $itemTax = $orderItem->getItemTax();
                                if ($itemTax->isSetCurrencyCode()) 
                                {
                                    echo("                            CurrencyCode\n");
                                    echo("                                " . $itemTax->getCurrencyCode() . "\n");
                                }
                                if ($itemTax->isSetAmount()) 
                                {
                                    echo("                            Amount\n");
                                    echo("                                " . $itemTax->getAmount() . "\n");
                                }
                            } 
                            if ($orderItem->isSetShippingTax()) { 
                                echo("                        ShippingTax\n");
                                $shippingTax = $orderItem->getShippingTax();
                                if ($shippingTax->isSetCurrencyCode()) 
                                {
                                    echo("                            CurrencyCode\n");
                                    echo("                                " . $shippingTax->getCurrencyCode() . "\n");
                                }
                                if ($shippingTax->isSetAmount()) 
                                {
                                    echo("                            Amount\n");
                                    echo("                                " . $shippingTax->getAmount() . "\n");
                                }
                            } 
                            if ($orderItem->isSetGiftWrapTax()) { 
                                echo("                        GiftWrapTax\n");
                                $giftWrapTax = $orderItem->getGiftWrapTax();
                                if ($giftWrapTax->isSetCurrencyCode()) 
                                {
                                    echo("                            CurrencyCode\n");
                                    echo("                                " . $giftWrapTax->getCurrencyCode() . "\n");
                                }
                                if ($giftWrapTax->isSetAmount()) 
                                {
                                    echo("                            Amount\n");
                                    echo("                                " . $giftWrapTax->getAmount() . "\n");
                                }
                            } 
                            if ($orderItem->isSetShippingDiscount()) { 
                                echo("                        ShippingDiscount\n");
                                $shippingDiscount = $orderItem->getShippingDiscount();
                                if ($shippingDiscount->isSetCurrencyCode()) 
                                {
                                    echo("                            CurrencyCode\n");
                                    echo("                                " . $shippingDiscount->getCurrencyCode() . "\n");
                                }
                                if ($shippingDiscount->isSetAmount()) 
                                {
                                    echo("                            Amount\n");
                                    echo("                                " . $shippingDiscount->getAmount() . "\n");
                                }
                            } 
                            if ($orderItem->isSetPromotionDiscount()) { 
                                echo("                        PromotionDiscount\n");
                                $promotionDiscount = $orderItem->getPromotionDiscount();
                                if ($promotionDiscount->isSetCurrencyCode()) 
                                {
                                    echo("                            CurrencyCode\n");
                                    echo("                                " . $promotionDiscount->getCurrencyCode() . "\n");
                                }
                                if ($promotionDiscount->isSetAmount()) 
                                {
                                    echo("                            Amount\n");
                                    echo("                                " . $promotionDiscount->getAmount() . "\n");
                                }
                            } 
                            if ($orderItem->isSetPromotionIds()) { 
                                echo("                        PromotionIds\n");
                                $promotionIds = $orderItem->getPromotionIds();
                                $promotionIdList  =  $promotionIds->getPromotionId();
                                foreach ($promotionIdList as $promotionId) { 
                                    echo("                            PromotionId\n");
                                    echo("                                " . $promotionId);
                                }	
                            } 
                            if ($orderItem->isSetCODFee()) { 
                                echo("                        CODFee\n");
                                $CODFee = $orderItem->getCODFee();
                                if ($CODFee->isSetCurrencyCode()) 
                                {
                                    echo("                            CurrencyCode\n");
                                    echo("                                " . $CODFee->getCurrencyCode() . "\n");
                                }
                                if ($CODFee->isSetAmount()) 
                                {
                                    echo("                            Amount\n");
                                    echo("                                " . $CODFee->getAmount() . "\n");
                                }
                            } 
                            if ($orderItem->isSetCODFeeDiscount()) { 
                                echo("                        CODFeeDiscount\n");
                                $CODFeeDiscount = $orderItem->getCODFeeDiscount();
                                if ($CODFeeDiscount->isSetCurrencyCode()) 
                                {
                                    echo("                            CurrencyCode\n");
                                    echo("                                " . $CODFeeDiscount->getCurrencyCode() . "\n");
                                }
                                if ($CODFeeDiscount->isSetAmount()) 
                                {
                                    echo("                            Amount\n");
                                    echo("                                " . $CODFeeDiscount->getAmount() . "\n");
                                }
                            } 
                            if ($orderItem->isSetGiftMessageText()) 
                            {
                                echo("                        GiftMessageText\n");
                                echo("                            " . $orderItem->getGiftMessageText() . "\n");
                            }
                            if ($orderItem->isSetGiftWrapLevel()) 
                            {
                                echo("                        GiftWrapLevel\n");
                                echo("                            " . $orderItem->getGiftWrapLevel() . "\n");
                            }
                            if ($orderItem->isSetInvoiceData()) { 
                                echo("                        InvoiceData\n");
                                $invoiceData = $orderItem->getInvoiceData();
                                if ($invoiceData->isSetInvoiceRequirement()) 
                                {
                                    echo("                            InvoiceRequirement\n");
                                    echo("                                " . $invoiceData->getInvoiceRequirement() . "\n");
                                }
                                if ($invoiceData->isSetBuyerSelectedInvoiceCategory()) 
                                {
                                    echo("                            BuyerSelectedInvoiceCategory\n");
                                    echo("                                " . $invoiceData->getBuyerSelectedInvoiceCategory() . "\n");
                                }
                                if ($invoiceData->isSetInvoiceTitle()) 
                                {
                                    echo("                            InvoiceTitle\n");
                                    echo("                                " . $invoiceData->getInvoiceTitle() . "\n");
                                }
                                if ($invoiceData->isSetInvoiceInformation()) 
                                {
                                    echo("                            InvoiceInformation\n");
                                    echo("                                " . $invoiceData->getInvoiceInformation() . "\n");
                                }
                            } 
                        }
                    } 
                } 
                if ($response->isSetResponseMetadata()) { 
                    echo("            ResponseMetadata\n");
                    $responseMetadata = $response->getResponseMetadata();
                    if ($responseMetadata->isSetRequestId()) 
                    {
                        echo("                RequestId\n");
                        echo("                    " . $responseMetadata->getRequestId() . "\n");
                    }
                } 

              echo("            ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");
     } catch (MarketplaceWebServiceOrders_Exception $ex) {
         echo("Caught Exception: " . $ex->getMessage() . "\n");
         echo("Response Status Code: " . $ex->getStatusCode() . "\n");
         echo("Error Code: " . $ex->getErrorCode() . "\n");
         echo("Error Type: " . $ex->getErrorType() . "\n");
         echo("Request ID: " . $ex->getRequestId() . "\n");
         echo("XML: " . $ex->getXML() . "\n");
         echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
     }
 }
                    