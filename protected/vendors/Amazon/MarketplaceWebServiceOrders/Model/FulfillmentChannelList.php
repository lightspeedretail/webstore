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
 * MarketplaceWebServiceOrders_Model_FulfillmentChannelList
 * 
 * Properties:
 * <ul>
 * 
 * <li>Channel: FulfillmentChannelEnum</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_FulfillmentChannelList extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_FulfillmentChannelList
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>Channel: FulfillmentChannelEnum</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'Channel' => array('FieldValue' => array(), 'FieldType' => array('FulfillmentChannelEnum')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the Channel .
     * 
     * @return array of FulfillmentChannelEnum Channel
     */
    public function getChannel() 
    {
        return $this->_fields['Channel']['FieldValue'];
    }

    /**
     * Sets the value of the Channel.
     * 
     * @param FulfillmentChannelEnum or an array of FulfillmentChannelEnum Channel
     * @return this instance
     */
    public function setChannel($channel) 
    {
        if (!$this->_isNumericArray($channel)) {
            $channel =  array ($channel);    
        }
        $this->_fields['Channel']['FieldValue'] = $channel;
        return $this;
    }
  

    /**
     * Sets single or multiple values of Channel list via variable number of arguments. 
     * For example, to set the list with two elements, simply pass two values as arguments to this function
     * <code>withChannel($channel1, $channel2)</code>
     * 
     * @param FulfillmentChannelEnum  $fulfillmentChannelEnumArgs one or more Channel
     * @return MarketplaceWebServiceOrders_Model_FulfillmentChannelList  instance
     */
    public function withChannel($fulfillmentChannelEnumArgs)
    {
        foreach (func_get_args() as $channel) {
            $this->_fields['Channel']['FieldValue'][] = $channel;
        }
        return $this;
    }  
      

    /**
     * Checks if Channel list is non-empty
     * 
     * @return bool true if Channel list is non-empty
     */
    public function isSetChannel()
    {
        return count ($this->_fields['Channel']['FieldValue']) > 0;
    }




}