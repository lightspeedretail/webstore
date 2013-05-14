<?php
/**
This Example shows how to retrieve email domain performance for a campaign via
the API and do some basic error checking.
**/
// Include the MailChimp API code.  Do Not Edit This!
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$domains = $api->campaignEmailDomainPerformance($campaignId);

if ($api->errorCode){
	echo "Unable to run campaignEmailDomainPerformance()!\n";
	echo "\tCode=".$api->errorCode."\n";
	echo "\tMsg=".$api->errorMessage."\n";
} else {
    if (sizeof($domains)==0){
        echo "No Email Domain stats yet!\n";
    } else {
        foreach($domains as $domain){
            echo $domain['domain']."\n";
            echo "\tEmails: ".$domain['emails']."\n";
            echo "\tOpens: ".$domain['opens']."\n";
            echo "\tClicks: ".$domain['clicks']."\n";
        }
    }
}

?> 

