<?php

/**
 * This file contains the pre-init ClassFile / ClassPath mangling that may
 * occur. This is the last change before we actually start initializing the
 * Web Store code. 
 */

if (!empty(QApplication::$Database)) {
	if (!defined('__PREPEND_QUICKINIT__')) {

		// Ensure we load the database session storage object if needed
		if (_xls_get_conf('SESSION_HANDLER') == 'DB') { 
			QApplication::$ClassFile['xlssessionhandler'] =
				__XLSWS_INCLUDES__ .
				'/core/session/XLSDBSessionHandler.class.php';
			XLSSessionHandler::$CollectionOverridePhp = true;
		}
	}
}
