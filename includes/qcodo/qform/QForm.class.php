<?php
	abstract class QForm extends QFormBase {
		///////////////////////////
		// Form Preferences
		///////////////////////////

		/**
		 * If you wish to encrypt the resulting formstate data to be put on the form (via
		 * QCryptography), please specify a key to use.  The default cipher and encrypt mode
		 * on QCryptography will be used, and because the resulting encrypted data will be
		 * sent via HTTP POST, it will be Base64 encoded.
		 *
		 * @var string EncryptionKey the key to use, or NULL if no encryption is required
		 */
		public static $EncryptionKey = null;

		/**
		 * The QFormStateHandler to use to handle the actual serialized form.  By default,
		 * QFormStateHandler will be used (which simply outputs the entire serialized
		 * form data stream to the form), but file- and session- based, or any custom db-
		 * based FormState handling can be used as well.
		 *
		 * @var string FormStateHandler the classname of the FormState handler to use
		 */
		public static $FormStateHandler = 'QFormStateHandler';

		/**
		 * These are the list of core QForm JavaScript files, or JavaScript files needed by
		 * a QControl, which QForm should IGNORE trying to load during a RenderBegin() or RenderAjax() call.
		 * 
		 * Feel free to add the filename of any JS files, relative to __JS_ASSETS__, that you want Qcodo
		 * to ignore because you have already explicilty placed them on the page using HTML <script>
		 * tags.  So for example, if you are using FooControl, where FooControl requires "foo.js" via its
		 * $strJavaScripts property, Qcodo will automagically load "foo.js" at run-time if and when it is
		 * needed.  But if you plan on explicitly placing
		 * 		<script type="text/javascript" src="/assets/js/foo.js"></script>
		 * on the HTML page, then you need to add "foo.js" to this IgnoreJavaScriptFileArray so that Qcodo
		 * doesn't try and also load "foo.js" for you. 
		 * 
		 * NOTE: In production or as a performance tweak, you may want to use the compressed "_qc_packed.js"
		 * library (which is a compressed, single file version of ALL the qcodo .js files that is in _core).
		 * 
		 * If you want to do this, MAKE SURE you FIRST MANUALLY do a <script> inclusion of
		 * "/assets/js/_core/_qc_packed.js" in your HTML.  Then, you can specify that QForm "ignore" all the
		 * other qcodo _core javascripts by adding just "_core" to the array.
		 *
		 * @var array
		 */
		protected $strIgnoreJavaScriptFileArray = array();
//		protected $strIgnoreJavaScriptFileArray = array('_core');

		/**
		 * This should be very rarely used.
		 * 
		 * This mechanism acts similarly to the strIgnoreJavascriptFileArray, except it applies to StyleSheets.
		 * However, any QControl that specifies a StyleSheet file to include is MEANT to have that property be modified / customized.
		 * 
		 * Therefore, there should be little to no need for this attribute.  However, it is here anyway, just in case.
		 *
		 * @var array
		 */
		protected $strIgnoreStyleSheetFileArray = array();
		// protected $strIgnoreStyleSheetFileArray = array('datagrid.css', 'calendar.css', 'textbox.css', 'listbox.css');
	}
?>