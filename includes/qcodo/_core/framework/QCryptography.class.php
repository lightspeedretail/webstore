<?php
	// Requires libmcrypt v2.4.x or higher

	class QCryptographyException extends QCallerException {}

	class QCryptography extends QBaseClass {
		protected $objMcryptModule;
		protected $blnBase64;
		
		protected $strKey;
		protected $strIv;

		/**
		 * Default Cipher for any new QCryptography instances that get constructed
		 * @var string Cipher
		 */
		public static $Cipher = MCRYPT_TRIPLEDES;

		/**
		 * Default Mode for any new QCryptography instances that get constructed
		 * @var string Mode
		 */
		public static $Mode = MCRYPT_MODE_ECB;

		/**
		 * The Random Number Generator the library uses to generate the IV:
		 *  - MCRYPT_DEV_RANDOM = /dev/random (only on *nix systems)
		 *  - MCRYPT_DEV_URANDOM = /dev/urandom (only on *nix systems)
		 *  - MCRYPT_RAND = the internal PHP srand() mechanism
		 * (on Windows, you *must* use MCRYPT_RAND, b/c /dev/random and /dev/urandom doesn't exist)
		 * 
		 * TODO: there appears to be some /dev/random locking issues on the Qcodo development
		 * environment (using Fedora Core 3 with PHP 5.0.4 and LibMcrypt 2.5.7).  Because of this,
		 * we are using MCRYPT_RAND be default.  Feel free to change to to /dev/*random at your own risk.
		 * 
		 * @var string RandomSource
		 */
		public static $RandomSource = MCRYPT_RAND;

		/**
		 * Default Base64 mode for any new QCryptography instances that get constructed.
		 * 
		 * This is similar to MIME-based Base64 encoding/decoding, but is safe to use
		 * in URLs, POST/GET data, and any other text-based stream.
		 * 
		 * Note that by setting Base64 to true, it will result in an encrypted data string
		 * that is 33% larger.
		 * @var string Base64
		 */
		public static $Base64 = true;

		/**
		 * Default Key for any new QCryptography instances that get constructed
		 * @var string Key
		 */
		public static $Key = "qc0Do!d3F@lT.k3Y";

		public function __construct($strKey = null, $blnBase64 = null, $strCipher = null, $strMode = null) {
			// Get the Key
			if (is_null($strKey))
				$strKey = self::$Key;

			// Get the Base64 Flag
			try {
				if (is_null($blnBase64))
					$this->blnBase64 = QType::Cast(self::$Base64, QType::Boolean);
				else
					$this->blnBase64 = QType::Cast($blnBase64, QType::Boolean);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Get the Cipher
			if (is_null($strCipher))
				$strCipher = self::$Cipher;

			// Get the Mode
			if (is_null($strMode))
				$strMode = self::$Mode;

			$this->objMcryptModule = mcrypt_module_open($strCipher, null, $strMode, null);
			if (!$this->objMcryptModule)
				throw new QCryptographyException('Unable to open LibMcrypt Module');

			// Determine IV Size
			$intIvSize = mcrypt_enc_get_iv_size($this->objMcryptModule);

			// Create the IV
			if (self::$RandomSource != MCRYPT_RAND) {
				// Ignore All Warnings
				$strIv = @mcrypt_create_iv($intIvSize, self::$RandomSource);

				// If the RandomNumGenerator didn't work, we revert back to using MCRYPT_RAND
				if (strlen($strIv) != $intIvSize) {
					srand();
					$strIv = mcrypt_create_iv($intIvSize, MCRYPT_RAND);
				}
			} else {
				srand();
				$strIv = mcrypt_create_iv($intIvSize, MCRYPT_RAND);				
			}

			$this->strIv = $strIv;

			// Determine KeySize length
			$intKeySize = mcrypt_enc_get_key_size($this->objMcryptModule);

			// Create the Key Based on Key Passed In
			$this->strKey = substr(md5($strKey), 0, $intKeySize);
		}

		public function Encrypt($strData) {
			// Initialize Encryption
			$intReturnValue = mcrypt_generic_init($this->objMcryptModule, $this->strKey, $this->strIv);
			if (($intReturnValue === false) || ($intReturnValue < 0))
				throw new QCryptographyException('Incorrect Parameters used in LibMcrypt Initialization');
			// Add Length to strData
			$strData = strlen($strData) . '/' . $strData;

			$strEncryptedData =  mcrypt_generic($this->objMcryptModule, $strData);
			if ($this->blnBase64) {
				$strEncryptedData = base64_encode($strEncryptedData);
				$strEncryptedData = str_replace('+', '-', $strEncryptedData);
				$strEncryptedData = str_replace('/', '_', $strEncryptedData);
				$strEncryptedData = str_replace('=', '', $strEncryptedData);
			}


			// Deinitialize Encryption
			if (!mcrypt_generic_deinit($this->objMcryptModule))
				throw new QCryptographyException('Unable to deinitialize encryption buffer');

			return $strEncryptedData;
		}

		public function Decrypt($strEncryptedData) {
			// Initialize Encryption
			$intReturnValue = mcrypt_generic_init($this->objMcryptModule, $this->strKey, $this->strIv);
			if (($intReturnValue === false) || ($intReturnValue < 0))
				throw new QCryptographyException('Incorrect Parameters used in LibMcrypt Initialization');

			if ($this->blnBase64) {
				$strEncryptedData = str_replace('_', '/', $strEncryptedData);
				$strEncryptedData = str_replace('-', '+', $strEncryptedData);
				$strEncryptedData = base64_decode($strEncryptedData);
			}
			$intBlockSize = mcrypt_enc_get_block_size($this->objMcryptModule);
			$strDecryptedData = mdecrypt_generic($this->objMcryptModule, $strEncryptedData);

			// Figure Out Length and Truncate
			$intPosition = strpos($strDecryptedData, '/');
			if (!$intPosition)
				throw new QCryptographyException('Invalid Length Header in Decrypted Data');
			$intLength = substr($strDecryptedData, 0, $intPosition);
			$strDecryptedData = substr($strDecryptedData, $intPosition + 1);			
			$strDecryptedData = substr($strDecryptedData, 0, $intLength);

			// Deinitialize Encryption
			if (!mcrypt_generic_deinit($this->objMcryptModule))
				throw new QCryptographyException('Unable to deinitialize encryption buffer');

			return $strDecryptedData;
		}

		public function EncryptFile($strFile) {
			if (file_exists($strFile)) {
				$strData = file_get_contents($strFile);
				return $this->Encrypt($strData);
			} else
				throw new QCallerException('File does not exist: ' . $strFile);
		}

		public function DecryptFile($strFile) {
			if (file_exists($strFile)) {
				$strEncryptedData = file_get_contents($strFile);
				return $this->Decrypt($strEncryptedData);
			} else
				throw new QCallerException('File does not exist: ' . $strFile);
		}

		public function __destruct() {
			if ($this->objMcryptModule) {
				// Ignore All Warnings
				@mcrypt_module_close($this->objMcryptModule);
			}
		}
	}
?>