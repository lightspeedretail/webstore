Any cli-based tools or applications that you wish to have in the context
of your Qcodo application environment should reside in this
"scripts/" subdirectory this cli directory and should be suffixed with
a ".cli.php" extension.

Note that any custom scripts you build can actually reside in a
subdirectory of this "cli/scripts/" subdirectory as well.  Just note that
when you call on any of those scripts, be sure to specify the subdirectory.
So if you have a custom Qcodo-based .cli.php script residing in your
/path/to/my/cli/scripts/foo/bar.cli.php, you would call it by calling
"qcodo foo/bar".
