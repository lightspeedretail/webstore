<?php
/**
This Example shows how to update a List Member's information using the MCAPI.php 
class and do some basic error checking.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$merge_vars = array("FNAME"=>'Richard', "LNAME"=>'Wright');

$retval = $api->listUpdateMember($listId, $my_email, $merge_vars, 'html', false);

if ($api->errorCode){
	echo "Unable to update member info!\n";
	echo "\tCode=".$api->errorCode."\n";
	echo "\tMsg=".$api->errorMessage."\n";
} else {    
	echo "Returned: ".$retval."\n";
}

?>
