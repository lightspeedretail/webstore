<?php
/**
This Example shows how to retrieve campaign Advice messages from the API with
some basic error checking.
**/

// Include the MailChimp API code.  Do Not Edit This!
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

// Connect to the MailChimp server with the user's credentials.
$api = new MCAPI($apikey);

$advice = $api->campaignAdvice($campaignId);

if ($api->errorCode){
	echo "Unable to run campaignAdvice()!\n";
	echo "\tCode=".$api->errorCode."\n";
	echo "\tMsg=".$api->errorMessage."\n";
} else {
    if (sizeof($advice)>0){
        foreach($advice as $adv){
            echo "Type: ".$adv['type']."\n";
            echo "Message: ".$adv['msg']."\n";
        }
    } else {
        echo "Sorry, no advice for this campaign!\n";
    }
}

?> 

