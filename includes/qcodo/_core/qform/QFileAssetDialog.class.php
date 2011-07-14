<?php
	class QFileAssetDialog extends QDialogBox {
		public $lblMessage;
		public $flcFileAsset;
		public $lblError;
		public $btnUpload;
		public $btnCancel;
		public $objSpinner;
		protected $strFileUploadCallback;

		public function __construct($objParentObject, $strFileUploadCallback, $strControlId = null) {
			// Call parent constructor and define FileUploadCallback
			parent::__construct($objParentObject, $strControlId);
			$this->strFileUploadCallback = $strFileUploadCallback;

			// Setup the Dialog Box, itself
			$this->strTemplate = __QCODO_CORE__ . '/assets/QFileAssetDialog.tpl.php';
			$this->blnDisplay = false;
			$this->blnMatteClickable = false;

			// Controls for Upload FileAsset Dialog Box
			$this->lblMessage = new QLabel($this);
			$this->lblMessage->HtmlEntities = false;

			$this->lblError = new QLabel($this);
			$this->lblError->HtmlEntities = false;

			$this->flcFileAsset = new QFileControl($this);
			$this->btnUpload = new QButton($this);
			$this->btnCancel = new QButton($this);
			$this->objSpinner = new QWaitIcon($this);

			// Events on the Dialog Box Controls
			$this->flcFileAsset->AddAction(new QEnterKeyEvent(), new QTerminateAction());

			$this->btnUpload->AddAction(new QClickEvent(), new QToggleEnableAction($this->btnUpload));
			$this->btnUpload->AddAction(new QClickEvent(), new QToggleEnableAction($this->btnCancel));
			$this->btnUpload->AddAction(new QClickEvent(), new QToggleDisplayAction($this->objSpinner));
			$this->btnUpload->AddAction(new QClickEvent(), new QServerControlAction($this, 'btnUpload_Click'));

			$this->btnCancel->AddAction(new QClickEvent(), new QHideDialogBox($this));
		}

		public function btnUpload_Click($strFormId, $strControlId, $strParameter) {
			$this->btnUpload->Enabled = true;
			$this->btnCancel->Enabled = true;
			$this->objSpinner->Display = false;

			$strFileControlCallback = $this->strFileUploadCallback;
			if ($this->objParentControl)
				$this->objParentControl->$strFileControlCallback($strFormId, $strControlId, $strParameter);
			else
				$this->objForm->$strFileControlCallback($strFormId, $strControlId, $strParameter);
		}

		public function ShowError($strErrorMessage) {
			$this->lblError->Text = $strErrorMessage;
			$this->flcFileAsset->Focus();
			$this->Blink();
		}
	}
?>