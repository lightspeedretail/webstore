<?php
	// Abstract object which is extended by anything which involves lists of selectable items.
	// (e.g. ListBox, CheckBoxList, RadioButtonList and TreeNav)
	// It contains a private objItemsArray which contains an array of ListItems.
	// * "ItemCount" (readonly) is the current count of ListItems in the control.
	// * "SelectedIndex" is the index # of the control that is selected.  "-1" means that nothing
	//   is selected.  If multiple items are selected, it will return the lowest index # of 
	//   all ListItems that are currently selected.  SETTING SelectedIndex will obviously
	//   select that specific ListItem, but it will also automatically UNSELECT ALL OTHER currently
	//   selected ListItems (if applicable).
	// * "SelectedItem" (readonly) returns the ListItem object, itself, that is selected 
	//   (or the ListItem with the lowest index # of a ListItems that are currently selected
	//   if multiple items are selected).  It will return null if nothing is selected.
	// * "SelectedName" (readonly) is simply returns ListControl::SelectedItem->Name, or null
	//   if nothing is selected.
	// * "SelectedValue" (readonly) is simply returns ListControl::SelectedItem->Value, or null
	//   if nothing is selected.
	// * "SelectedItems" (readonly) returns an array of selected ListItems (if any).

	abstract class QListControl extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// MISC
		protected $objItemsArray = array();

		//////////
		// Methods
		//////////
		
		// Allows you to add a ListItem to the ListControl at the end of the private objItemsArray.
		// This method exhibits polymorphism: you can either pass in a ListItem object, **OR** you can
		// pass in three strings:
		//	* Name of the ListItem (string)
		//	* Value of the ListItem (string, optional)
		//	* Selected flag for the ListItem (bool, optional)
		//  * OverrideParameters for ListItemStyle (optional)
		public function AddItem($mixListItemOrName, $strValue = null, $blnSelected = null, $strItemGroup = null, $strOverrideParameters = null) {
			$this->blnModified = true;
			if (gettype($mixListItemOrName) == QType::Object)
				$objListItem = QType::Cast($mixListItemOrName, "QListItem");
			elseif ($strOverrideParameters)			
				// The OverrideParameters can only be included if they are not null, because OverrideAttributes in QBaseClass can't except a NULL Value
				$objListItem = new QListItem($mixListItemOrName, $strValue, $blnSelected, $strItemGroup, $strOverrideParameters);
			else 
				$objListItem = new QListItem($mixListItemOrName, $strValue, $blnSelected, $strItemGroup);

			array_push($this->objItemsArray, $objListItem);
		}

		// Used if you want to add a LIstItem at a specific location in objItemsArray
		public function AddItemAt($intIndex, QListItem $objListItem) {
			$this->blnModified = true;
			try {
				$intIndex = QType::Cast($intIndex, QType::Integer);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			if (($intIndex < 0) || 
				($intIndex > count($this->objItemsArray)))
				throw new QIndexOutOfRangeException($intIndex, "AddItemAt()");
			for ($intCount = count($this->objItemsArray); $intCount > $intIndex; $intCount--) {
				$this->objItemsArray[$intCount] = $this->objItemsArray[$intCount - 1];
			}
			
			$this->objItemsArray[$intIndex] = $objListItem;
		}

		// Gets the ListItem at a specific location in objItemsArray
		public function GetItem($intIndex) {
			try {
				$intIndex = QType::Cast($intIndex, QType::Integer);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			if (($intIndex < 0) || 
				($intIndex >= count($this->objItemsArray)))
				throw new QIndexOutOfRangeException($intIndex, "GetItem()");

			return $this->objItemsArray[$intIndex];
		}

		// REmoves all the items in objItemsArray
		public function RemoveAllItems() {
			$this->blnModified = true;
			$this->objItemsArray = array();
		}
		
		// Removes a specific ListItem at a specific location in objItemsArray
		public function RemoveItem($intIndex) {
			$this->blnModified = true;
			try {
				$intIndex = QType::Cast($intIndex, QType::Integer);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			if (($intIndex < 0) ||
				($intIndex > (count($this->objItemsArray) - 1)))
				throw new QIndexOutOfRangeException($intIndex, "RemoveItem()");
			for ($intCount = $intIndex; $intCount < count($this->objItemsArray) - 1; $intCount++) {
				$this->objItemsArray[$intCount] = $this->objItemsArray[$intCount + 1];
			}
			
			$this->objItemsArray[$intCount] = null;
			unset($this->objItemsArray[$intCount]);
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				case "ItemCount":
					if ($this->objItemsArray)
						return count($this->objItemsArray);
					else
						return 0;
				case "SelectedIndex":
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							return $intIndex;
					}
					return -1;
				case "SelectedName":
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							return $this->objItemsArray[$intIndex]->Name;
					}
					return null;
				case "SelectedValue":
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							return $this->objItemsArray[$intIndex]->Value;
					}
					return null;
				case "SelectedItem":
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							return $this->objItemsArray[$intIndex];
					}
					return null;
				case "SelectedItems":
					$objToReturn = array();
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							array_push($objToReturn, $this->objItemsArray[$intIndex]);
//							$objToReturn[count($objToReturn)] = $this->objItemsArray[$intIndex];
					}
					return $objToReturn;
				case "SelectedNames":
					$strNamesArray = array();
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							array_push($strNamesArray, $this->objItemsArray[$intIndex]->Name);
//							$strNamesArray[count($strNamesArray)] = $this->objItemsArray[$intIndex]->Name;
					}
					return $strNamesArray;
				case "SelectedValues":
					$objToReturn = array();
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							array_push($objToReturn, $this->objItemsArray[$intIndex]->Value);
//							$objToReturn[count($objToReturn)] = $this->objItemsArray[$intIndex]->Value;
					}
					return $objToReturn;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		public function __set($strName, $mixValue) {
			$this->blnModified = true;
			switch ($strName) {
				case "SelectedIndex":
					try {
						$mixValue = QType::Cast($mixValue, QType::Integer);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

					// Special Case
					if ($mixValue == -1)
						$mixValue = null;

					if (($mixValue < 0) ||
						($mixValue > (count($this->objItemsArray) - 1)))
						throw new QIndexOutOfRangeException($mixValue, "SelectedIndex");
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++)
						if ($mixValue == $intIndex)
							$this->objItemsArray[$intIndex]->Selected = true;
						else
							$this->objItemsArray[$intIndex]->Selected = false;
					return $mixValue;
					break;

				case "SelectedName":
					foreach ($this->objItemsArray as $objItem)
						if ($objItem->Name == $mixValue)
							$objItem->Selected = true;
						else
							$objItem->Selected = false;
					return $mixValue;
					break;

				case "SelectedValue":
					foreach ($this->objItemsArray as $objItem)
						if ($objItem->Value == $mixValue)
							$objItem->Selected = true;
						else
							$objItem->Selected = false;
					return $mixValue;
					break;


				case "SelectedNames":
					try {
						$mixValue = QType::Cast($mixValue, QType::ArrayType);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					foreach ($this->objItemsArray as $objItem) {
						$objItem->Selected = false;
						foreach ($mixValue as $mixName) {
							if ($objItem->Name == $mixName) {
								$objItem->Selected = true;
								break;
							}
						}
					}
					return $mixValue;
					break;

				case "SelectedValues":
					try {
						$mixValue = QType::Cast($mixValue, QType::ArrayType);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					foreach ($this->objItemsArray as $objItem) {
						$objItem->Selected = false;
						foreach ($mixValue as $mixName) {
							if ($objItem->Value == $mixName) {
								$objItem->Selected = true;
								break;
							}
						}
					}
					return $mixValue;
					break;

				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}
?>