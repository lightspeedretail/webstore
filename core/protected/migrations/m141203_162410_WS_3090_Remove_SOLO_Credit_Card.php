<?php

class m141203_162410_WS_3090_Remove_SOLO_Credit_Card extends CDbMigration
{

	public function up()
	{
		$this->delete('xlsws_credit_card', 'validfunc = :key', array('key' => 'SOLO'));
	}

	public function down()
	{
		$this->insert('xlsws_credit_card', array(
			'id' => 9,
			'label' => 'Solo',
			'sort_order' => '0',
			'enabled' => '0',
			'validfunc' => 'SOLO',
			'modified' => '2013-11-05 19:46:01'
			));

	}
}
