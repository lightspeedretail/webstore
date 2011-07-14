<?php
	// This Database Adapter depends on MySqliDatabase
	if (!class_exists('QMySqliDatabase'))
		require(__QCODO_CORE__ . '/database/QMySqliDatabase.class.php');

	class QMySqlDatabase extends QMySqliDatabase {
		const Adapter = 'MySql Legacy Database Adapter for MySQL 4';

		protected $objDb;

		public function Connect() {
			// Lookup Adapter-Specific Connection Properties
			$strServer = $this->Server;
			$strName = $this->Database;
			$strUsername = $this->Username;
			$strPassword = $this->Password;
			$strPort = $this->Port;

			if ($strPort)
				$strServer .= ':' . $strPort;

			// Connect to the Database Server
			$this->objDb = mysql_connect($strServer, $strUsername, $strPassword, true);
			if (!$this->objDb)
				throw new QMySqliDatabaseException("Unable to connect to Database Server: $strServer", -1, null);
			if (mysql_errno($this->objDb))
				throw new QMySqliDatabaseException(mysql_error($this->objDb), mysql_errno($this->objDb), null);

			// Select the DB
			if (!mysql_select_db($strName, $this->objDb))
				throw new QMySqliDatabaseException("Unable to select the Database: $strName", -1, null);
			if (mysql_errno($this->objDb))
				throw new QMySqliDatabaseException(mysql_error($this->objDb), mysql_errno($this->objDb), null);

			// Update "Connected" Flag
			$this->blnConnectedFlag = true;

			// Set to AutoCommit
			$this->NonQuery('SET AUTOCOMMIT=1;');

			// Set NAMES (if applicable)
			if (array_key_exists('encoding', $this->objConfigArray))
				$this->NonQuery('SET NAMES ' . $this->objConfigArray['encoding'] . ';');
		}

		public function __get($strName) {
			switch ($strName) {
				case 'AffectedRows':
					return mysql_affected_rows($this->objDb);
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		public function Query($strQuery) {
			// Connect if Applicable
			if (!$this->blnConnectedFlag) $this->Connect();

			// Log Query (for Profiling, if applicable)
			$this->LogQuery($strQuery);

			// Perform the Query
			$objResult = mysql_query($strQuery, $this->objDb);			
			if (mysql_errno($this->objDb))
				throw new QMySqliDatabaseException(mysql_error($this->objDb), mysql_errno($this->objDb), $strQuery);

			// Return the Result
			$objMySqlDatabaseResult = new QMySqlDatabaseResult($objResult, $this);
			return $objMySqlDatabaseResult;
		}

		public function NonQuery($strNonQuery) {
			// Connect if Applicable
			if (!$this->blnConnectedFlag) $this->Connect();

			// Log Query (for Profiling, if applicable)
			$this->LogQuery($strNonQuery);

			// Perform the Query
			mysql_query($strNonQuery, $this->objDb);
			if (mysql_errno($this->objDb))
				throw new QMySqliDatabaseException(mysql_error($this->objDb), mysql_errno($this->objDb), $strNonQuery);
		}

		public function InsertId($strTableName = null, $strColumnName = null) {
			return mysql_insert_id($this->objDb);
		}

		public function Close() {
			mysql_close($this->objDb);
		}

		public function GetFieldsForTable($strTableName) {
			$objResult = new QMySqlDatabaseResult(mysql_list_fields($this->Database, $strTableName, $this->objDb), $this);
			return $objResult->FetchFields();
		}
	}

	class QMySqlDatabaseResult extends QMySqliDatabaseResult {
		protected $objMySqlResult;
		protected $objDb;

		public function __construct($objResult, QMySqlDatabase $objDb) {
			$this->objMySqlResult = $objResult;
			$this->objDb = $objDb;
		}

		public function FetchArray() {
			return mysql_fetch_array($this->objMySqlResult);
		}

		public function FetchFields() {
			$objArrayToReturn = array();
			while ($objField = mysql_fetch_field($this->objMySqlResult)) {
				array_push($objArrayToReturn, new QMySqlDatabaseField($objField, $this->objDb));
			}
			return $objArrayToReturn;
		}

		public function FetchField() {
			if ($objField = mysql_fetch_field($this->objMySqlResult))
				return new QMySqlDatabaseField($objField, $this->objDb);
		}

		public function FetchRow() {
			return mysql_fetch_row($this->objMySqlResult);
		}
		
		public function MySqlFetchField() {
			return mysql_fetch_field($this->objMySqlResult);
		}

		public function CountRows() {
			return mysql_num_rows($this->objMySqlResult);
		}

		public function CountFields() {
			return mysql_num_fields($this->objMySqlResult);
		}

		public function Close() {
			mysql_free_result($this->objMySqlResult);
		}
	}
	
	class QMySqlDatabaseField extends QMySqliDatabaseField {
		public function __construct($mixFieldData, $objDb = null) {
			$this->strName = $mixFieldData->name;
			$this->strOriginalName = $this->strName;
			$this->strTable = $mixFieldData->table;
			$this->strOriginalTable = $mixFieldData->table;
			$this->strDefault = $mixFieldData->def;
			$this->intMaxLength = null;

			// Calculate MaxLength of this column (e.g. if it's a varchar, calculate length of varchar
			// Also, see if it's auto increment
			if ($this->strOriginalTable) {
				$objDescriptionResult = $objDb->Query(sprintf("DESCRIBE `%s`", $this->strOriginalTable));
				while (($objRow = $objDescriptionResult->FetchArray())) {
					if ($objRow["Field"] == $this->strOriginalName) {
						if ($objRow["Extra"] == 'auto_increment')
							$this->blnIdentity = true;
						else
							$this->blnIdentity = false;

						$strLengthArray = explode("(", $objRow["Type"]);
						if ((count($strLengthArray) > 1) &&
							(strtolower($strLengthArray[0]) != 'enum') &&
							(strtolower($strLengthArray[0]) != 'set')) {
							$strLengthArray = explode(")", $strLengthArray[1]);
							$this->intMaxLength = $strLengthArray[0];
	
							// If the length is something like (7,2), then let's pull out just the "7"
							$intCommaPosition = strpos($this->intMaxLength, ',');
							if ($intCommaPosition !== false)
								$this->intMaxLength = substr($this->intMaxLength, 0, $intCommaPosition);
	
							if (!is_numeric($this->intMaxLength))
								throw new Exception("Not a valid Column Length: " . $objRow["Type"]);			
						}
					}
				}
			}

			$this->blnNotNull = QType::Cast($mixFieldData->not_null, QType::Boolean);
			$this->blnPrimaryKey = QType::Cast($mixFieldData->primary_key, QType::Boolean);
			$this->blnUnique = QType::Cast($mixFieldData->unique_key, QType::Boolean);

			switch ($mixFieldData->type) {
				case 'int':
					if ($this->intMaxLength == 1)
						$this->strType = QDatabaseFieldType::Bit;
					else
						$this->strType = QDatabaseFieldType::Integer;
					break;
				case 'real':
				case 'float':
					$this->strType = QDatabaseFieldType::Float;
					break;
				case 'double':
					// NOTE: PHP does not offer full support of double-precision floats.
					// Value will be set as a VarChar which will guarantee that the precision will be maintained.
					//    However, you will not be able to support full typing control (e.g. you would
					//    not be able to use a QFloatTextBox -- only a regular QTextBox)
					$this->strType = QDatabaseFieldType::VarChar;
					break;
				case 'timestamp':
					// System-generated Timestamp values need to be treated as plain text
					$this->strType = QDatabaseFieldType::VarChar;
					$this->blnTimestamp = true;
					break;
				case 'date':
					$this->strType = QDatabaseFieldType::Date;
					break;
				case 'time':
					$this->strType = QDatabaseFieldType::Time;
					break;
				case 'datetime':
					$this->strType = QDatabaseFieldType::DateTime;
					break;
				case 'blob':
					$this->strType = QDatabaseFieldType::Blob;
					break;
				case 'string':
					$this->strType = QDatabaseFieldType::VarChar;
					break;
				case 'char':
					$this->strType = QDatabaseFieldType::Char;
					break;
				default:
					throw new Exception("Unable to determine MySqli Database Field Type: " . $mixFieldData->type);
					break;
			}
		}
	}
?>