<?php

class LicenseController extends AdminBaseController
{

	public $editSectionInstructions;
	public $page;
	public $license;

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index','end'),
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		if (_xls_get_conf('INSTALLED',0)==1)
			throw new CHttpException(404,'The requested page does not exist.');

		$this->layout = "license";


		$this->editSectionInstructions = "";
		$this->license = $this->renderPartial("license",null,true,false);

		$model = new InstallForm();
		$getpage = "getPage" . $model->page;
		$model->scenario = "page1";
		$formDefinition = $model->$getpage();

		if(isset($_POST['InstallForm']))
		{
			$model->scenario = "page".$_POST['InstallForm']['page'];
			if (isset($_POST['buttonSkip']) && $_POST['InstallForm']['page']==4)
				$model->scenario = "page-skip".$_POST['InstallForm']['page'];
			$model->attributes = $_POST['InstallForm'];

			if ($model->validate())
			{

				switch ($model->page)
				{
					case 1:
						$model->page=2;
						break;

					case 2:
						$model->savePage(2);
						$model->page=3;
						break;
					case 3:
						$model->savePage(3);
						$model->page=4;
						break;
					case 4:
						$model->savePage(4);
						_xls_set_conf('INSTALLED',1);
						$this->redirect($this->createUrl('license/end'));
						break;


				}

				$model->scenario = "page".$model->page;
				$model->attributes = $model->readFromSession($model->page);


			}

			//Possibly after submit, refetch these items

			$getpage = "getPage" . $model->page;
			$formDefinition = $model->$getpage();

		}



		foreach ($formDefinition['elements'] as $key=>$value)
			if ($key!="iagree")
				$formDefinition['elements'][$key]['layout']=
				'<div class="span3 optionlabel">{label}</div><div class="span4 optionvalue">{input}</div>{error}<div class="span2 maxhint">{hint}</div>';

		$this->render('index', array('model'=>$model,'form'=>new CForm($formDefinition,$model)));


	}



	public function actionEnd()
	{
		$this->layout = "license";

		$this->render('end');


	}
}