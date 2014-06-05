<?php


Yii::import('system.cli.commands.MigrateCommand');

/**
 * We extend the Migrate Command to for use in install
 */
class SetMigrateCommand extends MigrateCommand
{

	public $defaultAction = 'mark';
	/**
	 * Mark this database as past the initial data loading, used for an upgrade.
	 *
	 * @param $args
	 * @return void
	 */
	public function actionMark($args)
	{
		$args = array('m140411_120957_load_misc');
		parent::actionMark($args);
	}
}



