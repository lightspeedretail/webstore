<?php

class m140429_223913_update_promo_codes extends CDbMigration
{

	public function up()
	{

		$this->update(
			'xlsws_promo_code',
			array('valid_from' => null),
			'valid_from = :id',
			array(':id' => '0000-00-00')
		);

		$this->update(
			'xlsws_promo_code',
			array('valid_until' => null),
			'valid_until = :id',
			array(':id' => '0000-00-00')
		);



	}

	public function down()
	{
		echo "m140429_223913_update_promo_codes does not support migration down.\n";

	}

}