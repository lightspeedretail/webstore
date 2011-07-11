<?php
	/*
	 * This class is INTENDED to be modified.  Please define any custom "Render"-based methods
	 * to handle custom global rendering functionality for all your controls.
	 *
	 * As an EXAMPLE, a RenderWithName method is included for you.  Feel free to modify this method,
	 * or to add as many of your own as you wish.
	 *
	 * Please note: All custom render methods should start with a RenderHelper call and end with a RenderOUtput call.
	 */
	abstract class QControl extends QControlBase {
		// This will call GetControlHtml() for the bulk of the work, but will add layout html as well.  It will include
		// the rendering of the Controls' name label, any errors or warnings, instructions, and html before/after (if specified).
		// 
		// This one method can define how ALL controls should be rendered when "Rendered with Name" throughout the entire site.
		// For example:
		//			<Name>				<HTML Before><Control><HTML After>
		//			<Instructions>		<Error> or <warning>
		//
		// REMEMBER: THIS IS JUST AN EXAMPLE!!!  Feel free to modify.
		public function RenderWithName($blnDisplayOutput = true) {
			////////////////////
			// Call RenderHelper
			$this->RenderHelper(func_get_args(), __FUNCTION__);
			////////////////////

			// Custom Render Functionality Here

			// Because this example RenderWithName will render a block-based element (e.g. a DIV), let's ensure
			// that IsBlockElement is set to true
			$this->blnIsBlockElement = true;

			// Render the Control's Dressing
			$strToReturn = '<div class="renderWithName">';

			// Render the Left side
			$strLeftClass = "left";
			if ($this->blnRequired)
				$strLeftClass .= ' required';
			if (!$this->blnEnabled)
				$strLeftClass .= ' disabled';

			if ($this->strInstructions)
				$strInstructions = '<br/><span class="instructions">' . $this->strInstructions . '</span>';
			else
				$strInstructions = '';

			$strToReturn .= sprintf('<div class="%s"><label for="%s">%s</label>%s</div>' , $strLeftClass, $this->strControlId, $this->strName, $strInstructions);

			// Render the Right side
			if ($this->strValidationError)
				$strMessage = sprintf('<span class="error">%s</span>', $this->strValidationError);
			else if ($this->strWarning)
				$strMessage = sprintf('<span class="error">%s</span>', $this->strWarning);
			else
				$strMessage = '';

			try {
				$strToReturn .= sprintf('<div class="right">%s%s%s%s</div>',
					$this->strHtmlBefore, $this->GetControlHtml(), $this->strHtmlAfter, $strMessage);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			$strToReturn .= '</div>';

			////////////////////////////////////////////
			// Call RenderOutput, Returning its Contents
			return $this->RenderOutput($strToReturn, $blnDisplayOutput);
			////////////////////////////////////////////
		}
	}
?>