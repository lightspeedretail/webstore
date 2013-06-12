<?php
/**
This Example shows how to pull the Info for a Member of a List using the MCAPI.php 
class and do some basic error checking.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$retval = $api->listUnsubscribe( $listId,$my_email);
if ($api->errorCode){
    echo "Unable to load listUnsubscribe()!\n";
	echo "\tCode=".$api->errorCode."\n";
	echo "\tMsg=".$api->errorMessage."\n";
} else {
    echo "Returned: ".$retval."\n";
}

?>
