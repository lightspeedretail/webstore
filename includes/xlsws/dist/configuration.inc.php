<?php
// STND

    define ('SERVER_INSTANCE', 'dev');

    switch (SERVER_INSTANCE) {
        case 'dev':
            break;

        case 'test':
            break;

        case 'prod':
            define ('ALLOW_REMOTE_ADMIN', false);

            define ('__DOCROOT__', /*INSERT_DOCROOT*/);
            define ('__VIRTUAL_DIRECTORY__', /*INSERT_VIRTUAL*/);
            define ('__SUBDIRECTORY__', /*INSERT_SUBDIR*/);

            define('DB_CONNECTION_1', serialize(array(
                'adapter' => 'MySqli5',
                'server' => /*INSERT_SERVER*/,
                'port' => /*INSERT_PORT*/,
                'database' => /*INSERT_DATABASE*/,
                'username' => /*INSERT_USER*/,
                'password' => /*INSERT_PWORD*/,
                'encoding' => 'utf8',
                'profiling' => false)));
            break;

        case 'stage':
            break;
    }

    // __DOCROOT__ was not defined so we need to launch the installer
    if (!defined('__DOCROOT__')) {
        header("Location: install.php");
    }

    define ('__URL_REWRITE__', 'apache');

    include(dirname(__FILE__) . '/configurationpaths.inc.php');

?>
