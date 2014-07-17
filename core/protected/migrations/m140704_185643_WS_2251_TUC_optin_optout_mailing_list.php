<?php

class m140704_185643_WS_2251_TUC_optin_optout_mailing_list extends CDbMigration
{
	public function up()
	{
		$this->insert(
			'xlsws_configuration',
			array(
				'title' => 'Customers must OPT IN to mailing list',
				'key_name' => 'DISABLE_ALLOW_NEWSLETTER',
				'key_value' => '1',
				'helper_text' => 'Determines whether the “Allow Us To Send You Emails About Our Products” checkbox is enabled or disabled by default to comply with CASL requirements.',
				'configuration_type_id' => 3,
				'sort_order' => 6,
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

	}

	public function down()
	{
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'DISABLE_ALLOW_NEWSLETTER'));
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}