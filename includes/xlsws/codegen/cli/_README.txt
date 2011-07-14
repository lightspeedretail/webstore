This directory contains command-line-based tools for your Qcodo application.

Any cli-based tools or applications that you wish to have in the context
of your Qcodo application environment, including codegen, the qpm tools,
qcodo updater, and any custom ones that you build, should reside in the
"scripts/" subdirectory this cli directory and should be suffixed with
a ".cli.php" extension.

All Qcodo-based CLI tools can be called using one of the following CLI
runner wrappers:
* qcodo (for POSIX-based environments, including Mac OS X, Unix/Linux, Cygwin)
* qcodo.bat (for Windows-based environments)

For example, to execute codegen in your POSIX-based environment, you can run:
  /path/to/my/cli/qcodo codegen

To execute codegen in your Windows-based enviornment, you can run:
  c:\path\to\my\cli\qcodo.bat codegen

Note that you may need to alter your qcodo or qcodo.bat CLI runner wrapper
script to correctly reflect the path to your PHP CLI bin executable file.
For more information, refer to either of those files.
