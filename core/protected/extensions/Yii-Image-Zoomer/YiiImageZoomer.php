<?php
/**
Copyright (c) 2013, GOGI

Permission is hereby granted, free of charge, to any person obtaining a copy of this software 
and associated documentation files (the "Software"), to deal in the Software without restriction, 
including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, 
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial 
portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT 
LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/**
 * 
 * YiiImageZoomer- The Yii-Image-Zoomer is a extension which consists of two type of image zoom.They are:
 *					1) Single Image Zoom: This type of zoom is used when you want to apply zoom effect on single image.
 *					2) Multi-image Zoom: This type of zoom is used when you want to apply zoom effect on Multiple images .
 *				    Yii-Image-Zoomer uses Featured Image Zoomer v2.1 script from http://www.dynamicdrive.com/dynamicindex4/featuredzoomer.htm
 *
 * @version 1.0-- First Release 
 * @author Gogi <gogi_25@oopeducation.com>. 2013
 * @copyright Gogi <gogi_25@oopeducation.com>. 2013
 * 
 */
class YiiImageZoomer extends CWidget
{
	
	// Below are the paramteres which can be customized according to your needs
	
    /** 
	* @var boolean $multiple_zoom - used to enable or disable MultiImage zoom
	* when set to 'true' will enable MultiImage zoom
	* when set to 'false' will disable MultiImage Zoom
	* @default : none
	*/
	public $multiple_zoom;
	
	/** 
	* @var string $imagefolder - used to specify the images folder path
	* @default : "images"
	*/
   	public $imagefolder='images';
    
	/** 
	* @var boolean $cursorshade - used to enable or disable cursor shade
	* when set to 'true' will enable cursor shade
	* when set to 'false' will disable the cursor shade
	* @default : true
	*/
	public $cursorshade=false;
	
	/** 
	* @var string $cursorshadecolor - used to specify the cursor shade colour
	* @default : "#fff" (white color)
	*/	
   	public $cursorshadecolor='#fff';
	
	/** 
	* @var decimal $cursorshadeopacity - used to specify the cursor shade opacity
	* minimum value = 0.1 which is almost transparent.
	* maximum value=1 which is fully opaque (as if no opacity is applied).
	* @default : 0.1 (almost transparent)
	*/
    
	public $cursorshadeopacity=0.1;
	
	/** 
	* @var string $cursorshadeoborder - used to specify the cursor shade border
	* @default: '1px solid black'
	*/    
	public $cursorshadeborder='1px solid black';
	
	/** 
	* @var boolean $imagevertcenter
	* when set to 'true', the image will centers vertically within its container
	* when set to 'false' the image will not centers vertically within its container
	* @default: false
	*/
	public $imagevertcenter=false;
	
	/** 
	* @var boolean $magvertcenter 
	* when set to 'true',the magnified area centers vertically in relation to the zoomable image
	* when set to 'false' the magnified area will not centers vertically in relation to the zoomable image
	* @default: false
	*/
    public $magvertcenter=false;
	
	/** 
	* @var string $magnifierpos- used to set the position of the magnifying area relative to the original image. 
	* when set to "right" ,the position of the magnifying area will be set to right
	* when set to "left", the position of the magnifying area will be set to left
	* Note: If there's not enough room for it in the window in the desired direction, it will automatically shift to the other direction.
	* @default: 'right'
	*/
    
	public $magnifierpos='right';
	
	/** 
	* @var array $magnifiersize- used to set the magnifying area's dimensions in pixels 
	* @default: Default is [200, 200] , or 200px wide by 200px tall
	*/
   	public $magnifiersize=array('200','200');
	
	/** 
	* @var int $width- this option lets you set the width of the zoomable image 
	* @default: undefined (script determines width of the zoomable image)
	*/	
    public $width;
	
	/** 
	* @var int $height- this option lets you set the height of the zoomable image 
	* @default: undefined (script determines height of the zoomable image)
	*/	
    public $height;
	
	/** 
	* @var array $zoomrange- used to set the zoom level of the magnified image relative to the original image. 
	*						 The value should be in the form [x, y], where x and y are integers that 
	*						 define the lower(minimum value:3) and upper bounds(maximum value:10) of the zoom level. 
	* @default: Default is [3,10]
	*/
    public $zoomrange=array(3,10);
	
	/** 
	* @var boolean $initzoomablefade- whether or not the zoomable image should fade in automatically when the page loads
	* if set to 'true', the zoomable image will fade when the page loads
	* if set to 'false', the zoomable image will not fade when the page loads
	* Note: See also zoomablefade option. If zoomablefade is set to false, this will also be false.
	* If you are using multi-zoom, if zoomablefade is true and this option is set to false, only the first zoomable image will not fade in and rest of the images when loaded will fade in. 
	* @default: true
	*/

