<?php

class m141204_175432_WS_1936_CustomPageColumnChange extends CDbMigration
{
	public function up()
	{
		$this->renameColumn('xlsws_custom_page', 'page', 'page_data');

	}

	public function down()
	{
		$this->renameColumn('xlsws_custom_page', 'page_data', 'page');
	}
}
