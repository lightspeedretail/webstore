<?php

class XLSStateControl extends XLSListControl {
    protected $blnFilterDestinations = false;
    protected $strLabelForSelect = '-- Select One --';
    protected $strLabelForNone = '--';

    protected $strCountryControlId = null;

    public function __construct($objParentControl, $strControlId, 
        $strCountryControlId
    ) {
        try { 
            parent::__construct($objParentControl, $strControlId);
        }
        catch (QCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        $this->strCountryControlId = $strCountryControlId;
    }

    protected function GetCountryControl() {
        if ($this->strCountryControlId)
            return $this->Form->GetControl($this->strCountryControlId);

        return false;
    }

    public function Update() {
        $this->RemoveAllItems();
        $objCountry = $this->GetCountryControl();

        if (is_null($objCountry->SelectedValue)) {
            $this->AddItem(_sp($this->strLabelForSelect));
            return;
        }

        $objStates = array();

        if ($this->blnFilterDestinations) {
            $objDestinations = Destination::LoadByCountry(
                $objCountry->SelectedValue
            );
            $strStates = array();

            if ($objDestinations) {
                foreach ($objDestinations as $objDestination) {
                    $strCode = $objDestination->State;

                    if ($strCode == '*') {
                        $objStates = State::LoadArrayByCountryCode(
                            $objCountry->SelectedValue
                        );
                        break;
                    }

                    if ($strCode && !in_array($strCode, $strStates))
                        $strStates[] = $strCode;
                }

                if ($strStates) {
                    $objStates = State::QueryArray(
                        QQ::In(
                            QQN::State()->Code,
                            $strStates
                        ),
                        QQ::Clause(
                            QQ::OrderBy(
                                QQN::State()->SortOrder, 
                                QQN::State()->State
                            )
                        )
                    );
                }
            }
        }

        if (!$objStates) {
            $objStates = State::LoadArrayByCountryCode(
                $objCountry->SelectedValue
            );
        }

        if ($objStates) {
            $this->AddItem(_sp($this->strLabelForSelect), null);

            foreach ($objStates as $objState)
                $this->AddItem($objState->State, $objState->Code);
        }
        else { 
            $this->AddItem(_sp($this->strLabelForNone), null);
        }
    }

    public function __get($strName) {
        switch ($strName) {
            case 'FilterDestinations':
                return $this->blnFilterDestinations;
            
            default: 
                try { return parent::__get($strName); }
                catch (QCallerException $objExc) { 
                    $objExc->IncrementOffset();
                    throw $objExc;
                }   
        }   
    }   

    public function __set($strName, $mixValue) {
        switch ($strName) {
            case 'FilterDestinations':
                return ($this->blnFilterDestinations = $mixValue);
            
            default: 
                try { return (parent::__set($strName, $mixValue)); }
                catch (QCallerException $objExc) { 
                    $objExc->IncrementOffset();
                    throw $objExc;
                }   
        }   
    }
}

