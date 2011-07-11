<?php
	// This class will render HTML via a pair of <span></span> tags.  It is
	// used to simply display any miscellaneous HTML that you would want displayed.
	// Because of this, it can't really execute any actions.  Any Server- or Client-Actions
	// defined will simply be ignored.
	// * "Text" is the Html that you want rendered.

	abstract class QBlockControl extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		protected $strText = null;
		protected $strFormat = null;
		protected $strTemplate = null;
		protected $blnAutoRenderChildren = false;
		protected $strPadding = null;

		protected $strTagName = null;
		protected $blnHtmlEntities = true;

		// BEHAVIOR
		protected $blnDropTarget = false;

		// LAYOUT
		protected $strHorizontalAlign = QHorizontalAlign::NotSet;
		protected $strVerticalAlign = QVerticalAlign::NotSet;

		// Move Targets and Drop Zones
		protected $objMovesControlsArray = array();
		protected $objDropsControlsArray = array();
		protected $objDropsGroupingsArray = array();
		protected $objIsDropZoneFor = array();

		// Resize Handles
		protected $objUpperResizeControlsArray = array();
		protected $objLowerResizeControlsArray = array();
		protected $strResizeHandleDirection = null;
		protected $intResizeHandleMinimum;
		protected $intResizeHandleMaximum;

		public function AddUpperControlToResize(QBlockControl $objTargetControl) {
			$this->strJavaScripts = '_core/control_handle.js,_core/control_resize.js';
			if ($objTargetControl->strControlId == $this->strControlId)
				throw new QCallerException('A Resize Handle cannot be made to resize itself');
			$this->objUpperResizeControlsArray[$objTargetControl->strControlId] = $objTargetControl;
		}

		public function RemoveUpperControlToResize(QBlockControl $objTargetControl) {
			$this->objUpperResizeControlsArray[$objTargetControl->ControlId] = null;
			unset($this->objUpperResizeControlsArray[$objTargetControl->ControlId]);
		}

		public function RemoveAllUpperControlsToResize() {
			$this->objUpperResizeControlsArray = array();
		}

		public function AddLowerControlToResize(QBlockControl $objTargetControl) {
			$this->strJavaScripts = '_core/control_handle.js,_core/control_resize.js';
			if ($objTargetControl->strControlId == $this->strControlId)
				throw new QCallerException('A Resize Handle cannot be made to resize itself');
			$this->objLowerResizeControlsArray[$objTargetControl->strControlId] = $objTargetControl;
		}

		public function RemoveLowerControlToResize(QBlockControl $objTargetControl) {
			$this->objLowerResizeControlsArray[$objTargetControl->ControlId] = null;
			unset($this->objLowerResizeControlsArray[$objTargetControl->ControlId]);
		}

		public function RemoveAllLowerControlsToResize() {
			$this->objLowerResizeControlsArray = array();
		}

		public function AddControlToMove($objTargetControl = null) {
			$this->strJavaScripts = '_core/control_handle.js,_core/control_move.js';
			if (!$objTargetControl) {
				$this->blnMoveable = true;
				$this->objMovesControlsArray[$this->strControlId] = $this;

				if (($this->objParentControl) && ($this->objParentControl instanceof QBlockControl))
					$this->AddDropZone($this->objParentControl);
				else
					$this->AddDropZone($this->objForm);
			} else if ($objTargetControl instanceof QControl) { 
				$objTargetControl->Moveable = true;
				$this->objMovesControlsArray[$objTargetControl->ControlId] = $objTargetControl;

				if (($objTargetControl->ParentControl) && ($objTargetControl->ParentControl instanceof QBlockControl))
					$this->AddDropZone($objTargetControl->ParentControl);
				else
					$this->AddDropZone($objTargetControl->Form);
			} else
				throw new QCallerException('TargetControl that this will move must be a QControl object');
		}

		public function RemoveControlToMove(QControl $objTargetControl) {
			$this->objMovesControlsArray[$objTargetControl->ControlId] = null;
			unset($this->objMovesControlsArray[$objTargetControl->ControlId]);
		}

		public function RemoveAllControlsToMove() {
			$this->objMovesControlsArray = array();
			$this->RemoveAllDropZones();
		}

		public function AddDropZone($objParentObject) {
			$this->strJavaScripts = '_core/control_handle.js,_core/control_move.js';
			if ($objParentObject instanceof QForm) {
				$this->objDropsControlsArray[$objParentObject->FormId] = true;
			} else if ($objParentObject instanceof QBlockControl) {
				$this->objDropsControlsArray[$objParentObject->ControlId] = true;
				$objParentObject->DropTarget = true;
				$objParentObject->objIsDropZoneFor[$this->ControlId] = true;
			} else if ($objParentObject instanceof QDropZoneGrouping) {
				$this->objDropsGroupingsArray[$objParentObject->GroupingId] = true;
			} else
				throw new QCallerException('ParentObject must be either a QForm or QBlockControl object');
		}

		public function RemoveDropZone($objParentObject) {
			if ($objParentObject instanceof QForm) {
				$this->objDropsControlsArray[$objParentObject->FormId] = false;
			} else if ($objParentObject instanceof QBlockControl) {
				$this->objDropsControlsArray[$objParentObject->ControlId] = false;
				$objParentObject->objIsDropZoneFor[$this->ControlId] = false;
			} else
				throw new QCallerException('ParentObject must be either a QForm or QBlockControl object');
		}

		public function RemoveAllDropZones() {
			foreach ($this->objDropsControlsArray as $strControlId => $blnValue) {
				if ($blnValue) {
					$objControl = $this->objForm->GetControl($strControlId);
					if ($objControl)
						$objControl->objIsDropZoneFor[$this->ControlId] = false;
				}
			}
			$this->objDropsControlsArray = array();
		}
		
		public function GetEndScript() {
			$strToReturn = parent::GetEndScript();

			// MOVE TARGETS
			if (count($this->objMovesControlsArray)) {
//				$strToReturn .= sprintf('qcodo.registerControlMoveHandle("%s"); ', $this->strControlId);
				$strToReturn .= sprintf('qc.regCMH("%s"); ', $this->strControlId);

				foreach ($this->objMovesControlsArray as $objControl) {
//					$strToReturn .= sprintf('qcodo.getWrapper("%s").registerMoveTarget("%s"); ', $this->strControlId, $objControl->ControlId);
					$strToReturn .= sprintf('qc.getW("%s").regMT("%s"); ', $this->strControlId, $objControl->ControlId);
				}
			}

			// DROP ZONES
			foreach ($this->objDropsControlsArray as $strKey => $blnIsDropZone) {
				if ($blnIsDropZone)
//					$strToReturn .= sprintf('qcodo.getWrapper("%s").registerDropZone("%s"); ', $this->strControlId, $strKey);
					$strToReturn .= sprintf('qc.getW("%s").regDZ("%s"); ', $this->strControlId, $strKey);
			}

			foreach ($this->objIsDropZoneFor as $strKey => $blnIsDropZone) {
				if ($blnIsDropZone) {
					$objControl = $this->objForm->GetControl($strKey);
					if ($objControl && ($objControl->strRenderMethod))
//						$strToReturn .= sprintf('qcodo.registerControlMoveHandle("%s"); qcodo.getWrapper("%s").registerDropZone("%s"); ', $strKey, $strKey, $this->strControlId);
						$strToReturn .= sprintf('qc.regCMH("%s"); qc.getW("%s").regDZ("%s"); ', $strKey, $strKey, $this->strControlId);
				}
			}
			
			foreach ($this->objDropsGroupingsArray as $strKey => $blnIsDropZone) {
				if ($blnIsDropZone) {
					$strToReturn .= sprintf('qc.getW("%s").regDZG("%s");', $this->strControlId, $strKey);
				}
			}

			// ResizeHandle
			// regCRH is a shortcut for registerControlResizeHandle
			// setUC = setUpperControl
			// setLC = setLowerControl
			if (($this->strResizeHandleDirection == QResizeHandleDirection::Vertical) ||
				($this->strResizeHandleDirection == QResizeHandleDirection::Horizontal)) {
				if ($this->strResizeHandleDirection == QResizeHandleDirection::Vertical)
					$strToReturn .= sprintf('qc.regCRH("%s", true);', $this->strControlId);
				else
					$strToReturn .= sprintf('qc.regCRH("%s", false);', $this->strControlId);

				foreach ($this->objUpperResizeControlsArray as $objBlockControl)
					$strToReturn .= sprintf('qc.getW("%s").setUC("%s");', $this->strControlId, $objBlockControl->strControlId);
				foreach ($this->objLowerResizeControlsArray as $objBlockControl)
					$strToReturn .= sprintf('qc.getW("%s").setLC("%s");', $this->strControlId, $objBlockControl->strControlId);
				if (!is_null($this->intResizeHandleMinimum))
					$strToReturn .= sprintf('qc.getW("%s").setReMi("%s");', $this->strControlId, $this->intResizeHandleMinimum);
				else
					$strToReturn .= sprintf('qc.getW("%s").setReMi(null);', $this->strControlId);
				if (!is_null($this->intResizeHandleMaximum))
					$strToReturn .= sprintf('qc.getW("%s").setReMa("%s");', $this->strControlId, $this->intResizeHandleMaximum);
				else
					$strToReturn .= sprintf('qc.getW("%s").setReMa(null);', $this->strControlId);
			}

			return $strToReturn;
		}

