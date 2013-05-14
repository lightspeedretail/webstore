<?php

/**
 * Themes class.
 * For choosing from themes
 */
class Themes extends CFormModel
{
	public $id;
	public $name;
	public $thumbnail_url;

	public $options;



	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('id,name,thumbnail_url,options','safe'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
		);
	}

	public function getForm()
	{
		return array(
			'elements'=>array(
				'name'=>array(
					'type'=>'hidden',
					'name'=>$this->name,
				),
				'thumbnail_url'=>array(
					'type'=>'radio',
					'layout'=>'<div class="span4">
							<div class="themeselect" style="display:none">{input}</div>
							<div class="themeicon">{label}</div>
							<div class="themeoptions">{hint}</div>
						</div>',
				),
			),
		);
	}


	public function getInstalledThemes()
	{
		$arr = array();
		$d = dir(YiiBase::getPathOfAlias('webroot')."/themes");
		while (false!== ($filename = $d->read())) {
			if ($filename[0] != ".") {
				$fnOptions = YiiBase::getPathOfAlias('webroot')."/themes/".$filename."/config.xml";
				if (file_exists($fnOptions)) {
					$strXml = file_get_contents($fnOptions);
					$oXML = new SimpleXMLElement($strXml);
					$arr[$filename] = $this->buildThemeChooser($oXML);
				}
			}
		}
		$d->close();

		return $arr;
	}


	protected function buildThemeChooser($oXML)
	{
		$retVal = CHtml::image($oXML->thumbnail,
			$oXML->name);

		return $retVal;

	}


}


