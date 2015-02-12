<?php

class m000000_000001_legacy_schema_patch extends CDbMigration
{
  public function getSchema()
  {
    $result = $this->getDbConnection()->createCommand()
      ->select('key_value')
      ->from('xlsws_configuration')
      ->where('key_name = :key', array(':key' => 'DATABASE_SCHEMA_VERSION'))
      ->queryScalar();
    return $result;
  }

  public function setSchema($intSchema)
  {
    $this->update('xlsws_configuration', 
      array('key_value' => $intSchema),
      'key_name = :key',
      array(':key' => 'DATABASE_SCHEMA_VERSION')
    );
  }

	public function up()
  {
    $intSchema = $this->getSchema();
    if($intSchema !== false && (int)$intSchema < 447)
    {
      do {
        $intSchema++;

        switch($intSchema)
        {
        case 304:
          $this->update('xlsws_configuration', 
            array('sort_order' => 99),
            'key_name = :key',
            array(':key' => 'DEBUG_LOGGING')
          );
          break;
        case 326:
          $this->alterColumn('xlsws_log',
            'message',
            'LONGTEXT  CHARACTER SET utf8 COLLATE utf8_general_ci NULL'
          );
          break;
        case 431:
          $this->update('xlsws_configuration',
            array('key_name' => 'LIGHTSPEED_HOSTING_CUSTOM_URL'),
            'key_name = :key',
            array(':key' => 'LIGHTSPEED_HOSTING_ORIGINAL_URL')
          );
          break;
        case 432:
          $this->update('xlsws_configuration',
            array('key_name' => 'LIGHTSPEED_HOSTING_LIGHTSPEED_URL'),
            'key_name = :key',
            array(':key' => 'LIGHTSPEED_HOSTING_SSL_URL')
          );
          break;
        case 433:
          $this->update('xlsws_configuration',
            array('key_name' => 'LIGHTSPEED_HOSTING_COMMON_SSL'),
            'key_name = :key',
            array(':key' => 'LIGHTSPEED_HOSTING_SHARED_URL')
          );
          break;
        case 436:
          $this->alterColumn('xlsws_category',
            'label',
            'VARCHAR(255)'
          );
          break;
        }

        $this->setSchema($intSchema);
      } while ($intSchema < 448);
    }
	}

	public function down()
  {
    echo "m000000_000001_legacy_schema_patch does not support migration down.\n";
	}
}
