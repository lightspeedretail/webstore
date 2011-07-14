<?php
	class QImageControl extends QImageControlBase {
		// If you wish to set a cache for the generated images so that they
		// are not dynamically recreated every time, specify a default CacheFolder here.
		//
		// The Cache Folder is an absolute folder location relative to the root of the
		// qcodo application.  So for example, if you have the qcodo application installed
		// at /var/web/wwwroot/my_application, and if docroot is "/var/web/wwwroot" and if
		// you therefore have a subfolder defined as "/my_application", then if you specify
		// a CacheFolder of "/text_images", the following will happen:
		// * Cached images will be stored at /var/web/wwwroot/my_application/text_images/...
		// * Cached images will be accessed by <img src="/my_application/text_images/...">
		//
		// Remember: CacheFolder *must* have a leading "/" and no trailing "/", and also
		// be sure that the webserver process has WRITE access to the CacheFolder, itself.
		protected $strCacheFolder = null;
	}
?>