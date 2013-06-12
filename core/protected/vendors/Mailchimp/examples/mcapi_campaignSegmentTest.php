<?php
/**
This Example shows how to test a List Segment for use with a new campaign 
via the MCAPI class.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$conditions = array();
$conditions[] = array('field'=>'email', 'op'=>'like', 'value'=>'mailchimp');
$opts = array('match'=>'all', 'conditions'=>$conditions);

$retval = $api->campaignSegmentTest($listId, $opts );

if ($api->errorCode){
	echo "Unable to Segment Campaign!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
	echo "Your Segement matched [".$retval."] members.\n";
}

?>
