<?php

abstract class QControl extends QControlBase {

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
}

?>
