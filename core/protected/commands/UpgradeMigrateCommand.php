<?php


Yii::import('system.cli.commands.MigrateCommand');

/**
 * We extend the Migrate Command to for use in the first db update for existing stores
 */
class UpgradeMigrateCommand extends MigrateCommand
{

	public $defaultAction='mark';
	/**
	 * Mark this database as past the initial data loading, used for an upgrade.
	 *
	 * @param $args
	 * @return void
	 */
	public function actionMark($args)
	{
		$args = array('m140430_200509_ship_pay_cc_updates');
		parent::actionMark($args);
	}
}



