<?php

// Include the MailChimp API code.  Do Not Edit This!
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$reports = $api->listAbuseReports($listId);

if ($api->errorMessage!=''){
	echo "Unable to run listAbuseReports()!\n";
	echo "\tCode=".$api->errorCode."\n";
	echo "\tMsg=".$api->errorMessage."\n";
} else {
    foreach($reports as $rpt){
        echo $rpt['date']." - ".$rpt['email']." - ";
        echo $rpt['campaign_id']." - ".$rpt['type']."\n";   
    }
}

?> 
