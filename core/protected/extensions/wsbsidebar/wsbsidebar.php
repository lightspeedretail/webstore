<?php


class wsbsidebar extends CWidget {
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
	public $htmlOptions=array();

	public $checkTheme = true;

	public $sidebarName = "Generic Sidebar";

	/**
	 * Renders the content of the widget.
	 */
	public $links;


	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		Yii::import('application.extensions.'.get_class($this).'.models.*');

	}

	/* Runs widget which simply loads the search template
	*/
	/* Runs widget which simply loads the search template
	*/
	public function run()
	{


		$model=new SidebarForm();

		if(isset($_POST['SidebarForm']))
		{


			$model->attributes=$_POST['SidebarForm'];

			if($model->validate())
			{
				//we successfully validated
			}
		}
		$this->render('index',array('model'=>$model));
	}
}