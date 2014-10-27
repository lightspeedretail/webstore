<?php

class m140911_015316_WS_2638_store_address_updates extends CDbMigration
{
	public function up()
	{
		// update sort order of existing keys to make room for new ones
		$this->execute('UPDATE xlsws_configuration SET sort_order = sort_order + 4 WHERE sort_order >= 7 AND configuration_type_id = 2;');

		// insert new config keys but make sure they don't already exist
		$sql = "SELECT * FROM xlsws_configuration WHERE key_name = 'STORE_CITY';";
		$row = $this->dbConnection->createCommand($sql)->queryRow();
		if (empty($row))
		{
			$this->insert(
				'xlsws_configuration',
				array(
					'title' => 'Store City',
					'key_name' => 'STORE_CITY',
					'key_value' => 'Anytown',
					'helper_text' => 'City',
					'configuration_type_id' => 2,
					'sort_order' => 7,
					'template_specific' => 0,
					'required' => 0
				)
			);
		}

		$sql = "SELECT * FROM xlsws_configuration WHERE key_name = 'STORE_STATE';";
		$row = $this->dbConnection->createCommand($sql)->queryRow();
		if (empty($row))
		{
			$this->insert('xlsws_configuration',array(
				'title' => 'Store State/Province',
				'key_name' => 'STORE_STATE',
				'key_value' => 44,
				'helper_text' => 'State / Province',
				'configuration_type_id' => 2,
				'sort_order' => 8,
				'options' => 'STATE',
				'template_specific' => 0,
				'required' => 0,
			));
		}

		$sql = "SELECT * FROM xlsws_configuration WHERE key_name = 'STORE_COUNTRY';";
		$row = $this->dbConnection->createCommand($sql)->queryRow();
		if (empty($row))
		{
			$this->insert('xlsws_configuration',array(
				'title' => 'Store Country',
				'key_name' => 'STORE_COUNTRY',
				'key_value' => 224,
				'helper_text' => 'Country',
				'configuration_type_id' => 2,
				'sort_order' => 9,
				'options' => 'COUNTRY',
				'template_specific' => 0,
				'required' => 0,
			));
		}

		$sql = "SELECT * FROM xlsws_configuration WHERE key_name = 'STORE_ZIP';";
		$row = $this->dbConnection->createCommand($sql)->queryRow();
		if (empty($row))
		{
			$this->insert('xlsws_configuration',array(
				'title' => 'Store Zip / Postal',
				'key_name' => 'STORE_ZIP',
				'key_value' => '12345',
				'helper_text' => 'Zip / Postal Code',
				'configuration_type_id' => 2,
				'sort_order' => 10,
				'template_specific' => 0,
				'required' => 0,
			));
		}

		$this->update(
			'xlsws_configuration',
			array('title' => 'Store Address 2'),
			'key_name = :key',
			array(':key' => 'STORE_ADDRESS2')
		);
	}

	public function down()
	{
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'STORE_CITY'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'STORE_STATE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'STORE_COUNTRY'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'STORE_ZIP'));
		$this->execute('UPDATE xlsws_configuration SET sort_order = sort_order - 4 WHERE sort_order >= 11 AND configuration_type_id = 2;');
		$this->update(
			'xlsws_configuration',
			array('title' => 'Store City, State, Zip'),
			'key_name = :key',
			array(':key' => 'STORE_ADDRESS2')
		);
	}
}