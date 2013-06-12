<?php

/**
 * This is the base model class for table "{{customer}}".
 *
 * The followings are the available columns in table '{{customer}}':
 * @property string $id
 * @property integer $record_type
 * @property string $first_name
 * @property string $last_name
 * @property string $lightspeed_id
 * @property string $company
 * @property string $default_billing_id
 * @property string $default_shipping_id
 * @property string $currency
 * @property string $email
 * @property integer $email_verified
 * @property string $pricing_level
 * @property string $preferred_language
 * @property string $mainphone
 * @property string $mainphonetype
 * @property string $lightspeed_user
 * @property string $facebook
 * @property integer $check_same
 * @property integer $newsletter_subscribe
 * @property integer $html_email
 * @property string $password
 * @property string $temp_password
 * @property integer $allow_login
 * @property string $created
 * @property string $modified
 * @property string $last_login
 *
 * The followings are the available model relations:
 * @property Cart[] $carts
 * @property CustomerAddress $defaultBilling
 * @property CustomerAddress $defaultShipping
 * @property PricingLevels $pricingLevel
 * @property CustomerAddress[] $customerAddresses
 * @property Document[] $documents
 * @property EmailQueue[] $emailQueues
 * @property Sro[] $sros
 * @property Wishlist[] $wishlists
 * @property WishlistItem[] $wishlistItems
 *
 * @package application.models.base
 * @name BaseCustomer
 */
abstract class BaseCustomer extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{customer}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created, modified', 'required'),
			array('record_type, email_verified, check_same, newsletter_subscribe, html_email, allow_login', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name', 'length', 'max'=>64),
			array('lightspeed_id, default_billing_id, default_shipping_id, facebook', 'length', 'max'=>20),
			array('company, email, password, temp_password', 'length', 'max'=>255),
			array('currency', 'length', 'max'=>3),
			array('pricing_level', 'length', 'max'=>11),
			array('preferred_language, mainphonetype', 'length', 'max'=>8),
			array('mainphone, lightspeed_user', 'length', 'max'=>32),
			array('last_login', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, record_type, first_name, last_name, lightspeed_id, company, default_billing_id, default_shipping_id, currency, email, email_verified, pricing_level, preferred_language, mainphone, mainphonetype, lightspeed_user, facebook, check_same, newsletter_subscribe, html_email, password, temp_password, allow_login, created, modified, last_login', 'safe', 'on'=>'search'),
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
			'carts' => array(self::HAS_MANY, 'Cart', 'customer_id'),
			'defaultBilling' => array(self::BELONGS_TO, 'CustomerAddress', 'default_billing_id'),
			'defaultShipping' => array(self::BELONGS_TO, 'CustomerAddress', 'default_shipping_id'),
			'pricingLevel' => array(self::BELONGS_TO, 'PricingLevels', 'pricing_level'),
			'customerAddresses' => array(self::HAS_MANY, 'CustomerAddress', 'customer_id'),
			'documents' => array(self::HAS_MANY, 'Document', 'customer_id'),
			'emailQueues' => array(self::HAS_MANY, 'EmailQueue', 'customer_id'),
			'sros' => array(self::HAS_MANY, 'Sro', 'customer_id'),
			'wishlists' => array(self::HAS_MANY, 'Wishlist', 'customer_id'),
			'wishlistItems' => array(self::HAS_MANY, 'WishlistItem', 'purchased_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'record_type' => 'Record Type',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'lightspeed_id' => 'Lightspeed',
			'company' => 'Company',
			'default_billing_id' => 'Default Billing',
			'default_shipping_id' => 'Default Shipping',
			'currency' => 'Currency',
			'email' => 'Email',
			'email_verified' => 'Email Verified',
			'pricing_level' => 'Pricing Level',
			'preferred_language' => 'Preferred Language',
			'mainphone' => 'Mainphone',
			'mainphonetype' => 'Mainphonetype',
			'lightspeed_user' => 'Lightspeed User',
			'facebook' => 'Facebook',
			'check_same' => 'Check Same',
			'newsletter_subscribe' => 'Newsletter Subscribe',
			'html_email' => 'Html Email',
			'password' => 'Password',
			'temp_password' => 'Temp Password',
			'allow_login' => 'Allow Login',
			'created' => 'Created',
			'modified' => 'Modified',
			'last_login' => 'Last Login',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('record_type',$this->record_type);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('lightspeed_id',$this->lightspeed_id,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('default_billing_id',$this->default_billing_id,true);
		$criteria->compare('default_shipping_id',$this->default_shipping_id,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('email_verified',$this->email_verified);
		$criteria->compare('pricing_level',$this->pricing_level,true);
		$criteria->compare('preferred_language',$this->preferred_language,true);
		$criteria->compare('mainphone',$this->mainphone,true);
		$criteria->compare('mainphonetype',$this->mainphonetype,true);
		$criteria->compare('lightspeed_user',$this->lightspeed_user,true);
		$criteria->compare('facebook',$this->facebook,true);
		$criteria->compare('check_same',$this->check_same);
		$criteria->compare('newsletter_subscribe',$this->newsletter_subscribe);
		$criteria->compare('html_email',$this->html_email);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('temp_password',$this->temp_password,true);
		$criteria->compare('allow_login',$this->allow_login);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);
		$criteria->compare('last_login',$this->last_login,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}