<?php
/**
This Example shows how to pull a list of your Campaign Folders via the MCAPI class.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$retval = $api->folders();

if ($api->errorCode){
	echo "Unable to Pull List of Folders or You have not created any!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
    if (sizeof($retval)==0){
        echo "No Folders found!\n";
    } else {
	    echo "Your Folders:\n";
        foreach($retval as $folder){
            echo $folder['folder_id'].' => '.$folder['name']."\n";
        }
    }
}

?>
