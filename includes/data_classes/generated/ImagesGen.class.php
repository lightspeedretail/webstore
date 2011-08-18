<?php
	/**
	 * The abstract ImagesGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the Images subclass which
	 * extends this ImagesGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the Images class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $ImagePath the value for strImagePath 
	 * @property string $ImageData the value for strImageData 
	 * @property integer $Width the value for intWidth 
	 * @property integer $Height the value for intHeight 
	 * @property string $Checksum the value for strChecksum 
	 * @property integer $Parent the value for intParent 
	 * @property QDateTime $Created the value for dttCreated (Not Null)
	 * @property string $Modified the value for strModified (Read-Only Timestamp)
	 * @property Images $ParentObject the value for the Images object referenced by intParent 
	 * @property Product $_ProductAsImage the value for the private _objProductAsImage (Read-Only) if set due to an expansion on the xlsws_product_image_assn association table
	 * @property Product[] $_ProductAsImageArray the value for the private _objProductAsImageArray (Read-Only) if set due to an ExpandAsArray on the xlsws_product_image_assn association table
	 * @property Images $_ChildImages the value for the private _objChildImages (Read-Only) if set due to an expansion on the xlsws_images.parent reverse relationship
	 * @property Images[] $_ChildImagesArray the value for the private _objChildImagesArray (Read-Only) if set due to an ExpandAsArray on the xlsws_images.parent reverse relationship
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class ImagesGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_images.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_images.image_path
		 * @var string strImagePath
		 */
		protected $strImagePath;
		const ImagePathMaxLength = 255;
		const ImagePathDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_images.image_data
		 * @var string strImageData
		 */
		protected $strImageData;
		const ImageDataDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_images.width
		 * @var integer intWidth
		 */
		protected $intWidth;
		const WidthDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_images.height
		 * @var integer intHeight
		 */
		protected $intHeight;
		const HeightDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_images.checksum
		 * @var string strChecksum
		 */
		protected $strChecksum;
		const ChecksumMaxLength = 32;
		const ChecksumDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_images.parent
		 * @var integer intParent
		 */
		protected $intParent;
		const ParentDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_images.created
		 * @var QDateTime dttCreated
		 */
		protected $dttCreated;
		const CreatedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_images.modified
		 * @var string strModified
		 */
		protected $strModified;
		const ModifiedDefault = null;


		/**
		 * Private member variable that stores a reference to a single ProductAsImage object
		 * (of type Product), if this Images object was restored with
		 * an expansion on the xlsws_product_image_assn association table.
		 * @var Product _objProductAsImage;
		 */
		private $_objProductAsImage;

		/**
		 * Private member variable that stores a reference to an array of ProductAsImage objects
		 * (of type Product[]), if this Images object was restored with
		 * an ExpandAsArray on the xlsws_product_image_assn association table.
		 * @var Product[] _objProductAsImageArray;
		 */
		private $_objProductAsImageArray = array();

		/**
		 * Private member variable that stores a reference to a single ChildImages object
		 * (of type Images), if this Images object was restored with
		 * an expansion on the xlsws_images association table.
		 * @var Images _objChildImages;
		 */
		private $_objChildImages;

		/**
		 * Private member variable that stores a reference to an array of ChildImages objects
		 * (of type Images[]), if this Images object was restored with
		 * an ExpandAsArray on the xlsws_images association table.
		 * @var Images[] _objChildImagesArray;
		 */
		private $_objChildImagesArray = array();

		/**
		 * Protected array of virtual attributes for this object (e.g. extra/other calculated and/or non-object bound
		 * columns from the run-time database query result for this object).  Used by InstantiateDbRow and
		 * GetVirtualAttribute.
		 * @var string[] $__strVirtualAttributeArray
		 */
		protected $__strVirtualAttributeArray = array();

		/**
		 * Protected internal member variable that specifies whether or not this object is Restored from the database.
		 * Used by Save() to determine if Save() should perform a db UPDATE or INSERT.
		 * @var bool __blnRestored;
		 */
		protected $__blnRestored;




		///////////////////////////////
		// PROTECTED MEMBER OBJECTS
		///////////////////////////////

		/**
		 * Protected member variable that contains the object pointed by the reference
		 * in the database column xlsws_images.parent.
		 *
		 * NOTE: Always use the ParentObject property getter to correctly retrieve this Images object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Images objParentObject
		 */
		protected $objParentObject;





		///////////////////////////////
		// CLASS-WIDE LOAD AND COUNT METHODS
		///////////////////////////////

		/**
		 * Static method to retrieve the Database object that owns this class.
		 * @return QDatabaseBase reference to the Database object that can query this class
		 */
		public static function GetDatabase() {
			return QApplication::$Database[1];
		}

		/**
		 * Load a Images from PK Info
		 * @param integer $intRowid
		 * @return Images
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return Images::QuerySingle(
				QQ::Equal(QQN::Images()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all Imageses
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Images[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call Images::QueryArray to perform the LoadAll query
			try {
				return Images::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all Imageses
		 * @return int
		 */
		public static function CountAll() {
			// Call Images::QueryCount to perform the CountAll query
			return Images::QueryCount(QQ::All());
		}




		///////////////////////////////
		// QCODO QUERY-RELATED METHODS
		///////////////////////////////

		/**
		 * Internally called method to assist with calling Qcodo Query for this class
		 * on load methods.
		 * @param QQueryBuilder &$objQueryBuilder the QueryBuilder object that will be created
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause object or array of QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with (sending in null will skip the PrepareStatement step)
		 * @param boolean $blnCountOnly only select a rowcount
		 * @return string the query statement
		 */
		protected static function BuildQueryStatement(&$objQueryBuilder, QQCondition $objConditions, $objOptionalClauses, $mixParameterArray, $blnCountOnly) {
			// Get the Database Object for this Class
			$objDatabase = Images::GetDatabase();

			// Create/Build out the QueryBuilder object with Images-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_images');
			Images::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_images');

			// Set "CountOnly" option (if applicable)
			if ($blnCountOnly)
				$objQueryBuilder->SetCountOnlyFlag();

			// Apply Any Conditions
			if ($objConditions)
				try {
					$objConditions->UpdateQueryBuilder($objQueryBuilder);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			// Iterate through all the Optional Clauses (if any) and perform accordingly
			if ($objOptionalClauses) {
				if ($objOptionalClauses instanceof QQClause)
					$objOptionalClauses->UpdateQueryBuilder($objQueryBuilder);
				else if (is_array($objOptionalClauses))
					foreach ($objOptionalClauses as $objClause)
						$objClause->UpdateQueryBuilder($objQueryBuilder);
				else
					throw new QCallerException('Optional Clauses must be a QQClause object or an array of QQClause objects');
			}

			// Get the SQL Statement
			$strQuery = $objQueryBuilder->GetStatement();

			// Prepare the Statement with the Query Parameters (if applicable)
			if ($mixParameterArray) {
				if (is_array($mixParameterArray)) {
					if (count($mixParameterArray))
						$strQuery = $objDatabase->PrepareStatement($strQuery, $mixParameterArray);

					// Ensure that there are no other Unresolved Named Parameters
					if (strpos($strQuery, chr(QQNamedValue::DelimiterCode) . '{') !== false)
						throw new QCallerException('Unresolved named parameters in the query');
				} else
					throw new QCallerException('Parameter Array must be an array of name-value parameter pairs');
			}

			// Return the Objects
			return $strQuery;
		}

		/**
		 * Static Qcodo Query method to query for a single Images object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Images the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Images::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new Images object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Images::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of Images objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Images[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Images::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Images::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of Images objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Images::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and return the row_count
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);

			// Figure out if the query is using GroupBy
			$blnGrouped = false;

			if ($objOptionalClauses) foreach ($objOptionalClauses as $objClause) {
				if ($objClause instanceof QQGroupBy) {
					$blnGrouped = true;
					break;
				}
			}

			if ($blnGrouped)
				// Groups in this query - return the count of Groups (which is the count of all rows)
				return $objDbResult->CountRows();
			else {
				// No Groups - return the sql-calculated count(*) value
				$strDbRow = $objDbResult->FetchRow();
				return QType::Cast($strDbRow[0], QType::Integer);
			}
		}

/*		public static function QueryArrayCached($strConditions, $mixParameterArray = null) {
			// Get the Database Object for this Class
			$objDatabase = Images::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_images_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with Images-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				Images::GetSelectFields($objQueryBuilder);
				Images::GetFromFields($objQueryBuilder);

				// Ensure the Passed-in Conditions is a string
				try {
					$strConditions = QType::Cast($strConditions, QType::String);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

				// Create the Conditions object, and apply it
				$objConditions = eval('return ' . $strConditions . ';');

				// Apply Any Conditions
				if ($objConditions)
					$objConditions->UpdateQueryBuilder($objQueryBuilder);

				// Get the SQL Statement
				$strQuery = $objQueryBuilder->GetStatement();

				// Save the SQL Statement in the Cache
				$objCache->SaveData($strQuery);
			}

			// Prepare the Statement with the Parameters
			if ($mixParameterArray)
				$strQuery = $objDatabase->PrepareStatement($strQuery, $mixParameterArray);

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objDatabase->Query($strQuery);
			return Images::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this Images
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_images';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'image_path', $strAliasPrefix . 'image_path');
			$objBuilder->AddSelectItem($strTableName, 'image_data', $strAliasPrefix . 'image_data');
			$objBuilder->AddSelectItem($strTableName, 'width', $strAliasPrefix . 'width');
			$objBuilder->AddSelectItem($strTableName, 'height', $strAliasPrefix . 'height');
			$objBuilder->AddSelectItem($strTableName, 'checksum', $strAliasPrefix . 'checksum');
			$objBuilder->AddSelectItem($strTableName, 'parent', $strAliasPrefix . 'parent');
			$objBuilder->AddSelectItem($strTableName, 'created', $strAliasPrefix . 'created');
			$objBuilder->AddSelectItem($strTableName, 'modified', $strAliasPrefix . 'modified');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a Images from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this Images::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return Images
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;

			// See if we're doing an array expansion on the previous item
			$strAlias = $strAliasPrefix . 'rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (($strExpandAsArrayNodes) && ($objPreviousItem) &&
				($objPreviousItem->intRowid == $objDbRow->GetColumn($strAliasName, 'Integer'))) {

				// We are.  Now, prepare to check for ExpandAsArray clauses
				$blnExpandedViaArray = false;
				if (!$strAliasPrefix)
					$strAliasPrefix = 'xlsws_images__';

				$strAlias = $strAliasPrefix . 'productasimage__product_id__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objProductAsImageArray)) {
						$objPreviousChildItem = $objPreviousItem->_objProductAsImageArray[$intPreviousChildItemCount - 1];
						$objChildItem = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productasimage__product_id__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objProductAsImageArray[] = $objChildItem;
					} else
						$objPreviousItem->_objProductAsImageArray[] = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productasimage__product_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}


				$strAlias = $strAliasPrefix . 'childimages__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objChildImagesArray)) {
						$objPreviousChildItem = $objPreviousItem->_objChildImagesArray[$intPreviousChildItemCount - 1];
						$objChildItem = Images::InstantiateDbRow($objDbRow, $strAliasPrefix . 'childimages__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objChildImagesArray[] = $objChildItem;
					} else
						$objPreviousItem->_objChildImagesArray[] = Images::InstantiateDbRow($objDbRow, $strAliasPrefix . 'childimages__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				// Either return false to signal array expansion, or check-to-reset the Alias prefix and move on
				if ($blnExpandedViaArray)
					return false;
				else if ($strAliasPrefix == 'xlsws_images__')
					$strAliasPrefix = null;
			}

			// Create a new instance of the Images object
			$objToReturn = new Images();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'image_path', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'image_path'] : $strAliasPrefix . 'image_path';
			$objToReturn->strImagePath = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'image_data', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'image_data'] : $strAliasPrefix . 'image_data';
			$objToReturn->strImageData = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'width', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'width'] : $strAliasPrefix . 'width';
			$objToReturn->intWidth = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'height', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'height'] : $strAliasPrefix . 'height';
			$objToReturn->intHeight = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'checksum', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'checksum'] : $strAliasPrefix . 'checksum';
			$objToReturn->strChecksum = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'parent', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'parent'] : $strAliasPrefix . 'parent';
			$objToReturn->intParent = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'created', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'created'] : $strAliasPrefix . 'created';
			$objToReturn->dttCreated = $objDbRow->GetColumn($strAliasName, 'DateTime');
			$strAliasName = array_key_exists($strAliasPrefix . 'modified', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'modified'] : $strAliasPrefix . 'modified';
			$objToReturn->strModified = $objDbRow->GetColumn($strAliasName, 'VarChar');

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'xlsws_images__';

			// Check for ParentObject Early Binding
			$strAlias = $strAliasPrefix . 'parent__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objParentObject = Images::InstantiateDbRow($objDbRow, $strAliasPrefix . 'parent__', $strExpandAsArrayNodes, null, $strColumnAliasArray);



			// Check for ProductAsImage Virtual Binding
			$strAlias = $strAliasPrefix . 'productasimage__product_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objProductAsImageArray[] = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productasimage__product_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objProductAsImage = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productasimage__product_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}


			// Check for ChildImages Virtual Binding
			$strAlias = $strAliasPrefix . 'childimages__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objChildImagesArray[] = Images::InstantiateDbRow($objDbRow, $strAliasPrefix . 'childimages__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objChildImages = Images::InstantiateDbRow($objDbRow, $strAliasPrefix . 'childimages__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}

		/**
		 * Instantiate an array of Imageses from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return Images[]
		 */
		public static function InstantiateDbResult(QDatabaseResultBase $objDbResult, $strExpandAsArrayNodes = null, $strColumnAliasArray = null) {
			$objToReturn = array();
			
			if (!$strColumnAliasArray)
				$strColumnAliasArray = array();

			// If blank resultset, then return empty array
			if (!$objDbResult)
				return $objToReturn;

			// Load up the return array with each row
			if ($strExpandAsArrayNodes) {
				$objLastRowItem = null;
				while ($objDbRow = $objDbResult->GetNextRow()) {
					$objItem = Images::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = Images::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single Images object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return Images
		*/
		public static function LoadByRowid($intRowid) {
			return Images::QuerySingle(
				QQ::Equal(QQN::Images()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load a single Images object,
		 * by Width, Height, Parent Index(es)
		 * @param integer $intWidth
		 * @param integer $intHeight
		 * @param integer $intParent
		 * @return Images
		*/
		public static function LoadByWidthHeightParent($intWidth, $intHeight, $intParent) {
			return Images::QuerySingle(
				QQ::AndCondition(
				QQ::Equal(QQN::Images()->Width, $intWidth),
				QQ::Equal(QQN::Images()->Height, $intHeight),
				QQ::Equal(QQN::Images()->Parent, $intParent)
				)
			);
		}
			
		/**
		 * Load an array of Images objects,
		 * by Parent Index(es)
		 * @param integer $intParent
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Images[]
		*/
		public static function LoadArrayByParent($intParent, $objOptionalClauses = null) {
			// Call Images::QueryArray to perform the LoadArrayByParent query
			try {
				return Images::QueryArray(
					QQ::Equal(QQN::Images()->Parent, $intParent),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Imageses
		 * by Parent Index(es)
		 * @param integer $intParent
		 * @return int
		*/
		public static function CountByParent($intParent) {
			// Call Images::QueryCount to perform the CountByParent query
			return Images::QueryCount(
				QQ::Equal(QQN::Images()->Parent, $intParent)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////
			/**
		 * Load an array of Product objects for a given ProductAsImage
		 * via the xlsws_product_image_assn table
		 * @param integer $intProductId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Images[]
		*/
		public static function LoadArrayByProductAsImage($intProductId, $objOptionalClauses = null) {
			// Call Images::QueryArray to perform the LoadArrayByProductAsImage query
			try {
				return Images::QueryArray(
					QQ::Equal(QQN::Images()->ProductAsImage->ProductId, $intProductId),
					$objOptionalClauses
				);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Imageses for a given ProductAsImage
		 * via the xlsws_product_image_assn table
		 * @param integer $intProductId
		 * @return int
		*/
		public static function CountByProductAsImage($intProductId) {
			return Images::QueryCount(
				QQ::Equal(QQN::Images()->ProductAsImage->ProductId, $intProductId)
			);
		}




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this Images
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = Images::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_images` (
							`image_path`,
							`image_data`,
							`width`,
							`height`,
							`checksum`,
							`parent`,
							`created`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strImagePath) . ',
							' . $objDatabase->SqlVariable($this->strImageData) . ',
							' . $objDatabase->SqlVariable($this->intWidth) . ',
							' . $objDatabase->SqlVariable($this->intHeight) . ',
							' . $objDatabase->SqlVariable($this->strChecksum) . ',
							' . $objDatabase->SqlVariable($this->intParent) . ',
							' . $objDatabase->SqlVariable($this->dttCreated) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_images', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`modified`
							FROM
								`xlsws_images`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strModified)
							throw new QOptimisticLockingException('Images');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_images`
						SET
							`image_path` = ' . $objDatabase->SqlVariable($this->strImagePath) . ',
							`image_data` = ' . $objDatabase->SqlVariable($this->strImageData) . ',
							`width` = ' . $objDatabase->SqlVariable($this->intWidth) . ',
							`height` = ' . $objDatabase->SqlVariable($this->intHeight) . ',
							`checksum` = ' . $objDatabase->SqlVariable($this->strChecksum) . ',
							`parent` = ' . $objDatabase->SqlVariable($this->intParent) . ',
							`created` = ' . $objDatabase->SqlVariable($this->dttCreated) . '
						WHERE
							`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
					');
				}

			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Update __blnRestored and any Non-Identity PK Columns (if applicable)
			$this->__blnRestored = true;

			// Update Local Timestamp
			$objResult = $objDatabase->Query('
				SELECT
					`modified`
				FROM
					`xlsws_images`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strModified = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this Images
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this Images with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = Images::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_images`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all Imageses
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = Images::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_images`');
		}

		/**
		 * Truncate xlsws_images table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = Images::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_images`');
		}

		/**
		 * Reload this Images from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved Images object.');

			// Reload the Object
			$objReloaded = Images::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strImagePath = $objReloaded->strImagePath;
			$this->strImageData = $objReloaded->strImageData;
			$this->intWidth = $objReloaded->intWidth;
			$this->intHeight = $objReloaded->intHeight;
			$this->strChecksum = $objReloaded->strChecksum;
			$this->Parent = $objReloaded->Parent;
			$this->dttCreated = $objReloaded->dttCreated;
			$this->strModified = $objReloaded->strModified;
		}



		////////////////////
		// PUBLIC OVERRIDERS
		////////////////////

				/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string $strName Name of the property to get
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				///////////////////
				// Member Variables
				///////////////////
				case 'Rowid':
					// Gets the value for intRowid (Read-Only PK)
					// @return integer
					return $this->intRowid;

				case 'ImagePath':
					// Gets the value for strImagePath 
					// @return string
					return $this->strImagePath;

				case 'ImageData':
					// Gets the value for strImageData 
					// @return string
					return $this->strImageData;

				case 'Width':
					// Gets the value for intWidth 
					// @return integer
					return $this->intWidth;

				case 'Height':
					// Gets the value for intHeight 
					// @return integer
					return $this->intHeight;

				case 'Checksum':
					// Gets the value for strChecksum 
					// @return string
					return $this->strChecksum;

				case 'Parent':
					// Gets the value for intParent 
					// @return integer
					return $this->intParent;

				case 'Created':
					// Gets the value for dttCreated (Not Null)
					// @return QDateTime
					return $this->dttCreated;

				case 'Modified':
					// Gets the value for strModified (Read-Only Timestamp)
					// @return string
					return $this->strModified;


				///////////////////
				// Member Objects
				///////////////////
				case 'ParentObject':
					// Gets the value for the Images object referenced by intParent 
					// @return Images
					try {
						if ((!$this->objParentObject) && (!is_null($this->intParent)))
							$this->objParentObject = Images::Load($this->intParent);
						return $this->objParentObject;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				////////////////////////////
				// Virtual Object References (Many to Many and Reverse References)
				// (If restored via a "Many-to" expansion)
				////////////////////////////

				case '_ProductAsImage':
					// Gets the value for the private _objProductAsImage (Read-Only)
					// if set due to an expansion on the xlsws_product_image_assn association table
					// @return Product
					return $this->_objProductAsImage;

				case '_ProductAsImageArray':
					// Gets the value for the private _objProductAsImageArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_product_image_assn association table
					// @return Product[]
					return (array) $this->_objProductAsImageArray;

				case '_ChildImages':
					// Gets the value for the private _objChildImages (Read-Only)
					// if set due to an expansion on the xlsws_images.parent reverse relationship
					// @return Images
					return $this->_objChildImages;

				case '_ChildImagesArray':
					// Gets the value for the private _objChildImagesArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_images.parent reverse relationship
					// @return Images[]
					return (array) $this->_objChildImagesArray;


				case '__Restored':
					return $this->__blnRestored;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

				/**
		 * Override method to perform a property "Set"
		 * This will set the property $strName to be $mixValue
		 *
		 * @param string $strName Name of the property to set
		 * @param string $mixValue New value of the property
		 * @return mixed
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				///////////////////
				// Member Variables
				///////////////////
				case 'ImagePath':
					// Sets the value for strImagePath 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strImagePath = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ImageData':
					// Sets the value for strImageData 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strImageData = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Width':
					// Sets the value for intWidth 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intWidth = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Height':
					// Sets the value for intHeight 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intHeight = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Checksum':
					// Sets the value for strChecksum 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strChecksum = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Parent':
					// Sets the value for intParent 
					// @param integer $mixValue
					// @return integer
					try {
						$this->objParentObject = null;
						return ($this->intParent = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Created':
					// Sets the value for dttCreated (Not Null)
					// @param QDateTime $mixValue
					// @return QDateTime
					try {
						return ($this->dttCreated = QType::Cast($mixValue, QType::DateTime));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				///////////////////
				// Member Objects
				///////////////////
				case 'ParentObject':
					// Sets the value for the Images object referenced by intParent 
					// @param Images $mixValue
					// @return Images
					if (is_null($mixValue)) {
						$this->intParent = null;
						$this->objParentObject = null;
						return null;
					} else {
						// Make sure $mixValue actually is a Images object
						try {
							$mixValue = QType::Cast($mixValue, 'Images');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						} 

						// Make sure $mixValue is a SAVED Images object
						if (is_null($mixValue->Rowid))
							throw new QCallerException('Unable to set an unsaved ParentObject for this Images');

						// Update Local Member Variables
						$this->objParentObject = $mixValue;
						$this->intParent = $mixValue->Rowid;

						// Return $mixValue
						return $mixValue;
					}
					break;

				default:
					try {
						return parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/**
		 * Lookup a VirtualAttribute value (if applicable).  Returns NULL if none found.
		 * @param string $strName
		 * @return string
		 */
		public function GetVirtualAttribute($strName) {
			if (array_key_exists($strName, $this->__strVirtualAttributeArray))
				return $this->__strVirtualAttributeArray[$strName];
			return null;
		}



		///////////////////////////////
		// ASSOCIATED OBJECTS' METHODS
		///////////////////////////////

			
		
		// Related Objects' Methods for ChildImages
		//-------------------------------------------------------------------

		/**
		 * Gets all associated ChildImageses as an array of Images objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Images[]
		*/ 
		public function GetChildImagesArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return Images::LoadArrayByParent($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated ChildImageses
		 * @return int
		*/ 
		public function CountChildImageses() {
			if ((is_null($this->intRowid)))
				return 0;

			return Images::CountByParent($this->intRowid);
		}

		/**
		 * Associates a ChildImages
		 * @param Images $objImages
		 * @return void
		*/ 
		public function AssociateChildImages(Images $objImages) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateChildImages on this unsaved Images.');
			if ((is_null($objImages->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateChildImages on this Images with an unsaved Images.');

			// Get the Database Object for this Class
			$objDatabase = Images::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_images`
				SET
					`parent` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objImages->Rowid) . '
			');
		}

		/**
		 * Unassociates a ChildImages
		 * @param Images $objImages
		 * @return void
		*/ 
		public function UnassociateChildImages(Images $objImages) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateChildImages on this unsaved Images.');
			if ((is_null($objImages->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateChildImages on this Images with an unsaved Images.');

			// Get the Database Object for this Class
			$objDatabase = Images::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_images`
				SET
					`parent` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objImages->Rowid) . ' AND
					`parent` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all ChildImageses
		 * @return void
		*/ 
		public function UnassociateAllChildImageses() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateChildImages on this unsaved Images.');

			// Get the Database Object for this Class
			$objDatabase = Images::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_images`
				SET
					`parent` = null
				WHERE
					`parent` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated ChildImages
		 * @param Images $objImages
		 * @return void
		*/ 
		public function DeleteAssociatedChildImages(Images $objImages) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateChildImages on this unsaved Images.');
			if ((is_null($objImages->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateChildImages on this Images with an unsaved Images.');

			// Get the Database Object for this Class
			$objDatabase = Images::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_images`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objImages->Rowid) . ' AND
					`parent` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated ChildImageses
		 * @return void
		*/ 
		public function DeleteAllChildImageses() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateChildImages on this unsaved Images.');

			// Get the Database Object for this Class
			$objDatabase = Images::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_images`
				WHERE
					`parent` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

			
		// Related Many-to-Many Objects' Methods for ProductAsImage
		//-------------------------------------------------------------------

		/**
		 * Gets all many-to-many associated ProductsAsImage as an array of Product objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Product[]
		*/ 
		public function GetProductAsImageArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return Product::LoadArrayByImagesAsImage($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all many-to-many associated ProductsAsImage
		 * @return int
		*/ 
		public function CountProductsAsImage() {
			if ((is_null($this->intRowid)))
				return 0;

			return Product::CountByImagesAsImage($this->intRowid);
		}

		/**
		 * Checks to see if an association exists with a specific ProductAsImage
		 * @param Product $objProduct
		 * @return bool
		*/
		public function IsProductAsImageAssociated(Product $objProduct) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsProductAsImageAssociated on this unsaved Images.');
			if ((is_null($objProduct->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsProductAsImageAssociated on this Images with an unsaved Product.');

			$intRowCount = Images::QueryCount(
				QQ::AndCondition(
					QQ::Equal(QQN::Images()->Rowid, $this->intRowid),
					QQ::Equal(QQN::Images()->ProductAsImage->ProductId, $objProduct->Rowid)
				)
			);

			return ($intRowCount > 0);
		}

		/**
		 * Associates a ProductAsImage
		 * @param Product $objProduct
		 * @return void
		*/ 
		public function AssociateProductAsImage(Product $objProduct) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProductAsImage on this unsaved Images.');
			if ((is_null($objProduct->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProductAsImage on this Images with an unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Images::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				INSERT INTO `xlsws_product_image_assn` (
					`image_id`,
					`product_id`
				) VALUES (
					' . $objDatabase->SqlVariable($this->intRowid) . ',
					' . $objDatabase->SqlVariable($objProduct->Rowid) . '
				)
			');
		}

		/**
		 * Unassociates a ProductAsImage
		 * @param Product $objProduct
		 * @return void
		*/ 
		public function UnassociateProductAsImage(Product $objProduct) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductAsImage on this unsaved Images.');
			if ((is_null($objProduct->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductAsImage on this Images with an unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Images::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_image_assn`
				WHERE
					`image_id` = ' . $objDatabase->SqlVariable($this->intRowid) . ' AND
					`product_id` = ' . $objDatabase->SqlVariable($objProduct->Rowid) . '
			');
		}

		/**
		 * Unassociates all ProductsAsImage
		 * @return void
		*/ 
		public function UnassociateAllProductsAsImage() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAllProductAsImageArray on this unsaved Images.');

			// Get the Database Object for this Class
			$objDatabase = Images::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_image_assn`
				WHERE
					`image_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}




		////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="Images"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="ImagePath" type="xsd:string"/>';
			$strToReturn .= '<element name="ImageData" type="xsd:string"/>';
			$strToReturn .= '<element name="Width" type="xsd:int"/>';
			$strToReturn .= '<element name="Height" type="xsd:int"/>';
			$strToReturn .= '<element name="Checksum" type="xsd:string"/>';
			$strToReturn .= '<element name="ParentObject" type="xsd1:Images"/>';
			$strToReturn .= '<element name="Created" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="Modified" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('Images', $strComplexTypeArray)) {
				$strComplexTypeArray['Images'] = Images::GetSoapComplexTypeXml();
				Images::AlterSoapComplexTypeArray($strComplexTypeArray);
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, Images::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new Images();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'ImagePath'))
				$objToReturn->strImagePath = $objSoapObject->ImagePath;
			if (property_exists($objSoapObject, 'ImageData'))
				$objToReturn->strImageData = $objSoapObject->ImageData;
			if (property_exists($objSoapObject, 'Width'))
				$objToReturn->intWidth = $objSoapObject->Width;
			if (property_exists($objSoapObject, 'Height'))
				$objToReturn->intHeight = $objSoapObject->Height;
			if (property_exists($objSoapObject, 'Checksum'))
				$objToReturn->strChecksum = $objSoapObject->Checksum;
			if ((property_exists($objSoapObject, 'ParentObject')) &&
				($objSoapObject->ParentObject))
				$objToReturn->ParentObject = Images::GetObjectFromSoapObject($objSoapObject->ParentObject);
			if (property_exists($objSoapObject, 'Created'))
				$objToReturn->dttCreated = new QDateTime($objSoapObject->Created);
			if (property_exists($objSoapObject, 'Modified'))
				$objToReturn->strModified = $objSoapObject->Modified;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, Images::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->objParentObject)
				$objObject->objParentObject = Images::GetSoapObjectFromObject($objObject->objParentObject, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intParent = null;
			if ($objObject->dttCreated)
				$objObject->dttCreated = $objObject->dttCreated->__toString(QDateTime::FormatSoap);
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeImagesProductAsImage extends QQAssociationNode {
		protected $strType = 'association';
		protected $strName = 'productasimage';

		protected $strTableName = 'xlsws_product_image_assn';
		protected $strPrimaryKey = 'image_id';
		protected $strClassName = 'Product';

		public function __get($strName) {
			switch ($strName) {
				case 'ProductId':
					return new QQNode('product_id', 'ProductId', 'integer', $this);
				case 'Product':
					return new QQNodeProduct('product_id', 'ProductId', 'integer', $this);
				case '_ChildTableNode':
					return new QQNodeProduct('product_id', 'ProductId', 'integer', $this);
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

	class QQNodeImages extends QQNode {
		protected $strTableName = 'xlsws_images';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Images';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'ImagePath':
					return new QQNode('image_path', 'ImagePath', 'string', $this);
				case 'ImageData':
					return new QQNode('image_data', 'ImageData', 'string', $this);
				case 'Width':
					return new QQNode('width', 'Width', 'integer', $this);
				case 'Height':
					return new QQNode('height', 'Height', 'integer', $this);
				case 'Checksum':
					return new QQNode('checksum', 'Checksum', 'string', $this);
				case 'Parent':
					return new QQNode('parent', 'Parent', 'integer', $this);
				case 'ParentObject':
					return new QQNodeImages('parent', 'ParentObject', 'integer', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'ProductAsImage':
					return new QQNodeImagesProductAsImage($this);
				case 'ChildImages':
					return new QQReverseReferenceNodeImages($this, 'childimages', 'reverse_reference', 'parent');

				case '_PrimaryKeyNode':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

	class QQReverseReferenceNodeImages extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_images';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Images';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'ImagePath':
					return new QQNode('image_path', 'ImagePath', 'string', $this);
				case 'ImageData':
					return new QQNode('image_data', 'ImageData', 'string', $this);
				case 'Width':
					return new QQNode('width', 'Width', 'integer', $this);
				case 'Height':
					return new QQNode('height', 'Height', 'integer', $this);
				case 'Checksum':
					return new QQNode('checksum', 'Checksum', 'string', $this);
				case 'Parent':
					return new QQNode('parent', 'Parent', 'integer', $this);
				case 'ParentObject':
					return new QQNodeImages('parent', 'ParentObject', 'integer', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'ProductAsImage':
					return new QQNodeImagesProductAsImage($this);
				case 'ChildImages':
					return new QQReverseReferenceNodeImages($this, 'childimages', 'reverse_reference', 'parent');

				case '_PrimaryKeyNode':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

?>