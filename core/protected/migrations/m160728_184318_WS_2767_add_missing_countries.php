<?php

class m160728_184318_WS_2767_add_missing_countries extends CDbMigration
{
	public function up()
	{
		$table = 'xlsws_country';

		// Add missing countries

		$this->insert(
			$table,
			array(
				'code' => 'AD',
				'region' => 'LA',
				'active' => 1,
				'sort_order' => 10,
				'country' => 'Andorra',
			)
		);

		$this->insert(
			$table,
			array(
				'code' => 'CW',
				'region' => 'LA',
				'active' => 1,
				'sort_order' => 10,
				'country' => 'Curaçao',
			)
		);

		$this->insert(
			$table,
			array(
				'code' => 'SX',
				'region' => 'LA',
				'active' => 1,
				'sort_order' => 10,
				'country' => 'Sint Maarten',
			)
		);

		// Update country names
		$this->update($table, ['country' => 'Bosnia-Herzegovina'], 'code = :code', [':code' => 'BA']);
		$this->update($table, ['country' => "Cote d'Ivoire"], 'code = :code', [':code' => 'CI']);
		$this->update($table, ['country' => 'Falkland Islands'], 'code = :code', [':code' => 'FK']);
		$this->update($table, ['country' => 'North Korea'], 'code = :code', [':code' => 'KP']);
		$this->update($table, ['country' => 'South Korea'], 'code = :code', [':code' => 'KR']);
		$this->update($table, ['country' => 'Pitcairn Island'], 'code = :code', [':code' => 'PN']);
		$this->update($table, ['country' => 'Vietnam'], 'code = :code', [':code' => 'VN']);
	}

	public function down()
	{
		// Remove added countries
		$this->delete('xlsws_country', 'code = :code', array('code' => 'SX'));
		$this->delete('xlsws_country', 'code = :code', array('code' => 'CW'));
		$this->delete('xlsws_country', 'code = :code', array('code' => 'AD'));

		// Leave updated country names as is.
	}
}
