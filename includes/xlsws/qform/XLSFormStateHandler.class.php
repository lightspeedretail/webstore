<?php
 /*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/**
 * This will store the formstate in a pre-specified directory on the file
 * system. This offers significant speed advantage over PHP SESSION
 * because EACH form state is saved in its own file, and only the form
 * state that is needed for loading will be accessed (as opposed to with
 * session, ALL the form states are loaded into memory every time).
 */
class XLSFormStateHandler extends QBaseClass {
	/**
	 * The PATH where the FormState files should be saved
	 *
	 * @var string StatePath
	 */
	public static $StatePath = '/tmp';

	/**
	 * The filename prefix to be used by all FormState files
	 *
	 * @var string FileNamePrefix
	 */
	public static $FileNamePrefix = 'state_';

	/**
	 * Maximum duration to keep FormState files
	 */
	public static $GarbageCollectionMaxSeconds = 86400;

	/**
	 * If PHP SESSION is enabled, then this method will delete all
	 * formstate files specifically for this SESSION user (and no one else)
	 * This can be used in lieu of or in addition to the standard
	 * interval-based garbage collection mechanism.
	 *
	 * When using XLSSessionHandler, this is automatically tied into
	 * the session handler.
	 */
	public static function Destroy($strName) {
		$strSessionId = session_id();
		$strPrefix = self::$FileNamePrefix . $strSessionId;

		// Go through all the files
		if (strlen($strSessionId)) {
			$objDirectory = dir(self::$StatePath);
			while (($strFile = $objDirectory->read()) !== false) {
				$intPosition = strpos($strFile, $strPrefix);
				if (($intPosition !== false) && ($intPosition == 0))
					unlink(sprintf('%s/%s', self::$StatePath, $strFile));
			}
		}
	}

	public static function GarbageCollect() {
		// Go through all the files
		$objDirectory = dir(self::$StatePath);
		while (($strFile = $objDirectory->read()) !== false) {
			$intPosition = strpos($strFile, self::$FileNamePrefix);

			if (($intPosition !== false) && ($intPosition == 0)) {
				$strFile = sprintf('%s/%s', self::$StatePath, $strFile);
				$intTimeInterval =
					time() - self::$GarbageCollectionMaxSeconds;
				$intModifiedTime = filemtime($strFile);

				if ($intModifiedTime < $intTimeInterval)
					unlink($strFile);
			}
		}
	}

	public static function Save($strFormState, $blnBackButtonFlag) {
		// Compress (if available)
		if (function_exists('gzcompress'))
			$strFormState = gzcompress($strFormState, 9);

		// Figure Out Session Id (if applicable)
		$strSessionId = session_id();

		// Calculate a new unique Page Id
		$strPageId = md5(microtime());

		// Figure Out FilePath
		$strFilePath = sprintf('%s/%s%s_%s',
			self::$StatePath,
			self::$FileNamePrefix,
			$strSessionId,
			$strPageId);

		// Save THIS formstate to the file system
		// NOTE: if gzcompress is used, we are saving the *BINARY* data stream of the compressed formstate
		// In theory, this SHOULD work.  But if there is a webserver/os/php version that doesn't like
		// binary session streams, you can first base64_encode before saving to session (see note below).
		file_put_contents($strFilePath, $strFormState);

		// Return the Page Id
		// Because of the MD5-random nature of the Page ID, there is no need/reason to encrypt it
		return $strPageId;
	}

	public static function Load($strPostDataState) {
		// Pull Out strPageId
		$strPageId = $strPostDataState;

		// Figure Out Session Id (if applicable)
		$strSessionId = session_id();

		// Figure Out FilePath
		$strFilePath = sprintf('%s/%s%s_%s',
			self::$StatePath,
			self::$FileNamePrefix,
			$strSessionId,
			$strPageId);

		if (file_exists($strFilePath)) {
			// Pull FormState from file system
			// NOTE: if gzcompress is used, we are restoring the *BINARY* data stream of the compressed formstate
			// In theory, this SHOULD work.  But if there is a webserver/os/php version that doesn't like
			// binary session streams, you can first base64_decode before restoring from session (see note above).
			$strSerializedForm = file_get_contents($strFilePath);

			// Uncompress (if available)
			if (function_exists('gzcompress'))
				$strSerializedForm = gzuncompress($strSerializedForm);

			return $strSerializedForm;
		} else
			return null;
	}
}
