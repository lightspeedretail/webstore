<?php

class m140704_193530_WS_2271_block_access_runtime extends CDbMigration
{
	public function up()
	{
		// Add new line to .htaccess to block access to runtime folder
		$fileHtaccess = YiiBase::getPathOfAlias('application')
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . '.htaccess';

		if (file_exists($fileHtaccess))
		{
			$strHtaccessContent = file_get_contents($fileHtaccess);

			if ($strHtaccessContent && strpos($strHtaccessContent, "RedirectMatch 404 /runtime/") === false)
			{
				$strToAppend = "\n# block runtime folder"
					. "\nRedirectMatch 404 /runtime/";
				file_put_contents($fileHtaccess, $strToAppend, FILE_APPEND | LOCK_EX);
			}
		}
	}

	public function down()
	{
		echo "m140704_193530_WS_2271_block_access_runtime does not support migration down.\n";
		return false;
	}
}