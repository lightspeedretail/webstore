<?php

abstract class XLSCustomerComposite extends XLSCompositeControl {
    protected $strLabelForRequired = '%s is required';
    protected $strLabelForRequiredUnnamed = 'Required';

    public function UpdateField($strField, $strValue) {
        $strProperty = false;

        $objField = $this->GetChildByName($strField);
        if (!$objField)
            return;

        if ($objField->Value != $strValue) {
            $objField->Value = $strValue;

            return true;
        }   

        return false;
    }   

    public function UpdateFieldsFromArray($strFieldArray) {
        foreach ($strFieldArray as $strField => $strValue)
            $this->UpdateField($strField, $strValue);
    }
}

