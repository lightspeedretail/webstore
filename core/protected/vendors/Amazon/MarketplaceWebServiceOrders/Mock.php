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
 *  @see MarketplaceWebServiceOrders_Interface
 */
require_once ('MarketplaceWebServiceOrders/Interface.php'); 

/**
 * This contains the Order Retrieval API section of the Marketplace Web Service.
 * 
 */
class  MarketplaceWebServiceOrders_Mock implements MarketplaceWebServiceOrders_Interface
{
    // Public API ------------------------------------------------------------//

            
    /**
     * List Orders By Next Token 
     * If ListOrders returns a nextToken, thus indicating that there are more orders
     * than returned that matched the given filter criteria, ListOrdersByNextToken
     * can be used to retrieve those other orders using that nextToken.
     *   
     * @see http://docs.amazonwebservices.com/${docPath}ListOrdersByNextToken.html      
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_ListOrdersByNextToken request or MarketplaceWebServiceOrders_Model_ListOrdersByNextToken object itself
     * @see MarketplaceWebServiceOrders_Model_ListOrdersByNextToken
     * @return MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function listOrdersByNextToken($request) 
    {
        require_once ('MarketplaceWebServiceOrders/Model/ListOrdersByNextTokenResponse.php');
        return MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenResponse::fromXML($this->_invoke('ListOrdersByNextToken'));
    }


            
    /**
     * List Order Items By Next Token 
     * If ListOrderItems cannot return all the order items in one go, it will
     * provide a nextToken. That nextToken can be used with this operation to
     * retrive the next batch of items for that order.
     *   
     * @see http://docs.amazonwebservices.com/${docPath}ListOrderItemsByNextToken.html      
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_ListOrderItemsByNextToken request or MarketplaceWebServiceOrders_Model_ListOrderItemsByNextToken object itself
     * @see MarketplaceWebServiceOrders_Model_ListOrderItemsByNextToken
     * @return MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenResponse MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function listOrderItemsByNextToken($request) 
    {
        require_once ('MarketplaceWebServiceOrders/Model/ListOrderItemsByNextTokenResponse.php');
        return MarketplaceWebServiceOrders_Model_ListOrderItemsByNextTokenResponse::fromXML($this->_invoke('ListOrderItemsByNextToken'));
    }


            
    /**
     * Get Order 
     * This operation takes up to 50 order ids and returns the corresponding orders.
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetOrder.html      
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_GetOrder request or MarketplaceWebServiceOrders_Model_GetOrder object itself
     * @see MarketplaceWebServiceOrders_Model_GetOrder
     * @return MarketplaceWebServiceOrders_Model_GetOrderResponse MarketplaceWebServiceOrders_Model_GetOrderResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function getOrder($request) 
    {
        require_once ('MarketplaceWebServiceOrders/Model/GetOrderResponse.php');
        return MarketplaceWebServiceOrders_Model_GetOrderResponse::fromXML($this->_invoke('GetOrder'));
    }


            
    /**
     * List Order Items 
     * This operation can be used to list the items of the order indicated by the
     * given order id (only a single Amazon order id is allowed).
     *   
     * @see http://docs.amazonwebservices.com/${docPath}ListOrderItems.html      
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_ListOrderItems request or MarketplaceWebServiceOrders_Model_ListOrderItems object itself
     * @see MarketplaceWebServiceOrders_Model_ListOrderItems
     * @return MarketplaceWebServiceOrders_Model_ListOrderItemsResponse MarketplaceWebServiceOrders_Model_ListOrderItemsResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function listOrderItems($request) 
    {
        require_once ('MarketplaceWebServiceOrders/Model/ListOrderItemsResponse.php');
        return MarketplaceWebServiceOrders_Model_ListOrderItemsResponse::fromXML($this->_invoke('ListOrderItems'));
    }


            
    /**
     * List Orders 
     * ListOrders can be used to find orders that meet the specified criteria.
     *   
     * @see http://docs.amazonwebservices.com/${docPath}ListOrders.html      
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_ListOrders request or MarketplaceWebServiceOrders_Model_ListOrders object itself
     * @see MarketplaceWebServiceOrders_Model_ListOrders
     * @return MarketplaceWebServiceOrders_Model_ListOrdersResponse MarketplaceWebServiceOrders_Model_ListOrdersResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function listOrders($request) 
    {
        require_once ('MarketplaceWebServiceOrders/Model/ListOrdersResponse.php');
        return MarketplaceWebServiceOrders_Model_ListOrdersResponse::fromXML($this->_invoke('ListOrders'));
    }


            
    /**
     * Get Service Status 
     * Returns the service status of a particular MWS API section. The operation
     * takes no input.
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetServiceStatus.html      
     * @param mixed $request array of parameters for MarketplaceWebServiceOrders_Model_GetServiceStatus request or MarketplaceWebServiceOrders_Model_GetServiceStatus object itself
     * @see MarketplaceWebServiceOrders_Model_GetServiceStatus
     * @return MarketplaceWebServiceOrders_Model_GetServiceStatusResponse MarketplaceWebServiceOrders_Model_GetServiceStatusResponse
     *
     * @throws MarketplaceWebServiceOrders_Exception
     */
    public function getServiceStatus($request) 
    {
        require_once ('MarketplaceWebServiceOrders/Model/GetServiceStatusResponse.php');
        return MarketplaceWebServiceOrders_Model_GetServiceStatusResponse::fromXML($this->_invoke('GetServiceStatus'));
    }

    // Private API ------------------------------------------------------------//

    private function _invoke($actionName)
    {
        return $xml = file_get_contents('MarketplaceWebServiceOrders/Mock/' . $actionName . 'Response.xml', /** search include path */ TRUE);
    }
}