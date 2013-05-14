<?php
/**
 * JCarousel class file.
 *
 * @author Nicola Puddu <nicola@creationgears.com>
 * @version 1.1
 */

Yii::import('zii.widgets.CBaseListView');

/**
 * JCarousel renders a gallery carousel from a data provider.
 *
 * JCarousel should be used together with a {@link IDataProvider data provider}, preferrably a
 * {@link CActiveDataProvider}.
 *
 * The minimal code needed to use JCarousel is as follows:
 *
 * <pre>
 * $dataProvider=new CActiveDataProvider('Gallery');
 *
 * $this->widget('ext.JCarousel.JCarousel', array(
 *     'dataProvider'=>$dataProvider,
 *     'thumbUrl' => '"/gallery/thumbs/thumb_".$data->file_name',
 *     'imageUrl' => '"/gallery/".$data->file_name',
 *     'target' => 'YOUR-DIV-ID',
 * ));
 * </pre>
 *
 * The above code first creates a data provider for the <code>Gallery</code> ActiveRecord class.
 * It then uses JCarousel to display the image in every <code>Gallery</code> instance.
 *
 * thumbUrl, imageUrl and linkClass must be php expressions, therefore inside them you can use
 * $data to access the current record data
 * 
 * clickCallback is the js code that has to be executed when someone click on one of
 * the carousel images.
 * If you won't set this property the default behaviour is to create load an image inside the target div.
 * If you want to set this property you have the following javascript variables that you can use inside:
 * 
 * itemSrc = the src value of the link
 * itemClass = the class value of the link
 * target = the target id. You can use it like this $(target).html("whatever");
 * imageElement = the image tag with the src attribute setted correctly
 *
 * @author Nicola Puddu <nicola@creationgears.com>
 */
class JCarousel extends CBaseListView
{

	/**
	 * JCarousel properties
	 */
	
