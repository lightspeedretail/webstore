<?php
/** 
 *  PHP Version 5
 *
 *  @category    Amazon
 *  @package     MarketplaceWebService
 *  @copyright   Copyright 2009 Amazon Technologies, Inc.
 *  @link        http://aws.amazon.com
 *  @license     http://aws.amazon.com/apache2.0  Apache License, Version 2.0
 *  @version     2009-01-01
 */
/******************************************************************************* 

 *  Marketplace Web Service PHP5 Library
 *  Generated: Thu May 07 13:07:36 PDT 2009
 * 
 */

/**
 * Get Report Schedule List  Sample
 */

include_once ('.config.inc.php'); 

/************************************************************************
* Uncomment to configure the client instance. Configuration settings
* are:
*
* - MWS endpoint URL
* - Proxy host and port.
* - MaxErrorRetry.
***********************************************************************/
// IMPORTANT: Uncomment the approiate line for the country you wish to
// sell in:
// United States:
//$serviceUrl = "https://mws.amazonservices.com";
// United Kingdom
//$serviceUrl = "https://mws.amazonservices.co.uk";
// Germany
//$serviceUrl = "https://mws.amazonservices.de";
// France
//$serviceUrl = "https://mws.amazonservices.fr";
// Italy
//$serviceUrl = "https://mws.amazonservices.it";
// Japan
//$serviceUrl = "https://mws.amazonservices.jp";
// China
//$serviceUrl = "https://mws.amazonservices.com.cn";
// Canada
//$serviceUrl = "https://mws.amazonservices.ca";
// India
//$serviceUrl = "https://mws.amazonservices.in";

$config = array (
  'ServiceURL' => $serviceUrl,
  'ProxyHost' => null,
  'ProxyPort' => -1,
  'MaxErrorRetry' => 3,
);

/************************************************************************
 * Instantiate Implementation of MarketplaceWebService
 * 
 * AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY constants 
 * are defined in the .config.inc.php located in the same 
 * directory as this sample
 ***********************************************************************/
 $service = new MarketplaceWebService_Client(
     AWS_ACCESS_KEY_ID, 
     AWS_SECRET_ACCESS_KEY, 
     $config,
     APPLICATION_NAME,
     APPLICATION_VERSION);
 
/************************************************************************
 * Uncomment to try out Mock Service that simulates MarketplaceWebService
 * responses without calling MarketplaceWebService service.
 *
 * Responses are loaded from local XML files. You can tweak XML files to
 * experiment with various outputs during development
 *
 * XML files available under MarketplaceWebService/Mock tree
 *
 ***********************************************************************/
 // $service = new MarketplaceWebService_Mock();

/************************************************************************
 * Setup request parameters and uncomment invoke to try out 
 * sample for Get Report Schedule List Action
 ***********************************************************************/
 // @TODO: set request. Action can be passed as MarketplaceWebService_Model_GetReportScheduleListRequest
 // object or array of parameters
 
//$parameters = array (
//  'Merchant' => MERCHANT_ID,
//  'ReportTypeList' => array ('Type' => array ('_GET_ORDERS_DATA_', '_GET_FLAT_FILE_ORDERS_DATA_')),
//);     
//
//$request = new MarketplaceWebService_Model_GetReportScheduleListRequest($parameters);

//$request = new MarketplaceWebService_Model_GetReportScheduleListRequest();
//$request->setMerchant(MERCHANT_ID);
//
//$typeList = new MarketplaceWebService_Model_TypeList();
//$request->setReportTypeList($typeList->withType('_GET_ORDERS_DATA_', '_GET_FLAT_FILE_ORDERS_DATA_'));
//
//invokeGetReportScheduleList($service, $request);

                                                                                                    
/**
  * Get Report Schedule List Action Sample
  * returns the list of report schedules
  *   
  * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
  * @param mixed $request MarketplaceWebService_Model_GetReportScheduleList or array of parameters
  */
  function invokeGetReportScheduleList(MarketplaceWebService_Interface $service, $request) 
  {
      try {
              $response = $service->getReportScheduleList($request);
              
                echo ("Service Response\n");
                echo ("=============================================================================\n");

                echo("        GetReportScheduleListResponse\n");
                if ($response->isSetGetReportScheduleListResult()) { 
                    echo("            GetReportScheduleListResult\n");
                    $getReportScheduleListResult = $response->getGetReportScheduleListResult();
                    if ($getReportScheduleListResult->isSetNextToken()) 
                    {
                        echo("                NextToken\n");
                        echo("                    " . $getReportScheduleListResult->getNextToken() . "\n");
                    }
                    if ($getReportScheduleListResult->isSetHasNext()) 
                    {
                        echo("                HasNext\n");
                        echo("                    " . $getReportScheduleListResult->getHasNext() . "\n");
                    }
                    $reportScheduleList = $getReportScheduleListResult->getReportSchedule();
                    foreach ($reportScheduleList as $reportSchedule) {
                        echo("                ReportSchedule\n");
                        if ($reportSchedule->isSetReportType()) 
                        {
                            echo("                    ReportType\n");
                            echo("                        " . $reportSchedule->getReportType() . "\n");
                        }
                        if ($reportSchedule->isSetSchedule()) 
                        {
                            echo("                    Schedule\n");
                            echo("                        " . $reportSchedule->getSchedule() . "\n");
                        }
                        if ($reportSchedule->isSetScheduledDate()) 
                        {
                            echo("                    ScheduledDate\n");
                            echo("                        " . $reportSchedule->getScheduledDate()->format(DATE_FORMAT) . "\n");
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
     } catch (MarketplaceWebService_Exception $ex) {
         echo("Caught Exception: " . $ex->getMessage() . "\n");
         echo("Response Status Code: " . $ex->getStatusCode() . "\n");
         echo("Error Code: " . $ex->getErrorCode() . "\n");
         echo("Error Type: " . $ex->getErrorType() . "\n");
         echo("Request ID: " . $ex->getRequestId() . "\n");
         echo("XML: " . $ex->getXML() . "\n");
         echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
     }
 }
    
