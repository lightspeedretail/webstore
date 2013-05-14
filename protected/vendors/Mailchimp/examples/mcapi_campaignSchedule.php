<?php
/**
This Example shows how to schedule a campaign for future delivery
via the MCAPI class.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$schedule_for = '2018-04-01 09:05:21';
$retval = $api->campaignSchedule($campaignId, $schedule_for);

if ($api->errorCode){
	echo "Unable to Schedule Campaign!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
	echo "Campaign Scheduled to be delivered $schedule_for!\n";
}

?>
