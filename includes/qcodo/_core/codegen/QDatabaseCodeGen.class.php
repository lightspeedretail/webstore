<?php
	require(__QCODO_CORE__ . '/codegen/QColumn.class.php');
	require(__QCODO_CORE__ . '/codegen/QIndex.class.php');
	require(__QCODO_CORE__ . '/codegen/QManyToManyReference.class.php');
	require(__QCODO_CORE__ . '/codegen/QReference.class.php');
	require(__QCODO_CORE__ . '/codegen/QReverseReference.class.php');
	require(__QCODO_CORE__ . '/codegen/QTable.class.php');
	require(__QCODO_CORE__ . '/codegen/QTypeTable.class.php');

	class QDatabaseCodeGen extends QCodeGen {
		// Objects
		protected $objTableArray;
		protected $strExcludedTableArray;
		protected $objTypeTableArray;
		protected $strAssociationTableNameArray;
		protected $objDb;

		protected $intDatabaseIndex;

		// Table Suffixes
		protected $strTypeTableSuffix;
		protected $intTypeTableSuffixLength;
		protected $strAssociationTableSuffix;
		protected $intAssociationTableSuffixLength;

		// Table Prefix
		protected $strStripTablePrefix;
		protected $intStripTablePrefixLength;

		// Exclude Patterns & Lists
		protected $strExcludePattern;
		protected $strExcludeListArray;

		// Include Patterns & Lists
		protected $strIncludePattern;
		protected $strIncludeListArray;

		// Uniquely Associated Objects
		protected $strAssociatedObjectPrefix;
		protected $strAssociatedObjectSuffix;

		// Manual Query (e.g. "Beta 2 Query") Suppor
		protected $blnManualQuerySupport = false;

		// Relationship Scripts
		protected $strRelationships;
		protected $blnRelationshipsIgnoreCase;

		protected $strRelationshipsScriptPath;
		protected $strRelationshipsScriptFormat;
		protected $blnRelationshipsScriptIgnoreCase;
		
		protected $strRelationshipLinesQcodo = array();
		protected $strRelationshipLinesSql = array();

		// Type Table Items, Table Name and Column Name RegExp Patterns
		protected $strPatternTableName = '[[:alpha:]_][[:alnum:]_]*';
		protected $strPatternColumnName = '[[:alpha:]_][[:alnum:]_]*';
		protected $strPatternKeyName = '[[:alpha:]_][[:alnum:]_]*';

		public function GetTable($strTableName) {
			$strTableName = strtolower($strTableName);
			if (array_key_exists($strTableName, $this->objTableArray))
				return $this->objTableArray[$strTableName];
			throw new QCallerException(sprintf('Table does not exist or does not have a defined Primary Key: %s', $strTableName));
		}

		public function GetColumn($strTableName, $strColumnName) {
			try {
				$objTable = $this->GetTable($strTableName);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			$strColumnName = strtolower($strColumnName);
			if (array_key_exists($strColumnName, $objTable->ColumnArray))
				return $objTable->ColumnArray[$strColumnName];
			throw new QCallerException(sprintf('Column does not exist in %s: %s', $strTableName, $strColumnName));
		}
		
		/**
		 * Given a CASE INSENSITIVE table and column name, it will return TRUE if the Table/Column
		 * exists ANYWHERE in the already analyzed database
		 *
		 * @param string $strTableName
		 * @param string $strColumnName
		 * @return boolean true if it is found/validated
		 */
		public function ValidateTableColumn($strTableName, $strColumnName) {
			$strTableName = trim(strtolower($strTableName));
			$strColumnName = trim(strtolower($strColumnName));

			if (array_key_exists($strTableName, $this->objTableArray))
				$strTableName = $this->objTableArray[$strTableName]->Name;
			else if (array_key_exists($strTableName, $this->objTypeTableArray))
				$strTableName = $this->objTypeTableArray[$strTableName]->Name;
			else if (array_key_exists($strTableName, $this->strAssociationTableNameArray))
				$strTableName = $this->strAssociationTableNameArray[$strTableName];
			else
				return false;

			$objFieldArray = $this->objDb->GetFieldsForTable($strTableName);

			foreach ($objFieldArray as $objField) {
				if (trim(strtolower($objField->Name)) == $strColumnName)
					return true;
			}

			return false;
		}

		public function GetTitle() {
			if (array_key_exists($this->intDatabaseIndex, QApplication::$Database)) {
				$objDatabase = QApplication::$Database[$this->intDatabaseIndex];
				return sprintf('Database Index #%s (%s / %s / %s)', $this->intDatabaseIndex, $objDatabase->Adapter, $objDatabase->Server, $objDatabase->Database);
			} else
				return sprintf('Database Index #%s (N/A)', $this->intDatabaseIndex);
		}

		public function GetConfigXml() {
			$strCrLf = "\r\n";
			$strToReturn = sprintf('		<database index="%s">%s', $this->intDatabaseIndex, $strCrLf);
			$strToReturn .= sprintf('			<className prefix="%s" suffix="%s"/>%s', $this->strClassPrefix, $this->strClassSuffix, $strCrLf);
			$strToReturn .= sprintf('			<associatedObjectName prefix="%s" suffix="%s"/>%s', $this->strAssociatedObjectPrefix, $this->strAssociatedObjectSuffix, $strCrLf);
			$strToReturn .= sprintf('			<typeTableIdentifier suffix="%s"/>%s', $this->strTypeTableSuffix, $strCrLf);
			$strToReturn .= sprintf('			<associationTableIdentifier suffix="%s"/>%s', $this->strAssociationTableSuffix, $strCrLf);
			$strToReturn .= sprintf('			<stripFromTableName prefix="%s"/>%s', $this->strStripTablePrefix, $strCrLf);
			$strToReturn .= sprintf('			<excludeTables pattern="%s" list="%s"/>%s', $this->strExcludePattern, implode(',', $this->strExcludeListArray), $strCrLf);
			$strToReturn .= sprintf('			<includeTables pattern="%s" list="%s"/>%s', $this->strIncludePattern, implode(',', $this->strIncludeListArray), $strCrLf);
			$strToReturn .= sprintf('			<manualQuery support="%s"/>%s', ($this->blnManualQuerySupport) ? 'true' : 'false', $strCrLf);
			$strToReturn .= sprintf('			<relationships>%s', $strCrLf);
			if ($this->strRelationships)
				$strToReturn .= sprintf('			%s%s', $this->strRelationships, $strCrLf);
			$strToReturn .= sprintf('			</relationships>%s', $strCrLf);
			$strToReturn .= sprintf('			<relationshipsScript filepath="%s" format="%s"/>%s', $this->strRelationshipsScriptPath, $this->strRelationshipsScriptFormat, $strCrLf);
			$strToReturn .= sprintf('		</database>%s', $strCrLf);
			return $strToReturn;
		}

		public function GetReportLabel() {
			// Setup Report Label
			$intTotalTableCount = count($this->objTableArray) + count($this->objTypeTableArray);
			if ($intTotalTableCount == 0)
				$strReportLabel = 'There were no tables available to attempt code generation.';
			else if ($intTotalTableCount == 1)
				$strReportLabel = 'There was 1 table available to attempt code generation:';
			else
				$strReportLabel = 'There were ' . $intTotalTableCount . ' tables available to attempt code generation:';
				
			return $strReportLabel;
		}

		public function GenerateAll() {
			$strReport = '';

			// Iterate through all the tables, generating one class at a time
			if ($this->objTableArray) foreach ($this->objTableArray as $objTable) {
				if ($this->GenerateTable($objTable)) {
					$intCount = $objTable->ReferenceCount;
					if ($intCount == 0)
						$strCount = '(with no relationships)';
					else if ($intCount == 1)
						$strCount = '(with 1 relationship)';
					else
						$strCount = sprintf('(with %s relationships)', $intCount);
					$strReport .= sprintf("Successfully generated DB ORM Class:   %s %s\r\n", $objTable->ClassName, $strCount);
				} else
					$strReport .= sprintf("FAILED to generate DB ORM Class:       %s\r\n", $objTable->ClassName);
			}

			// Iterate through all the TYPE tables, generating one TYPE class at a time
			if ($this->objTypeTableArray) foreach ($this->objTypeTableArray as $objTypeTable) {
				if ($this->GenerateTypeTable($objTypeTable))
					$strReport .= sprintf("Successfully generated DB Type Class:  %s\n", $objTypeTable->ClassName);
				else
					$strReport .= sprintf("FAILED to generate DB Type class:      %s\n", $objTypeTable->ClassName);
			}

			return $strReport;
		}
		
		public static function GenerateAggregateHelper($objCodeGenArray) {
			$strToReturn = array();

			if (count($objCodeGenArray)) {
				// Standard ORM Tables
				$objTableArray = array();
				foreach ($objCodeGenArray as $objCodeGen) {
					$objCurrentTableArray = $objCodeGen->TableArray;
					foreach ($objCurrentTableArray as $objTable)
						$objTableArray[$objTable->ClassName] = $objTable;
				}

				$mixArgumentArray = array('objTableArray' => $objTableArray);
				if ($objCodeGenArray[0]->GenerateFiles('aggregate_db_orm', $mixArgumentArray))
					$strToReturn[] = 'Successfully generated Aggregate DB ORM file(s)';
				else
					$strToReturn[] = 'FAILED to generate Aggregate DB ORM file(s)';

				// Type Tables
				$objTableArray = array();
				foreach ($objCodeGenArray as $objCodeGen) {
					$objCurrentTableArray = $objCodeGen->TypeTableArray;
					foreach ($objCurrentTableArray as $objTable)
						$objTableArray[$objTable->ClassName] = $objTable;
				}

				$mixArgumentArray = array('objTableArray' => $objTableArray);
				if ($objCodeGenArray[0]->GenerateFiles('aggregate_db_type', $mixArgumentArray))
					$strToReturn[] = 'Successfully generated Aggregate DB Type file(s)';
				else
					$strToReturn[] = 'FAILED to generate Aggregate DB Type file(s)';
			}

			return $strToReturn;
		}

		public function __construct($objSettingsXml) {
			// Setup Local Arrays
			$this->strAssociationTableNameArray = array();
			$this->objTableArray = array();
			$this->objTypeTableArray = array();
			$this->strExcludedTableArray = array();

			// Set the DatabaseIndex
			$this->intDatabaseIndex = QCodeGen::LookupSetting($objSettingsXml, null, 'index', QType::Integer);

			// Append Suffix/Prefixes
			$this->strClassPrefix = QCodeGen::LookupSetting($objSettingsXml, 'className', 'prefix');			
			$this->strClassSuffix = QCodeGen::LookupSetting($objSettingsXml, 'className', 'suffix');
			$this->strAssociatedObjectPrefix = QCodeGen::LookupSetting($objSettingsXml, 'associatedObjectName', 'prefix');
			$this->strAssociatedObjectSuffix = QCodeGen::LookupSetting($objSettingsXml, 'associatedObjectName', 'suffix');

			// Table Type Identifiers
			$this->strTypeTableSuffix = QCodeGen::LookupSetting($objSettingsXml, 'typeTableIdentifier', 'suffix');
			$this->intTypeTableSuffixLength = strlen($this->strTypeTableSuffix);
			$this->strAssociationTableSuffix = QCodeGen::LookupSetting($objSettingsXml, 'associationTableIdentifier', 'suffix');
			$this->intAssociationTableSuffixLength = strlen($this->strAssociationTableSuffix);

			// Stripping TablePrefixes
			$this->strStripTablePrefix = QCodeGen::LookupSetting($objSettingsXml, 'stripFromTableName', 'prefix');
			$this->intStripTablePrefixLength = strlen($this->strStripTablePrefix);

			// Exclude/Include Tables
			$this->strExcludePattern = QCodeGen::LookupSetting($objSettingsXml, 'excludeTables', 'pattern');
			$strExcludeList = QCodeGen::LookupSetting($objSettingsXml, 'excludeTables', 'list');
			$this->strExcludeListArray = explode(',',$strExcludeList);
			array_walk($this->strExcludeListArray, 'array_trim');

			// Include Patterns
			$this->strIncludePattern = QCodeGen::LookupSetting($objSettingsXml, 'includeTables', 'pattern');
			$strIncludeList = QCodeGen::LookupSetting($objSettingsXml, 'includeTables', 'list');
			$this->strIncludeListArray = explode(',',$strIncludeList);
			array_walk($this->strIncludeListArray, 'array_trim');

			// ManualQuery Support
			$this->blnManualQuerySupport = QCodeGen::LookupSetting($objSettingsXml, 'manualQuery', 'support', QType::Boolean);

			// Relationship Scripts
			$this->strRelationships = QCodeGen::LookupSetting($objSettingsXml, 'relationships');
			$this->strRelationshipsScriptPath = QCodeGen::LookupSetting($objSettingsXml, 'relationshipsScript', 'filepath');
			$this->strRelationshipsScriptFormat = QCodeGen::LookupSetting($objSettingsXml, 'relationshipsScript', 'format');

			// Check to make sure things that are required are there
			if (!$this->intDatabaseIndex)
				$this->strErrors .= "CodeGen Settings XML Fatal Error: databaseIndex was invalid or not set\r\n";

			// Aggregate RelationshipLinesQcodo and RelationshipLinesSql arrays
			if ($this->strRelationships) {
				$strLines = explode("\n", strtolower($this->strRelationships));
				if ($strLines) foreach ($strLines as $strLine) {
					$strLine = trim($strLine);

					if (($strLine) && 
						(strlen($strLine) > 2) &&
						(substr($strLine, 0, 2) != '//') &&
						(substr($strLine, 0, 2) != '--') &&
						(substr($strLine, 0, 1) != '#')) {
						$this->strRelationshipLinesQcodo[$strLine] = $strLine;
					}
				}
			}

			if ($this->strRelationshipsScriptPath) {
				if (!file_exists($this->strRelationshipsScriptPath))
					$this->strErrors .= sprintf("CodeGen Settings XML Fatal Error: relationshipsScript filepath \"%s\" does not exist\r\n", $this->strRelationshipsScriptPath);
				else {
					$strScript = strtolower(trim(file_get_contents($this->strRelationshipsScriptPath)));
					switch ($this->strRelationshipsScriptFormat) {
						case 'qcodo':
							$strLines = explode("\n", $strScript);
							if ($strLines) foreach ($strLines as $strLine) {
								$strLine = trim($strLine);

								if (($strLine) && 
									(strlen($strLine) > 2) &&
									(substr($strLine, 0, 2) != '//') &&
									(substr($strLine, 0, 2) != '--') &&
									(substr($strLine, 0, 1) != '#')) {
									$this->strRelationshipLinesQcodo[$strLine] = $strLine;
								}
							}
							break;

						case 'sql':
							// Separate all commands in the script (separated by ";")
							$strCommands = explode(';', $strScript);
							if ($strCommands) foreach ($strCommands as $strCommand) {
								$strCommand = trim($strCommand);

								if ($strCommand) {
									// Take out all comment lines in the script
									$strLines = explode("\n", $strCommand);
									$strCommand = '';
									foreach ($strLines as $strLine) {
										$strLine = trim($strLine);
										if (($strLine) &&
											(substr($strLine, 0, 2) != '//') &&
											(substr($strLine, 0, 2) != '--') &&
											(substr($strLine, 0, 1) != '#')) {
											$strLine = str_replace('	', ' ', $strLine);
											$strLine = str_replace('        ', ' ', $strLine);
											$strLine = str_replace('       ', ' ', $strLine);
											$strLine = str_replace('      ', ' ', $strLine);
											$strLine = str_replace('     ', ' ', $strLine);
											$strLine = str_replace('    ', ' ', $strLine);
											$strLine = str_replace('   ', ' ', $strLine);
											$strLine = str_replace('  ', ' ', $strLine);
											$strLine = str_replace('  ', ' ', $strLine);
											$strLine = str_replace('  ', ' ', $strLine);
											$strLine = str_replace('  ', ' ', $strLine);
											$strLine = str_replace('  ', ' ', $strLine);

											$strCommand .= $strLine . ' ';
										}
									}

									$strCommand = trim($strCommand);
									if ((strpos($strCommand, 'alter table') === 0) &&
										(strpos($strCommand, 'foreign key') !== false))
										$this->strRelationshipLinesSql[$strCommand] = $strCommand;
								}
							}
							break;

						default:
							$this->strErrors .= sprintf("CodeGen Settings XML Fatal Error: relationshipsScript format \"%s\" is invalid (must be either \"qcodo\" or \"sql\")\r\n", $this->strRelationshipsScriptFormat);
							break;
					}
				}
			}

			if ($this->strErrors)
				return;

			$this->AnalyzeDatabase();
		}

		protected function AnalyzeDatabase() {
			// Set aside the Database object
			if (array_key_exists($this->intDatabaseIndex, QApplication::$Database))
				$this->objDb = QApplication::$Database[$this->intDatabaseIndex];

			// Ensure the DB Exists
			if (!$this->objDb) {
				$this->strErrors = 'FATAL ERROR: No database configured at index ' . $this->intDatabaseIndex . '.';
				return;
			}

			// Ensure DB Profiling is DISABLED on this DB
			if ($this->objDb->EnableProfiling) {
				$this->strErrors = 'FATAL ERROR: Code generator cannot analyze the database at index ' . $this->intDatabaseIndex . ' while DB Profiling is enabled.';
				return;
			}

			// Get the list of Tables as a string[]
			$strTableArray = $this->objDb->GetTables();


			// ITERATION 1: Simply create the Table and TypeTable Arrays
			if ($strTableArray) foreach ($strTableArray as $strTableName) {

				// Do we Exclude this Table Name? (given includeTables and excludeTables)
				// First check the lists of Excludes and the Exclude Patterns
				if (in_array($strTableName,$this->strExcludeListArray) ||
					(strlen($this->strExcludePattern) > 0 && preg_match(":".$this->strExcludePattern.":i",$strTableName))) {
						
					// So we THINK we may be excluding this table
					// But check against the explicit INCLUDE list and patterns
					if (in_array($strTableName,$this->strIncludeListArray) ||
						(strlen($this->strIncludePattern) > 0 && preg_match(":".$this->strIncludePattern.":i",$strTableName))) {
						// If we're here, we explicitly want to include this table
						// Therefore, do nothing
					} else {
						// If we're here, then we want to exclude this table
						$this->strExcludedTableArray[strtolower($strTableName)] = true;

						// Exit this iteration of the foreach loop
						continue;
					}
				}

				// Check to see if this table name exists anywhere else yet, and warn if it is
				foreach (QCodeGen::$CodeGenArray as $objCodeGen) {
					if ($objCodeGen instanceof QDatabaseCodeGen) {
						foreach ($objCodeGen->TableArray as $objPossibleDuplicate)
							if (strtolower($objPossibleDuplicate->Name) == strtolower($strTableName))
								$this->strErrors .= 'Duplicate Table Name Used: ' . $strTableName . "\r\n";
					}
				}

				// Perform different tasks based on whether it's an Association table,
				// a Type table, or just a regular table
				if (($this->intTypeTableSuffixLength) &&
					(strlen($strTableName) > $this->intTypeTableSuffixLength) &&
					(substr($strTableName, strlen($strTableName) - $this->intTypeTableSuffixLength) == $this->strTypeTableSuffix)) {
					// Create a TYPE Table and add it to the array
					$objTypeTable = new QTypeTable($strTableName);
					$this->objTypeTableArray[strtolower($strTableName)] = $objTypeTable;
//					_p("TYPE Table: $strTableName<br />", false);

				} else if (($this->intAssociationTableSuffixLength) &&
					(strlen($strTableName) > $this->intAssociationTableSuffixLength) &&
					(substr($strTableName, strlen($strTableName) - $this->intAssociationTableSuffixLength) == $this->strAssociationTableSuffix)) {
					// Add this ASSOCIATION Table Name to the array
					$this->strAssociationTableNameArray[strtolower($strTableName)] = $strTableName;
//					_p("ASSN Table: $strTableName<br />", false);

				} else {
					// Create a Regular Table and add it to the array
					$objTable = new QTable($strTableName);
					$this->objTableArray[strtolower($strTableName)] = $objTable;
//					_p("Table: $strTableName<br />", false);
				}
			}


			// Analyze All the Type Tables
			if ($this->objTypeTableArray) foreach ($this->objTypeTableArray as $objTypeTable)
				$this->AnalyzeTypeTable($objTypeTable);

			// Analyze All the Regular Tables
			if ($this->objTableArray) foreach ($this->objTableArray as $objTable)
				$this->AnalyzeTable($objTable);

			// Analyze All the Association Tables
			if ($this->strAssociationTableNameArray) foreach ($this->strAssociationTableNameArray as $strAssociationTableName)
				$this->AnalyzeAssociationTable($strAssociationTableName);

			// Finall, for each Relationship in all Tables, Warn on Non Single Column PK based FK:
			if ($this->objTableArray) foreach ($this->objTableArray as $objTable)
				if ($objTable->ColumnArray) foreach ($objTable->ColumnArray as $objColumn)
					if ($objColumn->Reference && !$objColumn->Reference->IsType) {
						$objReference = $objColumn->Reference;
//						$objReferencedTable = $this->objTableArray[strtolower($objReference->Table)];
						$objReferencedTable = $this->GetTable($objReference->Table);
						$objReferencedColumn = $objReferencedTable->ColumnArray[strtolower($objReference->Column)];

						
						if (!$objReferencedColumn->PrimaryKey) {
							$this->strErrors .= sprintf("Warning: Invalid Relationship created in %s class (for foreign key \"%s\") -- column \"%s\" is not the single-column primary key for the referenced \"%s\" table\r\n",
								$objReferencedTable->ClassName, $objReference->KeyName, $objReferencedColumn->Name, $objReferencedTable->Name);
						} else if (count($objReferencedTable->PrimaryKeyColumnArray) != 1) {
							$this->strErrors .= sprintf("Warning: Invalid Relationship created in %s class (for foreign key \"%s\") -- column \"%s\" is not the single-column primary key for the referenced \"%s\" table\r\n",
								$objReferencedTable->ClassName, $objReference->KeyName, $objReferencedColumn->Name, $objReferencedTable->Name);
						}
					}
		}

		protected function ListOfColumnsFromTable(QTable $objTable) {
			$strArray = array();
			$objColumnArray = $objTable->ColumnArray;
			if ($objColumnArray) foreach ($objColumnArray as $objColumn)
				array_push($strArray, $objColumn->Name);
			return implode(', ', $strArray);
		}
		
		protected function GetColumnArray(QTable $objTable, $strColumnNameArray) {
			$objToReturn = array();

			if ($strColumnNameArray) foreach ($strColumnNameArray as $strColumnName) {
				array_push($objToReturn, $objTable->ColumnArray[strtolower($strColumnName)]);
			}
			
			return $objToReturn;
		}

		public function GenerateTable(QTable $objTable) {
			// Create Argument Array
			$mixArgumentArray = array('objTable' => $objTable);
			return $this->GenerateFiles('db_orm', $mixArgumentArray);
		}

		public function GenerateTypeTable(QTypeTable $objTypeTable) {
			// Create Argument Array
			$mixArgumentArray = array('objTypeTable' => $objTypeTable);
			return $this->GenerateFiles('db_type', $mixArgumentArray);
		}

		protected function AnalyzeAssociationTable($strTableName) {
			$objFieldArray = $this->objDb->GetFieldsForTable($strTableName);

			// Association tables must have 2 fields
			if (count($objFieldArray) != 2) {
				$this->strErrors .= sprintf("AssociationTable %s does not have exactly 2 columns.\n",
					$strTableName);
				return;
			}

			if ((!$objFieldArray[0]->NotNull) ||
				(!$objFieldArray[1]->NotNull)) {
				$this->strErrors .= sprintf("AssociationTable %s's two columns must both be not null or a composite Primary Key",
					$strTableName);
				return;
			}
			
			if (((!$objFieldArray[0]->PrimaryKey) &&
				 ($objFieldArray[1]->PrimaryKey)) || 
				(($objFieldArray[0]->PrimaryKey) &&
				 (!$objFieldArray[1]->PrimaryKey))) {
				$this->strErrors .= sprintf("AssociationTable %s only support two-column composite Primary Keys.\n",
					$strTableName);
				return;
			}

			$objForeignKeyArray = $this->objDb->GetForeignKeysForTable($strTableName);

			// Add to it, the list of Foreign Keys from any Relationships Script
			$objForeignKeyArray = $this->GetForeignKeysFromRelationshipsScript($strTableName, $objForeignKeyArray);

			if (count($objForeignKeyArray) != 2) {
				$this->strErrors .= sprintf("AssociationTable %s does not have exactly 2 foreign keys.  Code Gen analysis found %s.\n",
					$strTableName, count($objForeignKeyArray));
				return;
			}

			// Setup two new ManyToManyReference objects
			$objManyToManyReferenceArray[0] = new QManyToManyReference();
			$objManyToManyReferenceArray[1] = new QManyToManyReference();

			// Ensure that the linked tables are both not excluded
			if (array_key_exists($objForeignKeyArray[0]->ReferenceTableName, $this->strExcludedTableArray) ||
				array_key_exists($objForeignKeyArray[1]->ReferenceTableName, $this->strExcludedTableArray))
				return;

			// Setup GraphPrevixArray (if applicable)
			if ($objForeignKeyArray[0]->ReferenceTableName == $objForeignKeyArray[1]->ReferenceTableName) {
				// We are analyzing a graph association
				$strGraphPrefixArray = $this->CalculateGraphPrefixArray($objForeignKeyArray);
			} else {
				$strGraphPrefixArray = array('', '');
			}

			// Go through each FK and setup each ManyToManyReference object
			for ($intIndex = 0; $intIndex < 2; $intIndex++) {
				$objManyToManyReference = $objManyToManyReferenceArray[$intIndex];

				$objForeignKey = $objForeignKeyArray[$intIndex];
				$objOppositeForeignKey = $objForeignKeyArray[($intIndex == 0) ? 1 : 0];

				// Make sure the FK is a single-column FK
				if (count($objForeignKey->ColumnNameArray) != 1) {
					$this->strErrors .= sprintf("AssoiationTable %s has multi-column foreign keys.\n",
						$strTableName);
					return;
				}

				$objManyToManyReference->KeyName = $objForeignKey->KeyName;
				$objManyToManyReference->Table = $strTableName;
				$objManyToManyReference->Column = $objForeignKey->ColumnNameArray[0];
				$objManyToManyReference->OppositeColumn = $objOppositeForeignKey->ColumnNameArray[0];
				$objManyToManyReference->AssociatedTable = $objOppositeForeignKey->ReferenceTableName;
				
				// Calculate OppositeColumnVariableName
				// Do this by first making a fake column which is the PK column of the AssociatedTable,
				// but who's column name is ManyToManyReference->Column
//				$objOppositeColumn = clone($this->objTableArray[strtolower($objManyToManyReference->AssociatedTable)]->PrimaryKeyColumnArray[0]);
				$objOppositeColumn = clone($this->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]);
				$objOppositeColumn->Name = $objManyToManyReference->OppositeColumn;
				$objManyToManyReference->OppositeVariableName = $this->VariableNameFromColumn($objOppositeColumn);
				$objManyToManyReference->OppositePropertyName = $this->PropertyNameFromColumn($objOppositeColumn);
				$objManyToManyReference->OppositeVariableType = $objOppositeColumn->VariableType;

				$objManyToManyReference->VariableName = $this->ReverseReferenceVariableNameFromTable($objOppositeForeignKey->ReferenceTableName);
				$objManyToManyReference->VariableType = $this->ReverseReferenceVariableTypeFromTable($objOppositeForeignKey->ReferenceTableName);

				$objManyToManyReference->ObjectDescription = $strGraphPrefixArray[$intIndex] . $this->CalculateObjectDescriptionForAssociation($strTableName, $objForeignKey->ReferenceTableName, $objOppositeForeignKey->ReferenceTableName, false);
				$objManyToManyReference->ObjectDescriptionPlural = $strGraphPrefixArray[$intIndex] . $this->CalculateObjectDescriptionForAssociation($strTableName, $objForeignKey->ReferenceTableName, $objOppositeForeignKey->ReferenceTableName, true);

				$objManyToManyReference->OppositeObjectDescription = $strGraphPrefixArray[($intIndex == 0) ? 1 : 0] . $this->CalculateObjectDescriptionForAssociation($strTableName, $objOppositeForeignKey->ReferenceTableName, $objForeignKey->ReferenceTableName, false);
			}


			// Iterate through the list of Columns to create objColumnArray
			$objColumnArray = array();
			foreach ($objFieldArray as $objField) {
				if (($objField->Name != $objManyToManyReferenceArray[0]->Column) &&
					($objField->Name != $objManyToManyReferenceArray[1]->Column)) {
					$objColumn = $this->AnalyzeTableColumn($objField, null);
					$objColumnArray[strtolower($objColumn->Name)] = $objColumn;
				}
			}
			$objManyToManyReferenceArray[0]->ColumnArray = $objColumnArray;
			$objManyToManyReferenceArray[1]->ColumnArray = $objColumnArray;			
			
			// Push the ManyToManyReference Objects to the tables
			for ($intIndex = 0; $intIndex < 2; $intIndex++) {
				$objManyToManyReference = $objManyToManyReferenceArray[$intIndex];
				$strTableWithReference = $objManyToManyReferenceArray[($intIndex == 0) ? 1 : 0]->AssociatedTable;

//				$objArray = $this->objTableArray[strtolower($strTableWithReference)]->ManyToManyReferenceArray;
				$objArray = $this->GetTable($strTableWithReference)->ManyToManyReferenceArray;
				array_push($objArray, $objManyToManyReference);
//				$this->objTableArray[strtolower($strTableWithReference)]->ManyToManyReferenceArray = $objArray;
				$this->GetTable($strTableWithReference)->ManyToManyReferenceArray = $objArray;
			}

		}

		protected function AnalyzeTypeTable(QTypeTable $objTypeTable) {
			// Setup the Array of Reserved Words
			$strReservedWords = explode(',', QCodeGen::PhpReservedWords);
			for ($intIndex = 0; $intIndex < count($strReservedWords); $intIndex++)
				$strReservedWords[$intIndex] = strtolower(trim($strReservedWords[$intIndex]));

			// Setup the Type Table Object
			$strTableName = $objTypeTable->Name;
			$objTypeTable->ClassName = $this->ClassNameFromTableName($strTableName);
			
			// Ensure that there are only 2 fields, an integer PK field (can be named anything) and a unique varchar field
			$objFieldArray = $this->objDb->GetFieldsForTable($strTableName);

			if (($objFieldArray[0]->Type != QDatabaseFieldType::Integer) ||
				(!$objFieldArray[0]->PrimaryKey)) {
				$this->strErrors .= sprintf("TypeTable %s's first column is not a PK integer.\n",
					$strTableName);
				return;
			}
			
			if (($objFieldArray[1]->Type != QDatabaseFieldType::VarChar) ||
				(!$objFieldArray[1]->Unique)) {
				$this->strErrors .= sprintf("TypeTable %s's second column is not a unique VARCHAR.\n",
					$strTableName);
				return;
			}

			// Get the rows
			$objResult = $this->objDb->Query(sprintf('SELECT * FROM %s', $strTableName));
			$strNameArray = array();
			$strTokenArray = array();
			$strExtraPropertyArray = array();
			$strExtraFields = array();
			while ($objRow = $objResult->FetchRow()) {
				$strNameArray[$objRow[0]] = str_replace("'", "\\'", str_replace('\\', '\\\\', $objRow[1]));
				$strTokenArray[$objRow[0]] = $this->TypeTokenFromTypeName($objRow[1]);
				if (sizeof($objRow) > 2) { // there are extra columns to process
					$strExtraPropertyArray[$objRow[0]] = array();
					for ($i = 2; $i < sizeof($objRow); $i++) {
						$strFieldName = QCodeGen::TypeNameFromColumnName($objFieldArray[$i]->Name);
						$strExtraFields[$i - 2] = $strFieldName;
						$strExtraPropertyArray[$objRow[0]][$strFieldName] = $objRow[$i];
					}
				}

				foreach ($strReservedWords as $strReservedWord)
					if (trim(strtolower($strTokenArray[$objRow[0]])) == $strReservedWord) {
						$this->strErrors .= sprintf("Warning: TypeTable %s contains a type name which is a reserved word: %s.  Appended _ to the beginning of it.\r\n",
							$strTableName, $strReservedWord);
						$strTokenArray[$objRow[0]] = '_' . $strTokenArray[$objRow[0]];
					}
				if (strlen($strTokenArray[$objRow[0]]) == 0) {
					$this->strErrors .= sprintf("Warning: TypeTable %s contains an invalid type name: %s\r\n",
						$strTableName, stripslashes($strNameArray[$objRow[0]]));
					return;
				}
			}

			ksort($strNameArray);
			ksort($strTokenArray);

			$objTypeTable->NameArray = $strNameArray;
			$objTypeTable->TokenArray = $strTokenArray;
			$objTypeTable->ExtraFieldNamesArray = $strExtraFields;
			$objTypeTable->ExtraPropertyArray = $strExtraPropertyArray;
		}

		protected function AnalyzeTable(QTable $objTable) {
			// Setup the Table Object
			$strTableName = $objTable->Name;
			$objTable->ClassName = $this->ClassNameFromTableName($strTableName);
			$objTable->ClassNamePlural = $this->Pluralize($objTable->ClassName);


			// Get the List of Columns
			$objFieldArray = $this->objDb->GetFieldsForTable($strTableName);
	
			// Iterate through the list of Columns to create objColumnArray
			$objColumnArray = array();
			if ($objFieldArray) foreach ($objFieldArray as $objField) {
				$objColumn = $this->AnalyzeTableColumn($objField, $objTable);
				$objColumnArray[strtolower($objColumn->Name)] = $objColumn;
			}
			$objTable->ColumnArray = $objColumnArray;




			// Get the List of Indexes
			$objTable->IndexArray = $this->objDb->GetIndexesForTable($objTable->Name);
			
			// Create an Index array
			$objIndexArray = array();
			// Create our Index for Primary Key (if applicable)
			$strPrimaryKeyArray = array();
			foreach ($objColumnArray as $objColumn)
				if ($objColumn->PrimaryKey) {
					$objPkColumn = $objColumn;
					array_push($strPrimaryKeyArray, $objColumn->Name);
				}
			if (count($strPrimaryKeyArray)) {
				$objIndex = new QIndex();
				$objIndex->KeyName = 'pk_' . $strTableName;
				$objIndex->PrimaryKey = true;
				$objIndex->Unique = true;
				$objIndex->ColumnNameArray = $strPrimaryKeyArray;
				array_push($objIndexArray, $objIndex);
				
				if (count($strPrimaryKeyArray) == 1) {
					$objPkColumn->Unique = true;
					$objPkColumn->Indexed = true;
				}
			}
//if ($strTableName == 'campus_job') exit(var_dump($objPkColumn));

			// Iterate though each Index that exists in this table, set any Columns's "Index" property
			// to TRUE if they are a single-column index
			if ($objTable->IndexArray) foreach ($objArray = $objTable->IndexArray as $objDatabaseIndex) {
				// Make sure the columns are defined
				if (count ($objDatabaseIndex->ColumnNameArray) == 0)
					$this->strErrors .= sprintf("Index %s in table %s indexes on no columns.\n",
						$objDatabaseIndex->KeyName, $strTableName);
				else {
					// Ensure every column exist in the DbIndex's ColumnNameArray
					$blnFailed = false;
					foreach ($objArray = $objDatabaseIndex->ColumnNameArray as $strColumnName) {
						if (array_key_exists(strtolower($strColumnName), $objTable->ColumnArray) &&
							($objTable->ColumnArray[strtolower($strColumnName)])) {
							// It exists -- do nothing
						} else {
							// Otherwise, add a warning
							$this->strErrors .= sprintf("Index %s in table %s indexes on the column %s, which does not appear to exist.\n",
								$objDatabaseIndex->KeyName, $strTableName, $strColumnName);
							$blnFailed = true;
						}
					}

					if (!$blnFailed) {
						// Let's make sure if this is a single-column index, we haven't already created a single-column index for this column
						$blnAlreadyCreated = false;
						foreach ($objIndexArray as $objIndex)
							if (count($objIndex->ColumnNameArray) == count($objDatabaseIndex->ColumnNameArray))
								if (implode(',', $objIndex->ColumnNameArray) == implode(',', $objDatabaseIndex->ColumnNameArray))
									$blnAlreadyCreated = true;

						if (!$blnAlreadyCreated) {
							// Create the Index Object
							$objIndex = new QIndex();
							$objIndex->KeyName = $objDatabaseIndex->KeyName;
							$objIndex->PrimaryKey = $objDatabaseIndex->PrimaryKey;
							$objIndex->Unique = $objDatabaseIndex->Unique;
							if ($objDatabaseIndex->PrimaryKey)
								$objIndex->Unique = true;
							$objIndex->ColumnNameArray = $objDatabaseIndex->ColumnNameArray;

							// Add the new index object to the index array
							array_push($objIndexArray, $objIndex);

							// Lastly, if it's a single-column index, update the Column in the table to reflect this
							if (count($objDatabaseIndex->ColumnNameArray) == 1) {
								$strColumnName = $objDatabaseIndex->ColumnNameArray[0];
								$objColumn = $objTable->ColumnArray[strtolower($strColumnName)];
								$objColumn->Indexed = true;

								if ($objIndex->Unique)
									$objColumn->Unique = true;
							}
						}
					}
				}
			}
			
			// Add the IndexArray to the table
			$objTable->IndexArray = $objIndexArray;




			// Get the List of Foreign Keys from the database
			$objForeignKeys = $this->objDb->GetForeignKeysForTable($objTable->Name);

			// Add to it, the list of Foreign Keys from any Relationships Script
			$objForeignKeys = $this->GetForeignKeysFromRelationshipsScript($strTableName, $objForeignKeys);

			// Iterate through each foreign key that exists in this table
			if ($objForeignKeys) foreach ($objForeignKeys as $objForeignKey) {

				// Make sure it's a single-column FK
				if (count($objForeignKey->ColumnNameArray) != 1)
					$this->strErrors .= sprintf("Foreign Key %s in table %s keys on multiple columns.  Multiple-columned FKs are not supported by the code generator.\n",
						$objForeignKey->KeyName, $strTableName);
				else {
					// Make sure the column in the FK definition actually exists in this table
					$strColumnName = $objForeignKey->ColumnNameArray[0];

					if (array_key_exists(strtolower($strColumnName), $objTable->ColumnArray) &&
						($objColumn = $objTable->ColumnArray[strtolower($strColumnName)])) {
							
						// Now, we make sure there is a single-column index for this FK that exists
						$blnFound = false;
						if ($objIndexArray = $objTable->IndexArray) foreach ($objIndexArray as $objIndex) {
							if ((count($objIndex->ColumnNameArray) == 1) &&
								(strtolower($objIndex->ColumnNameArray[0]) == strtolower($strColumnName)))
								$blnFound = true;
						}

						if (!$blnFound) {
							// Single Column Index for this FK does not exist.  Let's create a virtual one and warn
							$objIndex = new QIndex();
							$objIndex->KeyName = sprintf('virtualix_%s_%s', $objTable->Name, $objColumn->Name);
							$objIndex->Unique = $objColumn->Unique;
							$objIndex->ColumnNameArray = array($objColumn->Name);

							$objIndexArray = $objTable->IndexArray;
							$objIndexArray[] = $objIndex;
							$objTable->IndexArray = $objIndexArray;

							if ($objIndex->Unique)
								$this->strErrors .= sprintf("Notice: It is recommended that you add a single-column UNIQUE index on \"%s.%s\" for the Foreign Key %s\r\n",
									$strTableName, $strColumnName, $objForeignKey->KeyName);
							else
								$this->strErrors .= sprintf("Notice: It is recommended that you add a single-column index on \"%s.%s\" for the Foreign Key %s\r\n",
									$strTableName, $strColumnName, $objForeignKey->KeyName);
						}

						// Make sure the table being referenced actually exists
						if ((array_key_exists(strtolower($objForeignKey->ReferenceTableName), $this->objTableArray)) ||
							(array_key_exists(strtolower($objForeignKey->ReferenceTableName), $this->objTypeTableArray))) {

							// STEP 1: Create the New Reference
							$objReference = new QReference();
	
							// Retrieve the Column object
							$objColumn = $objTable->ColumnArray[strtolower($strColumnName)];
	
							// Setup Key Name
							$objReference->KeyName = $objForeignKey->KeyName;
	
							$strReferencedTableName = $objForeignKey->ReferenceTableName;

							// Setup IsType flag
							if (array_key_exists(strtolower($strReferencedTableName), $this->objTypeTableArray)) {
								$objReference->IsType = true;
							} else {
								$objReference->IsType = false;
							}

							// Setup Table and Column names
							$objReference->Table = $strReferencedTableName;
							$objReference->Column = $objForeignKey->ReferenceColumnNameArray[0];
	
							// Setup VariableType
							$objReference->VariableType = $this->ClassNameFromTableName($strReferencedTableName);
							
							// Setup PropertyName and VariableName
							$objReference->PropertyName = $this->ReferencePropertyNameFromColumn($objColumn);
							$objReference->VariableName = $this->ReferenceVariableNameFromColumn($objColumn);
							
							// Add this reference to the column
							$objColumn->Reference = $objReference;
							
							
							
							// STEP 2: Setup the REVERSE Reference for Non Type-based References
							if (!$objReference->IsType) {						
								// Retrieve the ReferencedTable object
//								$objReferencedTable = $this->objTableArray[strtolower($objReference->Table)];
								$objReferencedTable = $this->GetTable($objReference->Table);
								$objReverseReference = new QReverseReference();
								$objReverseReference->KeyName = $objReference->KeyName;
								$objReverseReference->Table = $strTableName;
								$objReverseReference->Column = $strColumnName;
								$objReverseReference->NotNull = $objColumn->NotNull;
								$objReverseReference->Unique = $objColumn->Unique;
								$objReverseReference->PropertyName = $this->PropertyNameFromColumn($this->GetColumn($strTableName, $strColumnName));

								$objReverseReference->ObjectDescription = $this->CalculateObjectDescription($strTableName, $strColumnName, $strReferencedTableName, false);
								$objReverseReference->ObjectDescriptionPlural = $this->CalculateObjectDescription($strTableName, $strColumnName, $strReferencedTableName, true);
								$objReverseReference->VariableName = $this->ReverseReferenceVariableNameFromTable($objTable->Name);
								$objReverseReference->VariableType = $this->ReverseReferenceVariableTypeFromTable($objTable->Name);

								// For Special Case ReverseReferences, calculate Associated MemberVariableName and PropertyName...

								// See if ReverseReference is due to an ORM-based Class Inheritence Chain
								if ((count($objTable->PrimaryKeyColumnArray) == 1) && ($objColumn->PrimaryKey)) {
									$objReverseReference->ObjectMemberVariable = QConvertNotation::PrefixFromType(QType::Object) . $objReverseReference->VariableType;
									$objReverseReference->ObjectPropertyName = $objReverseReference->VariableType;
									$objReverseReference->ObjectDescription = $objReverseReference->VariableType;
									$objReverseReference->ObjectDescriptionPlural = $this->Pluralize($objReverseReference->VariableType);

								// Otherwise, see if it's just plain ol' unique
								} else if ($objColumn->Unique) {
									$objReverseReference->ObjectMemberVariable = $this->CalculateObjectMemberVariable($strTableName, $strColumnName, $strReferencedTableName);
									$objReverseReference->ObjectPropertyName = $this->CalculateObjectPropertyName($strTableName, $strColumnName, $strReferencedTableName);
								}

								// Add this ReverseReference to the referenced table's ReverseReferenceArray
								$objArray = $objReferencedTable->ReverseReferenceArray;
								array_push($objArray, $objReverseReference);
								$objReferencedTable->ReverseReferenceArray = $objArray;
							}
						} else {
							$this->strErrors .= sprintf("Foreign Key %s in table %s references a table %s that does not appear to exist.\n",
								$objForeignKey->KeyName, $strTableName, $objForeignKey->ReferenceTableName);
						}
					} else {
						$this->strErrors .= sprintf("Foreign Key %s in table %s indexes on a column that does not appear to exist.\n",
							$objForeignKey->KeyName, $strTableName);
					}
				}
			}

			// Verify: Table Name is valid (alphanumeric + "_" characters only, must not start with a number)
			// and NOT a PHP Reserved Word
			$strMatches = array();
			preg_match('/' . $this->strPatternTableName . '/', $strTableName, $strMatches);
			if (count($strMatches) && ($strMatches[0] == $strTableName) && ($strTableName != '_')) {
				// Setup Reserved Words
				$strReservedWords = explode(',', QCodeGen::PhpReservedWords);
				for ($intIndex = 0; $intIndex < count($strReservedWords); $intIndex++)
					$strReservedWords[$intIndex] = strtolower(trim($strReservedWords[$intIndex]));

				$strTableNameToTest = trim(strtolower($strTableName));
				foreach ($strReservedWords as $strReservedWord)
					if ($strTableNameToTest == $strReservedWord) {
						$this->strErrors .= sprintf("Table '%s' has a table name which is a PHP reserved word.\r\n", $strTableName);
						unset($this->objTableArray[strtolower($strTableName)]);
						return;
					}
			} else {
				$this->strErrors .= sprintf("Table '%s' can only contain characters that are alphanumeric or _, and must not begin with a number.\r\n", $strTableName);
				unset($this->objTableArray[strtolower($strTableName)]);
				return;
			}

			// Verify: Column Names are all valid names
			$objColumnArray = $objTable->ColumnArray;
			foreach ($objColumnArray as $objColumn) {
				$strColumnName = $objColumn->Name;
				$strMatches = array();
				preg_match('/' . $this->strPatternColumnName . '/', $strColumnName, $strMatches);
				if (count($strMatches) && ($strMatches[0] == $strColumnName) && ($strColumnName != '_')) {
				} else {
					$this->strErrors .= sprintf("Table '%s' has an invalid column name: '%s'\r\n", $strTableName, $strColumnName);
					unset($this->objTableArray[strtolower($strTableName)]);
					return;
				}
			}

			// Verify: Table has at least one PK
			$blnFoundPk = false;
			$objColumnArray = $objTable->ColumnArray;
			foreach ($objColumnArray as $objColumn) {
				if ($objColumn->PrimaryKey)
					$blnFoundPk = true;
			}
			if (!$blnFoundPk) {
				$this->strErrors .= sprintf("Table %s does not have any defined primary keys.\n", $strTableName);
				unset($this->objTableArray[strtolower($strTableName)]);
				return;
			}
		}

		protected function AnalyzeTableColumn(QDatabaseFieldBase $objField, $objTable) {
			$objColumn = new QColumn();
			$objColumn->Name = $objField->Name;
			$objColumn->DbType = $objField->Type;

			$objColumn->VariableType = $this->VariableTypeFromDbType($objColumn->DbType);
			$objColumn->VariableTypeAsConstant = QType::Constant($objColumn->VariableType);

			$objColumn->Length = $objField->MaxLength;
			$objColumn->Default = $objField->Default;

			$objColumn->PrimaryKey = $objField->PrimaryKey;
			$objColumn->NotNull = $objField->NotNull;
			$objColumn->Identity = $objField->Identity;
			$objColumn->Unique = $objField->Unique;
			if (($objField->PrimaryKey) && $objTable && (count($objTable->PrimaryKeyColumnArray) == 1))
				$objColumn->Unique = true;
			$objColumn->Timestamp = $objField->Timestamp;

			$objColumn->VariableName = $this->VariableNameFromColumn($objColumn);
			$objColumn->PropertyName = $this->PropertyNameFromColumn($objColumn);

			return $objColumn;
		}

		protected function StripPrefixFromTable($strTableName) {
			// If applicable, strip any StripTablePrefix from the table name
			if ($this->intStripTablePrefixLength &&
				(strlen($strTableName) > $this->intStripTablePrefixLength) &&
				(substr($strTableName, 0, $this->intStripTablePrefixLength - strlen($strTableName)) == $this->strStripTablePrefix))
				return substr($strTableName, $this->intStripTablePrefixLength);	

			return $strTableName;
		}

		protected function GetForeignKeyForQcodoRelationshipDefinition($strTableName, $strLine) {
			$strTokens = explode('=>', $strLine);
			if (count($strTokens) != 2) {
				$this->strErrors .= sprintf("Could not parse Relationships Script reference: %s (Incorrect Format)\r\n", $strLine);
				$this->strRelationshipLinesQcodo[$strLine] = null;
				return null;
			}

			$strSourceTokens = explode('.', $strTokens[0]);
			$strDestinationTokens = explode('.', $strTokens[1]);

			if ((count($strSourceTokens) != 2) ||
				(count($strDestinationTokens) != 2)) {
				$this->strErrors .= sprintf("Could not parse Relationships Script reference: %s (Incorrect Table.Column Format)\r\n", $strLine);
				$this->strRelationshipLinesQcodo[$strLine] = null;
				return null;
			}

			$strColumnName = trim($strSourceTokens[1]);
			$strReferenceTableName = trim($strDestinationTokens[0]);
			$strReferenceColumnName = trim($strDestinationTokens[1]);
			$strFkName = sprintf('virtualfk_%s_%s', $strTableName, $strColumnName);

			if (strtolower($strTableName) == trim($strSourceTokens[0])) {
				$this->strRelationshipLinesQcodo[$strLine] = null;
				return $this->GetForeignKeyHelper($strLine, $strFkName, $strTableName, $strColumnName, $strReferenceTableName, $strReferenceColumnName);
			}

			return null;
		}

		protected function GetForeignKeyForSqlRelationshipDefinition($strTableName, $strLine) {
			$strMatches = array();

			// Start
			$strPattern = '/alter[\s]+table[\s]+';
			// Table Name
			$strPattern .= '[\[\`\'\"]?(' . $this->strPatternTableName . ')[\]\`\'\"]?[\s]+';
			
			// Add Constraint
			$strPattern .= '(add[\s]+)?(constraint[\s]+';
			$strPattern .= '[\[\`\'\"]?(' . $this->strPatternKeyName . ')[\]\`\'\"]?[\s]+)?[\s]*';
			// Foreign Key
			$strPattern .= 'foreign[\s]+key[\s]*(' . $this->strPatternKeyName . ')[\s]*\(';
			$strPattern .= '([^)]+)\)[\s]*';
			// References
			$strPattern .= 'references[\s]+';
			$strPattern .= '[\[\`\'\"]?(' . $this->strPatternTableName . ')[\]\`\'\"]?[\s]*\(';
			$strPattern .= '([^)]+)\)[\s]*';
			// End
			$strPattern .= '/';

			// Perform the RegExp
			preg_match($strPattern, $strLine, $strMatches);

			if (count($strMatches) == 9) {
				$strColumnName = trim($strMatches[6]);
				$strReferenceTableName = trim($strMatches[7]);
				$strReferenceColumnName = trim($strMatches[8]);
				$strFkName = $strMatches[5];
				if (!$strFkName)
					$strFkName = sprintf('virtualfk_%s_%s', $strTableName, $strColumnName);

				if ((strpos($strColumnName, ',') !== false) ||
					(strpos($strReferenceColumnName, ',') !== false)) {
					$this->strErrors .= sprintf("Relationships Script has a foreign key definition with multiple columns: %s (Multiple-columned FKs are not supported by the code generator)\r\n", $strLine);
					$this->strRelationshipLinesSql[$strLine] = null;
					return null;
				}

				// Cleanup strColumnName nad strreferenceColumnName
				$strColumnName = str_replace("'", '', $strColumnName);
				$strColumnName = str_replace('"', '', $strColumnName);
				$strColumnName = str_replace('[', '', $strColumnName);
				$strColumnName = str_replace(']', '', $strColumnName);
				$strColumnName = str_replace('`', '', $strColumnName);
				$strColumnName = str_replace('	', '', $strColumnName);
				$strColumnName = str_replace(' ', '', $strColumnName);
				$strColumnName = str_replace("\r", '', $strColumnName);
				$strColumnName = str_replace("\n", '', $strColumnName);
				$strReferenceColumnName = str_replace("'", '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace('"', '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace('[', '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace(']', '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace('`', '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace('	', '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace(' ', '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace("\r", '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace("\n", '', $strReferenceColumnName);

				if (strtolower($strTableName) == trim($strMatches[1])) {
					$this->strRelationshipLinesSql[$strLine] = null;
					return $this->GetForeignKeyHelper($strLine, $strFkName, $strTableName, $strColumnName, $strReferenceTableName, $strReferenceColumnName);
				}

				return null;
			} else {
				$this->strErrors .= sprintf("Could not parse Relationships Script reference: %s (Not in ANSI SQL Format)\r\n", $strLine);
				$this->strRelationshipLinesSql[$strLine] = null;
				return null;
			}
		}

		protected function GetForeignKeyHelper($strLine, $strFkName, $strTableName, $strColumnName, $strReferencedTable, $strReferencedColumn) {
			// Make Sure Tables/Columns Exist, or display error otherwise
			if (!$this->ValidateTableColumn($strTableName, $strColumnName)) {
				$this->strErrors .= sprintf("Could not parse Relationships Script reference: \"%s\" (\"%s.%s\" does not exist)\r\n",
					$strLine, $strTableName, $strColumnName);
				return null;
			}

			if (!$this->ValidateTableColumn($strReferencedTable, $strReferencedColumn)) {
				$this->strErrors .= sprintf("Could not parse Relationships Script reference: \"%s\" (\"%s.%s\" does not exist)\r\n",
					$strLine, $strReferencedTable, $strReferencedColumn);
				return null;
			}

			return new QDatabaseForeignKey($strFkName, array($strColumnName), $strReferencedTable, array($strReferencedColumn));
		}

		/**
		 * This will go through the various Relationships Script lines (if applicable) as setup during
		 * the __constructor() through the <relationships> and <relationshipsScript> tags in the
		 * configuration settings.
		 *
		 * If no Relationships are defined, this method will simply exit making no changes.
		 *
		 * @param string $strTableName Name of the table to pull foreign keys for
		 * @param DatabaseForeignKeyBase[] Array of currently found DB FK objects which will be appended to
		 * @return DatabaseForeignKeyBase[] Array of DB FK objects that were parsed out
		 */
		protected function GetForeignKeysFromRelationshipsScript($strTableName, $objForeignKeyArray) {
			foreach ($this->strRelationshipLinesQcodo as $strLine) {
				if ($strLine) {
					$objForeignKey = $this->GetForeignKeyForQcodoRelationshipDefinition($strTableName, $strLine);

					if ($objForeignKey) {
						array_push($objForeignKeyArray, $objForeignKey);
						$this->strRelationshipLinesQcodo[$strLine] = null;
					}
				}					
			}

			foreach ($this->strRelationshipLinesSql as $strLine) {
				if ($strLine) {
					$objForeignKey = $this->GetForeignKeyForSqlRelationshipDefinition($strTableName, $strLine);

					if ($objForeignKey) {
						array_push($objForeignKeyArray, $objForeignKey);
						$this->strRelationshipLinesSql[$strLine] = null;
					}
				}
			}

			return $objForeignKeyArray;
		}


		////////////////////
		// Public Overriders
		////////////////////

		/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string strName Name of the property to get
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'TableArray':
					return $this->objTableArray;
				case 'TypeTableArray':
					return $this->objTypeTableArray;
				case 'DatabaseIndex':
					return $this->intDatabaseIndex;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		public function __set($strName, $mixValue) {
			try {
				switch($strName) {
					default:
						return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
			}
		}
	}

	function array_trim(&$strValue) {
		$strValue = trim($strValue);
	}
?>