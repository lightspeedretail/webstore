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
		if (_xls_get_conf('INSTALLED', 0) == 1)
		{
			_xls_404();
		}

		$this->layout = "license";

		$this->editSectionInstructions = "";
		$this->license = $this->renderPartial("license",null,true,false);

		$model = new InstallForm();
		$getpage = "getPage" . $model->page;
		$model->scenario = "page1";
		$model->setScenario($this->cloudMtScenario($model->getScenario()));
		$formDefinition = $model->$getpage();

		if(isset($_POST['InstallForm']))
		{
			$model->scenario = "page".$_POST['InstallForm']['page'];
			if (isset($_POST['buttonSkip']) && $_POST['InstallForm']['page']==4)
				$model->scenario = "page-skip".$_POST['InstallForm']['page'];

			$model->setScenario($this->cloudMtScenario($model->getScenario()));


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


				if (_xls_get_conf('LIGHTSPEED_CLOUD',0)>0 && $model->page==2)
					$model->scenario = "page".$model->page."-cld";
				else
					if (_xls_get_conf('LIGHTSPEED_CLOUD',0)==0 && _xls_get_conf('LIGHTSPEED_MT',0)>0 && $model->page==2)
						$model->scenario = "page".$model->page."-mt";
					else $model->scenario = "page".$model->page;

				$model->attributes = $model->readFromSession($model->page);


			} else Yii::log("Install Wizard ".$model->scenario." error ".print_r($model->getErrors(),true),
				'error', 'application.'.__CLASS__.".".__FUNCTION__);

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


	protected function cloudMtScenario($scenario)
	{
		if(Yii::app()->params['LIGHTSPEED_CLOUD']>0)
			$scenario .= "-cld";
		elseif(Yii::app()->params['LIGHTSPEED_MT']>0)
			$scenario .= "-mt";

		return $scenario;
	}

	public function actionEnd()
	{
		$this->layout = "license";

		$this->render('end');


	}
}