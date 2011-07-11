<?php
	/**
	 * The following methods are the rare, few "core" functions used by Qcodo.
	 * Please note that all core functions are prefixed with a "_".  Some methods
	 * are actually prefixed with "__" or "__qcodo_" if they are intended to be
	 * callbacks to PHP functionality (global magic methods) or to other Qcodo methods.
	 */

	// Default Qcodo output buffering callback	
	function __qcodo_ob_callback($strBuffer) {
		return QApplication::OutputPage($strBuffer);
	}

	// Default Qcodo Exception Handler callback
	function __qcodo_handle_exception(Exception $objException) {
		return QErrorHandler::HandleException($objException);
	}

	// Default Qcodo Error Handler callback
	function __qcodo_handle_error($intErrorNumber, $strErrorString, $strErrorFile, $intErrorLine) {
		return QErrorHandler::HandleError($intErrorNumber, $strErrorString, $strErrorFile, $intErrorLine);
	}



	// Special Print Functions / Shortcuts
	// NOTE: These are simply meant to be shortcuts to actual Qcodo functional
	// calls to make your templates a little easier to read.  By no means do you have to
	// use them.  Your templates can just as easily make the fully-named method/function calls.
		/**
		 * Standard Print function.  To aid with possible cross-scripting vulnerabilities,
		 * this will automatically perform QApplication::HtmlEntities() unless otherwise specified.
		 *
		 * @param string $strString string value to print
		 * @param boolean $blnHtmlEntities perform HTML escaping on the string first
		 */
		function _p($strString, $blnHtmlEntities = true) {
			// Standard Print
			if ($blnHtmlEntities && (gettype($strString) != 'object'))
				print(QApplication::HtmlEntities($strString));
			else
				print($strString);
		}

		/**
		 * Standard Print as Block function.  To aid with possible cross-scripting vulnerabilities,
		 * this will automatically perform QApplication::HtmlEntities() unless otherwise specified.
		 * 
		 * Difference between _b() and _p() is that _b() will convert any linebreaks to <br/> tags.
		 * This allows _b() to print any "block" of text that will have linebreaks in standard HTML.
		 *
		 * @param string $strString
		 * @param boolean $blnHtmlEntities
		 */
		function _b($strString, $blnHtmlEntities = true) {
			// Text Block Print
			if ($blnHtmlEntities && (gettype($strString) != 'object'))
				print(nl2br(QApplication::HtmlEntities($strString)));
			else
				print(nl2br($strString));
		}

		/**
		 * Standard Print-Translated function.  Note: Because translation typically
		 * occurs on coded text strings, NO HTML ESCAPING will be performed on the string.
		 * 
		 * Uses QApplication::Translate() to perform the translation (if applicable)
		 *
		 * @param string $strString string value to print via translation
		 */
		function _t($strString) {
			// Print, via Translation (if applicable)
			print(QApplication::Translate($strString));
		}

		function _i($intNumber) {
			// Not Yet Implemented
			// Print Integer with Localized Formatting
		}

		function _f($intNumber) {
			// Not Yet Implemented
			// Print Float with Localized Formatting
		}

		function _c($strString) {
			// Not Yet Implemented
			// Print Currency with Localized Formatting
		}
	//////////////////////////////////////
?>