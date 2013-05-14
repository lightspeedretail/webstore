<?php
/**
This Example shows how to run a Batch Unsubscribe on a List using the MCAPI.php 
class and do some basic error checking or handle the return values.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$emails = array($my_email, $boss_man_email);
$delete = false; //don't completely remove the emails
$bye = true; // yes, send a goodbye email
$notify = false; // no, don't tell me I did this

$vals = $api->listBatchUnsubscribe($listId, $emails, $delete, $bye, $notify);

if ($api->errorCode){
	// an api error occurred
	echo "code:".$api->errorCode."\n";
	echo "msg :".$api->errorMessage."\n";
} else {
	echo "success:".$vals['success_count']."\n";
	echo "errors:".$vals['error_count']."\n";
	foreach($vals['errors'] as $val){
		echo "\t*".$val['email']. " failed\n";
		echo "\tcode:".$val['code']."\n";
		echo "\tmsg :".$val['message']."\n\n";
	}
}
?> 


