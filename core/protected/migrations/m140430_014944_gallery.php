<?php

class m140430_014944_gallery extends CDbMigration
{
	public function up()
	{
		$this->createTable(
			'xlsws_gallery',
			array(
				'id' => 'pk',
				'versions_data' => 'text NOT NULL',
				'name' => 'tinyint(1) NOT NULL DEFAULT 1',
				'description' => 'tinyint(1) NOT NULL DEFAULT 1',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_gallery_photo',
			array(
				'id' => 'pk',
				'gallery_id' => 'int(11) NOT NULL',
				'rank' => 'int(11) NOT NULL DEFAULT 0',
				'name' => 'varchar(512) NOT NULL DEFAULT \'\'',
				'description' => 'text DEFAULT NULL',
				'file_name' => 'varchar(128) NOT NULL DEFAULT \'\'',
				'thumb_ext' => 'varchar(6) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createIndex('fk_gallery_photo_gallery1', 'xlsws_gallery_photo', 'gallery_id', FALSE);
		$this->addForeignKey('fk_xlsws_gallery_photo_xlsws_gallery_gallery_id', 'xlsws_gallery_photo', 'gallery_id', 'xlsws_gallery', 'id', 'NO ACTION', 'NO ACTION');

		$this->execute("set @exist := (select count(*) from information_schema.statistics where table_name = 'xlsws_images_cloud' and index_name = 'cloud_image_id' AND TABLE_SCHEMA = DATABASE());
			set @sqlstmt := if( @exist > 0, 'select ''INFO: Index already exists.''', 'create index cloud_image_id on xlsws_images_cloud ( cloud_image_id )');
			PREPARE stmt FROM @sqlstmt;
			EXECUTE stmt;");

	}

	public function down()
	{
		$this->dropTable('xlsws_gallery');
		$this->dropTable('xlsws_gallery_photo');
		$this->dropForeignKey('fk_xlsws_gallery_photo_xlsws_gallery_gallery_id', 'xlsws_gallery_photo');

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