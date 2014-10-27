<?php

class m140828_005057_WS_2486_finalize_checkout_aim extends CDbMigration
{
	public function up()
	{
		// add columns for WS-2486 Finalize Checkout AIM

		// get all columns from the table
		$dbComponent = $this->dbConnection;
		$arr = $dbComponent->schema->getTable('xlsws_cart_payment')->columns;

		// add column if not exists
		if (!array_key_exists('payment_card', $arr))
		{
			$this->addColumn(
				'xlsws_cart_payment',
				'payment_card',
				'VARCHAR(50) DEFAULT NULL  AFTER `payment_data`'
			);
		}

		// add column if not exists
		if (!array_key_exists('card_digits', $arr))
		{
			$this->addColumn(
				'xlsws_cart_payment',
				'card_digits',
				'VARCHAR(4) DEFAULT NULL  AFTER `datetime_posted`'
			);
		}

	}

	public function down()
	{
		echo "m140828_005057_WS_2486_finalize_checkout_aim does not support migration down.\n";
		return false;
	}

}