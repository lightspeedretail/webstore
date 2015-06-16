<?php

class m150511_184017_WS_3017_google_universal_analytics extends CDbMigration
{
	public function up()
	{
		$installed = $this->getDbConnection()->createCommand()
			->select('key_value')
			->from('xlsws_configuration')
			->where('key_name = :key', array(':key' => 'INSTALLED'))
			->queryScalar();

		// insert new config keys but make sure they don't already exist
		$sql = "SELECT * FROM xlsws_configuration WHERE key_name = 'GOOGLE_UA';";
		$row = $this->dbConnection->createCommand($sql)->queryRow();
		if (empty($row))
		{
			$this->insert(
				'xlsws_configuration',
				array(
					'title' => 'Google Universal Analytics',
					'key_name' => 'GOOGLE_UA',
					'key_value' => intval(!$installed), // ON for new customers. OFF for Existing Customers.
					'helper_text' => 'Turn on to use Universal Analytics. Turn off to use Classic Analytics.',
					'configuration_type_id' => 20,
					'sort_order' => 0,
					'options' => 'BOOL',
					'template_specific' => 0,
					'required' => 0
				)
			);
		}

		$sql = "SELECT * FROM xlsws_configuration WHERE key_name = 'GOOGLE_LABEL';";
		$row = $this->dbConnection->createCommand($sql)->queryRow();
		if (empty($row))
		{
			$this->insert(
				'xlsws_configuration',
				array(
					'title' => 'Google AdWords Conversion Label',
					'key_name' => 'GOOGLE_LABEL',
					'key_value' => '',
					'helper_text' => "Google AdWords Conversion Label (found in line 'var google_conversion_label' of Tag code in Conversion settings)",
					'configuration_type_id' => 20,
					'sort_order' => 3,
					'template_specific' => 0,
					'required' => 0
				)
			);
		}

		$this->update(
			'xlsws_configuration',
			array(
				'title' => 'Google AdWords Conversion ID',
				'helper_text' => "Google AdWords Conversion ID (found in line 'var google_conversion_id' of Tag code in Conversion settings)"
			),
			'key_name = "GOOGLE_ADWORDS"'
		);

		$this->update(
			'xlsws_configuration',
			array('sort_order' => 4),
			'key_name = "GOOGLE_VERIFY"'
		);
	}

	public function down()
	{
		$this->delete(
			'xlsws_configuration',
			'key_name = :key',
			array('key' => 'GOOGLE_UA')
		);

		$this->delete(
			'xlsws_configuration',
			'key_name = :key',
			array('key' => 'GOOGLE_LABEL')
		);

		$this->update(
			'xlsws_configuration',
			array('sort_order' => 3),
			'key_name = "GOOGLE_VERIFY"'
		);
	}

}