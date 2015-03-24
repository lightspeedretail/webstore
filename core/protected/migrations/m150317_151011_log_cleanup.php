<?php

class m150317_151011_log_cleanup extends CDbMigration
{
	public function up()
	{
		// This migration could take some time, depending on the number of rows
		// in your xlsws_log table.  Tested on a table with 8 million rows, 6.2
		// million of them that had created = NULL and it took 276 seconds,
		// almost 5 minutes.
		$this->execute("DELETE FROM `xlsws_log` WHERE `created` IS NULL");
	}

	public function down()
	{
		// While you can't technically roll this transaction back, since it
		// doesn't change the schema, it makes sense to allow the system to roll
		// back through this change to attempt to rollback the next one as well.
		return true;
	}
}
