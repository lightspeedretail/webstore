<?php
	// This defines a the stle for a row <tr> for a DataGrid
	// All the appearance properties should be self-explanatory.

	// For more information about DataGrid appearance, please see DataGrid.inc
	
	class QDataGridRowStyle extends QBaseClass {
		protected $strBackColor = null;
		protected $strBorderColor = null;
		protected $strBorderStyle = QBorderStyle::NotSet;
		protected $strBorderWidth = null;
		protected $strCssClass = null;
		protected $blnFontBold = false;
		protected $blnFontItalic = false;
		protected $strFontNames = null;
		protected $blnFontOverline = false;
		protected $strFontSize = null;
		protected $blnFontStrikeout = false;
		protected $blnFontUnderline = false;
		protected $strForeColor = null;
		protected $strHeight = null;
		protected $strHorizontalAlign = QHorizontalAlign::NotSet;
		protected $strVerticalAlign = QVerticalAlign::NotSet;
		protected $blnWrap = true;

		public function ApplyOverride(QDataGridRowStyle $objOverrideStyle) {
			$objNewStyle = clone $this;

			if (!$objOverrideStyle->Wrap)
				$objNewStyle->Wrap = false;
			
			if (($objOverrideStyle->HorizontalAlign) && ($objOverrideStyle->HorizontalAlign != QHorizontalAlign::NotSet))
				$objNewStyle->HorizontalAlign = $objOverrideStyle->HorizontalAlign;

			if (($objOverrideStyle->VerticalAlign) && ($objOverrideStyle->VerticalAlign != QVerticalAlign::NotSet))
				$objNewStyle->VerticalAlign = $objOverrideStyle->VerticalAlign;

			if ($objOverrideStyle->Height)
				$objNewStyle->Height = $objOverrideStyle->Height;

			if ($objOverrideStyle->CssClass)
				$objNewStyle->CssClass = $objOverrideStyle->CssClass;

			if ($objOverrideStyle->ForeColor)
				$objNewStyle->ForeColor = $objOverrideStyle->ForeColor;
			if ($objOverrideStyle->BackColor)
				$objNewStyle->BackColor = $objOverrideStyle->BackColor;
			if ($objOverrideStyle->BorderColor)
				$objNewStyle->BorderColor = $objOverrideStyle->BorderColor;
			if ($objOverrideStyle->BorderWidth)
				$objNewStyle->BorderWidth = $objOverrideStyle->BorderWidth;
			if (($objOverrideStyle->BorderStyle) && ($objOverrideStyle->BorderStyle != QBorderStyle::NotSet))
				$objNewStyle->BorderStyle = $objOverrideStyle->BorderStyle;

			if ($objOverrideStyle->FontNames)
				$objNewStyle->FontNames = $objOverrideStyle->FontNames;
			if ($objOverrideStyle->FontSize)
				$objNewStyle->FontSize = $objOverrideStyle->FontSize;

			if ($objOverrideStyle->FontBold)
				$objNewStyle->FontBold = true;
			if ($objOverrideStyle->FontItalic)
				$objNewStyle->FontItalic = true;

			if ($objOverrideStyle->FontUnderline)
				$objNewStyle->FontUnderline = true;
			if ($objOverrideStyle->FontOverline)
				$objNewStyle->FontOverline = true;
			if ($objOverrideStyle->FontStrikeout)
				$objNewStyle->FontStrikeout = true;

			return $objNewStyle;
		}

		public function GetAttributes() {
			$strToReturn = "";

			if (!$this->blnWrap)
				$strToReturn .= 'nowrap="nowrap" ';

			switch ($this->strHorizontalAlign) {
				case QHorizontalAlign::Left:
					$strToReturn .= 'align="left" ';
					break;
				case QHorizontalAlign::Right:
					$strToReturn .= 'align="right" ';
					break;
				case QHorizontalAlign::Center:
					$strToReturn .= 'align="center" ';
					break;
				case QHorizontalAlign::Justify:
					$strToReturn .= 'align="justify" ';
					break;
			}

			switch ($this->strVerticalAlign) {
				case QVerticalAlign::Top:
					$strToReturn .= 'valign="top" ';
					break;
				case QVerticalAlign::Middle:
					$strToReturn .= 'valign="middle" ';
					break;
				case QVerticalAlign::Bottom:
					$strToReturn .= 'valign="bottom" ';
					break;
			}

			if ($this->strCssClass)
				$strToReturn .= sprintf('class="%s" ', $this->strCssClass);

			$strStyle = "";			
			
			if ($this->strHeight) {
				if (is_numeric($this->strHeight))
					$strStyle .= sprintf("height:%s;", $this->strHeight);
				else
					$strStyle .= sprintf("height:%spx;", $this->strHeight);
			}
			if ($this->strForeColor)
				$strStyle .= sprintf("color:%s;", $this->strForeColor);
			if ($this->strBackColor)
				$strStyle .= sprintf("background-color:%s;", $this->strBackColor);
			if ($this->strBorderColor)
				$strStyle .= sprintf("border-color:%s;", $this->strBorderColor);
			if ($this->strBorderWidth) {
				$strStyle .= sprintf("border-width:%s;", $this->strBorderWidth);
				if ((!$this->strBorderStyle) || ($this->strBorderStyle == QBorderStyle::NotSet))
					// For "No Border Style" -- apply a "solid" style because width is set
					$strStyle .= "border-style:solid;";
			}
			if (($this->strBorderStyle) && ($this->strBorderStyle != QBorderStyle::NotSet))
				$strStyle .= sprintf("border-style:%s;", $this->strBorderStyle);
			
			if ($this->strFontNames)
				$strStyle .= sprintf("font-family:%s;", $this->strFontNames);
			if ($this->strFontSize) {
				if (is_numeric($this->strFontSize))
					$strStyle .= sprintf("font-size:%spx;", $this->strFontSize);
				else
					$strStyle .= sprintf("font-size:%s;", $this->strFontSize);
			}
			if ($this->blnFontBold)
				$strStyle .= "font-weight:bold;";
			if ($this->blnFontItalic)
				$strStyle .= "font-style:italic;";
			
			$strTextDecoration = "";
			if ($this->blnFontUnderline)
				$strTextDecoration .= "underline ";
			if ($this->blnFontOverline)
				$strTextDecoration .= "overline ";
			if ($this->blnFontStrikeout)
				$strTextDecoration .= "line-through ";
			
			if ($strTextDecoration) {
				$strTextDecoration = trim($strTextDecoration);
				$strStyle .= sprintf("text-decoration:%s;", $strTextDecoration);
			}
			
			if ($strStyle)
				$strToReturn .= sprintf('style="%s" ', $strStyle);
			
			return $strToReturn;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				case "BackColor": return $this->strBackColor;
				case "BorderColor": return $this->strBorderColor;
				case "BorderStyle": return $this->strBorderStyle;
				case "BorderWidth": return $this->strBorderWidth;
				case "CssClass": return $this->strCssClass;
				case "FontBold": return $this->blnFontBold;
				case "FontItalic": return $this->blnFontItalic;
				case "FontNames": return $this->strFontNames;
				case "FontOverline": return $this->blnFontOverline;
				case "FontSize": return $this->strFontSize;
				case "FontStrikeout": return $this->blnFontStrikeout;
				case "FontUnderline": return $this->blnFontUnderline;
				case "ForeColor": return $this->strForeColor;
				case "Height": return $this->strHeight;
				case "HorizontalAlign": return $this->strHorizontalAlign;
				case "VerticalAlign": return $this->strVerticalAlign;
				case "Wrap": return $this->blnWrap;

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
				case "BackColor": 
					try {
						$this->strBackColor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "BorderColor":
					try {
						$this->strBorderColor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "BorderStyle":
					try {
						$this->strBorderStyle = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "BorderWidth":
					try {
						$this->strBorderWidth = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "CssClass":
					try {
						$this->strCssClass = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontBold":
					try {
						$this->blnFontBold = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontItalic":
					try {
						$this->blnFontItalic = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontNames":
					try {
						$this->strFontNames = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontOverline":
					try {
						$this->blnFontOverline = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontSize":
					try {
						$this->strFontSize = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontStrikeout":
					try {
						$this->blnFontStrikeout = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontUnderline":
					try {
						$this->blnFontUnderline = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ForeColor":
					try {
						$this->strForeColor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Height":
					try {
						$this->strHeight = QType::Cast($mixValue, QType::String);
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
				case "Wrap":
					try {
						$this->blnWrap = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

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