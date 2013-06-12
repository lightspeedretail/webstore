<?php
/**
This Example shows how to Update an A/B Split Campaign via the MCAPI class.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$field = "absplit";
$value = "My New Title";

$ab_opts = array();
$ab_opts['split_test'] = 'from_name';
$ab_opts['pick_winner'] = 'manual';
$ab_opts['from_name_a'] = 'David Gilmour';
$ab_opts['from_email_a'] = 'david@example.org';
$ab_opts['from_name_b'] = 'Roger Waters';
$ab_opts['from_email_b'] = 'roger@example.org';

$retval = $api->campaignUpdate($campaignId, $field, $ab_opts);

if ($api->errorCode){
	echo "Unable to Update Campaign!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
    echo "SUCCESS!\n";
}

?>
