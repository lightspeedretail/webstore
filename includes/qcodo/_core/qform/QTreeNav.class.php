<?php
	class QTreeNav extends QControl {
		protected $strJavaScripts = '_core/treenav.js';

		protected $strItemCssStyle = 'treenav_item';
		protected $strItemSelectedCssStyle = 'treenav_item treenav_item_selected';
		protected $strItemHoverCssStyle = 'treenav_item treenav_item_hover';

		protected $intIndentWidth = 15;
		protected $intItemHeight = 15;
		protected $intItemWidth = 0;

		protected $objChildItemArray = array();
		protected $objItemArray = array();
		protected $intNextItemId = 1;
		protected $objSelectedTreeNavItem = null;

		protected $blnIsBlockElement = true;
		protected $blnExpandOnSelect = true;

		protected function GetItemHtml($objItem) {
			$strItemId = $this->strControlId . '_' . $objItem->ItemId;

			$objChildren = $objItem->ChildItemArray;
			$intChildCount = count($objChildren);

			$strSubNodeHtml = '';
			$strImageHtml = '';
			$strLabelHtml = '';

			if ($intChildCount) {
				// This Item has Children -- Must show either Collapsed or Expanded icon
				if ($objItem->Expanded) {
					$strImageHtml = sprintf('<span style="margin-right: 2px;"><img id="%s_image" src="%s/treenav_expanded.png" width="11" height="11" alt="" style="position: relative; top: 2px; cursor: pointer;" onclick="treenavToggleImage(\'%s\')"/></span>',
						$strItemId, __VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__, $strItemId);

					for ($intIndex = 0; $intIndex < $intChildCount; $intIndex++) {
						$objChildItem = $objChildren[$intIndex];
						$strChildItemId = $this->strControlId . '_' . $objChildItem->ItemId;
						$strSubNodeHtml .= sprintf('<div id="%s">%s</div>', $strChildItemId, $this->GetItemHtml($objChildItem, $strChildItemId));
					}

					$strSubNodeHtml = sprintf('<div id="%s_sub" style="margin-left: %spx;">%s</div>',
						$strItemId,
						$this->intIndentWidth,
						$strSubNodeHtml);
				} else {
					$strSubNodeHtml = sprintf('<div id="%s_sub" style="margin-left: %spx; display: none;"><span class="%s" style="cursor: auto;">%s</span></div>',
						$strItemId,
						$this->intIndentWidth,
						$this->strItemCssStyle,
						QApplication::Translate('Loading...')
					);

					$strCommand = sprintf('onclick="treenavToggleImage(\'%s\'); qc.pA(\'%s\', \'%s\', \'QTreeNav_Expand\', \'%s\')"',
						$strItemId,
						$this->objForm->FormId,
						$this->strControlId,
						$strItemId
					);

					$strImageHtml = sprintf('<span style="margin-right: 2px;"><img id="%s_image" src="%s/treenav_not_expanded.png" width="11" height="11" alt="" style="position: relative; top: 2px; cursor: pointer;" %s/></span>',
						$strItemId, __VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__, $strCommand);				
				}

			} else {
				// No Children -- we are displaying an End Node
				$strImageHtml = '<span style="margin-right: 2px;"><img src="' . __VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__ .
					'/treenav_child.png" width="11" height="11" alt="" style="position: relative; top: 2px;"/></span>';
			}

			$strCommand = sprintf('onclick="qc.pA(\'%s\', \'%s\', \'QChangeEvent\', \'%s\')"',
				$this->objForm->FormId,
				$this->strControlId,
				$strItemId);

			$strLabelHtml = sprintf('<span id="%s_label" class="%s" onmouseover="treenavItemSetStyle(\'%s_label\', \'%s\')" onmouseout="treenavItemSetStyle(\'%s_label\', \'%s\')" %s>%s</span>',
				$strItemId,
				($objItem->Selected) ? $this->strItemSelectedCssStyle : $this->strItemCssStyle,
				$strItemId, $this->strItemHoverCssStyle,
				$strItemId, ($objItem->Selected) ? $this->strItemSelectedCssStyle : $this->strItemCssStyle,
				$strCommand,
				$objItem->Name);

			if ($this->intItemWidth > 0)
				return sprintf('<div style="height: %spx; width: %spx;">%s%s</div>%s',
					$this->intItemHeight, $this->intItemWidth, $strImageHtml, $strLabelHtml, $strSubNodeHtml);
			else
				return sprintf('<div style="height: %spx;">%s%s</div>%s',
					$this->intItemHeight, $strImageHtml, $strLabelHtml, $strSubNodeHtml);
		}

		public function AddChildItem(QTreeNavItem $objItem) {
			array_push($this->objChildItemArray, $objItem);
		}

		public function AddItem(QTreeNavItem $objItem) {
			if (array_key_exists($objItem->ItemId, $this->objItemArray))
				throw new QCallerException('Item Id already exists in QTreeNav ' . $this->strControlId . ': ' . $objItem->ItemId, 2);
			$this->objItemArray[$objItem->ItemId] = $objItem;
		}

		public function GetItem($strItemId) {
			if (strpos($strItemId, '_') !== false) {
				$intIndexArray = explode('_', $strItemId);
				$strItemId = $intIndexArray[1];
			}

			if (array_key_exists($strItemId, $this->objItemArray))
				return $this->objItemArray[$strItemId];
			else
				return null;
		}

		public function GenerateItemId() {
			$strToReturn = 'i' . $this->intNextItemId;
			$this->intNextItemId++;
			return $strToReturn;
		}

		public function ParsePostData() {
			if (array_key_exists('Qform__FormControl', $_POST) && ($_POST['Qform__FormControl'] == $this->strControlId)) {
				if ($_POST['Qform__FormEvent'] == 'QChangeEvent') {
					$strParameter = $_POST['Qform__FormParameter'];
					$objItem = $this->GetItem($strParameter);

					$this->SelectedItem = $objItem;

					$strItemHtml = $this->GetItemHtml($objItem, $strParameter);
					$strItemHtml = addslashes($strItemHtml);
					QApplication::ExecuteJavaScript('treenavRedrawElement("' . $strParameter . '", "' . $strItemHtml . '")');
				} else if ($_POST['Qform__FormEvent'] == 'QTreeNav_Expand') {
					$strParameter = $_POST['Qform__FormParameter'];
					$objItem = $this->GetItem($strParameter);
					$objItem->Expanded = true;

					$strItemHtml = $this->GetItemHtml($objItem, $strParameter);
					$strItemHtml = addslashes($strItemHtml);
					QApplication::ExecuteJavaScript('treenavRedrawElement("' . $strParameter . '", "' . $strItemHtml . '")');
				}
			}
		}

		public function Validate() {return true;}

		public function GetControlHtml() {
			$strAttributes = $this->GetAttributes();
			$strStyles = $this->GetStyleAttributes();

			if ($strStyles)
				$strStyles = sprintf(' style="%s"', $strStyles);

			$strItemHtml = '';
			foreach ($this->objChildItemArray as $objItem) {
				$strItemId = $this->strControlId . '_' . $objItem->ItemId;
				$strItemHtml .= '<div id="' . $strItemId . '">' . $this->GetItemHtml($objItem) . '</div>';
			}

			return sprintf('<div id="%s" %s%s>%s</div>',
				$this->strControlId,
				$strAttributes,
				$strStyles,
				$strItemHtml);
		}


		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				case "SelectedItem": return $this->objSelectedTreeNavItem;
				case "SelectedValue":
					if ($this->objSelectedTreeNavItem)
						return $this->objSelectedTreeNavItem->Value;
					else
						return null;
				case "ChildItemArray": return (array) $this->objChildItemArray;
				case "ItemArray": return (array) $this->objItemArray;

				case "ItemCssStyle": return $this->strItemCssStyle;
				case "ItemSelectedCssStyle": return $this->strItemSelectedCssStyle;
				case "ItemHoverCssStyle": return $this->strItemHoverCssStyle;

				case "IndentWidth": return $this->intIndentWidth;
				case "ItemHeight": return $this->intItemHeight;
				case "ItemWidth": return $this->intItemWidth;

				case "ExpandOnSelect": return $this->blnExpandOnSelect;

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
			switch ($strName) {
				case "ItemCssStyle":
					try {
						$this->strItemCssStyle = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ItemSelectedCssStyle":
					try {
						$this->strItemSelectedCssStyle = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ItemHoverCssStyle":
					try {
						$this->strItemHoverCssStyle = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "IndentWidth":
					try {
						$this->intIndentWidth = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ItemHeight":
					try {
						$this->intItemHeight = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ItemWidth":
					try {
						$this->intItemWidth = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ExpandOnSelect":
					try {
						$this->blnExpandOnSelect = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "SelectedItem":
					try {
						$objItem = QType::Cast($mixValue, "QTreeNavItem");
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

					// If the currently selected item is $objItem, then do nothing
					if ($objItem && $this->objSelectedTreeNavItem && ((string) $this->objSelectedTreeNavItem->ItemId == (string) $objItem->ItemId))
						return $objItem;

					// Deselect the Old (if applicable)
					if ($this->objSelectedTreeNavItem) {
						// if we are in an AJAX response scenario, we MUST remember to use a javascript update call
						// to "deselect" the old selected item
						QApplication::ExecuteJavaScript(sprintf("treenavItemUnselect('%s_%s_label', '%s')", $this->strControlId, $this->objSelectedTreeNavItem->ItemId, $this->strItemCssStyle));

						// Update deselection in the form state, too
						$this->objSelectedTreeNavItem->Selected = false;
					}

					if ($this->objSelectedTreeNavItem = $objItem) {
						$objItem->Selected = true;

						if ($this->blnExpandOnSelect)
							$objItem->Expanded = true;

						// Ensure that all parents and ancestors are expanded
						$objParent = $this->GetItem($objItem->ParentItemId);

						while ($objParent) {
							$objParent->Expanded = true;
							$objParent = $this->GetItem($objParent->ParentItemId);
						}
					}

					return $objItem;

				case "ItemExpanded":
					$strTokenArray = explode(' ', $mixValue);
					$objItem = $this->GetItem($strTokenArray[0]);
					$objItem->Expanded = $strTokenArray[1];
					return $strTokenArray[1];

				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}
	}
?>