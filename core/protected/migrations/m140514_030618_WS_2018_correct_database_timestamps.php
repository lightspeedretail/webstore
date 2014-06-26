<?php

class m140514_030618_WS_2018_correct_database_timestamps extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('xlsws_log','created','timestamp NULL DEFAULT CURRENT_TIMESTAMP');
		$this->alterColumn('xlsws_category','modified','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_category','created','timestamp NULL DEFAULT CURRENT_TIMESTAMP');
		$this->alterColumn('xlsws_category_addl','modified','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_category_addl','created','timestamp NULL DEFAULT CURRENT_TIMESTAMP');
		$this->alterColumn('xlsws_configuration','modified','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_configuration','created','timestamp NULL DEFAULT CURRENT_TIMESTAMP');
		$this->alterColumn('xlsws_custom_page','modified','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_custom_page','created','timestamp NULL DEFAULT CURRENT_TIMESTAMP');
		$this->alterColumn('xlsws_customer','modified','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_customer','created','timestamp NULL DEFAULT CURRENT_TIMESTAMP');
		$this->alterColumn('xlsws_customer_address','modified','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_customer_address','created','timestamp NULL DEFAULT CURRENT_TIMESTAMP');
		$this->alterColumn('xlsws_images','modified','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_images','created','timestamp NULL DEFAULT CURRENT_TIMESTAMP');
		$this->alterColumn('xlsws_modules','modified','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_modules','created','timestamp NULL DEFAULT CURRENT_TIMESTAMP');
		$this->alterColumn('xlsws_product','modified','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_product','created','timestamp NULL DEFAULT CURRENT_TIMESTAMP');
		$this->alterColumn('xlsws_task_queue','modified','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_task_queue','created','timestamp NULL DEFAULT CURRENT_TIMESTAMP');

		$this->alterColumn('xlsws_wishlist','modified','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_wishlist','created','timestamp NULL DEFAULT CURRENT_TIMESTAMP');
		$this->alterColumn('xlsws_wishlist_item','modified','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_wishlist_item','created','timestamp NULL DEFAULT CURRENT_TIMESTAMP');


		$this->tryCreateIndex('product_id', 'xlsws_product_category_assn', 'product_id', FALSE);
		$this->tryCreateIndex('category_id', 'xlsws_product_category_assn', 'category_id', FALSE);
		$this->tryCreateIndex('language', 'xlsws_stringtranslate', 'language', FALSE);
	}

	private function tryCreateIndex($indexName,$tableName,$column,$unique)
	{
		try
		{
			$this->createIndex($indexName, $tableName, $column, $unique);
		} catch (CDbException $e)
		{
			if ($e->errorInfo[1] !== 1061) // 1061 Duplicate key name
			{
				throw new Exception('Error creating key', 0, $e);  // not the error we were prepared to skip.
			}
		}
	}

	public function down()
	{
		$this->alterColumn('xlsws_log','created','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_category','created','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_category_addl','created','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_configuration','created','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_custom_page','created','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_customer','created','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_customer_address','created','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_images','created','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_modules','created','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_product','created','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_task_queue','created','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_wishlist','created','datetime DEFAULT NULL');
		$this->alterColumn('xlsws_wishlist_item','created','datetime DEFAULT NULL');

		$this->dropIndex('product_id', 'xlsws_product_category_assn');
		$this->dropIndex('category_id', 'xlsws_product_category_assn');
		$this->dropIndex('language', 'xlsws_stringtranslate');
	}
}