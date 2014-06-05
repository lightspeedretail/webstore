<?php

class m140531_144806_WS_2094_Amazon_NULLs_and_Custom_Pages_Tabs extends CDbMigration
{
	public function up()
	{

		$this->update('xlsws_category_amazon', array('name2' => NULL), 'name2 = :str', array(':str' => ''));
		$this->update('xlsws_category_amazon', array('name3' => NULL), 'name3 = :str', array(':str' => ''));
		$this->update('xlsws_category_amazon', array('name4' => NULL), 'name4 = :str', array(':str' => ''));
		$this->update('xlsws_category_amazon', array('name5' => NULL), 'name5 = :str', array(':str' => ''));
		$this->update('xlsws_category_amazon', array('name6' => NULL), 'name6 = :str', array(':str' => ''));
		$this->update('xlsws_category_amazon', array('name7' => NULL), 'name7 = :str', array(':str' => ''));
		$this->update('xlsws_category_amazon', array('name8' => NULL), 'name8 = :str', array(':str' => ''));
		$this->update('xlsws_category_amazon', array('name9' => NULL), 'name9 = :str', array(':str' => ''));

		$this->update('xlsws_category_amazon', array('item_type' => NULL), 'item_type = :str', array(':str' => ''));
		$this->update('xlsws_category_amazon', array('product_type' => NULL), 'product_type = :str', array(':str' => ''));
		$this->update('xlsws_category_amazon', array('refinements' => NULL), 'refinements = :str', array(':str' => ''));

		$this->update('xlsws_custom_page', array('tab_position' => 12), 'page_key = :key AND tab_position IS NULL', array(':key' => 'top'));
		$this->update('xlsws_custom_page', array('tab_position' => 11), 'page_key = :key AND tab_position IS NULL', array(':key' => 'new'));
		$this->update('xlsws_custom_page', array('tab_position' => 13), 'page_key = :key AND tab_position IS NULL', array(':key' => 'promo'));
		$this->update('xlsws_custom_page', array('tab_position' => 21), 'page_key = :key AND tab_position IS NULL', array(':key' => 'about'));
		$this->update('xlsws_custom_page', array('tab_position' => 23), 'page_key = :key AND tab_position IS NULL', array(':key' => 'privacy'));
		$this->update('xlsws_custom_page', array('tab_position' => 22), 'page_key = :key AND tab_position IS NULL', array(':key' => 'tc'));
		$this->update('xlsws_custom_page', array('tab_position' => 14), 'page_key = :key AND tab_position IS NULL', array(':key' => 'contactus'));
		$this->update('xlsws_custom_page', array('tab_position' => 0), 'page_key = :key AND tab_position IS NULL', array(':key' => 'Welcome'));

	}

	public function down()
	{

	}
}