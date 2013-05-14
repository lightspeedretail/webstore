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
 * This contains the Order Retrieval API section of the Marketplace Web Service.
 * 
 */

interface  MarketplaceWebServiceOrders_Interface 
{
    

            
    /**
     * List Orders By Next Token 
     * If ListOrders returns a nextToken, thus indicating that there are more orders
     * than returned that matched the given filter criteria, ListOrdersByNextToken
     * can be used to retrieve those other orders using that nextToken.
     *   
     * @see http://docs.amazonwebservices.com/${docPath}ListOrdersByNextToken.html      
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenRequest request
     * or MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenRequest object itself
     * @see MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenRequest
     * @return MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function listOrdersByNextToken($request);


            
    /**
     * List Order Items By Next Token 
     * If ListOrderItems cannot return all the order items in one go, it will
     * provide a nextToken. That nextToken can be used with this operation to
     * retrive the next batch of items for that order.
     *   
     * @see http://docs.amazonwebservices.com/${docPath}ListOrderItemsByNextToken.html      
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenRequest request
     * or MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenRequest object itself
     * @see MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenRequest
     * @return MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenResponse MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function listOrderItemsByNextToken($request);


            
    /**
     * Get Order 
     * This operation takes up to 50 order ids and returns the corresponding orders.
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetOrder.html      
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_GetOrderRequest request
     * or MarketplaceWebServiceOrders_Model_GetOrderRequest object itself
     * @see MarketplaceWebServiceOrders_Model_GetOrderRequest
     * @return MarketplaceWebServiceOrders_Model_GetOrderResponse MarketplaceWebServiceOrders_Model_GetOrderResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function getOrder($request);


            
    /**
     * List Order Items 
     * This operation can be used to list the items of the order indicated by the
     * given order id (only a single Amazon order id is allowed).
     *   
     * @see http://docs.amazonwebservices.com/${docPath}ListOrderItems.html      
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_ListOrderItemsRequest request
     * or MarketplaceWebServiceOrders_Model_ListOrderItemsRequest object itself
     * @see MarketplaceWebServiceOrders_Model_ListOrderItemsRequest
     * @return MarketplaceWebServiceOrders_Model_ListOrderItemsResponse MarketplaceWebServiceOrders_Model_ListOrderItemsResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function listOrderItems($request);


            
    /**
     * List Orders 
     * ListOrders can be used to find orders that meet the specified criteria.
     *   
     * @see http://docs.amazonwebservices.com/${docPath}ListOrders.html      
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_ListOrdersRequest request
     * or MarketplaceWebServiceOrders_Model_ListOrdersRequest object itself
     * @see MarketplaceWebServiceOrders_Model_ListOrdersRequest
     * @return MarketplaceWebServiceOrders_Model_ListOrdersResponse MarketplaceWebServiceOrders_Model_ListOrdersResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function listOrders($request);


            
    /**
     * Get Service Status 
     * Returns the service status of a particular MWS API section. The operation
     * takes no input.
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetServiceStatus.html      
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_GetServiceStatusRequest request
     * or MarketplaceWebServiceOrders_Model_GetServiceStatusRequest object itself
     * @see MarketplaceWebServiceOrders_Model_GetServiceStatusRequest
     * @return MarketplaceWebServiceOrders_Model_GetServiceStatusResponse MarketplaceWebServiceOrders_Model_GetServiceStatusResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function getServiceStatus($request);

}