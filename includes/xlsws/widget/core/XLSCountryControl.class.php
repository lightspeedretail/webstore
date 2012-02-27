<?php

class XLSCountryControl extends XLSListControl {
    protected $blnFilterDestinations = false;
    protected $strLabelForSelect = '-- Select One --';
    protected $strLabelForNone = '--';

    public function __construct($objParentControl, $strControlId) {
        try { 
            parent::__construct($objParentControl, $strControlId);
        }
        catch (QCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    public function Update() {
        $this->RemoveAllItems();

        $objCountries = array();

        if ($this->blnFilterDestinations) {
            $objDestinations = Destination::LoadAll();
            $strCountries = array();

            if ($objDestinations) {
                $objCountries = false;

                foreach ($objDestinations as $objDestination) {
                    $strCode = $objDestination->Country;

                    if ($strCode && !in_array($strCode, $strCountries))
                        $strCountries[] = $strCode;
                }

                if ($strCountries) {
                    $objCountries = Country::QueryArray(
                        QQ::In(
                            QQN::Country()->Code,
                            $strCountries
                        ),
                        QQ::Clause(
                            QQ::OrderBy(
                                QQN::Country()->SortOrder, 
                                QQN::Country()->Country
                            )
                        )
                    );
                }
            }
        }

        if (!$objCountries) {
            $objCountries = Country::LoadArrayByAvail(
                'Y',
                QQ::Clause(
                    QQ::OrderBy(
                        QQN::Country()->SortOrder, 
                        QQN::Country()->Country
                    )
                )
            );
        }

        if ($objCountries) {
            $this->AddItem(_sp($this->strLabelForSelect), null);

            foreach ($objCountries as $objCountry)
                $this->AddItem($objCountry->Country, $objCountry->Code);
        }
        else { 
            $this->AddItem(_sp($this->strLabelForNone), null);
        }
    }

    public function __get($strName) {
        switch ($strName) {
            case 'FilterDestinations': return $this->blnFilterDestinations;
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

