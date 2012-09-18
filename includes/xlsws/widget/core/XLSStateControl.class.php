<?php

class XLSStateControl extends XLSListControl {
    protected $blnFilterDestinations = false;
    protected $strLabelForSelect = '--';
    protected $strLabelForNone = 'n/a';

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
		$this->blnFilterDestinations = _xls_get_conf('SHIP_RESTRICT_DESTINATION',0);
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
                $objCountry->SelectedValue,QQ::Clause(
                            QQ::OrderBy(
                                QQN::State()->SortOrder, 
                                QQN::State()->State
                            )
                        )
            );
        }

        if ($objStates) {
            $this->AddItem(_sp($this->strLabelForSelect), null);

            foreach ($objStates as $objState)
                if ($objState->CountryCode=="US" || $objState->CountryCode=="CA")
                	$this->AddItem($objState->Code, $objState->Code);
                else $this->AddItem($objState->State, $objState->Code);
        }
        else {
            $this->AddItem(_sp("n/a"), null);
        }
    }

	public function Validate() {

		$objCountry = $this->GetCountryControl();

		if (!is_null($objCountry->SelectedValue)) {
			//Country is selected, verify state was picked off the list

			//If we only have one option on the state list, we've picked a country that doesn't use states
			//so pass the validation
			if (is_null($this->SelectedValue) && count($this->objItemsArray)==1)
				return true;
			if (!is_null($this->SelectedValue))
				return true;
		}

		$this->ValidationError = _sp("State is Required");
		return false;


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

