<?php
	/**
	 * Simple Session-based FormState handler.  Uses PHP Sessions so it's very straightforward
	 * and simple, utilizing the session handling and cleanup functionality in PHP, itself.
	 * 
	 * The downside is that for long running sessions, each individual session file can get
	 * very, very large, storing all hte various formstate data.  Eventually (if individual
	 * session files > 10, 15 MB), you can theoretically observe a geometrical
	 * degradation of performance.
	 * 
	 * If requested by QForm, the index will be encrypted.
	 *
	 */
	class QSessionFormStateHandler extends QBaseClass {
		public static function Save($strFormState, $blnBackButtonFlag) {
			// Compress (if available)
			if (function_exists('gzcompress'))
				$strFormState = gzcompress($strFormState, 9);

			// Setup CurrentStateIndex (if none yet exists)
			if (!array_key_exists('qform_current_state_index', $_SESSION))
				$_SESSION['qform_current_state_index'] = 0;

			// Increment CurrentStateIndex if BackButtonFlag is true
			// Otherwise, we're in an ajax-to-ajax call, and the back button is invalid anyway
			// No need to increment session state index -- let's not to save space
			if ($blnBackButtonFlag)
				$_SESSION['qform_current_state_index'] = $_SESSION['qform_current_state_index'] + 1;
			$intStateIndex = $_SESSION['qform_current_state_index'];

			// Save THIS formstate
			// NOTE: if gzcompress is used, we are saving the *BINARY* data stream of the compressed formstate
			// In theory, this SHOULD work.  But if there is a webserver/os/php version that doesn't like
			// binary session streams, you can first base64_encode before saving to session (see note below).
			$_SESSION['qform_' . $intStateIndex] = $strFormState;

			// Return StateIndex
			if (!is_null(QForm::$EncryptionKey)) {
				// Use QCryptography to Encrypt
				$objCrypto = new QCryptography(QForm::$EncryptionKey, true);
				return $objCrypto->Encrypt($intStateIndex);
			} else
				return $intStateIndex;
		}

		public static function Load($strPostDataState) {
			global $_SESSION;
			//error_log("OENTUHOENTUH QSessStateHandlLoad:called ");
			// Pull Out intStateIndex
			if (!is_null(QForm::$EncryptionKey)) {
				//error_log("OENTUHOENTUH QSessStateHandlLoad: have encrypt key...");
				// Use QCryptography to Decrypt
				$objCrypto = new QCryptography(QForm::$EncryptionKey, true);
				$intStateIndex = $objCrypto->Decrypt($strPostDataState);
			} else {

				//error_log("OENTUHOENTUH QSessStateHandlLoad: no key, play raw");
				$intStateIndex = $strPostDataState;
			}

			// Pull FormState from Session
			// NOTE: if gzcompress is used, we are restoring the *BINARY* data stream of the compressed formstate
			// In theory, this SHOULD work.  But if there is a webserver/os/php version that doesn't like
			// binary session streams, you can first base64_decode before restoring from session (see note above).
			if (array_key_exists('qform_' . $intStateIndex, $_SESSION)) {
				$strSerializedForm = $_SESSION['qform_' . $intStateIndex];

				//error_log("OENTUHOENTUH QSessStateHandlLoad: form is $strSerializedForm");
				// Uncompress (if available)
				if (function_exists('gzcompress'))
					$strSerializedForm = gzuncompress($strSerializedForm);

				//error_log("OENTUHOENTUH QSessStateHandlLoad: $strSerializedForm");
				return $strSerializedForm;
			} else {
				//error_log("OENTUHOENTUH QSessStateHandlLoad: no such session var " . 'qform_' . $intStateIndex);
				//error_log("NOTNOTNOTONTO SESSION is " . var_export($_SESSION, TRUE));
				return null;
			}
		}

		public static function Load2($strPostDataState) {
			global $_SESSION;
			// Pull Out intStateIndex
			if (!is_null(QForm::$EncryptionKey)) {
				// Use QCryptography to Decrypt
				$objCrypto = new QCryptography(QForm::$EncryptionKey, true);
				$intStateIndex = $objCrypto->Decrypt($strPostDataState);
			} else
				$intStateIndex = $strPostDataState;

			// Pull FormState from Session
			// NOTE: if gzcompress is used, we are restoring the *BINARY* data stream of the compressed formstate
			// In theory, this SHOULD work.  But if there is a webserver/os/php version that doesn't like
			// binary session streams, you can first base64_decode before restoring from session (see note above).
			if (array_key_exists('qform_' . $intStateIndex, $_SESSION)) {
				$strSerializedForm = $_SESSION['qform_' . $intStateIndex];

				// Uncompress (if available)
				if (function_exists('gzcompress'))
					$strSerializedForm = gzuncompress($strSerializedForm);

				return $strSerializedForm;
			} else
				return null;
		}
	}
?>
