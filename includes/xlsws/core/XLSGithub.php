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


class Github {

	var $LatestTag;
	
	private function getJson($url){
	    $base = "https://api.github.com/";
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $base . $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    $content = curl_exec($curl);
	    curl_close($curl);
	    return $content;
	}
	
	private function getTags(){
	    // Get the name of the repo that we'll use in the request url
	    return json_decode($this->getJson("repos/lightspeedretail/webstore/tags"),true);
	} 
	
	public function getLatestRelease() {
		$arrGitTags = $this->getTags();
		foreach($arrGitTags as $arrTag)
			$tag[] = $arrTag['name'];
		rsort($tag);
	
		$this->LatestTag=$tag[0];
		return $this->LatestTag;
	}
	
	
}