    public $initzoomablefade=true;
	
	/**
	* @var boolean $zoomablefade- Sets whether or not the zoomable image within a 
	* Image Zoomer should fade in as the page loads and, if this is a multi-zoom,
	* when the user switches between midsized images using the thumbnail links. 
	*	
	* @default: true
	*/

    public $zoomablefade=true;
	
	/** 
	* @var int $speed- sets the duration of fade in for zoomable images (in milliseconds) when zoomablefade is set to true 
	* @default: 600
	*/	
    public $speed=600;
	
	/** 	  
	  * @var int $zIndex-In most cases the script will determine the optimal z-index to use here, 
	  * so this property should only be used if there are z-index stacking problems. If there are, 
	  * use it to set the z-index for created elements. It should be at least as high as the highest
	  * z-index of the zoomable (midsized) image and, if any its positioned parents	
	  * @default: script determines the optimal z-index value to use 
	 */
    public $zIndex=0;
	
	/** 
	* @var array $images- this is where you specify the images for multi-zoom 
	* @default: empty array
	*/
	public $images=array();
	
	/**  
	* @var array $single_image- this is where you specify the image for single image zoom 
	* @default: empty array
	*/
	public $single_image=array();

	/**
	* @var array $single_image- this is where you specify the image for single image zoom
	* @default: empty array
	*/
	public $css_thumbs = "thumbs";

	
	//Below are the parameters which cannot be customized and these are meant to be used within the class
		
	
	/**  
	* @var string $name- specifies the name used internally in the class 
	*/
	private $name='YiiImageZoomer';

	/**  
	* @var boolean $descpos- boolean used intenally in the class 
	*/
	private $descpos=true;
	
	/**  
	* @var string $descArea- string variable used internally in the class 
	* .it specifies the description area or we can say that the container 
	*  where the image description will be displayed.
	*/
	public $descArea='description';

	/**
	* @var string $targetArea- string variable used internally in the class
	* .it specifies the description area or we can say that the container
	*  where the image description will be displayed.
	*/
	public $css_target='targetarea';

	/**  
	* @var array $js_options- array used internally in the class for storing the javascript options which are passed to the script later on. 
	*/
	private $js_options=array();
		
	/**
	 * Inits of the class.
	 */
    public function init()
    {
        parent::init();
    }
    
    public function run()
    {
		//creating clientScript instance 
        $cs = Yii::app()->clientScript;
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        $baseurl = Yii::app()->getAssetManager()->publish($dir . 'assets');
        $js_options = array();
        $assets=$dir.'assets';
		//if $assets containes path to a valid directory than we will publish the required assets 
        if(is_dir($assets))
        {   
			
			//register the required scripts ans css files
            $clientScript = Yii::app()->getClientScript();
            $clientScript->registerCssFile($baseurl . '/multizoom.css');
			$clientScript->registerCoreScript("jquery.min");
            $clientScript->registerScriptFile( $baseurl . '/multizoom.js');	
			
			//if the user want to use multi-image zoom and call the function related to multiple zoom
	        if($this->multiple_zoom!==FALSE)
			{
				echo $this->build_multiple_images();
				$options = $this->build_js_options();
				$jsCode = <<<SETUP
function bindZoom() {
        jQuery('#image1').addimagezoom($options);
        }
bindZoom();
SETUP;
				//> register jsCode
				$cs->registerScript($this->name, $jsCode, CClientScript::POS_READY);

			}
			//else user want to use single image zoom
			else
			{
				echo $this->build_single_image();
				$options = $this->build_js_options();
				$jsCode = <<<SETUP
function bindZoom() {
        jQuery('#image1').addimagezoom($options)
        }
bindZoom();
SETUP;
				$cs->registerScript($this->name,$jsCode,CClientScript::POS_READY );
			}	     
        }
		//else exception will be thrown 
        else
        {
            throw new Exception('YiiImageZoomer - Error: Couldn\'t publish assets.');
        }
    }
	
	/**
	 * Generates the html for single image zoom
	 * @param : no paramter.
	 * @return string -$html (which containes the html code for single image zoom)
	 */
	