	/**
	 * Specifies wether the carousel appears in horizontal or 
	 * vertical orientation. 
	 * Changes the carousel from a left/right style to a up/down style carousel.
	 * @var boolean
	 */
	public $vertical;
	/**
	 * Specifies wether the carousel appears in RTL (Right-To-Left) mode.
	 * @var boolean
	 */
	public $rtl;
	/**
	 * The index of the item to start with.
	 * @var int
	 */
	public $start;
	/**
	 * The index of the first available item at initialisation.
	 * @var int
	 */
	public $offset;
	/**
	 * The number of total items.
	 * Defaults to number of existing <li> elements if 
	 * size is not passed explicitly
	 * @var int
	 */
	public $size;
	/**
	 * The number of items to scroll by.
	 * @var int
	 */
	public $scroll;
	/**
	 * If passed, the width/height of the items will be calculated and set 
	 * depending on the width/height of the clipping, 
	 * so that exactly that number of items will be visible.
	 * @var int
	 */
	public $visible;
	/**
	 * The speed of the scroll animation as string in jQuery terms 
	 * ("slow" or "fast") or milliseconds as integer. 
	 * If set to 0, animation is turned off.
	 * See {@link http://docs.jquery.com/effects/animate jQuery Documentation}
	 * @var mixed
	 */
	public $animation;
	/**
	 * The name of the easing effect that you want to use. 
	 * See {@link http://docs.jquery.com/effects/animate jQuery Documentation}.
	 * @var string
	 */
	public $easing;
	/**
	 * Specifies how many seconds to periodically autoscroll the content. 
	 * If set to 0 (default) then autoscrolling is turned off. 
	 * @var int
	 */
	public $auto;
	/**
	 * Specifies whether to wrap at the first/last item (or both) and jump 
	 * back to the start/end. Options are "first", "last", "both" or "circular" 
	 * as string. If set to null, wrapping is turned off (default).
	 * @var string
	 */
	public $wrap;
	/**
	 * JavaScript function that is called right after initialisation of the carousel. 
	 * Two parameters are passed: The instance of the requesting carousel and 
	 * the state of the carousel initialisation (init, reset or reload).
	 * @var string
	 */
	public $initCallback;
	/**
	 * JavaScript function that is called right after the carousel is completely setup. 
	 * One parameter is passed: The instance of the requesting carousel.
	 * @var string
	 */
	public $setupCallback;
	/**
	 * JavaScript function that is called when the carousel requests a set of 
	 * items to be loaded. Two parameters are passed: The instance of the requesting 
	 * carousel and the state of the carousel action (prev, next or init). 
	 * Alternatively, you can pass a hash of one or two functions which are 
	 * triggered before and/or after animation:
	 * 
	 * <pre>
	 * itemLoadCallback: {
	 *		onBeforeAnimation: callback1,
	 *		onAfterAnimation: callback2
	 * }
	 * </pre>
	 * 
	 * @var string
	 */
	public $itemLoadCallback;
	/**
	 * JavaScript function that is called (after the scroll animation) when 
	 * an item becomes the first one in the visible range of the carousel. 
	 * Four parameters are passed: The instance of the requesting carousel 
	 * and the <li> object itself, the index which indicates the position 
	 * of the item in the list and the state of the carousel action 
	 * (prev, next or init). Alternatively, you can pass a hash of one 
	 * or two functions which are triggered before and/or after animation:
	 * 
	 * <pre>
	 * itemFirstInCallback: {
	 * 		onBeforeAnimation: callback1,
	 * 		onAfterAnimation: callback2
	 * }
	 * </pre>
	 * @var string
	 */
	public $itemFirstInCallback;
	/**
	 * JavaScript function that is called (after the scroll animation) when 
	 * an item isn't longer the first one in the visible range of the carousel. 
	 * Four parameters are passed: The instance of the requesting carousel and 
	 * the <li> object itself, the index which indicates the position of the 
	 * item in the list and the state of the carousel action (prev, next or init). 
	 * Alternatively, you can pass a hash of one or two functions which are 
	 * triggered before and/or after animation:
	 *  
	 * <pre>
	 * itemFirstOutCallback: {
	 * 		onBeforeAnimation: callback1,
	 * 		onAfterAnimation: callback2
	 * }
	 * </pre>
	 * @var string
	 */
	public $itemFirstOutCallback;
	/**
	 * JavaScript function that is called (after the scroll animation) when 
	 * an item becomes the last one in the visible range of the carousel. 
	 * Four parameters are passed: The instance of the requesting carousel 
	 * and the <li> object itself, the index which indicates the position of 
	 * the item in the list and the state of the carousel action (prev, next or init). 
	 * Alternatively, you can pass a hash of one or two functions which are 
	 * triggered before and/or after animation:
	 * 
	 * <pre>
	 * itemLastInCallback: {
	 * 		onBeforeAnimation: callback1,
	 * 		onAfterAnimation: callback2
	 * }
	 * </pre>
	 * @var string
	 */
	public $itemLastInCallback;
	/**
	 * JavaScript function that is called when an item isn't longer the last one 
	 * in the visible range of the carousel. Four parameters are passed: 
	 * The instance of the requesting carousel and the <li> object itself, 
	 * the index which indicates the position of the item in the list and the 
	 * state of the carousel action (prev, next or init). 
	 * Alternatively, you can pass a hash of one or two functions which 
	 * are triggered before and/or after animation:
	 * 
	 * <pre>
	 * itemLastOutCallback: {
	 * 		onBeforeAnimation: callback1,
	 * 		onAfterAnimation: callback2
	 * }
	 * </pre>
	 * @var string
	 */
	public $itemLastOutCallback;
	/**
	 * JavaScript function that is called (after the scroll animation) when 
	 * an item is in the visible range of the carousel. Four parameters are 
	 * passed: The instance of the requesting carousel and the <li> object 
	 * itself, the index which indicates the position of the item in the 
	 * list and the state of the carousel action (prev, next or init). 
	 * Alternatively, you can pass a hash of one or two functions 
	 * which are triggered before and/or after animation:
	 * 
	 * <pre>
	 * itemVisibleInCallback: {
	 * 		onBeforeAnimation: callback1,
	 * 		onAfterAnimation: callback2
	 * }
	 * </pre>
	 * @var unknown_type
	 */
	public $itemVisibleInCallback;
	/**
	 * JavaScript function that is called (after the scroll animation) when 
	 * an item isn't longer in the visible range of the carousel. 
	 * Four parameters are passed: The instance of the requesting carousel 
	 * and the <li> object itself, the index which indicates the position 
	 * of the item in the list and the state of the carousel action (prev, next or init). 
	 * Alternatively, you can pass a hash of one or two functions which 
	 * are triggered before and/or after animation:
	 * 
	 * <pre>
	 * itemVisibleOutCallback: {
	 * 		onBeforeAnimation: callback1,
	 * 		onAfterAnimation: callback2
	 * }
	 * </pre>
	 * @var string
	 */
	public $itemVisibleOutCallback;
	/**
	 * JavaScript function that is called after each animation step. 
	 * This function is directly passed to jQuery's .animate() 
	 * method as the step parameter. 
	 * See the jQuery documentation for the parameters it will receive.
	 * @var string
	 */
	public $animationStepCallback;
	/**
	 * JavaScript function that is called when the state of the 'next' 
	 * control is changing. The responsibility of this method is to enable 
	 * or disable the 'next' control. 
	 * Three parameters are passed: The instance of the requesting carousel, 
	 * the control element and a flag indicating whether the button 
	 * should be enabled or disabled.
	 * @var string
	 */
	public $buttonNextCallback;
	/**
	 * JavaScript function that is called when the state of the 'previous' 
	 * control is changing. The responsibility of this method is to enable 
	 * or disable the 'previous' control. 
	 * Three parameters are passed: The instance of the requesting carousel, 
	 * the control element and a flag indicating whether 
	 * the button should be enabled or disabled.
	 * @var string
	 */
	public $buttonPrevCallback;
	/**
	 * The HTML markup for the auto-generated next button. 
	 * If set to null, no next-button is created.
	 * @var string
	 */
	public $buttonNextHTML;
	/**
	 * The HTML markup for the auto-generated prev button. 
	 * If set to null, no prev-button is created.
	 * @var string
	 */
	public $buttonPrevHTML;
	/**
	 * Specifies the event which triggers the next scroll.
	 * @var string
	 */
	public $buttonNextEvent;
	/**
	 * Specifies the event which triggers the prev scroll.
	 * @var string
	 */
	public $buttonPrevEvent;
	/**
	 * If, for some reason, jCarousel can not detect the 
	 * width of an item, you can set a fallback dimension 
	 * (width or height, depending on the orientation) 
	 * here to ensure correct calculations.
	 * @var int
	 */
	public $itemFallbackDimension;
	
