
# scssphp-compass

*This is work in progress, expect to have problems!*

This is a library for adding [Compass][0] to your [scssphp][1] installation.

The project composed of a couple parts:

 * A script that checks out Compass and extracts the SCSS
 * A PHP class that hooks into a instance of `scssc` from scssphp. This script
   updates the import path and adds built in functions required my Compass

Compass' SCSS is checked into this repository, so you only need to run the
extract script if you are updating the version of Compass that is included.

## Installation

**scssphp-compass** is a Composer package. Add the following to your
`composer.json`:

    {
      "require": {
        "leafo/scssphp-compass": "dev-master"
      }
    }


## Usage

    <?php
    require "vendor/autoload.php";

    $scss = new scssc();
    new scss_compass($scss);

    echo $scss->compile('
      @import "compass";

      .shadow {
        @include box-shadow(10px 10px 8px red);
      }
    ');


 [0]: http://compass-style.org/
 [1]: http://leafo.net/scssphp/

