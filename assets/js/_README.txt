The various assets directory are for assets and helpers for the various
QControls, including images, javascript files and popups.

Of course, these files can technically be anywhere in the docroot,
but the current directory location of /assets/* is meant to serve
as a centrally-available assets location for these QControl helpers.

If you want to move them (either individually or entirely),
be sure to update your configuration.inc.php to reflect the new
location(s) of the assets.

In short, feel free to add/modify as you wish.

And also, any additional QControl classes that you create or download which
may have their own assets should have their assets installed in one of these
subdirectories.  And any additional other js, css, etc. assets for your
application could be placed here, as well.

Finally, note that within EACH asset type (e.g. css, images, js and php),
files in the _core subdirectory are intended to be part of Qcodo Core,
and rules concerning the upgrading/modification for these files follows
the same rules for Qcodo Core files everywhere else.
