<?php

/**
 * This is the base model class for table "{{cart}}".
 *
 * The followings are the available columns in table '{{cart}}':
 * @property string $id
 * @property string $id_str
 * @property string $customer_id
 * @property string $shipaddress_id
 * @property string $billaddress_id
 * @property string $shipping_id
 * @property string $payment_id
 * @property string $document_id
 * @property string $po
 * @property integer $cart_type
 * @property string $status
 * @property string $currency
 * @property double $currency_rate
 * @property string $datetime_cre
 * @property string $datetime_due
 * @property string $printed_notes
 * @property string $tax_code_id
 * @property integer $tax_inclusive
 * @property double $subtotal
 * @property double $tax1
 * @property double $tax2
 * @property double $tax3
 * @property double $tax4
 * @property double $tax5
 * @property double $total
 * @property integer $item_count
 * @property integer $downloaded
 * @property string $lightspeed_user
 * @property string $origin
 * @property string $gift_registry
 * @property string $send_to
 * @property string $submitted
 * @property string $modified
 * @property string $linkid
 * @property integer $fk_promo_id
 *
 * The followings are the available model relations:
 * @property CustomerAddress $billaddress
 * @property CartPayment $payment
 * @property CustomerAddress $shipaddress
 * @property CartShipping $shipping
 * @property Customer $customer
 * @property TaxCode $taxCode
 * @property Document $document
 * @property CartItem[] $cartItems
 * @property Document[] $documents
 * @property EmailQueue[] $emailQueues
 * @property TransactionLog[] $transactionLogs
 *
 * @package application.models.base
 * @name BaseCart
 */
abstract class BaseCart extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{cart}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('modified', 'required'),
			array('cart_type, tax_inclusive, item_count, downloaded, fk_promo_id', 'numerical', 'integerOnly'=>true),
			array('currency_rate, subtotal, tax1, tax2, tax3, tax4, tax5, total', 'numerical'),
			array('id_str, po, linkid', 'length', 'max'=>64),
			array('customer_id, shipaddress_id, billaddress_id, shipping_id, payment_id, document_id, gift_registry', 'length', 'max'=>20),
			array('status, lightspeed_user', 'length', 'max'=>32),
			array('currency', 'length', 'max'=>3),
			array('tax_code_id', 'length', 'max'=>11),
			array('origin, send_to', 'length', 'max'=>255),
			array('datetime_cre, datetime_due, printed_notes, submitted', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_str, customer_id, shipaddress_id, billaddress_id, shipping_id, payment_id, document_id, po, cart_type, status, currency, currency_rate, datetime_cre, datetime_due, printed_notes, tax_code_id, tax_inclusive, subtotal, tax1, tax2, tax3, tax4, tax5, total, item_count, downloaded, lightspeed_user, origin, gift_registry, send_to, submitted, modified, linkid, fk_promo_id', 'safe', 'on'=>'search'),
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
			'billaddress' => array(self::BELONGS_TO, 'CustomerAddress', 'billaddress_id'),
			'payment' => array(self::BELONGS_TO, 'CartPayment', 'payment_id'),
			'shipaddress' => array(self::BELONGS_TO, 'CustomerAddress', 'shipaddress_id'),
			'shipping' => array(self::BELONGS_TO, 'CartShipping', 'shipping_id'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'taxCode' => array(self::BELONGS_TO, 'TaxCode', 'tax_code_id'),
			'document' => array(self::BELONGS_TO, 'Document', 'document_id'),
			'cartItems' => array(self::HAS_MANY, 'CartItem', 'cart_id'),
			'documents' => array(self::HAS_MANY, 'Document', 'cart_id'),
			'emailQueues' => array(self::HAS_MANY, 'EmailQueue', 'cart_id'),
			'transactionLogs' => array(self::HAS_MANY, 'TransactionLog', 'cart_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_str' => 'Id Str',
			'customer_id' => 'Customer',
			'shipaddress_id' => 'Shipaddress',
			'billaddress_id' => 'Billaddress',
			'shipping_id' => 'Shipping',
			'payment_id' => 'Payment',
			'document_id' => 'Document',
			'po' => 'Po',
			'cart_type' => 'Cart Type',
			'status' => 'Status',
			'currency' => 'Currency',
			'currency_rate' => 'Currency Rate',
			'datetime_cre' => 'Datetime Cre',
			'datetime_due' => 'Datetime Due',
			'printed_notes' => 'Printed Notes',
			'tax_code_id' => 'Tax Code',
			'tax_inclusive' => 'Tax Inclusive',
			'subtotal' => 'Subtotal',
			'tax1' => 'Tax1',
			'tax2' => 'Tax2',
			'tax3' => 'Tax3',
			'tax4' => 'Tax4',
			'tax5' => 'Tax5',
			'total' => 'Total',
			'item_count' => 'Item Count',
			'downloaded' => 'Downloaded',
			'lightspeed_user' => 'Lightspeed User',
			'origin' => 'Origin',
			'gift_registry' => 'Gift Registry',
			'send_to' => 'Send To',
			'submitted' => 'Submitted',
			'modified' => 'Modified',
			'linkid' => 'Linkid',
			'fk_promo_id' => 'Fk Promo',
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
		$criteria->compare('id_str',$this->id_str,true);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('shipaddress_id',$this->shipaddress_id,true);
		$criteria->compare('billaddress_id',$this->billaddress_id,true);
		$criteria->compare('shipping_id',$this->shipping_id,true);
		$criteria->compare('payment_id',$this->payment_id,true);
		$criteria->compare('document_id',$this->document_id,true);
		$criteria->compare('po',$this->po,true);
		$criteria->compare('cart_type',$this->cart_type);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('currency_rate',$this->currency_rate);
		$criteria->compare('datetime_cre',$this->datetime_cre,true);
		$criteria->compare('datetime_due',$this->datetime_due,true);
		$criteria->compare('printed_notes',$this->printed_notes,true);
		$criteria->compare('tax_code_id',$this->tax_code_id,true);
		$criteria->compare('tax_inclusive',$this->tax_inclusive);
		$criteria->compare('subtotal',$this->subtotal);
		$criteria->compare('tax1',$this->tax1);
		$criteria->compare('tax2',$this->tax2);
		$criteria->compare('tax3',$this->tax3);
		$criteria->compare('tax4',$this->tax4);
		$criteria->compare('tax5',$this->tax5);
		$criteria->compare('total',$this->total);
		$criteria->compare('item_count',$this->item_count);
		$criteria->compare('downloaded',$this->downloaded);
		$criteria->compare('lightspeed_user',$this->lightspeed_user,true);
		$criteria->compare('origin',$this->origin,true);
		$criteria->compare('gift_registry',$this->gift_registry,true);
		$criteria->compare('send_to',$this->send_to,true);
		$criteria->compare('submitted',$this->submitted,true);
		$criteria->compare('modified',$this->modified,true);
		$criteria->compare('linkid',$this->linkid,true);
		$criteria->compare('fk_promo_id',$this->fk_promo_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}