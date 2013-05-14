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
 * MarketplaceWebServiceOrders_Model_PromotionIdList
 * 
 * Properties:
 * <ul>
 * 
 * <li>PromotionId: string</li>
 *
 * </ul>
 */ 
class MarketplaceWebServiceOrders_Model_PromotionIdList extends MarketplaceWebServiceOrders_Model
{

    /**
     * Construct new MarketplaceWebServiceOrders_Model_PromotionIdList
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>PromotionId: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'PromotionId' => array('FieldValue' => array(), 'FieldType' => array('string')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the PromotionId .
     * 
     * @return array of string PromotionId
     */
    public function getPromotionId() 
    {
        return $this->_fields['PromotionId']['FieldValue'];
    }

    /**
     * Sets the value of the PromotionId.
     * 
     * @param string or an array of string PromotionId
     * @return this instance
     */
    public function setPromotionId($promotionId) 
    {
        if (!$this->_isNumericArray($promotionId)) {
            $promotionId =  array ($promotionId);    
        }
        $this->_fields['PromotionId']['FieldValue'] = $promotionId;
        return $this;
    }
  

    /**
     * Sets single or multiple values of PromotionId list via variable number of arguments. 
     * For example, to set the list with two elements, simply pass two values as arguments to this function
     * <code>withPromotionId($promotionId1, $promotionId2)</code>
     * 
     * @param string  $stringArgs one or more PromotionId
     * @return MarketplaceWebServiceOrders_Model_PromotionIdList  instance
     */
    public function withPromotionId($stringArgs)
    {
        foreach (func_get_args() as $promotionId) {
            $this->_fields['PromotionId']['FieldValue'][] = $promotionId;
        }
        return $this;
    }  
      

    /**
     * Checks if PromotionId list is non-empty
     * 
     * @return bool true if PromotionId list is non-empty
     */
    public function isSetPromotionId()
    {
        return count ($this->_fields['PromotionId']['FieldValue']) > 0;
    }




}