	/**
	 * this widget properties
	 */
	
	/**
	 * the url to the thumbnail image
	 * $data will contain the model.
	 * The string will be the argument of eval(),
	 * therefore must be a php expression
	 * @var string
	 */
	public $thumbUrl;
	/**
	 * the url to the full size image
	 * $data will contain the model.
	 * The string will be the argument of eval(),
	 * therefore must be a php expression
	 * @var string
	 */
	public $imageUrl;
	/**
	 * skin name for the widget.
	 * the widget will be contained inside a div with
	 * a class named jcarousel-skin-YOUSKIN.
	 * @var unknown_type
	 */
	public $skin = 'tango';
	/**
	 * the id of the target div that will contain the
	 * full size image
	 * @var string
	 */
	public $target;
	/**
	 * the php expression that will generate the link class
	 * @var string 
	 */
	public $linkClass;
	/**
	 * the js function that will be triggered onclick of an item in the list
	 * @var string
	 */
	public $clickCallback;
	/**
	 * php expression evaluated to create the alt text.
	 * @var string
	 * @since 1.1
	 */
	public $altText;
	/**
	 * php expression evaluated to create the title text.
	 * by default this value is going to be the same of altText
	 * if you won't say otherwise.
	 * If this property value is boolean false no title attribute
	 * will be displayed
	 * @var string
	 * @since 1.1
	 */
	public $titleText;

