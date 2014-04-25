<?php

// Rename this file to main.php to use here. Items below will be merged into the primary config/main.php
// Sample loading of different items has been shown below. Comment out what is not relevant.

return
	array(
		'controllerMap' => array(
			'product' => array(
				'class' => 'custom.controllers.MyProductController',
			),
		),

		'import' => array(
			'custom.mycompany.*',
		),

		'components' =>
			array(
				'widgetHandler' => array(
					//Load a component
					'class' => 'custom.mycompany.mywidget.mywidget',
				),

			)
);
