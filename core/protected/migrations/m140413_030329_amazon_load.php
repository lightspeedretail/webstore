<?php

class m140413_030329_amazon_load extends CDbMigration
{
	const ASSET_FILE = '/assets/xlsws_category_amazon.csv';
	const TABLE_NAME = 'xlsws_category_amazon';

	// Size of batched inserts.
	const BATCH_SIZE = 200;

	// Number of lines to skip at the start of the CSV file.
	const LINES_TO_SKIP = 1;

	public function up()
	{
		$strAssetFile = realpath(dirname(__FILE__)) . self::ASSET_FILE;
		if (!$importcsv = fopen($strAssetFile, "r"))
		{
			echo "could not open asset file " . $strAssetFile;
			return false;
		}

		$dbComponent = $this->dbConnection;
		$columnNames = $dbComponent->schema->getTable(self::TABLE_NAME)->columnNames;

		$intLineNumber = 0;
		$arrBatchData = array();

		// Skip over the header line.
		$arrLineData = fgetcsv($importcsv, ",");
		while ($intLineNumber < self::LINES_TO_SKIP)
		{
			$arrLineData = fgetcsv($importcsv, ",");
			if ($arrLineData === false)
				break;

			$intLineNumber += 1;
		}

		while ($arrLineData)
		{
			$arrRowData = array();
			foreach($arrLineData as $colIdx => $colVal)
			{
				if($colVal !== "NULL")
				{
					$colName = $columnNames[$colIdx];
					$arrRowData[$colName] = $colVal;
				}
			}

			$arrBatchData[] = $arrRowData;

			if (count($arrBatchData) === self::BATCH_SIZE)
			{
				$this->batchInsert($dbComponent, $arrBatchData);
				$arrBatchData = array();
			}

			$arrLineData = fgetcsv($importcsv, ",");
		}

		// Insert any remaining data.
		if (count($arrBatchData))
		{
			$this->batchInsert($dbComponent, $arrBatchData);
		}
	}

	public function down()
	{
		$this->truncateTable(self::TABLE_NAME);
	}

	protected function batchInsert($dbComponent, $arrDataInsert)
	{
		$builder = $dbComponent->schema->commandBuilder;
		$command = $builder->createMultipleInsertCommand(
			self::TABLE_NAME,
			$arrDataInsert
		);

		$command->execute();
	}
}
