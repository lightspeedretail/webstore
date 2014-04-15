<?php

/**
 * This is the model class for table "{{gallery_photo}}".
 *
 * @package application.models
 * @name GalleryPhoto
 *
 */
class GalleryPhoto extends BaseGalleryPhoto
{
	/** @var string Extensions for gallery images */
	public $galleryExt = 'jpg';
	/** @var string directory in web root for galleries */
	public $_galleryDir = 'images/gallery';


	public function __construct($scenario='insert')
	{
		$this->galleryExt = Yii::app()->params['IMAGE_FORMAT'];
		return parent::__construct($scenario);
	}
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GalleryPhoto the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		if ($this->dbConnection->tablePrefix !== null)
			return '{{gallery_photo}}';
		else
			return 'gallery_photo';

	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gallery_id', 'required'),
//            array('gallery_id, rank', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 512),
			array('file_name', 'length', 'max' => 128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, gallery_id, rank, name, description, file_name', 'safe', 'on' => 'search'),
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
			'gallery' => array(self::BELONGS_TO, 'Gallery', 'gallery_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'gallery_id' => 'Gallery',
			'rank' => 'Rank',
			'name' => 'Name',
			'description' => 'Description',
			'file_name' => 'File Name',
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

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('gallery_id', $this->gallery_id);
		$criteria->compare('rank', $this->rank);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('description', $this->description, true);
		$criteria->compare('file_name', $this->file_name, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function save($runValidation = true, $attributes = null)
	{
		parent::save($runValidation, $attributes);
		if ($this->rank == null) {
			$this->rank = $this->id;
			$this->setIsNewRecord(false);
			$this->save(false);
		}
		return true;
	}

	public function getGalleryDir()
	{
		return $this->_galleryDir."/".$this->gallery_id;
	}

	public function getPreview()
	{

		if(Yii::app()->params['LIGHTSPEED_MT']=='1')
			$this->_galleryDir = "gallery";

		if(Yii::app()->params['LIGHTSPEED_MT']=='1')
			$prefix = "//lightspeedwebstore.s3.amazonaws.com/" .
				Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'].'/'.$this->galleryDir;
		else
			$prefix = Yii::app()->request->baseUrl . '/' . $this->galleryDir;

		return $prefix . '/_' . $this->getFileName('') . '.' . $this->ThumbExt;
	}

	private function getFileName($version = '')
	{
		return $this->id . $version;
	}

	protected function getThumbExt()
	{
		return $this->thumb_ext;
	}

	public function getUrl($version = '')
	{
		if(Yii::app()->params['LIGHTSPEED_MT']=='1')
			$this->_galleryDir = "gallery";

		if(Yii::app()->params['LIGHTSPEED_MT']=='1')
			$prefix = "//lightspeedwebstore.s3.amazonaws.com/" .
				Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'].'/'.$this->galleryDir;
		else
			$prefix = Yii::app()->request->baseUrl . '/' . $this->galleryDir;

		return $prefix . '/' . $this->getFileName($version) . '.' . $this->galleryExt;
	}

	/**
	 * Save image either locally or on cloud.
	 * @param $path temporary file location
	 */
	public function setImage($imageFile)
	{
		$path = $imageFile->getTempName();
		$strExt = $this->getExtensionFromMime($imageFile);

		if(!is_dir($this->galleryDir))
			@mkdir($this->galleryDir,0775,true);

		//save image in original size
		Yii::app()->image->load($path)->save($this->galleryDir . '/' . $this->getFileName('') . '.' . $strExt);
		//create image preview for gallery manager
		Yii::app()->image->load($path)->resize(300, null)->save($this->galleryDir . '/_' . $this->getFileName('') . '.' . $strExt);

		foreach ($this->gallery->versions as $version => $actions) {
			$image = Yii::app()->image->load($path);
			foreach ($actions as $method => $args) {
				call_user_func_array(array($image, $method), is_array($args) ? $args : array($args));
			}
			$image->save($this->galleryDir . '/' . $this->getFileName($version) . '.' . $strExt);
		}
	}

	public function setS3Image($imageFile)
	{
		$path = $imageFile->getTempName();
		$strExt = $this->getExtensionFromMime($imageFile);

		if(Yii::app()->params['LIGHTSPEED_MT']=='1')
			$this->_galleryDir = "gallery";

		$objComponent=Yii::createComponent('ext.wscloud.wscloud');
		$objImage = new Images();
		$objImage->strImageName = $this->galleryDir . '/' . $this->getFileName('') . '.' . $strExt;

		//save image in original size
		$objComponent->SaveToS3($objImage->strImageName,$path);

		$d = YiiBase::getPathOfAlias('webroot')."/runtime/cloudimages/"._xls_get_conf('LIGHTSPEED_HOSTING_LIGHTSPEED_URL');
		@mkdir($d,0777,true);
		$tmpOriginal = tempnam($d,"galleryimg");
		@unlink($tmpOriginal);
		$tmpOriginal .= '.' . $strExt;

		//create image preview for gallery manager
		Yii::app()->image->load($path)->resize(300, null)->save($tmpOriginal);
		$objImage->strImageName = $this->galleryDir . '/_' . $this->getFileName('') . '.' . $strExt;
		$objComponent->SaveToS3($objImage->strImageName,$tmpOriginal);
		@unlink($tmpOriginal);

		foreach ($this->gallery->versions as $version => $actions) {
			$image = Yii::app()->image->load($path);
			foreach ($actions as $method => $args) {
				call_user_func_array(array($image, $method), is_array($args) ? $args : array($args));
			}
			$tmpOriginal = tempnam($d,"galleryimg");
			@unlink($tmpOriginal);
			$tmpOriginal .= '.' . $this->galleryExt;
			$objImage->strImageName = $this->galleryDir . '/' . $this->getFileName($version) . '.' . $strExt;
			$image->save($tmpOriginal);
			$objComponent->SaveToS3($objImage->strImageName,$tmpOriginal);
			@unlink($tmpOriginal);
		}
	}

	public function delete()
	{
		if(Yii::app()->params['LIGHTSPEED_MT']=='1')
			$this->_galleryDir = "gallery";

		$p = $this->file_name;
		$path = mb_pathinfo($p);
		$ext = $path['extension'];
		$ext = $this->thumb_ext;
		$this->removeFile($this->galleryDir . '/' . $this->getFileName('') . '.' . $ext);


		$this->removeFile($this->galleryDir . '/_' . $this->getFileName('') . '.' . $this->galleryExt);

		foreach ($this->gallery->versions as $version => $actions) {
			$this->removeFile($this->galleryDir . '/' . $this->getFileName($version) . '.' . $this->galleryExt);
		}
		return parent::delete();
	}

	private function removeFile($fileName)
	{
		if(Yii::app()->params['LIGHTSPEED_MT']=='1')
		{
			$this->_galleryDir = "gallery";
			$objComponent=Yii::createComponent('ext.wscloud.wscloud');
			$objImage = new Images();
			$objImage->strImageName = _xls_get_conf('LIGHTSPEED_HOSTING_LIGHTSPEED_URL').'/'.$fileName;
			$objComponent->RemoveImageFromS3($objImage,$objImage->strImageName);
		} elseif (file_exists($fileName))
			@unlink($fileName);
	}

	public function removeImages()
	{
		foreach ($this->gallery->versions as $version => $actions) {
			$this->removeFile($this->galleryDir . '/' . $this->getFileName($version) . '.' . $this->galleryExt);
		}
	}

	/**
	 * Regenerate image versions
	 */
	public function updateImages()
	{
		foreach ($this->gallery->versions as $version => $actions) {
			$this->removeFile($this->galleryDir . '/' . $this->getFileName($version) . '.' . $this->galleryExt);

			$image = Yii::app()->image->load($this->galleryDir . '/' . $this->getFileName('') . '.' . $this->galleryExt);
			foreach ($actions as $method => $args) {
				call_user_func_array(array($image, $method), is_array($args) ? $args : array($args));
			}
			$image->save($this->galleryDir . '/' . $this->getFileName($version) . '.' . $this->galleryExt);
		}
	}

	public function getExtensionFromMime($imageFile)
	{
		$type = $imageFile->type;
		if($type=="image/png") $type="png"; else $type = "jpg";

		return $type;
	}

}