	/**
	 * Added by LightSpeed - caption text that will appear below the photo, wrapped in a DIV using class "caption"
	 * @var string
	 */
	public $captionText;
	
	/**
	 * default widget properties
	 */
	
	/**
	 * @var string the URL of the CSS file used by this JCarousel. Defaults to null, meaning using the integrated
	 * CSS file. If this is set false, you are responsible to explicitly include the necessary CSS file in your page.
	 */
	public $cssFile;
	/**
	 * @var string the base script URL for jcarousel view resources (e.g. javascript, CSS file, images).
	 * Defaults to null, meaning using the integrated grid view resources (which are published as assets).
	 */
	public $baseScriptUrl;
	/**
	 * override of the summaryText property
	 * to default it to boolean false
	 * @var boolean
	 */
	public $summaryText = false;
	

	/**
	 * Initializes the grid view.
	 * This method will initialize required property values and instantiate {@link columns} objects.
	 */
	public function init()
	{
		parent::init();

		$this->htmlOptions['id']=$this->getId().'-widget';

		if($this->baseScriptUrl===null)
			$this->baseScriptUrl=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.JCarousel.assets'));

		if($this->cssFile!==false)
		{
			if($this->cssFile===null)
				$this->cssFile=$this->baseScriptUrl.'/skin.css';
			Yii::app()->getClientScript()->registerCssFile($this->cssFile);
		}
		
		if(!isset($this->thumbUrl))
			throw new CHttpException(500, Yii::t('jcarousel', 'you must assign a value for thumbUrl'));
		elseif (str_word_count($this->thumbUrl) === 1)
			$this->thumbUrl = '$data->'.$this->thumbUrl;
			
		if(!isset($this->imageUrl))
			throw new CHttpException(500, Yii::t('jcarousel', 'you must assign a value for imageUrl'));
		elseif (str_word_count($this->imageUrl) === 1)
			$this->imageUrl = '$data->'.$this->imageUrl;

	}

