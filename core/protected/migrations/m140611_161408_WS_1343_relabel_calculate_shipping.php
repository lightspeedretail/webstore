<?php

class m140611_161408_WS_1343_relabel_calculate_shipping extends CDbMigration
{
	public function up()
	{
		$this->update(
			'xlsws_stringsource',
			array('message' => 'Calculate Shipping'),
			'category = :category AND message = :str',
			array(':category' => 'category',':str' => 'Click to Calculate Shipping')
		);
	}

	public function down()
	{
		$this->update(
			'xlsws_stringsource',
			array('message' => 'Click to Calculate Shipping'),
			'category = :category AND message = :str',
			array(':category' => 'category',':str' => 'Calculate Shipping')
		);
	}


}