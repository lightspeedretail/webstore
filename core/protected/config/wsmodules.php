<?php

return searchForModules();

/**
 * Dynamically load any modules in our modules folder
 * @return array
 */
function searchForModules()
{

	$arr = array();
	foreach (glob(dirname(__FILE__).'/../modules/*', GLOB_ONLYDIR) as $moduleDirectory)
		$arr[] = basename($moduleDirectory);

	return $arr;

}

