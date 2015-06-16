<?php

class m150611_193542_WS_4297_disable_amazon_integration extends CDbMigration
{
	public function up()
	{
		$installed = $this->getDbConnection()->createCommand()
			->select('key_value')
			->from('xlsws_configuration')
			->where('key_name = :key', array(':key' => 'INSTALLED'))
			->queryScalar();

		$this->insert(
			'xlsws_configuration',
			array(
				'title' => 'Show Amazon Integration Menu',
				'key_name' => 'SHOW_AMAZON_INTEGRATION',
				'key_value' => intval($installed),
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'template_specific' => 0,
				'required' => 0
			)
		);
	}

	public function down()
	{
		$this->delete(
			'xlsws_configuration',
			'key_name = :key',
			array('key' => 'SHOW_AMAZON_INTEGRATION')
		);
	}
}