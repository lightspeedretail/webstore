<?php

class m150525_143928_WS_2518_remove_zoomed_image_size_restriction extends CDbMigration
{
	public function up()
	{
		$retail = $this->getDbConnection()->createCommand()
			->select('key_value')
			->from('xlsws_configuration')
			->where('key_name = :key', array(':key' => 'LIGHTSPEED_CLOUD'))
			->queryScalar();

		if (intval($retail) > 0)
		{
			$this->update(
				'xlsws_images',
				array('width' => null, 'height' => null)
			);
		}
	}

	public function down()
	{
		$retail = $this->getDbConnection()->createCommand()
			->select('key_value')
			->from('xlsws_configuration')
			->where('key_name = :key', array(':key' => 'LIGHTSPEED_CLOUD'))
			->queryScalar();

		if (intval($retail) > 0)
		{
			$this->update(
				'xlsws_images',
				array('width' => 512, 'height' => 512)
			);
		}
	}
}
