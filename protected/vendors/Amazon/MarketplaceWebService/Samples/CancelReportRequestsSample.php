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
 * Cancel Reports  Sample
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
 * sample for Cancel Reports Action
 ***********************************************************************/
 // @TODO: set request. Action can be passed as MarketplaceWebService_Model_CancelReportsRequest
 // object or array of parameters

// Request objects can be constructed with an array of parameters.
//$parameters = array (
//  'Merchant' => MERCHANT_ID,
//  'ReportRequestIdList' => array ( 'Id' => array ('4309077944', '4309077934')),
//);
//
//$request = new MarketplaceWebService_Model_CancelReportRequestsRequest($parameters);

//$request = new MarketplaceWebService_Model_CancelReportRequestsRequest();
//$request->setMerchant(MERCHANT_ID);
//
//$idList = new MarketplaceWebService_Model_IdList();
//$request->setReportRequestIdList($idList->withId('4309077944', '4309077934'));
//
//invokeCancelReportRequests($service, $request);
 
/**
  * Cancel Report Requests Action Sample
  * cancels report requests - by default all of the submissions of the
  * last 30 days that have not started processing
  *   
  * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
  * @param mixed $request MarketplaceWebService_Model_CancelFeedSubmissions or array of parameters
  */
  function invokeCancelReportRequests(MarketplaceWebService_Interface $service, $request) 
  {
      try {
              $response = $service->cancelReportRequests($request);
              
                echo ("Service Response\n");
                echo ("=============================================================================\n");

                echo("        CancelReportRequestsResponse\n");
                if ($response->isSetCancelReportRequestsResult()) { 
                    echo("            CancelReportRequestsResult\n");
                    $cancelReportRequestsResult = $response->getCancelReportRequestsResult();
                    if ($cancelReportRequestsResult->isSetCount()) 
                    {
                        echo("                Count\n");
                        echo("                    " . $cancelReportRequestsResult->getCount() . "\n");
                    }
                    $reportRequestInfoList = $cancelReportRequestsResult->getReportRequestInfoList();
                    foreach ($reportRequestInfoList as $reportRequestInfo) {
                        echo("                ReportRequestInfo\n");
                        if ($reportRequestInfo->isSetReportRequestId()) 
                        {
                            echo("                    ReportRequestId\n");
                            echo("                        " . $reportRequestInfo->getReportRequestId() . "\n");
                        }
                        if ($reportRequestInfo->isSetReportType()) 
                        {
                            echo("                    ReportType\n");
                            echo("                        " . $reportRequestInfo->getReportType() . "\n");
                        }
                        if ($reportRequestInfo->isSetStartDate()) 
                        {
                            echo("                    StartDate\n");
                            echo("                        " . $reportRequestInfo->getStartDate()->format(DATE_FORMAT) . "\n");
                        }
                        if ($reportRequestInfo->isSetEndDate()) 
                        {
                            echo("                    EndDate\n");
                            echo("                        " . $reportRequestInfo->getEndDate()->format(DATE_FORMAT) . "\n");
                        }
                        if ($reportRequestInfo->isSetSubmittedDate()) 
                        {
                            echo("                    SubmittedDate\n");
                            echo("                        " . $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT) . "\n");
                        }
                        if ($reportRequestInfo->isSetReportProcessingStatus()) 
                        {
                            echo("                    ReportProcessingStatus\n");
                            echo("                        " . $reportRequestInfo->getReportProcessingStatus() . "\n");
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
 
?>
