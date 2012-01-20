<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/** THIS SCRIPT IS USED BY THE LIGHTSPEED SOFTWARE TO POST IMAGES FROM THE POS TO THE WEB SERVER, DO NOT ALTER **/

require_once('xls_ws_service.php');

$Importer = new XLSWService('XLSWService', '');
$PassKey = null;

function sendAuthRequest($msg='') {
	if (!$msg)
		$msg =  'Must be authenticated to access this functionality';

	header('WWW-Authenticate: Basic realm="WS Image Uploads"');
	header('HTTP/1.0 401 Unauthorized');
	echo $msg;
	exit(0);
}


function checkAuthentication() {
	global $Importer;
	global $PassKey;

	$PassKey = $_SERVER['HTTP_PASSKEY'];

	return true;
}

function errorInParams($msg) {
	header('HTTP/1.0 422 Unprocessable Entity');
	echo $msg;

	exit(0);
}

function errorInImport($msg, $errCode) {
	header('HTTP/1.0 400 Bad Request');
	echo $msg;
	echo $errCode;

	exit(0);
}

function successResponse($msg='Success!') {
	header('HTTP/1.0 200 OK');
	header('Content-type: text/plain');
	echo $msg;

	exit(0);
}

function errorConflict($msg, $errCode) {
	header('HTTP/1.0 409 Conflict');
	echo $msg;
	echo $errCode;
	exit(0);
}

function getDestination() {
	if (!(isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO']))
		return errorInParams('No path info details present');

	$matches = array();

	if (!preg_match('@/product/(\d+)/index/([0-5])/@',$_SERVER['PATH_INFO'], $matches))
		return errorInParams('Badly formed path:' . $_SERVER['PATH_INFO']);

	$pid = $matches[1];
	$idx = $matches[2];

	$destination = array(
		'product_id' => $pid,
		'image_index' => $idx
	);

	return $destination;
}

function getImageData() {
	$rawPostData = file_get_contents('php://input');

	if (empty($rawPostData)) {
		return errorInParams('No image data posted?');
	}

	return $rawPostData;
}

function handlePost() {
	global $Importer;
	global $PassKey;

	if (! checkAuthentication())
		die("In handlePost but not authenticated?");

	$destination = getDestination();

	if (! ($destination && is_array($destination)))
		die("Could not get destination of POST ?");

	$imageData = getImageData();
	if (! $imageData)
		die("Could not get image data?");

	if ($destination['image_index'] > 0) {
		$additionalImgIdx = $destination['image_index'] - 1;
		$resp = $Importer->add_additional_product_image_at_index($PassKey, $destination['product_id'], $imageData, $additionalImgIdx);
		if ($resp != XLSWService::OK) {
			return errorConflict(
				'Problem adding additional image ' . $destination['image_index'] . ' to product ' . $destination['product_id'],
				$resp
			);
		}

	} elseif ($destination['image_index'] == 0) {
		// save master product image
		$resp = $Importer->save_product_image($PassKey, $destination['product_id'], $imageData);

		if ($resp != XLSWService::OK)
			return errorConflict('Problem saving image for product ' . $destination['product_id'], $resp);

	} else {
		return errorInParams("Image index specified is neither > 0 nor == 0 ??");
	}

	unset($imageData);

	return successResponse("Image saved for product " . $destination['product_id']);
}

handlePost();

?>