<?php
/**
 * 
 * Starplugins Cloudzoom
 * http://www.starplugins.com
 *
 * Package license purchased by Lightspeed Retail for distribution purposes
 * 
 */
class cloudzoom extends CWidget
{

	public $images=array();
	public $instructions = "Hover over image to zoom";
	public $imageFolder='images';
	public $zoomClass = "cloudzoom";
	public $zoomSizeMode = "lens";
	public $zoomPosition = 3;
	public $zoomFlyOut=true;
	public $zoomOffsetX=0;
	public $zoomOffsetY=0;
	public $autoInside=665;
	public $touchStartDelay=100;

	public $css_target='targetarea';
	public $css_thumbs = "thumbs";

    public function init()
    {
        parent::init();
    }
    
    public function run()
    {
		//creating clientScript instance 
	    $clientScript = Yii::app()->clientScript;
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        $baseurl = Yii::app()->getAssetManager()->publish($dir . 'assets');
        $js_options = array();
        $assets=$dir.'assets';

        if(is_dir($assets))
        {
            $clientScript->registerCssFile($baseurl.'/cloudzoom.css');
	        $clientScript->registerScriptFile($baseurl.'/cloudzoom.js',CClientScript::POS_HEAD);

        }
        else
            throw new Exception(get_class($this).' error: Couldn\'t publish assets.');

	    echo $this->buildInstructions();
	    echo $this->buildImages();

	    $jsCode = <<<SETUP
function bindZoom() {
        CloudZoom.quickStart();
        

SETUP;



	    $jsCode .= <<<BINDING
}

bindZoom();
BINDING;

	    //> register jsCode
	    $clientScript->registerScript(get_class($this), $jsCode, CClientScript::POS_READY);



    }

	/*
	 * If the original image is bigger than our detail size, show the instruction
	 */
	public function buildInstructions()
	{

		echo $this->instructions;
	}

	
    public function buildImages()
	{


		$html='<div class="'.$this->css_target.'">';

		$options = array(
			'encode'=>false,
			'class'=>$this->zoomClass,
			'id'=>'zoomPrimary',
			'data-cloudzoom'=>'zoomImage: \''.$this->images[0]['image_large'].'\',
				zoomSizeMode: \''.$this->zoomSizeMode.'\',
				zoomOffsetX: '.$this->zoomOffsetX.',
				zoomOffsetY: '.$this->zoomOffsetY.',
				zoomPosition: \''.$this->zoomPosition.'\',
				autoInside: \''.$this->autoInside.'\',
				touchStartDelay: \''.$this->touchStartDelay.'\',
				zoomFlyOut: '.$this->zoomFlyOut);
		$html .= CHtml::image($this->images[0]['image'],$this->images[0]['image_alt'],$options);
		$html .= "</div>";

		if(count($this->images)>1)
			$html .= $this->buildAdditionalImages();

		return $html;


	}

	public function buildAdditionalImages()
	{
		$html = '<div class="'.$this->css_thumbs.'">';
		foreach($this->images as $image)
		{
			
			$options = array(
				'encode'=>false,
				'class'=>'cloudzoom-gallery',
				'data-cloudzoom'=>'useZoom: \'#zoomPrimary\',
					image: \''.$image['image'].'\',zoomImage: \''.$image['image_large'].'\''
			);
			$html .= CHtml::link(CHtml::image($image['image_thumb'],$image['image_alt']),'#',$options);

		}
		$html .= "</div>";

		return $html;

	}

}
