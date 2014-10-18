<?php

require "vendor/autoload.php";
require "compass.inc.php";

$scss = new scssc();
new scss_compass($scss);

echo $scss->compile('
	@import "compass";
	
	div {
		@include box-shadow(10px 10px 8px red);
	}
');