    public function build_single_image() 
	{
		
		// if the image array "single_image" is not empty
		if(!empty($this->single_image))
		{
			//if the imageFolder exists  
			if(is_dir(Yii::getPathOfAlias('webroot') .'/'.$this->imagefolder))
			{	
				//if the image file exists than generate the html string and return it
				if(file_exists(Yii::getPathOfAlias('webroot') .'/'.$this->imagefolder. '/'.$this->single_image['image']))
				{
					$html='<div class="'.$this->css_target.'">';
					$html.= '<img src="'.Yii::app()->baseUrl.'/'.$this->imagefolder.'/'.$this->single_image['image'].'"  id="image1" /> </div>';
					$html.='<div id="'.$this->descArea.'">'.$this->single_image['image_desc'].'</div>';
					return $html;
				}
				// else the file doesn't exist than throw an exception
				else
				{
					throw new CHttpException(404, 'Error: The Image file '.$this->single_image['image'].' doesn\'t exists in ImageFolder '.$this->imagefolder.'  please make sure that you have specified the correct image file name.');
				}
			}
			//else the imagefolder doesn't exist than throw an exception. 
			else
			{
				throw new CHttpException(404, 'Error: The ImageFolder '.$this->imagefolder.' doesn\'t exists please make sure that you have specified the correct image path.');
			}
		}
		//else "single_image" array is empty than throw an exception 
		else
		{
				throw new Exception('YiiImageZoomer - Error: No Image Is Provided Please Specify The Image To Zoom.');
		}
	}
	
	/**
	 * Generates the javascript options based on the type of zoom
	 * @param : no paramter.
	 * @return string -$html (which containes the html code for multi-image zoom)
	 */
	public function build_multiple_images()
	{
		// if the image array "images" is not empty
		if(!empty($this->images))
		{
			//Loop through the images to check wheather they exists or not in the specified imageFolder
			foreach($this->images as $image)
			{
				//if the file specified in "image" doesn't exists than throw an exception 
				if(file_exists(Yii::getPathOfAlias('webroot') .'/'.$this->imagefolder. '/'.$image['image'])===false)
				{
					throw new CHttpException(404, 'Error: The Image file "'.$image['image'].'" doesn\'t exists in ImageFolder "'.$this->imagefolder.'"  please make sure that you have specified the correct image file name.');
				}
				if(array_key_exists('image_large', $image))
				{
					//if the file specified in "image_large" doesn't exists than throw an exception
					if(file_exists(Yii::getPathOfAlias('webroot') .'/'.$this->imagefolder. '/'.$image['image_large'])===false)
					{
						throw new CHttpException(404, 'Error: The Image file "'.$image['image_large'].'" doesn\'t exists in ImageFolder "'.$this->imagefolder.'"  please make sure that you have specified the correct image file name.');
					}
				}
				//if the file specified in "image_thumb" doesn't exists than throw an exception
				if(file_exists(Yii::getPathOfAlias('webroot') .'/'.$this->imagefolder. '/'.$image['image_thumb'])===false)
				{
					throw new CHttpException(404, 'Error: The Image file "'.$image['image_thumb'].'" doesn\'t exists in ImageFolder "'.$this->imagefolder.'"  please make sure that you have specified the correct image file name.');
				}
			}
			//generate the html to display
			$html='<div class="'.$this->css_target.'">';
			$html.='<img id="image1" alt="'.$this->images['0']['image_alt'].'" title=" " src="'.Yii::app()->baseUrl.'/'.$this->imagefolder.'/'.$this->images['0']['image'].'"/></div>';
			$html.='<div id="'.$this->descArea.'">'.$this->images['0']['image_desc'].'</div>';
			$html.='<div class="'.$this->css_thumbs.'">';
			
			//loop through the images to fetch image based data  and genrate the html to display
			foreach($this->images as $image)
			{	
				$a_options=array();
				
				if(array_key_exists('image_large', $image) and isset($image['image_large']))
				{
				
				$a_options['data-large']=Yii::app()->baseUrl.'/'.$this->imagefolder.'/'.$image['image_large'];
				}
				
				if(isset($this->zoomrange) and !empty($this->zoomrange))
				{
				
				//$a_options['data-zoomrange']=$this->zoomrange['0']','.$this->zoomrange['1']; 
				}
				
				if(array_key_exists('image_desc', $image)and isset($image['image_desc']))
				{
					$a_options['data-title']=$image['image_desc'];
				}
				$html.='<a href="'.Yii::app()->baseUrl.'/'.$this->imagefolder.'/'.$image['image'].'"';				
				
				foreach($a_options as $key=>$value)
				{
				$html.="  "."$key".'='."'$value'"."  "; 
				}
				$html.='data-magsize="'.$this->magnifiersize['0'].','.$this->magnifiersize['1'].'" data-dims="'.$this->width.','.$this->height.'" data-lens="'.$this->cursorshade.'">';
				$html.='<img src="'.Yii::app()->baseUrl.'/'.$this->imagefolder.'/'.$image['image_thumb'].'" alt="'.$image['image_alt'].'" />';
				$html.='</a>';

			}
			$html.='</div>';
			return $html;

		}
		//else throw exception that no images are provided(it means "images" array is empty)
		else
		{
				throw new CHttpException(404, 'YiiImageZoomer - Error: No Images Are Provided Please Specify the images to zoom.');
		}
	}
	
