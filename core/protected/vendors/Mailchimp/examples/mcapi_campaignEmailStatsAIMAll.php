<?php
/**
This Example shows how to iterate through the AIM stats for every email address
associated with a campaign and do some basic error checking.
**/

// Include the MailChimp API code.  Do Not Edit This!
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$limit = 5;
for($i=0;$i<5;$i++){
    echo "====== Page $i : START ======\n";
    $allstats = $api->campaignEmailStatsAIMAll($campaignId, $i*$limit, $limit);
    if ($api->errorCode){
	    echo "Unable to run campaignEmailStatsAIMAll()";
	    echo "\n\tCode=".$api->errorCode;
	    echo "\n\tMsg=".$api->errorMessage."\n";
        exit;
    }
    if ($allstats['total']==0){
        echo "No more stats available!\n";
        exit;
    }
    foreach($allstats['data'] as $email=>$stats){
        echo "[".$email."]\n";
        foreach($stats as $stat){
            echo "\t".$stat['action']." @ ".$stat['timestamp'];
            if ($stat['action']=='click'){
                echo "\n\tURL =  ".$stat['url']."\n";
            } else {
                echo "\n";
            }
        }
    }
    echo "====== Page $i : END   ======\n";
}

?>
