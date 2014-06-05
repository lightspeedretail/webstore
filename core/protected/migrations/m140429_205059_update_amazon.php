<?php

class m140429_205059_update_amazon extends CDbMigration
{
	public function up()
	{

		$this->update('xlsws_category_amazon', array('product_type' => 'AutoAccessory'), 'name1 like :name and product_type is null', array(':name' => '%Automotive%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'Beauty'), 'name1 like :name and product_type is null', array(':name' => '%Beauty%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'CameraPhoto'), 'name1 like :name and product_type is null', array(':name' => '%camera & photo%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'CE'), 'name1 like :name and product_type is null', array(':name' => '%Electronics%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'Computers'), 'name2 like :name and product_type is null', array(':name' => '%Computers & Accessories%'));

		$this->update('xlsws_category_amazon', array('product_type' => 'FoodAndBeverages'), 'name1 like :name and product_type is null', array(':name' => '%Grocery & Gourmet Food%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'Health'), 'name1 like :name and product_type is null', array(':name' => '%Health & Personal Care%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'Home'), 'name1 like :name and product_type is null', array(':name' => '%Home & Kitchen%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'Jewelry'), 'name1 like :name and product_type is null', array(':name' => '%Jewelry%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'MusicalInstruments'), 'name1 like :name and product_type is null', array(':name' => '%Musical Instruments%'));

		$this->update('xlsws_category_amazon', array('product_type' => 'Office'), 'name1 like :name and product_type is null', array(':name' => '%Office%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'PetSupplies'), 'name1 like :name and product_type is null', array(':name' => '%Pet Supplies%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'Shoes'), 'name1 like :name and product_type is null', array(':name' => '%Shoes%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'Sports'), 'name1 like :name and product_type is null', array(':name' => '%Sports & Outdoors%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'SWVG'), 'name1 like :name and product_type is null', array(':name' => '%Software%'));

		$this->update('xlsws_category_amazon', array('product_type' => 'SWVG'), 'name1 like :name and product_type is null', array(':name' => '%Video Games%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'TiresAndWheels'), 'name2 like :name and product_type is null', array(':name' => '%Tires & Wheels%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'Tools'), 'name1 like :name and product_type is null', array(':name' => '%Tools & Home Improvement%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'Toys'), 'name1 like :name and product_type is null', array(':name' => '%Toys & Games%'));
		$this->update('xlsws_category_amazon', array('product_type' => 'ToysBaby'), 'name2 like :name and product_type is null', array(':name' => '%Baby & Toddler Toys%'));

	}

	public function down()
	{
		echo "m140429_205059_update_amazon does not support migration down.\n";

	}


}