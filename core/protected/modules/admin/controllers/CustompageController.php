<?php

class CustompageController extends AdminBaseController
{

	public $controllerName = "Custom Pages";

	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('index','add','edit'),
				'roles' => array('admin'),
			),
		);
	}


	public function beforeAction($action)
	{


		$arrModules = CustomPage::model()->findAll(array('order' => 'title')); //Get active and inactive

		$menuSidebar = array();

		foreach ($arrModules as $module)
			$menuSidebar[] = array('label' => $module->title, 'url' => array('custompage/edit', 'id' => $module->id));


		$this->menuItems = array_merge(
			array(
				array('label' => 'Edit Pages', 'linkOptions' => array('class' => 'nav-header'))
			),
			$menuSidebar,
			array(
				array('label' => 'Custom Pages', 'linkOptions' => array('class' => 'nav-header')),
				array('label' => 'Create new page', 'url' => array('custompage/add')),
				//array('label'=>'Set active tabs', 'url'=>array('payments/promotasks')),

			)
		);

		//run parent init() after setting menu so highlighting works
		Yii::import('ext.imperavi-redactor-widget.ImperaviRedactorWidget');
		return parent::beforeAction($action);
	}
	public function actionIndex()
	{


		$model = new CustomPage();

		$this->render(
			'index',
			array('model' =>
				$model
			)
		);

	}

	/*
	 * Add Action
	 * The action for adding a new custom page.
	 *
	 * @return null
	*/
	public function actionAdd()
	{
		$model = new CustomPage();
		$this->editSectionName = "Add a new custom page";

		if(isset($_POST['CustomPage']))
		{
			$model->setPageData($_POST);
			$model->attributes = $_POST['CustomPage'];
			if ($model->validate())
			{
				if (!$model->save())
				{
					Yii::app()->user->setFlash('error', print_r($model->getErrors(), true));
				}
				else
				{
					Yii::app()->user->setFlash(
						'success',
						Yii::t(
							'admin',
							'Custom page added on {time}.',
							array('{time}' => date("d F, Y  h:i:sa")
							)
						)
					);
					$this->redirect(
						$this->createUrl(
							"custompage/edit",
							array('id' => $model->id)
						)
					);
				}
			}
		}

		$this->render('edit', array('model' => $model));

	}

	/*
	 * Edit Action
	 * The action for editing an existing custom page.
	 *
	 * @return null
	*/
	public function actionEdit()
	{
		// Retrieve the pageid we're looking to edit
		$id = Yii::app()->getRequest()->getQuery('id');
		$model = CustomPage::model()->findByPk($id);

		if (!($model instanceof CustomPage))
		{
			Yii::app()->user->setFlash('error', "Invalid Custom Page");
			$this->redirect($this->createUrl("custompage/index"));
		}

		Yii::log('POST: ' . print_r($_POST, true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		if (_xls_get_conf('LANG_MENU'))
		{
			$langs = _xls_comma_to_array(_xls_get_conf('LANG_OPTIONS'));
		}
		else
		{
			$langs = array("en:English");
		}

		if(isset($_POST['CustomPage']))
		{
			$model->setPageData($_POST);
			$_POST['CustomPage']['page_data'] = $model->page_data;
			$model->attributes = $_POST['CustomPage'];
			if ($model->validate())
			{
				if ($model->deleteMe)
				{
					$model->delete();
					Yii::app()->user->setFlash('info', "Custom page has been deleted");
					$this->redirect($this->createUrl("custompage/index"));
				}
				else
				{
					$model->request_url = _xls_seo_url($model->title);
					if (!$model->save())
					{
						Yii::app()->user->setFlash('error', print_r($model->getErrors(), true));
					}
					else
					{
						Yii::app()->user->setFlash(
							'success',
							Yii::t(
								'admin',
								'Custom page updated on {time}.',
								array('{time}' => date("d F, Y  h:i:sa")
								)
							)
						);
						$this->beforeAction('edit'); //In case we renamed one and we want to update menu
					}
				}
			}
		}

		$this->render('edit', array('model' => $model));
	}
}
