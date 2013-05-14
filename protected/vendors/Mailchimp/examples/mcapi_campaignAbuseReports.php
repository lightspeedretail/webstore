<?php
/**
This Example shows how to add grab a full set of Campaign Abuse Reports wtih 
some basic error checking.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

// Connect to the MailChimp api with an API Key
$api = new MCAPI($apikey);

$reports = $api->campaignAbuseReports($campaignId);

if ($api->errorCode){
	echo "Unable to run campaignAbuseReports()!\n";
	echo "\tCode=".$api->errorCode."\n";
	echo "\tMsg=".$api->errorMessage."\n";
} else {
	echo "Total reports:".$reports['total']."\n";
	echo "Reports returned:".sizeof($reports['data'])."\n";
	foreach ($reports['data'] as $rpt){
        echo $rpt['date']." - ".$rpt['email']." - ".$rpt['type']."\n";
    }
}

?>