	/**
	 * Registers necessary client scripts.
	 */
	public function registerClientScript()
	{
		$id=$this->getId();
		
		$options = array();
		if($this->vertical===true)
			$options['vertical'] = true;
		if ($this->rtl===true)
			$options['rtl'] = true;
		if (is_int($this->start))
			$options['start'] = $this->start;
		if (is_int($this->offset))
			$options['offset'] = $this->offset;
		if (is_int($this->size))
			$options['size'] = $this->size;
		if (is_int($this->scroll))
			$options['scroll'] = $this->scroll;
		if (is_int($this->visible))
			$options['visible'] = $this->visible;
		if (isset($this->animation))
			$options['animation'] = $this->animation;
		if (is_string($this->easing))
			$options['easing'] = $this->easing;
		if (is_int($this->auto))
			$options['auto'] = $this->auto;
		if (is_string($this->wrap))
			$options['wrap'] = $this->wrap;
		if (isset($this->initCallback))
			$options['initCallback'] = $this->initCallback;
		if (isset($this->setupCallback))
			$options['setupCallback'] = $this->setupCallback;
		if (isset($this->itemLoadCallback))
			$options['itemLoadCallback'] = $this->itemLoadCallback;
		if (isset($this->itemFirstInCallback))
			$options['itemFirstInCallback'] = $this->itemFirstInCallback;
		if (isset($this->itemFirstOutCallback))
			$options['itemFirstOutCallback'] = $this->itemFirstOutCallback;
		if (isset($this->itemLastInCallback))
			$options['itemLastInCallback'] = $this->itemLastInCallback;
		if (isset($this->itemLastOutCallback))
			$options['itemLastOutCallback'] = $this->itemLastOutCallback;
		if (isset($this->itemVisibleInCallback))
			$options['itemVisibleInCallback'] = $this->itemVisibleInCallback;
		if (isset($this->itemVisibleOutCallback))
			$options['itemVisibleOutCallback'] = $this->itemVisibleOutCallback;
		if (isset($this->animationStepCallback))
			$options['animationStepCallback'] = $this->animationStepCallback;
		if (isset($this->buttonNextCallback))
			$options['buttonNextCallback'] = $this->buttonNextCallback;
		if (isset($this->buttonPrevCallback))
			$options['buttonPrevCallback'] = $this->buttonPrevCallback;
		if (isset($this->buttonNextHTML))
			$options['buttonNextHTML'] = $this->buttonNextHTML;
		if (isset($this->buttonPrevHTML))
			$options['buttonPrevHTML'] = $this->buttonPrevHTML;
		if (isset($this->buttonNextEvent))
			$options['buttonNextEvent'] = $this->buttonNextEvent;
		if (isset($this->buttonPrevEvent))
			$options['buttonPrevEvent'] = $this->buttonPrevEvent;
		if (is_int($this->itemFallbackDimension))
			$options['itemFallbackDimension'] = $this->itemFallbackDimension;

		$options=CJavaScript::encode($options);
		$cs=Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile($this->baseScriptUrl.'/jquery.jcarousel.min.js',CClientScript::POS_END);
		$cs->registerScript(__CLASS__.'#'.$id,"jQuery('#$id').jcarousel($options);");
		
		if ($this->target) {
			$clickCallback = $this->clickCallback ? $this->clickCallback : '$(target).html(imageElement);';
			Yii::app()->clientScript->registerScript(__CLASS__.'#'.$id.'-clicker', '
				$("#'.$id.' a").click(function() {
					var itemSrc = $(this).attr("href");
					var itemClass = $(this).attr("class");
					var itemAlt = $(this).children("img").attr("alt");
					var itemTitle = $(this).children("img").attr("title");
					var titleAttr = "";
					if (itemTitle !== "") {
						titleAttr = "title=\""+itemTitle+"\"";
					}
					var target = "#'.$this->target.'";
					var imageElement = "<img src=\""+itemSrc+"\" alt=\""+itemAlt+"\" "+titleAttr+" />";'
					.$clickCallback.
					'return false;
				});	
			');
		}
	}

	/**
	 * Renders the data items for the grid view.
	 */
	public function renderItems()
	{
		if($this->dataProvider->getItemCount()>0)
		{
			echo '<ul id="'.$this->getId().'" class="jcarousel jcarousel-skin-'.$this->skin.'">';
			$this->renderGalleryItems();
			echo '</ul>';
		}
		else
			$this->renderEmptyText();
	}
	
	private function renderGalleryItems()
	{
		foreach ($this->dataProvider->getData() as $data) {

			$altText = $this->altText === NULL ? NULL : eval('return '.$this->altText.';');
			if ($this->titleText === false)
				$titleAttr = NULL;
			else {
				$titleText = $this->titleText === NULL ? $altText : eval('return '.$this->titleText.';');
				$titleAttr = 'title="'.$titleText.'"';
			}

			if ($this->captionText === false)
				$image = '<img src="'.eval('return '.$this->thumbUrl.';').'" alt="'.$altText.'" '.$titleAttr.' />';
			else
			{
				$captionText = eval('return '.$this->captionText.';');
				$image = '<img src="'.eval('return '.$this->thumbUrl.';').'" alt="'.$altText.'" '.$titleAttr.
					' /><div class="jcarousel jcarousel-skin-'.$this->skin.' caption">'.$captionText.'</div>';
			}

			if (isset($this->target))
				echo '<li><a href="'.eval('return '.$this->imageUrl.';').'" class="'.eval('return '.$this->linkClass.';').'">'.$image.'</a></li>';
			else
				echo '<li>'.$image.'</li>';
		}
	}
}
