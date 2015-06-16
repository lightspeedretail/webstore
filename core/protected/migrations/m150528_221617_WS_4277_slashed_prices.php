<?php

class m150528_221617_WS_4277_slashed_prices extends CDbMigration
{
	public function up()
	{
		$this->alterColumn(
			'xlsws_configuration',
			'helper_text',
			'MEDIUMTEXT'
		);

		$this->update(
			'xlsws_configuration',
			array(
				'title' => 'Show Strikethrough Pricing',
				'helper_text' => 'If you have dedicated Web Store product ' .
					'prices that are lower than the original product prices, ' .
					'enable this option to display both the original price with ' .
					'a line drawn through it (strikethrough), and the Web Store ' .
					'price as the sale price. If disabled, just the Web Store ' .
					'price will be displayed.'
			),
			'key_name = :key',
			array(':key' => 'ENABLE_SLASHED_PRICES')
		);
	}

	public function down()
	{
		$this->update(
			'xlsws_configuration',
			array(
				'title' => 'Enabled Slashed "Original" Prices',
				'helper_text' => 'If selected, will display original price slashed out and Web Price as a Sale Price.'
			),
			'key_name = :key',
			array(':key' => 'ENABLE_SLASHED_PRICES')
		);

		$this->alterColumn(
			'xlsws_configuration',
			'helper_text',
			'VARCHAR(255)'
		);

		return true;
	}
}
