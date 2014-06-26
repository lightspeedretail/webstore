<?php

class m140610_121645_WS_2131_Wishlist_creation_error extends CDbMigration
{
	public function up()
	{
		$this->dropColumn('xlsws_wishlist', 'html_content');
	}

	public function down()
	{
		$this->addColumn('xlsws_wishlist', 'html_content', 'varchar(255) NOT NULL DEFAULT \'\'');
	}


}