<?php


Yii::import('system.cli.commands.MigrateCommand');

/**
 * We extend the Migrate Command to forcibly run 1 up migration only, for use in install
 */
class SingleMigrateCommand extends MigrateCommand
{

	/**
	 * Apply a single database upgrade step.
	 *
	 * @param $args
	 * @return void
	 */
	public function actionUp($args)
	{
		$args = array(1);
		parent::actionUp($args);
	}
}