/*		public function GetEndHtml() {
			$strToReturn = parent::GetEndHtml();
			if ($this->blnDropTarget)
				$strToReturn .= sprintf('<span id="%s_ctldzmask"></span>', $this->strControlId);
			return $strToReturn;
		}
*/
		public function GetStyleAttributes() {
			$strStyle = parent::GetStyleAttributes();
			
			if ($this->strPadding) {
				if (is_numeric($this->strPadding))
					$strStyle .= sprintf('padding:%spx;', $this->strPadding);
				else
					$strStyle .= sprintf('padding:%s;', $this->strPadding);
			}

			if (($this->strHorizontalAlign) && ($this->strHorizontalAlign != QHorizontalAlign::NotSet))
				$strStyle .= sprintf('text-align:%s;', $this->strHorizontalAlign);

			if (($this->strVerticalAlign) && ($this->strVerticalAlign != QVerticalAlign::NotSet))
				$strStyle .= sprintf('vertical-align:%s;', $this->strVerticalAlign);

			return $strStyle;
		}

		//////////
		// Methods
		//////////
		public function ParsePostData() {}
		public function GetJavaScriptAction() {}
		protected function GetControlHtml() {
			$strStyle = $this->GetStyleAttributes();

			if ($strStyle)
				$strStyle = sprintf('style="%s"', $strStyle);

			if ($this->strFormat)
				$strText = sprintf($this->strFormat, $this->strText);
			else
				$strText = $this->strText;

			$strTemplateEvaluated = '';
			if ($this->strTemplate) {
				global $_CONTROL;
				$objCurrentControl = $_CONTROL;
				$_CONTROL = $this;
				$strTemplateEvaluated = $this->objForm->EvaluateTemplate($this->strTemplate);
				$_CONTROL = $objCurrentControl;
			}

			$strToReturn = sprintf('<%s id="%s" %s%s>%s%s%s</%s>',
				$this->strTagName,
				$this->strControlId,
				$this->GetAttributes(),
				$strStyle,
				($this->blnHtmlEntities) ? QApplication::HtmlEntities($strText) : $strText,
				$strTemplateEvaluated,
				($this->blnAutoRenderChildren) ? $this->RenderChildren(false) : '',
				$this->strTagName);

//			if ($this->blnDropTarget)
//				$strToReturn .= sprintf('<span id="%s_ctldzmask" style="position:absolute;"><span style="font-size: 1px">&nbsp;</span></span>', $this->strControlId);

			return $strToReturn;
		}
		public function Validate() {return true;}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Text": return $this->strText;
				case "Format": return $this->strFormat;
				case "Template": return $this->strTemplate;
				case "AutoRenderChildren": return $this->blnAutoRenderChildren;
				case "TagName": return $this->strTagName;
				case "Padding": return $this->strPadding;
				case "HtmlEntities": return $this->blnHtmlEntities;

				// BEHAVIOR
				case "DropTarget": return $this->blnDropTarget;

				case "HorizontalAlign": return $this->strHorizontalAlign;
				case "VerticalAlign": return $this->strVerticalAlign;

				case "ResizeHandleMinimum": return $this->intResizeHandleMinimum;
				case "ResizeHandleMaximum": return $this->intResizeHandleMaximum;
				case "ResizeHandleDirection": return $this->strResizeHandleDirection;

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
				// APPEARANCE
				case "Text":
					try {
						$this->strText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Format":
					try {
						$this->strFormat = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Template":
					try {
						if ($mixValue) {
							if (file_exists($mixValue))
								$this->strTemplate = QType::Cast($mixValue, QType::String);
							else
								throw new QCallerException('Template file does not exist: ' . $mixValue);
						} else
							$this->strTemplate = null; 
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "AutoRenderChildren":
					try {
						$this->blnAutoRenderChildren = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "TagName":
					try {
						$this->strTagName = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "HtmlEntities":
					try {
						$this->blnHtmlEntities = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Padding":
					try {
						$this->strPadding = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "DropTarget":
					try {
						$this->blnDropTarget = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "HorizontalAlign":
					try {
						$this->strHorizontalAlign = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "VerticalAlign":
					try {
						$this->strVerticalAlign = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "ResizeHandleDirection":
					try {
						$this->strResizeHandleDirection = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "ResizeHandleMinimum":
					try {
						$this->intResizeHandleMinimum = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "ResizeHandleMaximum":
					try {
						$this->intResizeHandleMaximum = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

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
	
	$_CONTROL = null;
?>