	/**
	 * Generates the javascript options based on the type of zoom
	 * @param : no paramter.
	 * @return array -$js_options (which containes the javascript encoded options which will be passed to the script)
	 */
	public function build_js_options()
	{
		//if the muti-image zoom is set than generate javascript options accordingly
		if($this->multiple_zoom!==FALSE)
		{		
				
			//generating javascript options for multi-image zoom.
			$this->js_options['cursorshade']=$this->cursorshade;
			$this->js_options['magnifierpos']=$this->magnifierpos;
			$this->js_options['speed']=$this->speed;
			$this->js_options['zIndex']=$this->zIndex;
			$this->js_options['cursorshadecolor']=$this->cursorshadecolor;
			$this->js_options['cursorshadeopacity']=$this->cursorshadeopacity;
			$this->js_options['cursorshadeborder']=$this->cursorshadeborder;
			$this->js_options['imagevertcenter']= $this->imagevertcenter;
			$this->js_options['magvertcenter']=$this->magvertcenter;
			$this->js_options['magnifiersize']=array($this->magnifiersize['0'],$this->magnifiersize['1']);
			$this->js_options['zoomrange']=array($this->zoomrange['0'],$this->zoomrange['1']);
			$this->js_options['initzoomablefade']=$this->initzoomablefade;
			$this->js_options['zoomablefade']=$this->zoomablefade;
			$this->js_options['width'] = $this->width;
			$this->js_options['height']= $this->height;
			$this->js_options['descArea']='#'.$this->descArea;
			$this->js_options['descpos']= $this->descpos;
			
			//encoding the javascript options 
			$this->js_options = CJavaScript::encode($this->js_options);
			// return the generated javascript options array (which is $js_options)
			return	$this->js_options;
		}
		//else generate options for single image zoom
		else
		{	
			//if the large image file  doesn't exist than throw an exception
			if(file_exists(Yii::getPathOfAlias('webroot') .'/'.$this->imagefolder. '/'.$this->single_image['image_large'])===false)
			{
				throw new CHttpException(404, 'Error: The Image file "'.$this->single_image['image_large'].'" doesn\'t exists in ImageFolder "'.$this->imagefolder.'"  please make sure that you have specified the correct image file name.');
			}
			//else large image file exist , generate the javascript options
			else
			{
				// $large_image stores the path to the large image path 
				$large_image=Yii::app()->baseUrl.'/'.$this->imagefolder.'/'.$this->single_image['image_large'];
				
				// generating the javascript options for single image zoom
				$this->js_options['magnifierpos']=$this->magnifierpos;
				$this->js_options['speed']=$this->speed;
				$this->js_options['zIndex']=$this->zIndex;
				$this->js_options['cursorshadecolor']=$this->cursorshadecolor;
				$this->js_options['cursorshadeopacity']=$this->cursorshadeopacity;
				$this->js_options['cursorshadeborder']=$this->cursorshadeborder;
				$this->js_options['largeimage']=$large_image;
				$this->js_options['cursorshade']=$this->cursorshade;
				$this->js_options['imagevertcenter']= $this->imagevertcenter;
				$this->js_options['magvertcenter']=$this->magvertcenter;
				$this->js_options['magnifiersize']=array($this->magnifiersize['0'],$this->magnifiersize['1']);
				$this->js_options['zoomrange']=array($this->zoomrange['0'],$this->zoomrange['1']);
				$this->js_options['initzoomablefade']=$this->initzoomablefade;
				$this->js_options['zoomablefade']=$this->zoomablefade;
				$this->js_options['width'] = $this->width;
				$this->js_options['height']= $this->height;
				$this->js_options['descArea']='#'.$this->descArea;
				$this->js_options['descpos']= $this->descpos;

				
				//encoding the javascript options
				$this->js_options = CJavaScript::encode($this->js_options);
				//return the javascript options array (which is js_options)
				return	$this->js_options;
			}
		}
	}
}

/* File ends Here */
?>