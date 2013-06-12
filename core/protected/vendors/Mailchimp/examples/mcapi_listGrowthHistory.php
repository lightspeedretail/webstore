<?php

// Include the MailChimp API code.  Do Not Edit This!
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$history = $api->listGrowthHistory($listId);

if ($api->errorCode){
	echo "Unable to run listGrowthHistory()!\n\tCode=".$api->errorCode."\n\tMsg=".$api->errorMessage."\n";
} else {
    foreach($history as $h){
        echo $h['month']."\n";
        echo "\tExisting=".$h['existing']."\n";
        echo "\tImports=".$h['imports']."\n";
        echo "\tOptins=".$h['optins']."\n";
    }

}

?> 

