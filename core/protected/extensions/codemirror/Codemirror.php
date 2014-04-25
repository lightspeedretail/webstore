<?php

/**
 * Yii Plugin for codemirror
 * @author Marcus Vinicius Ostrufka Freire
 * @version 1.0
 * @copyright (c) 2013-03-07, Marcus Freire
 */

class Codemirror extends CInputWidget
{

	public $script;
	public $baseurl;
	public $model;
	public $attribute;
	public $target = null;
	public $showHint = false;
	public $options = array();
	public $showTheme = false;
	public $extraKeys = array();
	public $htmlOptions = array();

	public function getScript()
	{
		return $this->script;
	}

	public function setScript($script)
	{
		$this->script = $script;
	}

	public function getBaseurl()
	{
		return $this->baseurl;
	}

	public function setBaseurl($baseurl)
	{
		$this->baseurl = $baseurl;
	}

	public function getTarget()
	{
		return $this->target;
	}

	public function setTarget($target)
	{
		$this->target = $target;
	}

	public function getShowHint()
	{
		return $this->showHint;
	}

	public function setShowHint($showHint)
	{
		$this->showHint = $showHint;
	}

	public function getOptions()
	{
		return CJavaScript::encode($this->options);
	}

	public function setOptions($options)
	{
		$this->options = $options;
	}

	public function getChangeTheme()
	{
		return $this->changeTheme;
	}

	public function setChangeTheme($changeTheme)
	{
		$this->changeTheme = $changeTheme;
	}

	public function getExtraKeys()
	{
		return $this->extraKeys;
	}

	public function setExtraKeys($extraKeys)
	{
		$this->extraKeys = $extraKeys;
	}

	public function setExtraKeysInOptions($value)
	{

		$op = CJSON::decode($this->getOptions(), true);
		$op['extraKeys'] = $value;

		return CJSON::encode($op);
	}

	public function init()
	{

		parent::init();

		if(empty($this->model) || empty($this->attribute))
		{
			throw new Exception("Model and Attribute must be declared");
		}
	}

	public function run()
	{

		// Publish extension assets
		$dir    = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'source';
		$assets = Yii::app()->getAssetManager()->publish($dir);
		$cs     = Yii::app()->getClientScript();

		if($this->target === null)
		{
			list( $name, $id ) = $this->resolveNameID();

			if($this->showTheme)
			{
				echo CHtml::dropDownList("ListTheme", 1, array(
					'default' => 'default',
					'ambiance' => 'ambiance',
					'cobalt' => 'cobalt',
					'eclipse' => 'eclipse',
					'elegant' => 'elegant',
					'monokai' => 'monokai',
				), array(
					'onchange'=>'js: selectTheme(this.value);',
				));

				$cs->registerScript(
					'showTheme' . $id,
					"function selectTheme(theme)
	                {
	                    codeMirror{$id}.setOption('theme', theme);
	                }
                    ",
					CClientScript::POS_END
				);

			}

			if($this->hasModel())
				echo CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
			else
				echo CHtml::textArea($name, $this->value, $this->htmlOptions);
		}

		$cs->registerScriptFile($assets . '/lib/codemirror.js');
		$cs->registerCssFile($assets . "/lib/codemirror.css");

		//THEMES
		$cs->registerCssFile($assets . '/theme/cobalt.css');
		$cs->registerCssFile($assets . "/theme/eclipse.css");
		$cs->registerCssFile($assets . "/theme/elegant.css");
		$cs->registerCssFile($assets . "/theme/monokai.css");

		if($this->getShowHint())
		{
			$cs->registerCssFile($assets . "/addon/hint/show-hint.css");
			$cs->registerScriptFile($assets . '/addon/hint/show-hint.js');
			$cs->registerScriptFile($assets . '/addon/hint/javascript-hint.js');
		}

		$cs->registerScriptFile($assets . '/addon/edit/matchbrackets.js');
		$cs->registerScriptFile($assets . '/mode/htmlmixed/htmlmixed.js');
		$cs->registerScriptFile($assets . '/mode/xml/xml.js');
		$cs->registerScriptFile($assets . '/mode/javascript/javascript.js');
		$cs->registerScriptFile($assets . '/mode/css/css.js');
		$cs->registerScriptFile($assets . '/mode/clike/clike.js');
		$cs->registerScriptFile($assets . '/mode/php/php.js');



		if($this->target === null)
		{
			$options =  $this->getOptions();

			if(!empty($this->extraKeys))
			{
				//$extra = $this->getExtraKeys();
				//$options = $this->setExtraKeysInOptions($extra);
			}
			else
			{

			}


			$script = "codeMirror{$id} = CodeMirror.fromTextArea(document.getElementById('{$id}'),
                {$options}
            ); ";

			if($this->getShowHint())
			{
				$script .= "
                    CodeMirror.commands.autocomplete = function(cm) {
                        CodeMirror.showHint(cm, CodeMirror.javascriptHint);
                    }
                ";

				if(empty($this->extraKeys))
				{
					throw new Exception("You must be declare a extraKey option.");
				}
				else
				{
					$script .=" codeMirror{$id}.setOption('extraKeys', {$this->extraKeys}); ";
				}
			}

			$cs->registerScript('codemirroredit' . $id, $script, CClientScript::POS_END);
		}
		else
		{
			$cs->registerScript('codemirroredit' . $this->target, "var codemirroredit{$this->target} = CodeMirror.fromTextArea(document.getElementById('{$this->target}'), { lineNumbers: true, matchBrackets: true, mode: 'text/x-php', indentUnit: 4, indentWithTabs: true, enterMode: \"keep\", tabMode: \"shift\" });", CClientScript::POS_END);
		}
	}

}

