<?php
	class QFileAssetBase extends QPanel {
		public $imgFileIcon;
		public $btnDelete;
		public $btnUpload;
		public $dlgFileAsset;

		protected $strAcceptibleMimeArray;
		protected $strUnacceptableMessage;
		protected $intFileAssetType;
		protected $strFile;
		protected $strFileName;
		protected $blnClickToView;

		protected $strIconFilePathArray = array();

		protected $strTemporaryUploadPath;

		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);

			// Setup IconFilePathArray
			$this->SetupIconFilePathArray();

			// Setup Required Properties/Parameters
			$this->dlgFileAsset = new QFileAssetDialog($this, 'dlgFileAsset_Upload');

			$this->imgFileIcon = new QImageControl($this);
			$this->imgFileIcon->Width = 80;
			$this->imgFileIcon->Height = 80;
			$this->imgFileIcon->ImagePath = $this->strIconFilePathArray['blank'];

			// Setup Controls
			$this->btnUpload = new QLinkButton($this);
			$this->btnUpload->HtmlEntities = false;
			$this->btnUpload->AddAction(new QClickEvent(), new QShowDialogBox($this->dlgFileAsset));
			$this->btnUpload->AddAction(new QClickEvent(), new QTerminateAction());

			// Define the "Delete" Button
			$this->btnDelete = new QLinkButton($this);
			$this->btnDelete->HtmlEntities = false;
			$this->btnDelete->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnDelete_Click'));
			$this->btnDelete->AddAction(new QClickEvent(), new QTerminateAction());
		}
		
		protected function SetupIconFilePathArray() {
			$this->strIconFilePathArray['blank'] = __DOCROOT__ . __IMAGE_ASSETS__ . '/file_asset_blank.png';
			$this->strIconFilePathArray['default'] = __DOCROOT__ . __IMAGE_ASSETS__ . '/file_asset_default.png';
			$this->strIconFilePathArray['pdf'] = __DOCROOT__ . __IMAGE_ASSETS__ . '/file_asset_pdf.png';
		}

		public function Validate() {
			$blnToReturn = parent::Validate();

			if ($blnToReturn) {
				if ($this->blnRequired && !$this->strFile) {
					$blnToReturn = false;
					if ($this->strName)
						$this->strValidationError = $this->strName . QApplication::Translate(' is required');
					else
						$this->strValidationError = $this->strName . QApplication::Translate('Required');
				}
			}

			return $blnToReturn;
		}

		public function dlgFileAsset_Upload() {
			// File Not Uploaded
			if (!file_exists($this->dlgFileAsset->flcFileAsset->File) || !$this->dlgFileAsset->flcFileAsset->Size) {
				$this->dlgFileAsset->ShowError($this->strUnacceptableMessage . ' 1');

			// File Has Incorrect MIME Type (only if an acceptiblemimearray is setup)
			} else if (is_array($this->strAcceptibleMimeArray) && (!array_key_exists($this->dlgFileAsset->flcFileAsset->Type, $this->strAcceptibleMimeArray))) {
				$this->dlgFileAsset->ShowError($this->strUnacceptableMessage . ' 2');

			// File Successfully Uploaded
			} else {
				// Setup Filename, Base Filename and Extension
				$strFilename = $this->dlgFileAsset->flcFileAsset->FileName;
				$intPosition = strrpos($strFilename, '.');

				if (is_array($this->strAcceptibleMimeArray) && array_key_exists($this->dlgFileAsset->flcFileAsset->Type, $this->strAcceptibleMimeArray))
					$strExtension = $this->strAcceptibleMimeArray[$this->dlgFileAsset->flcFileAsset->Type];
				else {
					if ($intPosition)
						$strExtension = substr($strFilename, $intPosition + 1);
					else
						$strExtension = null;
				}

				$strBaseFilename = substr($strFilename, 0, $intPosition);
				$strExtension = strtolower($strExtension);

				// Save the File in a slightly more permanent temporary location
				$strTempFilePath = $this->strTemporaryUploadPath . '/' . basename($this->dlgFileAsset->flcFileAsset->File) . rand(1000, 9999) . '.' . $strExtension;
				copy($this->dlgFileAsset->flcFileAsset->File, $strTempFilePath);
				$this->File = $strTempFilePath;

				// Cleanup and Save Filename
				$this->strFileName = preg_replace('/[^A-Z^a-z^0-9_\-]/', '', $strBaseFilename) . '.' . $strExtension;

				// Hide the Dialog Box
				$this->dlgFileAsset->HideDialogBox();

				// Refresh Thyself
				$this->Refresh();
			}
		}

		public function GetControlHtml() {
/*			if ($this->objFileAsset) {
				$this->strCssClass = 'FileAssetPanelItem';
				$this->SetCustomStyle('background', 'url(' . $this->objFileAsset->ThumbnailUrl() . ') no-repeat');
			} else {
				$this->strCssClass = 'FileAssetPanelItemNone';
				$this->SetCustomStyle('background', null);
			}*/
			return parent::GetControlHtml();
		}

		public function btnDelete_Click() {
			// Create a new shell FileAsset for this panel
			$this->File = null;
			$this->Refresh();

			// Temporary fix for Double DBox Render issue after Delete/Reupload
			QApplication::ExecuteJavaScript('document.getElementById(document.getElementById("Qform__FormId").value).removeChild(document.getElementById("' . $this->dlgFileAsset->ControlId . '_ctl"));');
		}

		public function __get($strName) {
			switch ($strName) {
				case 'File': return $this->strFile;
				case 'FileName': return $this->strFileName;
				case 'UnacceptableMessage': return $this->strUnacceptableMessage;
				case 'FileAssetType': return $this->intFileAssetType;
				case 'TemporaryUploadPath': return $this->strTemporaryUploadPath;
				case 'ClickToView': return $this->blnClickToView;

				case 'DialogBoxCssClass': return $this->dlgFileAsset->CssClass;
				case 'UploadText': return $this->dlgFileAsset->btnUpload->Text;
				case 'CancelText': return $this->dlgFileAsset->btnCancel->Text;
				case 'DialogBoxHtml': return $this->dlgFileAsset->lblMessage->Text;

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
		 * If the Selected File is WebRoot Accessible, return a valid URL
		 * Feel free to override this method.
		 *
		 * @return string Web-based URL to the File (for Downloading)
		 */
		public function GetWebUrl() {
			// First of all, if ClickToView is NOT set, then we obvioulsy will not pass out the URL
			if (!$this->blnClickToView)
				return null;

			// Now, we need to see if the file, itself, is actually in the docroot somewhere so that
			// it can be viewed, and if so, we need to return the web-based URL (relative to the docroot)
			if ($this->strFile) {

				// Normalize all backslashes to just plain slashes 
				$strFile = str_replace('\\', '/', substr($this->strFile, 0, strlen(__DOCROOT__)));
				$strDocRoot = str_replace('\\', '/', __DOCROOT__);
				if ($strFile == $strDocRoot) {
					$strToReturn = __VIRTUAL_DIRECTORY__ . substr($this->strFile, strlen(__DOCROOT__));

					// On Windows, we must replace all "\" with "/"
					if (substr(__DOCROOT__, 1, 2) == ':\\') {
						$strToReturn = str_replace('\\', '/', $strToReturn);
					}

					return $strToReturn;
				}
			}

			return null;
		}

		protected function SetFile($strFile) {
			if (!strlen($strFile)) {
				// No File Selected -- Remove
				$this->strFile = null;
				$this->imgFileIcon->ImagePath = $this->strIconFilePathArray['blank'];
			} else if (!is_file($strFile)) {
				// Invalid File Selected -- Throw Exception
				throw new QCallerException('File Not Found: ' . $strFile);
			} else {
				// Valid File Selected
				$this->strFile = realpath($strFile);

				// On Windows, we must replace all "\" with "/"
				if (substr($this->strFile, 1, 2) == ':\\') {
					$this->strFile = str_replace('\\', '/', $this->strFile);
				}

				// Figure Out File Type, and Display Icon Accordingly
				$strExtension = substr($this->strFile, strrpos($this->strFile, '.') + 1);
				switch (trim(strtolower($strExtension))) {
					case 'jpg':
					case 'jpeg':
					case 'png':
					case 'gif':
						$this->imgFileIcon->ImagePath = $this->strFile;
						break;
					case 'pdf':
						$this->imgFileIcon->ImagePath = $this->strIconFilePathArray[trim(strtolower($strExtension))];
						break;
					default:
						$this->imgFileIcon->ImagePath = $this->strIconFilePathArray['default'];
						break;
				}
			}

			$this->strFileName = basename($this->strFile);
			return $this->strFile;
		}
		
		protected function SetFileAssetType($intFileAssetType) {
			switch ($intFileAssetType) {
				case QFileAssetType::Image:
					$this->intFileAssetType = $intFileAssetType;
					$this->strAcceptibleMimeArray = array(
						'image/pjpeg' => 'jpg',
						'image/jpeg' => 'jpg',
						'image/jpg' => 'jpg',
						'image/png' => 'png',
						'image/x-png' => 'png',
						'image/gif' => 'gif');
					$this->strUnacceptableMessage = QApplication::Translate('Must be a JPG, PNG or GIF');
					break;
				case QFileAssetType::Pdf:
					$this->intFileAssetType = $intFileAssetType;
					$this->strAcceptibleMimeArray = array(
						'application/pdf' => 'pdf',
						'application/octet-stream' => 'pdf'
					);
					$this->strUnacceptableMessage = QApplication::Translate('Must be a PDF');
					break;
				case QFileAssetType::Document:
					$this->intFileAssetType = $intFileAssetType;
					$this->strAcceptibleMimeArray = array(
						'application/pdf' => 'pdf',
						'application/octet-stream' => 'pdf',
						'image/pjpeg' => 'jpg',
						'image/jpeg' => 'jpg',
						'image/jpg' => 'jpg',
						'image/png' => 'png',
						'image/x-png' => 'png',
						'image/gif' => 'gif');
					$this->strUnacceptableMessage = QApplication::Translate('Must be a valid document (Image, PDF, etc.)');
					break;
				default:
					throw new QCallerException('FileAssetType must be a valid QFileAssetType constant value');
					break;
			}

			return $intFileAssetType;
		}

		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				case 'File':
					try {
						return $this->SetFile($mixValue);
					} catch (QCallerException $objExc) {						
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DialogBoxCssClass':
					try {
						return ($this->dlgFileAsset->CssClass = $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'UploadText':
					try {
						return ($this->dlgFileAsset->btnUpload->Text = $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CancelText':
					try {
						return ($this->dlgFileAsset->btnCancel->Text = $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DialogBoxHtml':
					try {
						return ($this->dlgFileAsset->lblMessage->Text = $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'FileAssetType':
					try {
						return $this->SetFileAssetType($mixValue);
					} catch (QCallerException $objExc) {						
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'UnacceptableMessage':
					try {
						return ($this->strUnacceptableMessage = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'TemporaryUploadPath':
					try {
						return ($this->strTemporaryUploadPath = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ClickToView':
					try {
						return ($this->blnClickToView = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						return parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}
?>