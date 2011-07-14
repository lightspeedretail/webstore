<?php
	// This defines a specific column <td> for a DataGrid
	// All the appearance properties should be self-explanatory.
	
	// The SortByCommand and ReverseSortByCommand are both optional -- and are explained in more
	// depth in DataGrid.inc
	
	// "Name" is the name of the column, as displayed in the DataGrid's header row for that column
	// "Html" is the contents of the column itself -- the $this->strHtml contents can contain backticks ` to
	// deliniate commands that are to be PHP evaled (again, see DataGrid.inc for more info)

	class QDataGridColumn extends QBaseClass {
		// APPEARANCE
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
		protected $strHorizontalAlign = QHorizontalAlign::NotSet;
		protected $strVerticalAlign = QVerticalAlign::NotSet;
		protected $strWidth = null;
		protected $blnWrap = true;

		// BEHAVIOR
		protected $objOrderByClause = null;
		protected $objReverseOrderByClause = null;

		// MISC
		protected $strName;
		protected $strHtml;
		protected $blnHtmlEntities = true;

		public function __construct($strName, $strHtml = null, $objOverrideParameters = null) {
			$this->strName = $strName;
			$this->strHtml = $strHtml;

			$objOverrideArray = func_get_args();
			if (count($objOverrideArray) > 2)
				try {
					unset($objOverrideArray[0]);
					unset($objOverrideArray[1]);
					$this->OverrideAttributes($objOverrideArray);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}

		public function GetAttributes($blnIncludeCustom = true, $blnIncludeAction = true) {
			$strToReturn = "";
			$strStyle = "";			

			if (!$this->blnWrap)
				$strToReturn .= 'nowrap="nowrap" ';

			switch ($this->strHorizontalAlign) {
				case QHorizontalAlign::Left:
					$strStyle .= 'text-align:left;';
					break;
				case QHorizontalAlign::Right:
					$strStyle .= 'text-align:right;';
					break;
				case QHorizontalAlign::Center:
					$strStyle .= 'text-align:center;';
					break;
				case QHorizontalAlign::Justify:
					$strStyle .= 'text-align:justify;';
					break;
			}

			switch ($this->strVerticalAlign) {
				case QVerticalAlign::Top:
					$strStyle .= 'vertical-align:top;';
					break;
				case QVerticalAlign::Middle:
					$strStyle .= 'vertical-align:middle;';
					break;
				case QVerticalAlign::Bottom:
					$strStyle .= 'vertical-align:bottom;';
					break;
			}

			if ($this->strCssClass)
				$strToReturn .= sprintf('class="%s" ', $this->strCssClass);

			if ($this->strWidth) {
				if (is_numeric($this->strWidth))
					$strStyle .= sprintf("width:%spx;", $this->strWidth);
				else
					$strStyle .= sprintf("width:%s;", $this->strWidth);
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
				// APPEARANCE
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
				case "HorizontalAlign": return $this->strHorizontalAlign;
				case "VerticalAlign": return $this->strVerticalAlign;
				case "Width": return $this->strWidth;
				case "Wrap": return $this->blnWrap;

				// BEHAVIOR
				case "OrderByClause": return $this->objOrderByClause;
				case "ReverseOrderByClause": return $this->objReverseOrderByClause;

				// MANUAL QUERY BEHAVIORS
				case "SortByCommand": return $this->objOrderByClause;
				case "ReverseSortByCommand": return $this->objReverseOrderByClause;

				// MISC
				case "Html": return $this->strHtml;
				case "Name": return $this->strName;
				case "HtmlEntities": return $this->blnHtmlEntities;

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
				// APPEARANCE
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
				case "Width":
					try {
						$this->strWidth = QType::Cast($mixValue, QType::String);
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

					
				// BEHAVIOR
				case "OrderByClause":
					try {
						$this->objOrderByClause = QType::Cast($mixValue, 'QQOrderBy');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ReverseOrderByClause":
					try {
						$this->objReverseOrderByClause = QType::Cast($mixValue, 'QQOrderBy');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
					
				// MANUAL QUERY BEHAVIOR
				case "SortByCommand":
					try {
						$this->objOrderByClause = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ReverseSortByCommand":
					try {
						$this->objReverseOrderByClause = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
					
				// MISC
				case "Html":
					try {
						$this->strHtml = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Name":
					try {
						$this->strName = QType::Cast($mixValue, QType::String);
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