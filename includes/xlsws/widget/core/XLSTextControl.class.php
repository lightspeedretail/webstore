<?php

class XLSTextControl extends QTextBox {

    protected $strLabelForRequired = '%s is required';
    protected $strLabelForRequiredUnnamed = 'Required';

    public function __construct($objParentObject, $strControlId = null) {
        parent::__construct($objParentObject, $strControlId);

        $this->strLabelForRequired = 
            QApplication::Translate($this->strLabelForRequired);

        $this->strLabelForRequiredUnnamed = 
            QApplication::Translate($this->strLabelForRequiredUnnamed);
    }

    public function __toString() {
        return $this->Value;
    }

    public function ValidateValue() {
        $strText = stripslashes(trim($this->Text));
        if (!$strText == $this->Text)
            $this->Text = $strText;

        return true;
    }

    public function ValidateRequired() {
        if (!$this->blnRequired) return true;

        if ($this->Text == '') {
            if ($this->strName)
                $this->ValidationError = sprintf(
                    $this->strLabelForRequired,
                    $this->strName
                );
            else
                $this->ValidationError = 
                    $this->strLabelForRequiredUnnamed;

            return false;
        }

        return true;
    }

    public function Validate() {
        if (!$this->ValidateRequired()) return false;
        if (!$this->ValidateValue()) return false;

        $this->ValidationError = false;
        return true;
    }

    public function __get($strName) {
        switch ($strName) {
            case 'Value': return $this->Text;
            default:
                try { 
                    return parent::__get($strName);
                }
                catch (QCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }

    public function __set($strName, $mixValue) {
        switch ($strName) {
            case 'Value': return ($this->Text = $mixValue);
            default:
                try { 
                    return parent::__set($strName, $mixValue);
                }
                catch (QCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }
}

