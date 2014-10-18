Sass (SCSS) and Compass support for the Yii framework
========
yii-sass extension implements Sass and Compass support: compilation on-the-fly,
publishing, registration in views.

External Sass tools are NOT used, compilation is done using PHP compiler.

*Only SCSS syntax is supported.  
Indented syntax is not supported.*

**[Sass](http://sass-lang.com/guide "Syntactically Awesome StyleSheets - Guide")**
is an extension of CSS that adds power and elegance to the basic language.
It allows you to use variables, nested rules, mixins, inline imports, and more,
all with a fully CSS-compatible syntax. Sass helps keep large stylesheets
well-organized, and get small stylesheets up and running quickly.

**[Compass](http://compass-style.org/reference/compass/ "Compass framework reference")**
is an open-source CSS authoring framework which uses
the Sass stylesheet language to make writing stylesheets powerful and easy.

This extension caches compiled CSS code and prevents recompilation if there is no need in it.

Requirements
--------
* PHP >= 5.1
* Yii 1.0 or 1.1
* [scssphp compiler](http://leafo.net/scssphp/)
* [scssphp-compass library](https://github.com/leafo/scssphp-compass) -
*only if Compass support is needed*

Installation
--------
Installation can be done using
[Composer](http://getcomposer.org/doc/00-intro.md "Introduction to Composer")
or manually by downloading required files.

### Install using [Composer](http://getcomposer.org/doc/00-intro.md "Introduction to Composer")
1.  Allow installation of development versions of Composer packages  
    Required ["leafo/scssphp-compass" Composer package](https://packagist.org/packages/leafo/scssphp-compass "leafo/scssphp-compass Composer package on Packagist")
    doesn't have a stable version.  
    So you need to do one of the following:
    -   Add `"minimum-stability": "dev"` to your `composer.json` file
    -   **OR** add the following 2 lines to your `require` section of the `composer.json`:  
        `"leafo/scssphp": "dev-master@dev",`  
        `"leafo/scssphp-compass": "dev-master@dev"`

2.  Install **[artem-frolov/yii-sass](https://packagist.org/packages/artem-frolov/yii-sass "artem-frolov/yii-sass Composer package on Packagist.org")**
    package from Packagist.
    Required libraries will be installed automatically.  
    Execute in your application's directory:
    ```
    composer require artem-frolov/yii-sass "1.*"
    ```  
3.  Update your application's configuration *(e.g. protected/config/main.php)* like this:
    
    ```php
    'aliases' => array(
        ...
        // Path to your Composer's "vendor" directory
        // You can remove this if you use Composer's autoloader and Yii >= 1.1.15
        'vendor' => dirname(__FILE__) . '/../../../vendor',
    ),
    ...
    'components' => array(
        ...
        'sass' => array(
            // Path to the SassHandler class
            // You need the full path only if you don't use Composer's autoloader
            'class' => 'vendor.artem-frolov.yii-sass.SassHandler',
            
            // Use the following if you use Composer's autoloader and Yii >= 1.1.15
            //'class' => 'SassHandler',
            
            // Enable Compass support, defaults to false
            'enableCompass' => true,
        ),
        ...
    ),
    ```

### Install manually
1.  [Download yii-sass extension](https://github.com/artem-frolov/yii-sass/archive/master.zip "Download yii-sass extension from Github")  
    Put files to the "protected/extensions/yii-sass" directory so the path to the SassHandler.php
    will look like "protected/extensions/yii-sass/SassHandler.php"
2.  [Download scssphp compiler](https://github.com/leafo/scssphp/archive/master.zip "Download scssphp compiler from Github")  
    Put files to the "protected/vendor/scssphp" directory
3.  [Download scssphp-compass library](https://github.com/leafo/scssphp-compass/archive/master.zip "Download scssphp-compass library from Github")  
    *Skip this step if you don't need Compass support*  
    Put files to the "protected/vendor/scssphp-compass" directory
4.  Update your application's configuration *(e.g. protected/config/main.php)* like this:
    
    ```php
    'components' => array(
        ...
        'sass' => array(
            // Path to the SassHandler class
            'class' => 'ext.yii-sass.SassHandler',
            
            // Path and filename of scss.inc.php
            'compilerPath' => dirname(__FILE__) . '/../vendor/scssphp/scss.inc.php',
            
            // Path and filename of compass.inc.php
            // Required only if Compass support is required
            'compassPath' => dirname(__FILE__) . '/../vendor/scssphp-compass/compass.inc.php',
            
            // Enable Compass support, defaults to false
            'enableCompass' => true,
        ),
        ...
    ),
    ```

Usage
--------
Add the code like the following to your views/layout.  
It will compile SCSS file *(or recompile if needed)*, publish and register compiled CSS file: 
```php
Yii::app()->sass->register(Yii::getPathOfAlias('application.assets.sass') . '/your-file.scss');
```

Component options
--------
All options below are optional except the "class" item.
```php
'components' => array(
    ...
    'sass' => array(
        // Path to the SassHandler class
        'class' => 'vendor.artem-frolov.yii-sass.SassHandler',
        
        // Path and filename of scss.inc.php
        // Defaults to relative location in Composer's vendor directory
        'compilerPath' => dirname(__FILE__) . "/../../../vendor/leafo/scssphp/scss.inc.php",
        
        // Path and filename of compass.inc.php
        // Required only if Compass support is required
        // Defaults to relative location in Composer's vendor directory
        'compassPath' => dirname(__FILE__) . '/../../../vendor/leafo/scssphp-compass/compass.inc.php',

        // Path for cache files. Will be used if Yii caching is not enabled.
         // Yii aliases can be used.
         // Defaults to 'application.runtime.sass-cache'
        'cachePath' => 'application.runtime.sass-cache',
        
        // Enable Compass support.
        // Automatically add required import paths and functions.
        // Defaults to false
        'enableCompass' => false,
        
        // Path to the directory with compiled CSS files.
        // Will be created automatically if doesn't exist.
        // Will be chmod'ed if it's not writable by script.
        // Yii aliases can be used.
        // Defaults to 'application.runtime.sass-compiled'
        'sassCompiledPath' => 'application.runtime.sass-compiled',
        
        // Force compilation/recompilation on each request.
        // False value means that compilation will be done only if 
        // source SCSS file or related imported files have been
        // changed after previous compilation.
        // Defaults to false
        'forceCompilation' => false,
        
        // Turn on/off overwriting of already compiled CSS files.
        // Will be ignored if $this->forceCompilation is true.
        // True value means that compiled CSS file will be overwriten
        // if the source SCSS file or related imported files have
        // been changed after previous compilation.
        // False value means that compilation will be done only if
        // output CSS file doesn't exist.
        // Defaults to true
        'allowOverwrite' => true,
        
        // Automatically add directory containing SCSS file being processed
        // as an import path for the @import Sass directive.
        // Defaults to true
        'autoAddCurrentDirectoryAsImportPath' => true,
        
        // List of import paths.
        // Can be strings or callable functions:
        // function($searchPath) {return $targetPath;}
        // Defaults to empty array
        'importPaths' => array(),
        
        // Chmod permissions used for creating/updating of writable
        // directories for cache files and compiled CSS files.
        // Mind the leading zero for octal values.
        // Defaults to 0777
        'writableDirectoryPermissions' => 0777,
        
        // Customize the formatting of the output CSS.
        // Possible values are 'simple', 'nested', 'compressed'
        // @see http://leafo.net/scssphp/docs/#output_formatting
        // Default is 'nested'
        'compilerOutputFormatting' => 'nested',
    ),
    ...
),
```

Component methods
--------
```php
/**
 * Publish and register compiled CSS file.
 * Compile/recompile source SCSS file if needed.
 * 
 * Optionally can publish compiled CSS file inside specific published directory.
 * It's helpful when CSS code has relative references to other
 * resources (images/fonts) and when these resources are also published
 * using Yii asset manager. This method allows to publish compiled CSS files
 * along with other resources to make relative references work.
 * 
 * E.g.:
 * "image.jpg" is stored inside path alias "application.files.images"
 * Somewhere in the code the following is called during page generation:
 * Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.files'));
 * SCSS file has the following code: background-image: url(../images/image.jpg);
 * Then the correct call of the method will be:
 * Yii::app()->sass->register('path-to-scss-file.scss', '', 'application.files', 'css_compiled');
 * 
 * @param string $sourcePath Path to the source SCSS file
 * @param string $media Media that the CSS file should be applied to. If empty, it means all media types
 * @param string $insidePublishedDirectory Path to the directory with resource files
 * which is published somewhere in the application explicitly.
 * Default is null which means that CSS file will be published separately.
 * @param string $subDirectory Subdirectory for the CSS file within publicly available location. Default is null
 * @param boolean $hashByName Must be the same as in the CAssetManager::publish() call
 * for $insidePublishedDirectory. See CAssetManager::publish() for details. Default is false.
 */
Yii::app()->sass->register($sourcePath, $media = '', $insidePublishedDirectory = null, $subDirectory = null, $hashByName = false);


/**
 * Publish compiled CSS file.
 * Compile/recompile source SCSS file if needed.
 * 
 * Optionally can publish compiled CSS file inside specific published directory.
 * It's helpful when CSS code has relative references to other
 * resources (images/fonts) and when these resources are also published
 * using Yii asset manager. This method allows to publish compiled CSS files
 * along with other resources to make relative references work.
 * 
 * E.g.:
 * "image.jpg" is stored inside path alias "application.files.images"
 * Somewhere in the code the following is called during page generation:
 * Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.files'));
 * SCSS file has the following code: background-image: url(../images/image.jpg);
 * Then the correct call of the method will be:
 * Yii::app()->sass->publish('path-to-scss-file.scss', 'application.files', 'css_compiled');
 * 
 * @param string $sourcePath Path to the source SCSS file
 * @param string $insidePublishedDirectory Path to the directory with resource files
 * which is published somewhere in the application explicitly.
 * Default is null which means that CSS file will be published separately.
 * @param string $subDirectory Subdirectory for the CSS file within publicly available location. Default is null
 * @param boolean $hashByName Must be the same as in the CAssetManager::publish() call
 * for $insidePublishedDirectory. See CAssetManager::publish() for details. Default is false.
 * @return string URL of the published CSS file
 */
Yii::app()->sass->publish($sourcePath, $insidePublishedDirectory = null, $subDirectory = null, $hashByName = false);


/**
 * Get path to the compiled CSS file, compile/recompile source file if needed
 * 
 * @param string $sourcePath Path to the source SCSS file
 * @throws CException
 * @return string
 */
Yii::app()->sass->getCompiledFile($sourcePath);


/**
 * Compile SCSS file
 * 
 * @param string $sourcePath
 * @throws CException
 * @return string Compiled CSS code
 */
Yii::app()->sass->compile($sourcePath);


/**
 * Get compiler
 * Loads required files on initial request
 * 
 * @return ExtendedScssc
 */
Yii::app()->sass->getCompiler();
```

Changelog
--------
- **Version 1.1.0** — 2014-01-07
    - Allow to register and publish compiled CSS files inside specific published
      directory to allow using of relative references in CSS for images, fonts and
      other published files
    - Add `compilerOutputFormatting` component option, allow to switch between
      `simple`, `nested` and `compressed` *(Pavel Volyntsev)*
    - Change default value of the `writableDirectoryPermissions` component option
      from `0644` to `0777`. It should prevent permission errors on some Linux servers
    - Update code style and documentation
    

- **Version 1.0.0** — 2013-12-16
    - Initial release

Resources
--------
- [yii-sass extension page on the Yii framework website](http://www.yiiframework.com/extension/sass/)
- [Forum support topic for yii-sass extension](http://www.yiiframework.com/forum/index.php/topic/49937-extension-yii-sass-sass-scss-and-compass-support/)
- [yii-sass extension page on the Packagist](https://packagist.org/packages/artem-frolov/yii-sass)
- [scssphp compiler](http://leafo.net/scssphp/)