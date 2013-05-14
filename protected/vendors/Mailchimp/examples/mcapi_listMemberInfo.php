<?php
/**
This Example shows how to pull the Info for a Member of a List using the MCAPI.php 
class and do some basic error checking.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$retval = $api->listMemberInfo( $listId, array($my_email, $boss_man_email) );

if ($api->errorCode){
	echo "Unable to load listMemberInfo()!\n";
	echo "\tCode=".$api->errorCode."\n";
	echo "\tMsg=".$api->errorMessage."\n";
} else {
	echo "Success:".$retval['success']."\n";
	echo "Errors:".sizeof($retval['error'])."\n";
    //below is stupid code specific to what is returned
    //Don't actually do something like this.
    $i = 0;
    foreach($retval['data'] as $k=>$v){
        echo 'Member #'.(++$i)."\n";
        if (is_array($v)){
            //handle the merges
            foreach($v as $l=>$w){
                if (is_array($w)){
                    echo "\t$l:\n";
                    foreach($w as $m=>$x){
                        echo "\t\t$m = $x\n";
                    }
                } else {
                    echo "\t$l = $w\n";
                }
            }
        } else {
            echo "$k = $v\n";
        }
    }
}

?>
