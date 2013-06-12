<?php

/**
 * This is the base model class for table "{{product}}".
 *
 * The followings are the available columns in table '{{product}}':
 * @property string $id
 * @property string $title
 * @property string $image_id
 * @property string $class_id
 * @property string $code
 * @property integer $current
 * @property string $description_long
 * @property string $description_short
 * @property string $family_id
 * @property integer $gift_card
 * @property integer $inventoried
 * @property double $inventory
 * @property double $inventory_total
 * @property double $inventory_reserved
 * @property double $inventory_avail
 * @property integer $master_model
 * @property string $parent
 * @property string $product_size
 * @property string $product_color
 * @property double $product_height
 * @property double $product_length
 * @property double $product_width
 * @property double $product_weight
 * @property string $tax_status_id
 * @property double $sell
 * @property double $sell_tax_inclusive
 * @property double $sell_web
 * @property double $sell_web_tax_inclusive
 * @property string $upc
 * @property integer $web
 * @property string $request_url
 * @property integer $featured
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property CartItem[] $cartItems
 * @property DocumentItem[] $documentItems
 * @property Images[] $images
 * @property Product $parent0
 * @property Product[] $products
 * @property Images $image
 * @property Family $family
 * @property Classes $class
 * @property TaxStatus $taxStatus
 * @property Category[] $xlswsCategories
 * @property ProductQtyPricing[] $productQtyPricings
 * @property ProductRelated[] $productRelateds
 * @property ProductRelated[] $productRelateds1
 * @property ProductTags[] $productTags
 * @property SroItem[] $sroItems
 * @property TaskQueue[] $taskQueues
 * @property WishlistItem[] $wishlistItems
 *
 * @package application.models.base
 * @name BaseProduct
 */
abstract class BaseProduct extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{product}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, modified', 'required'),
			array('current, gift_card, inventoried, master_model, web, featured', 'numerical', 'integerOnly'=>true),
			array('inventory, inventory_total, inventory_reserved, inventory_avail, product_height, product_length, product_width, product_weight, sell, sell_tax_inclusive, sell_web, sell_web_tax_inclusive', 'numerical'),
			array('title, code, product_size, product_color, upc, request_url', 'length', 'max'=>255),
			array('image_id, class_id, family_id, parent', 'length', 'max'=>20),
			array('tax_status_id', 'length', 'max'=>11),
			array('description_long, description_short, created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, image_id, class_id, code, current, description_long, description_short, family_id, gift_card, inventoried, inventory, inventory_total, inventory_reserved, inventory_avail, master_model, parent, product_size, product_color, product_height, product_length, product_width, product_weight, tax_status_id, sell, sell_tax_inclusive, sell_web, sell_web_tax_inclusive, upc, web, request_url, featured, created, modified', 'safe', 'on'=>'search'),
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
			'cartItems' => array(self::HAS_MANY, 'CartItem', 'product_id'),
			'documentItems' => array(self::HAS_MANY, 'DocumentItem', 'product_id'),
			'images' => array(self::HAS_MANY, 'Images', 'product_id'),
			'parent0' => array(self::BELONGS_TO, 'Product', 'parent'),
			'products' => array(self::HAS_MANY, 'Product', 'parent'),
			'image' => array(self::BELONGS_TO, 'Images', 'image_id'),
			'family' => array(self::BELONGS_TO, 'Family', 'family_id'),
			'class' => array(self::BELONGS_TO, 'Classes', 'class_id'),
			'taxStatus' => array(self::BELONGS_TO, 'TaxStatus', 'tax_status_id'),
			'xlswsCategories' => array(self::MANY_MANY, 'Category', '{{product_category_assn}}(product_id, category_id)'),
			'productQtyPricings' => array(self::HAS_MANY, 'ProductQtyPricing', 'product_id'),
			'productRelateds' => array(self::HAS_MANY, 'ProductRelated', 'product_id'),
			'productRelateds1' => array(self::HAS_MANY, 'ProductRelated', 'related_id'),
			'productTags' => array(self::HAS_MANY, 'ProductTags', 'product_id'),
			'sroItems' => array(self::HAS_MANY, 'SroItem', 'product_id'),
			'taskQueues' => array(self::HAS_MANY, 'TaskQueue', 'product_id'),
			'wishlistItems' => array(self::HAS_MANY, 'WishlistItem', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'image_id' => 'Image',
			'class_id' => 'Class',
			'code' => 'Code',
			'current' => 'Current',
			'description_long' => 'Description Long',
			'description_short' => 'Description Short',
			'family_id' => 'Family',
			'gift_card' => 'Gift Card',
			'inventoried' => 'Inventoried',
			'inventory' => 'Inventory',
			'inventory_total' => 'Inventory Total',
			'inventory_reserved' => 'Inventory Reserved',
			'inventory_avail' => 'Inventory Avail',
			'master_model' => 'Master Model',
			'parent' => 'Parent',
			'product_size' => 'Product Size',
			'product_color' => 'Product Color',
			'product_height' => 'Product Height',
			'product_length' => 'Product Length',
			'product_width' => 'Product Width',
			'product_weight' => 'Product Weight',
			'tax_status_id' => 'Tax Status',
			'sell' => 'Sell',
			'sell_tax_inclusive' => 'Sell Tax Inclusive',
			'sell_web' => 'Sell Web',
			'sell_web_tax_inclusive' => 'Sell Web Tax Inclusive',
			'upc' => 'Upc',
			'web' => 'Web',
			'request_url' => 'Request Url',
			'featured' => 'Featured',
			'created' => 'Created',
			'modified' => 'Modified',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('image_id',$this->image_id,true);
		$criteria->compare('class_id',$this->class_id,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('current',$this->current);
		$criteria->compare('description_long',$this->description_long,true);
		$criteria->compare('description_short',$this->description_short,true);
		$criteria->compare('family_id',$this->family_id,true);
		$criteria->compare('gift_card',$this->gift_card);
		$criteria->compare('inventoried',$this->inventoried);
		$criteria->compare('inventory',$this->inventory);
		$criteria->compare('inventory_total',$this->inventory_total);
		$criteria->compare('inventory_reserved',$this->inventory_reserved);
		$criteria->compare('inventory_avail',$this->inventory_avail);
		$criteria->compare('master_model',$this->master_model);
		$criteria->compare('parent',$this->parent,true);
		$criteria->compare('product_size',$this->product_size,true);
		$criteria->compare('product_color',$this->product_color,true);
		$criteria->compare('product_height',$this->product_height);
		$criteria->compare('product_length',$this->product_length);
		$criteria->compare('product_width',$this->product_width);
		$criteria->compare('product_weight',$this->product_weight);
		$criteria->compare('tax_status_id',$this->tax_status_id,true);
		$criteria->compare('sell',$this->sell);
		$criteria->compare('sell_tax_inclusive',$this->sell_tax_inclusive);
		$criteria->compare('sell_web',$this->sell_web);
		$criteria->compare('sell_web_tax_inclusive',$this->sell_web_tax_inclusive);
		$criteria->compare('upc',$this->upc,true);
		$criteria->compare('web',$this->web);
		$criteria->compare('request_url',$this->request_url,true);
		$criteria->compare('featured',$this->featured);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}