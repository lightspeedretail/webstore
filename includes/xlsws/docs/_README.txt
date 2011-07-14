This is the central location for all include files.  Feel free to include
any new classes or include files in this directory.



**** configuration.inc.php ****

This conatins server-level configuration information (e.g. database connection
information, docroot paths (including subfoldering and virtual directories),
etc.  You must make modifications to this file to have it reflect the
configuration of your system.

See the inline documentation in qcodo/_core/configuration.inc.php-full for more information.



**** prepend.inc.php ****

This is the top-level include file for any and all PHP scripts which use
Qcodo.  Global, application-wide loaders, settings, etc. are in this file.

Feel free to make modifications/changes/additions to this file as you wish.
Note that the QApplication class is defined in prepend.inc as well.  Feel free
to make changes, override methods, or add functionality to QApplication as
needed.

See the inline documentation in prepend.inc.php for more information.



**** qcodo/ ****

The qcodo/ subdirectory contains the codebase for the qcodo framework, itself.

	** CodeGen, QForm and QControl Customizations

	If you want to make *customizations* to parts of the Qcodo framework, you can
	make customizations in the following places inside of qcodo/:

	* CodeGen customizations, as well as CodeGen templates/subtemplates changes,
	  can be made in the qcodo/codegen/ subdirectory
	* QForm and any QControl customizations can be made in the qcodo/qform/ subdirectory
	* New or other downloaded QControls that are not put in core can be put into
	  the qcodo/qform/ directory

	** Qcodo Core

	The qcodo/_core/ directory contains the "core" code that is not meant to be
	modified by most end users, excpet in cases where you are adding non-standard
	functionality or making bug fixes, etc.

	If you are making changes to files in qcodo/_core/ to fix core bugs/issues/errors,
	or to add functionality in Qcodo that does not exist, you are encouraged to post
	your changes to the Qcodo Forums (http://www.qcodo.com/forums/) so that those
	changes/fixes can hopefully be integrated into the Qcodo core.



**** data_classes/, data_classes/generated/, formbase_classes_generated/, panelbase_classes/generated ****

These directories are created when you code generate, and contain the code
generated classes.  Note that the files in any directory named "generated"
will ALWAYS be overwritten by the code generator.

HOWEVER, the files in data_classes., itself, will NEVER be overwritten.
Therefore, you should FEEL FREE to make ANY CUSTOMIZATIONS to your data 
classes in the data_classes directory.

You can see the "Customized Business Logic" example in Section 2 on the
Examples Site for more information.





**** MISC

If you wish to run Qcodo without any QForm interactions, simply comment out the following lines in qcodo.inc.php:

	QApplicationBase::$PreloadedClassFile['_enumerations'] = __QCODO_CORE__ . '/qform/_enumerations.inc.php';
	QApplicationBase::$PreloadedClassFile['QControlBase'] = __QCODO_CORE__ . '/qform/QControlBase.class.php';
	QApplicationBase::$PreloadedClassFile['QControl'] = __QCODO__ . '/qform/QControl.class.php';
	QApplicationBase::$PreloadedClassFile['QFormBase'] = __QCODO_CORE__ . '/qform/QFormBase.class.php';
	QApplicationBase::$PreloadedClassFile['QForm'] = __QCODO__ . '/qform/QForm.class.php';
	QApplicationBase::$PreloadedClassFile['_actions'] = __QCODO_CORE__ . '/qform/_actions.inc.php';
	QApplicationBase::$PreloadedClassFile['_events'] = __QCODO_CORE__ . '/qform/_events.inc.php';

With those lines commented out, nothing QForm-related will ever get loaded into your application.