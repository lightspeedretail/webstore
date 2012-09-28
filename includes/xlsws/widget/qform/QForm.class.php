<?php
	abstract class QForm extends QFormBase {
		public static $EncryptionKey = null;
		public static $FormStateHandler = 'XLSFormStateHandler';

        protected $strIgnoreJavaScriptFileArray = array();
		protected $strIgnoreStyleSheetFileArray = array();

        protected function ValidateControlAndChildren(QControl $objControl) {
            $blnToReturn = true;

            if ($objControl->Visible && $objControl->Enabled && $objControl->Display) {
                foreach ($objControl->GetChildControls() as $objChildControl) {
                    if (!$objChildControl->Visible || !$objChildControl->Enabled)
                        continue;

                    if (($objChildControl instanceof XLSCompositeControl) ||
                        ($objChildControl->RenderMethod && 
                         $objChildControl->OnPage))
                            if (!$this->ValidateControlAndChildren($objChildControl))
                                $blnToReturn = false;
                }

                $objControl->ValidationReset(false);
                if (!$objControl->Validate()) {
                    $objControl->MarkAsModified();
                    $blnToReturn = false;
                }
            }

            return $blnToReturn;
        }
	}

?>
