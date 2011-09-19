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
	public static $FileNamePrefix = 'state';

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
        $strPrefix = self::$FileNamePrefix . '.' . session_id();
        $strStateDir = self::$StatePath;
        $objStateDir = dir($strStateDir);

        while (($strHashDir = $objDirectory->read()) !== false) {
            if (($strHashDir == '.') || ($strHashDir == '..')) continue;

            $strHashDir = $strStateDir . '/' . $strHashDir;
            $objHashDir = dir($strHashDir);

            while (($strFile = $objHashDir->read()) !== false) {
                $intPosition = strpos($strFile, $strPrefix);
                if ($intPosition === false) continue;

                $strFile = $strHashDir . '/' . $strFile;
                unlink($strFile);
            }
		}
	}

    /**
     * Iterate through all subfolders and all state files to delete 
     * those which have expired. 
     */
    public static function GarbageCollect() {
        $strStateDir = self::$StatePath;
        $objStateDir = dir($strStateDir);

        $intLifeTime = time() - XLSSessionHandler::GetSessionLifetime();

        while (($strHashDir = $objStateDir->read()) !== false) { 
            if (($strHashDir == '.') || ($strHashDir == '..')) continue;

            $intHashTime = mktime(
                substr($strHashDir,8,2),
                substr($strHashDir,10,2),
                0,
                substr($strHashDir,4,2),
                substr($strHashDir,6,2),
                substr($strHashDir,0,4)
            );

            if ($intHashTime > $intLifeTime)
                continue;

            $strHashDir = $strStateDir . '/' . $strHashDir;
            $objHashDir = dir($strHashDir);

            while (($strFile = $objHashDir->read()) !== false) {
                $intPosition = strpos($strFile, self::$FileNamePrefix);
                if ($intPosition === false) continue;

                $strFile = $strHashDir . '/' . $strFile;

                $intModifiedTime = filemtime($strFile);
                if ($intModifiedTime < $intLifeTime)
                    unlink($strFile);
            }

            if (count(scandir($strHashDir)) == 2)
                rmdir($strHashDir);
        }
	}

	public static function Save($strFormState, $blnBackButtonFlag) {
		// Compress (if available)
		if (function_exists('gzcompress'))
			$strFormState = gzcompress($strFormState, 9);

        $strDate = date('YmdHi');
        $strStateId = md5(microtime());

        $strFilePath = sprintf('%s/%s/%s.%s.%s',
            self::$StatePath, 
            $strDate, 
            self::$FileNamePrefix,
            session_id(),
            $strStateId
        );

        $strPageId = $strDate . '/' . $strStateId;

        // Get the State file path
        $strDirPath = dirname($strFilePath);

        if (!is_dir($strDirPath))
            if (!mkdir($strDirPath, 0777, true)) {
                QApplication::Log(E_USER_ERROR, 'core', 
                    'Failed to create the state path : ' . $strDirPath);
                return false;
            }

        // Save the Form State
        file_put_contents($strFilePath, $strFormState);

		// Return the Page Id
		return $strPageId;
	}

	public static function Load($strPostDataState) {
		// Pull Out strPageId
        $strPageId = $strPostDataState;
        $strDate = dirname($strPageId);
        $strStateId = basename($strPageId);

        $strFilePath = sprintf('%s/%s/%s.%s.%s',
            self::$StatePath, 
            $strDate, 
            self::$FileNamePrefix,
            session_id(),
            $strStateId
        );

		if (file_exists($strFilePath)) {
			$strSerializedForm = file_get_contents($strFilePath);

			// Uncompress (if available)
			if (function_exists('gzcompress'))
				$strSerializedForm = gzuncompress($strSerializedForm);

			return $strSerializedForm;
        }
        else return null;
	}
}
