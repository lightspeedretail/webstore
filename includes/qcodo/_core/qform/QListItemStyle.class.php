<?php
	/* Qcodo Development Framework for PHP
	 * http://www.qcodo.com/
	 *
	 * Copyright (C) 2006
	 * Hunter Jensen - Barefoot Solutions - http://www.barefootsolutions.com/
	 *
	 * This program is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU General Public License
	 * as published by the Free Software Foundation; either version 2
	 * of the License, or (at your option) any later version.
	 *
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with this program; if not, write to the Free Software
	 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
	 */

	// This defines the style for an Item for a ListBox
	// All the appearance properties should be self-explanatory.

	// For more information about ListItem appearance, please see QListItem.class.php
	
	class QListItemStyle extends QBaseClass {
		
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
		protected $strWidth = null;

		public function ApplyOverride(QListItemStyle $objOverrideStyle) {
			$objNewStyle = clone $this;

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

		public function GetAttributes($blnIncludeCustom = true, $blnIncludeAction = true) {
			$strToReturn = "";

			if ($this->strCssClass)
				$strToReturn .= sprintf('class="%s" ', $this->strCssClass);

			$strStyle = "";			
			
			if ($this->strHeight)
				$strStyle .= sprintf("height:%s;", $this->strHeight);
			if ($this->strWidth)
				$strStyle .= sprintf("width:%s;", $this->strWidth);
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
			if ($this->strFontSize)
				$strStyle .= sprintf("font-size:%s;", $this->strFontSize);
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
				case "Width": return $this->strWidth;

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
				case "Width":
					try {
						$this->strWidth = QType::Cast($mixValue, QType::String);
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