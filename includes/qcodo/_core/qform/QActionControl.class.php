<?php
	// Abstract object which is extended by things like Buttons.
	// It basically pre-sets CausesValidation to be true (b/c most of the time,
	// when a button is clicked we'd assume that we want the validation to kick off)
	// And it pre-defines ParsePostData, GetJavaScriptAction and Validate.

	abstract class QActionControl extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		//////////
		// Methods
		//////////
		public function ParsePostData() {}
		public function GetJavaScriptAction() {return "onclick";}
		public function Validate() {return true;}
	}
?>