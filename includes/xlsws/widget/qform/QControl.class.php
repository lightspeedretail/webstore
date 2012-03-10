<?php

abstract class QControl extends QControlBase {

    public function GetTemplatePath($strPath) {
        if (!file_exists($strPath)) {
            $strPath = sprintf('templates/%s/%s', 
                _xls_get_conf('DEFAULT_TEMPLATE', 'framework'), 
                $strPath
            );
        }

        return $strPath;
    }

    public function RenderAsDefinition($blnDisplayOutput = true) {
        /*
         * <dl>
         *  <dt>
         *      <label for="">
         *          <span>*</span>
         *          Name
         *
         *      </label>
         *      <div> Instructions </div>
         *  </dt>
         *  <dd>
         *      Field
         *      <div> Error
         *      <div> Warning
         *  </dd>
         * </dl>
         */

        $this->RenderHelper(func_get_args(), __FUNCTION__);
        
        $this->blnIsBlockElement = true;

        $strToReturn = '';
        $strClass = '';

        if ($this->blnRequired)
            $strClass .= ' required';

        if (!$this->blnEnabled)
            $strClass .= ' enabled';

        if ($this->strWarning)
            $strClass .= ' warning';

        if ($this->strValidationError)
            $strClass .= ' error';

        if ($strClass) $strToReturn = sprintf('<dl class="%s">', $strClass);
        else $strToReturn = '<dl>';

        if ($this->strName) {
            $strRequired = '';
            if ($this->blnRequired)
                $strRequired = '<span class="red">*</span>';

            $strInstructions = '';
            if ($this->strInstructions)
                $strInstructions = sprintf('<br><span class="%s">%s</span>',
                    'instructions',
                    $this->strInstructions
                );

            $strToReturn .= sprintf('<dt><label for="%s">%s%s</label>%s</dt>',
                $this->strControlId, 
                $strRequired,
                $this->strName, 
                $strInstructions
            );
        }

        $strToReturn .= '<dd>';
        try { 
            $strToReturn .= $this->GetControlHtml();
        }
        catch (QCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        /*
        if ($this->strValidationError)
            $strToReturn .= sprintf('<br><span class="%s">%s</span>',
                'error',
                $this->strValidationError
            );
        */

        $strToReturn .= '</dd>';

		return $this->RenderOutput($strToReturn, $blnDisplayOutput);
    }

    protected function RenderChildren($blnDisplayOutput = true) {
        $strToReturn = '';

        foreach ($this->GetChildControls() as $objControl) {
            $strRenderMethod = $objControl->RenderMethod;
            if (!$strRenderMethod)
                $strRenderMethod = 'Render';

            if (!$objControl->Rendered)
                $strToReturn .= 
                    $objControl->$strRenderMethod($blnDisplayOutput);
        }

        if ($blnDisplayOutput) {
            print ($strToReturn);
            return null;
        }
        else
            return $strToReturn;
    }

    public function __get($strName) {
        switch ($strName) {
            case 'Active':
                if ($this->blnVisible && $this->blnEnabled)
                    return true;
                return false;

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
            case 'Active':
                $this->Visible = $mixValue;
                $this->Enabled = $mixValue;
                break;

            case 'Visible':
                if ($this->blnVisible != $mixValue)
                    return parent::__set($strName, $mixValue);
                else break;

            case 'Enabled':
                if ($this->blnEnabled != $mixValue)
                    return parent::__set($strName, $mixValue);
                else break;

            case 'Display':
                if ($this->blnDisplay != $mixValue)
                    return parent::__set($strName, $mixValue);
                else break;

            case 'ValidationError':
                if ($this->strValidationError != $mixValue)
                    $this->blnModified = true;

                $this->strValidationError = QType::Cast(
                    $mixValue, Qtype::String
                );
                break;

            case 'Template':
                $mixValue = $this->GetTemplatePath($mixValue);

                if (!file_exists($strPath) && stristr($strPath, '.tpl')) { 
                    QApplication::Log(
                        E_ERROR, 
                        'core',
                        _sp('Template file not found : ' . $strPath)
                    );
                    die (_sp('Template file not found : ' . $strPath));
                }
                
                $this->strTemplate = $mixValue;
                break;

            case 'RenderMethod':
                try { 
                    $this->blnModified = true;
                    $this->strRenderMethod = QType::Cast(
                        $mixValue, QType::String
                    );
                    break;
                }
                catch (QInvalidCastException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            default:
                try { 
                    parent::__set($strName, $mixValue);
                    break;
                }
                catch (QCallerException $objExc) { 
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }
}

?>
