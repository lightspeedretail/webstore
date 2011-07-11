<?php
	class QListBox extends QListBoxBase {
		///////////////////////////
		// ListBox Preferences
		///////////////////////////

		// Feel free to specify global display preferences/defaults for all QListBox controls
		protected $strCssClass = 'listbox';
//		protected $strFontNames = QFontFamily::Verdana;
//		protected $strFontSize = '12px';
//		protected $strWidth = '250px';

		// For multiple-select based listboxes, you can define the way a "Reset" button should look
		protected function GetResetButtonHtml() {
			$strToReturn = sprintf(' <a href="#" onclick="__resetListBox(%s, %s); return false;" class="listboxReset">%s</a>',
				"'" . $this->Form->FormId . "'",
				"'" . $this->strControlId . "'",
				QApplication::Translate('Reset'));

			return $strToReturn;
		}
	}
?>