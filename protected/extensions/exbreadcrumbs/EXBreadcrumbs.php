<?php
/**
 * This extension is a wrapper for the xbreadcrumb JQuery plugin that can be found
 * here : http://www.ajaxblender.com/xbreadcrumbs.html
 * Enjoy !!
 * @version 1.0
 * @author Raoul
 *
 */
class EXBreadcrumbs extends CWidget {
	/**
	 * @var mixed the CSS file used for the widget. Defaults to null, meaning
	 * using the default CSS file included together with the widget.
	 * If false, no CSS file will be used. Otherwise, the specified CSS file
	 * will be included when using this widget.
	 */
	public $cssFile;
	/**
	 * @var array additional HTML attributes that will be rendered in the UL tag.
	 * By Default, the class is set to 'xbreadcrumbs'.
	 */
	public $htmlOptions=array('class'=>'xbreadcrumbs');	
	/**
	 * @var mixed Numeric value in milliseconds or jQuery string value (fast,normal,slow).
	 * The parameter sets speed of sub level to appear. If empty then no transition is used.
	 * Default value is 'fast'.
	 */
	public $showSpeed;
	/**
	 * @var mixed Numeric value in milliseconds or jQuery string value  (fast,normal,slow)
	 * The parameter sets speed of sub level to hide. If empty then no transition is used.
	 * Default value is 'empty'.
	 */
	public $hideSpeed;
	/**
	 * @var boolean Parameters enables collapsing of upper levels. Default is 'false'.
	 */
	public $collapsible;
	/**
	 * @var int Width of collapsed level. Default is 10 pixels.
	 */
	public $collapsedWidth;
	/**
	 * @var boolean whether to HTML encode the link labels. Defaults to true.
	 */	
	public $encodeLabel=true;
	/**
	 * @var string the first hyperlink in the breadcrumbs (called home link).
	 * If this property is not set, it defaults to a link pointing to {@link CWebApplication::homeUrl} with label 'Home'.
	 * If this property is false, the home link will not be rendered.
	 */
	public $homeLink;
	/**
	 * @var array dropdown menu displayed on the first hyperlink in the breadcrumbs (the home link).
	 */
	public $homeMenu;
	public $homeClass = 'home';
	public $currentClass = 'current';
	/**
	 * @var array list of hyperlinks to appear in the breadcrumbs. If this property is empty,
	 * the widget will not render anything. Each key-value pair in the array
	 * will be used to generate a hyperlink by calling CHtml::link(key, value). For this reason, the key
	 * refers to the label of the link while the value can be a string or an array (used to
	 * create a URL). For more details, please refer to {@link CHtml::link}.
	 * If an element's key is an integer, it means the element will be rendered as a label only (meaning the current page).
	 *
	 * The following example will generate breadcrumbs as "Home > Sample post > Edit", where "Home" points to the homepage,
	 * "Sample post" points to the "index.php?r=post/view&id=12" page, and "Edit" is a label. Note that the "Home" link
	 * is specified via {@link homeLink} separately.
	 *
	 * <pre>
	 * array(
	 *     'Sample post'=>array('post/view', 'id'=>12),
	 *     'Edit',
	 * )
	 * </pre>
	 */
	public $links=array();	
	/**
	* @var array additional options that can be passed to the constructor of the xbreadcrumb js object.
	*/
	public $options=array();	
	/**
	 * Renders the content of the widget.
	 */	
	public function run()
	{
		if(empty($this->links))
			return;

		if(!isset($this->htmlOptions['id']))
			$this->htmlOptions['id']=$this->getId();

		$this->registerClientScript();
					
		echo CHtml::openTag('ul',$this->htmlOptions)."\n";
		$links=array();
		if($this->homeLink===null){
			$links[]='<li>'.
				CHtml::link(Yii::t('zii',''),Yii::app()->homeUrl, array('class'=>$this->homeClass)).
				$this->createMenu($this->homeMenu).
				'</li>';
		}else if($this->homeLink!==false){
			$links[]='<li>'.
				$this->homeLink.
				$this->createMenu($this->homeMenu).
				'</li>';
		}
		foreach($this->links as $label=>$url)
		{
			$urlIsArray= is_array($url);
			if(is_string($label) || $urlIsArray){				
				$menu=null;
				$htmlOptions=null;
				if($urlIsArray){
					if(isset($url['menu'])){
						$menu=$this->createMenu($url['menu']);
						unset($url['menu']);
					}
					if(isset($url['htmlOptions'])){
						$htmlOptions=$url['htmlOptions'];
						unset($url['htmlOptions']);
					}
					
				}				
				//$menu=($urlIsArray && isset($url['menu']) ? $this->createMenu($url['menu']) , unset($url['menu']) : null);
				
				$url= ($urlIsArray && isset($url['url'])  ? $url['url']:$url);
				
				Yii::trace('url='.CVarDumper::dumpAsString($url));
				$links[]='<li>'.CHtml::link($this->encodeLabel ? CHtml::encode($label) : $label, $url,$htmlOptions).$menu.'</li>';
			}else
				$links[]='<li class="'.$this->currentClass.'">'.($this->encodeLabel ? CHtml::encode($url) : $url).'</li>';

		}
		echo implode($links);
		echo CHtml::closeTag('ul');
	}
	/**
	 * 
	 * @param unknown_type $menu
	 */
	private function createMenu($menu){
		$m='';
		if(isset($menu) && is_array($menu)){
			$m='<ul>';
			foreach ($menu as $label => $url) {
				$m.='<li>'.CHtml::link($this->encodeLabel ? CHtml::encode($label) : $label, $url).'</li>';
			}
			$m.='</ul>';			
		}
		return $m;
	}
	/**
	 * 
	 * @throws CException
	 */
	public function registerClientScript()
	{			
		try{
			// get client js options in JSON format
			$options=$this->getClientOptions();
			$options=$options===array()?'{}' : CJavaScript::encode($options);	
						
			// publish and register assets : js, css
			// The xbreadcrumbs.css stylesheet is always published and registered because it
			// defines basic CSS, requires to display the breadcrumb correctly
			$assets = dirname(__FILE__).'/assets';
			$baseUrl = Yii::app()->assetManager->publish($assets);

			$id=$this->getId();
			$cs=Yii::app()->getClientScript();
								
			$cs->registerCoreScript('jquery');
			$cs->registerScriptFile($baseUrl.'/xbreadcrumbs.js', CClientScript::POS_HEAD);
			$cs->registerCssFile($baseUrl.'/xbreadcrumbs.css');
			$cs->registerScript('Yii.EXBreadcrumbs#'.$id,"$(\"#{$id}\").xBreadcrumbs($options);");
						
			
			// Set additional style if no custom style is defined
			if($this->cssFile===null){
				$cs->registerCssFile($baseUrl.'/style.css');					
			}
			else if($this->cssFile!==false)
				$cs->registerCssFile($this->cssFile);
			
		}catch(CException $e){
			throw new CException('failed to publish/register assets : '.$e->getMessage());
		}
	}	
	/**
	 * @return array the javascript options
	 */
	protected function getClientOptions()
	{
		$options=$this->options;
		foreach(array('showSpeed','hideSpeed','collapsible','collapsedWidth') as $name)
		{
			if($this->$name!==null)
				$options[$name]=$this->$name;
		}
		return $options;
	}
	
}