<?php

/**
 * This is the base model class for table "{{stringtranslate}}".
 *
 * The followings are the available columns in table '{{stringtranslate}}':
 * @property integer $id
 * @property string $language
 * @property string $translation
 *
 * The followings are the available model relations:
 * @property Stringsource $id0
 *
 * @package application.models.base
 * @name BaseStringtranslate
 */
abstract class BaseStringtranslate extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{stringtranslate}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('language', 'length', 'max'=>16),
			array('translation', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, language, translation', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'id0' => array(self::BELONGS_TO, 'Stringsource', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'language' => 'Language',
			'translation' => 'Translation',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('translation',$this->translation,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}