<?php
/**
 * JBlueprintGrid class file.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @license BSD
 * @version 1.0
 * 
 */

/** 
 *
 * This widget create a toggle button to show or hide Blueprint’s built-in grid
 * in debug mode
 * ({@link http://www.shinytype.com/tools/blueprint-grid/).
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 */

class JBlueprintGrid extends CWidget
{

	/**
	 * Initializes the widget.
	 * This method registers all needed client scripts 
	 */
	public function init()
	{
      	$baseUrl = CHtml::asset(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets');
        $jsFile = '/js/blueprint-grid.js';
        $url = $baseUrl.'/css/blueprint-grid.css';

        if (YII_DEBUG)
            Yii::app()->getClientScript()
                ->registerCssFile($url)
                ->registerCoreScript('jquery')
                ->registerScriptFile($baseUrl.$jsFile);
	}
}