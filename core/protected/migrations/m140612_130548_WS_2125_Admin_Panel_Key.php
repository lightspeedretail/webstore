<?php

class m140612_130548_WS_2125_Admin_Panel_Key extends CDbMigration
{
	public function up()
	{
		$this->insert(
			'xlsws_configuration',
			array(
				'title' => 'Admin Panel Opened',
				'key_name' => 'ADMIN_PANEL',
				'key_value' => '',
				'helper_text' => '',
				'configuration_type_id' => 0,
				'sort_order' => 1,
				'options' => '',
				'template_specific' => 0,
				'param' => 0,
				'required' => '1',
			)
		);
		$this->insert('xlsws_modules',array(
				'active' => '0',
				'module' => 'wsgooglemerchant',
				'category' => 'xml',
				'version' => '1',
				'name' => 'Google Merchant XML',
				'sort_order' => '1',
				'configuration' => ''
			));
	}

	public function down()
	{
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'ADMIN_PANEL'));
		$this->delete('xlsws_modules', 'module = :key', array('key' => 'wsgooglemerchant'));

	}
}