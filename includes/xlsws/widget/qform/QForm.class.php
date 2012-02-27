<?php
	abstract class QForm extends QFormBase {
		public static $EncryptionKey = null;
		public static $FormStateHandler = 'XLSFormStateHandler';

        protected $strIgnoreJavaScriptFileArray = array();
		protected $strIgnoreStyleSheetFileArray = array();

        public function GetTemplatePath($strName) {
            $strPath = sprintf(
                'templates/%s/%s', 
                _xls_get_conf('DEFAULT_TEMPLATE', 'framework'),
                $strName
            );

            if (stristr($strName, '.tpl') && !file_exists($strPath)) {
                QApplication::Log(
                    E_ERROR, 'core',
                    _sp('Template file not found : ') . $strPath
                );
                die(_sp('Template file not found : ') . $strPath);
            }

            return $strPath;
        }

        public function EvaluateTemplate($strTemplate) {
            if (!file_exists($strTemplate))
                $strTemplate = $this->GetTemplatePath($strTemplate);

            return parent::EvaluateTemplate($strTemplate);
        }

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

                $objControl->ValidationReset();
                if (!$objControl->Validate()) {
                    $objControl->MarkAsModified();
                    $blnToReturn = false;
                }
            }

            return $blnToReturn;
        }
	}

?>
