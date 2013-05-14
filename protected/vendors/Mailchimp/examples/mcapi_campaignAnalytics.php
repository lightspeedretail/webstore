<?php
/**
This Example shows how to retrieve Analytics data collected for a campaign with
some basic error checking.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$stats = $api->campaignAnalytics($campaignId);

if ($api->errorCode){
	echo "Unable to run campaignAnalytics()!\n";
	echo "\tCode=".$api->errorCode."\n";
	echo "\tMsg=".$api->errorMessage."\n";
} else {
    echo "Visits: ".$stat['visits']."\n";
    echo "Pages: ".$rpt['pages']."\n";
    echo "Goals ".$rpt['type']."\n";
    if ($stat['goals']){
        foreach($stat['goals'] as $goal){
            echo "\t".$goal['name']." => ".$goal['conversions']."\n";
        }
    }
}
?> 
