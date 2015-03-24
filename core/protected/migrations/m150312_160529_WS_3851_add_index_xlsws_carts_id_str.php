<?php

class m150312_160529_WS_3851_add_index_xlsws_carts_id_str extends CDbMigration
{
	public function up()
	{
		$this->createIndex('id_str', 'xlsws_cart', 'id_str', FALSE);
	}

	public function down()
	{
		$this->dropIndex('id_str', 'xlsws_cart');
	}
}