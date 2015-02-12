<?php

class m140919_135052_WS_2638_store_pickup_email extends CDbMigration
{
	public function up()
	{
		// add columns for WS-2638 Finalize checkout - Store Pickup

		// get all columns from the table
		$dbComponent = $this->dbConnection;
		$arr = $dbComponent->schema->getTable('xlsws_customer_address')->columns;

		// add column if not exists
		if (!array_key_exists('store_pickup_email', $arr))
		{
			$this->addColumn(
				'xlsws_customer_address',
				'store_pickup_email',
				'VARCHAR(255) DEFAULT NULL AFTER `last_name`'
			);
		}
	}

	public function down()
	{
		echo "m140919_135052_WS_2638_store_pickup_email does not support migration down.\n";
		return false;
	}

}