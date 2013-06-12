<?php

/**
 * This is the base model class for table "{{document}}".
 *
 * The followings are the available columns in table '{{document}}':
 * @property string $id
 * @property string $cart_id
 * @property string $order_str
 * @property string $invoice_str
 * @property string $customer_id
 * @property string $shipaddress_id
 * @property string $billaddress_id
 * @property string $shipping_id
 * @property string $payment_id
 * @property double $discount
 * @property string $po
 * @property integer $order_type
 * @property string $status
 * @property double $cost_total
 * @property string $currency
 * @property double $currency_rate
 * @property string $datetime_cre
 * @property string $datetime_due
 * @property double $sell_total
 * @property string $printed_notes
 * @property integer $fk_tax_code_id
 * @property integer $tax_inclusive
 * @property double $subtotal
 * @property double $tax1
 * @property double $tax2
 * @property double $tax3
 * @property double $tax4
 * @property double $tax5
 * @property double $total
 * @property integer $item_count
 * @property string $lightspeed_user
 * @property string $gift_registry
 * @property string $send_to
 * @property string $submitted
 * @property string $modified
 * @property string $linkid
 *
 * The followings are the available model relations:
 * @property Cart[] $carts
 * @property CustomerAddress $billaddress
 * @property CustomerAddress $shipaddress
 * @property Customer $customer
 * @property DocumentShipping $shipping
 * @property DocumentPayment $payment
 * @property Cart $cart
 * @property DocumentItem[] $documentItems
 *
 * @package application.models.base
 * @name BaseDocument
 */
abstract class BaseDocument extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{document}}';
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
			array('order_type, fk_tax_code_id, tax_inclusive, item_count', 'numerical', 'integerOnly'=>true),
			array('discount, cost_total, currency_rate, sell_total, subtotal, tax1, tax2, tax3, tax4, tax5, total', 'numerical'),
			array('cart_id, customer_id, shipaddress_id, billaddress_id, shipping_id, payment_id, gift_registry', 'length', 'max'=>20),
			array('order_str, invoice_str, po, linkid', 'length', 'max'=>64),
			array('status, lightspeed_user', 'length', 'max'=>32),
			array('currency', 'length', 'max'=>3),
			array('send_to', 'length', 'max'=>255),
			array('datetime_cre, datetime_due, printed_notes, submitted', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, cart_id, order_str, invoice_str, customer_id, shipaddress_id, billaddress_id, shipping_id, payment_id, discount, po, order_type, status, cost_total, currency, currency_rate, datetime_cre, datetime_due, sell_total, printed_notes, fk_tax_code_id, tax_inclusive, subtotal, tax1, tax2, tax3, tax4, tax5, total, item_count, lightspeed_user, gift_registry, send_to, submitted, modified, linkid', 'safe', 'on'=>'search'),
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
			'carts' => array(self::HAS_MANY, 'Cart', 'document_id'),
			'billaddress' => array(self::BELONGS_TO, 'CustomerAddress', 'billaddress_id'),
			'shipaddress' => array(self::BELONGS_TO, 'CustomerAddress', 'shipaddress_id'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'shipping' => array(self::BELONGS_TO, 'DocumentShipping', 'shipping_id'),
			'payment' => array(self::BELONGS_TO, 'DocumentPayment', 'payment_id'),
			'cart' => array(self::BELONGS_TO, 'Cart', 'cart_id'),
			'documentItems' => array(self::HAS_MANY, 'DocumentItem', 'document_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cart_id' => 'Cart',
			'order_str' => 'Order Str',
			'invoice_str' => 'Invoice Str',
			'customer_id' => 'Customer',
			'shipaddress_id' => 'Shipaddress',
			'billaddress_id' => 'Billaddress',
			'shipping_id' => 'Shipping',
			'payment_id' => 'Payment',
			'discount' => 'Discount',
			'po' => 'Po',
			'order_type' => 'Order Type',
			'status' => 'Status',
			'cost_total' => 'Cost Total',
			'currency' => 'Currency',
			'currency_rate' => 'Currency Rate',
			'datetime_cre' => 'Datetime Cre',
			'datetime_due' => 'Datetime Due',
			'sell_total' => 'Sell Total',
			'printed_notes' => 'Printed Notes',
			'fk_tax_code_id' => 'Fk Tax Code',
			'tax_inclusive' => 'Tax Inclusive',
			'subtotal' => 'Subtotal',
			'tax1' => 'Tax1',
			'tax2' => 'Tax2',
			'tax3' => 'Tax3',
			'tax4' => 'Tax4',
			'tax5' => 'Tax5',
			'total' => 'Total',
			'item_count' => 'Item Count',
			'lightspeed_user' => 'Lightspeed User',
			'gift_registry' => 'Gift Registry',
			'send_to' => 'Send To',
			'submitted' => 'Submitted',
			'modified' => 'Modified',
			'linkid' => 'Linkid',
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
		$criteria->compare('cart_id',$this->cart_id,true);
		$criteria->compare('order_str',$this->order_str,true);
		$criteria->compare('invoice_str',$this->invoice_str,true);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('shipaddress_id',$this->shipaddress_id,true);
		$criteria->compare('billaddress_id',$this->billaddress_id,true);
		$criteria->compare('shipping_id',$this->shipping_id,true);
		$criteria->compare('payment_id',$this->payment_id,true);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('po',$this->po,true);
		$criteria->compare('order_type',$this->order_type);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('cost_total',$this->cost_total);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('currency_rate',$this->currency_rate);
		$criteria->compare('datetime_cre',$this->datetime_cre,true);
		$criteria->compare('datetime_due',$this->datetime_due,true);
		$criteria->compare('sell_total',$this->sell_total);
		$criteria->compare('printed_notes',$this->printed_notes,true);
		$criteria->compare('fk_tax_code_id',$this->fk_tax_code_id);
		$criteria->compare('tax_inclusive',$this->tax_inclusive);
		$criteria->compare('subtotal',$this->subtotal);
		$criteria->compare('tax1',$this->tax1);
		$criteria->compare('tax2',$this->tax2);
		$criteria->compare('tax3',$this->tax3);
		$criteria->compare('tax4',$this->tax4);
		$criteria->compare('tax5',$this->tax5);
		$criteria->compare('total',$this->total);
		$criteria->compare('item_count',$this->item_count);
		$criteria->compare('lightspeed_user',$this->lightspeed_user,true);
		$criteria->compare('gift_registry',$this->gift_registry,true);
		$criteria->compare('send_to',$this->send_to,true);
		$criteria->compare('submitted',$this->submitted,true);
		$criteria->compare('modified',$this->modified,true);
		$criteria->compare('linkid',$this->linkid,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}