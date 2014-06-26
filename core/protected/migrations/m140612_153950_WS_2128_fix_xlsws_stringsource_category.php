<?php

class m140612_153950_WS_2128_fix_xlsws_stringsource_category extends CDbMigration
{
	public function up()
	{
		// Rules:
		// 1. If an entry exists in xlsws_stringtranslate and the entry is
		//    different to xlsws_stringsource then the user has modified the
		//    translation.
		//

		// Find stringtranslate rows where the translation is the same as the srouce.
		$rowsToDelete = $this->dbConnection->createCommand()
			->select()
			->from('xlsws_stringtranslate st')
			->where("ss.category = 'categories'")
			->andWhere("st.translation = ss.message")
			->join(
				'xlsws_stringsource ss',
				'st.id = ss.id'
			);

		// Delete these stringtranslate rows.
		while ($rowToDelete = $rowsToDelete->queryRow())
		{
			$this->dbConnection->createCommand()
				->delete('xlsws_stringtranslate', 'id = :id', array(':id' => $rowToDelete['id']));

			echo "Deleted from stringtranslate where id=" . $rowToDelete['id'] . "\n";
		}

		// Now find any stringsource rows which don't have a corresponding translation and delete them.
		$rowsToDelete = $this->dbConnection->createCommand()
			->select()
			->from('xlsws_stringsource ss')
			->where("ss.category = 'categories'")
			->andWhere("NOT EXISTS (select 1 from xlsws_stringtranslate st where ss.id = st.id)");

		// Delete these stringsource rows.
		while ($rowToDelete = $rowsToDelete->queryRow())
		{
			$this->dbConnection->createCommand()
				->delete('xlsws_stringsource', 'id = :id', array(':id' => $rowToDelete['id']));

			echo "Deleted from stringsource where id=" . $rowToDelete['id'] . "\n";
		}
	}

	public function down()
	{
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
