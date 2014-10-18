<?php

/**
 * Sass Handler
 *
 * Compiles SCSS file(s) on-the-fly
 * and publishes and/or registers output .css file
 *
 * @property ExtendedScssc $compiler
 * 
 * @author Artem Frolov <artem@frolov.net>
 * @link https://github.com/artem-frolov/yii-sass
 */
class SassHandler extends CApplicationComponent
{
    /**
     * Path for cache files. Will be used if Yii caching is not enabled.
     * Yii aliases can be used.
     * Defaults to 'application.runtime.sass-cache'
     * 
     * @var string
     */
    public $cachePath = 'application.runtime.sass-cache';
    
    /**
     * Path and filename of scss.inc.php
     * Defaults to relative location in Composer's vendor directory:
     * dirname(__FILE__) . "/../../leafo/scssphp/scss.inc.php" 
     * 
     * @var string
     */
    public $compilerPath;
    
    /**
     * Path and filename of compass.inc.php
     * Defaults to relative location in Composer's vendor directory:
     * dirname(__FILE__) . "/../../leafo/scssphp-compass/compass.inc.php" 
     * 
     * @var string
     */
    public $compassPath;
    
    /**
     * Enable Compass support.
     * Automatically add required import paths and functions.
     * Defaults to false
     * 
     * @var boolean
     */
    public $enableCompass = false;
    
    /**
     * Path to the directory with compiled CSS files.
     * Will be created automatically if doesn't exist.
     * Will be chmod'ed if it's not writable by script.
     * Yii aliases can be used.
     * Defaults to 'application.runtime.sass-compiled'
     * 
     * @var string
     */
    public $sassCompiledPath = 'application.runtime.sass-compiled';
    
    /**
     * Force compilation/recompilation on each request.
     * 
     * False value means that compilation will be done only if 
     * source SCSS file or related imported files have been
     * changed after previous compilation.
     * 
     * Defaults to false
     * 
     * @var boolean
     */
    public $forceCompilation = false;
    
    /**
     * Turn on/off overwriting of already compiled CSS files.
     * Will be ignored if $this->forceCompilation is true.
     * 
     * True value means that compiled CSS file will be overwriten
     * if the source SCSS file or related imported files have
     * been changed after previous compilation.
     * 
     * False value means that compilation will be done only if
     * output CSS file doesn't exist.
     * 
     * Defaults to true
     * 
     * @var boolean
     */
    public $allowOverwrite = true;
    
    /**
     * Automatically add directory containing SCSS file being processed
     * as an import path for the @import Sass directive.
     * Defaults to true
     * 
     * @var boolean
     */
    public $autoAddCurrentDirectoryAsImportPath = true;
    
    /**
     * List of import paths.
     * Can be strings or callable functions:
     * function($searchPath) {return $targetPath;}
     * Defaults to empty array
     * 
     * @var array
     */
    public $importPaths = array();
    
    /**
     * Chmod permissions used for creating/updating of writable
     * directories for cache files and compiled CSS files.
     * Mind the leading zero for octal values.
     * Defaults to 0777
     * 
     * @var integer
     */
    public $writableDirectoryPermissions = 0777;

    /**
     * Customize the formatting of the output CSS.
     * Possible values are 'simple', 'nested', 'compressed'
     * @see http://leafo.net/scssphp/docs/#output_formatting
     *
     * Default is 'nested'
     *
     * @var string
     */
    public $compilerOutputFormatting = self::OUTPUT_FORMATTING_NESTED;

    const OUTPUT_FORMATTING_NESTED = 'nested',
          OUTPUT_FORMATTING_COMPRESSED = 'compressed',
          OUTPUT_FORMATTING_SIMPLE = 'simple';

    /**
     * Compiler object
     * 
     * @var ExtendedScssc
     */
    protected $scssc;

    /**
     * Initialize component
     */
    public function init()
    {
        if (!$this->compilerPath) {
            $this->compilerPath = dirname(__FILE__) . '/../../leafo/scssphp/scss.inc.php';
        }

        if (!$this->compassPath) {
            $this->compassPath = dirname(__FILE__) . "/../../leafo/scssphp-compass/compass.inc.php";
        }
        
        parent::init();
    }
    
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
    public function register($sourcePath, $media = '', $insidePublishedDirectory = null, $subDirectory = null, $hashByName = false)
    {
    	$publishedPath = $this->publish($sourcePath, $insidePublishedDirectory, $subDirectory, $hashByName);
        Yii::app()->clientScript->registerCssFile($publishedPath, $media);
    }
    
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
    public function publish($sourcePath, $insidePublishedDirectory = null, $subDirectory = null, $hashByName = false)
    {
        $compiledFile = $this->getCompiledFile($sourcePath);
        
        if (empty($insidePublishedDirectory)) {
        	return Yii::app()->assetManager->publish($compiledFile);
        } else {
        	return $this->publishInside($compiledFile, $insidePublishedDirectory, $subDirectory, $hashByName);
        }
    }
    
    /**
     * Get path to the compiled CSS file, compile/recompile source file if needed
     * 
     * @param string $sourcePath Path to the source SCSS file
     * @throws CException
     * @return string
     */
    public function getCompiledFile($sourcePath)
    {
        $cssPath = $this->getCompiledCssFilePath($sourcePath);
        if ($this->isCompilationNeeded($sourcePath)) {
            $compiledCssCode = $this->compile($sourcePath);
            
            if (!file_put_contents($cssPath, $compiledCssCode, LOCK_EX)) {
                throw new CException('Can not write to the file: ' . $cssPath);
            }
            
            $this->saveParsedFilesInfoToCache($sourcePath);
        }
        return $cssPath;
    }

    /**
     * Compile SCSS file
     * 
     * @param string $sourcePath
     * @throws CException
     * @return string Compiled CSS code
     */
    public function compile($sourcePath)
    {
        if ($this->autoAddCurrentDirectoryAsImportPath) {
            $originalImportPaths = $this->compiler->getImportPaths();
            $this->compiler->addImportPath(dirname($sourcePath));
        }
        
        $sourceCode = file_get_contents($sourcePath);
        if ($sourceCode === false) {
            throw new CException('Can not read from the file: ' . $sourcePath);
        }
        
        $compiledCssCode = $this->compiler->compile($sourceCode);

        if ($this->autoAddCurrentDirectoryAsImportPath) {
            $this->compiler->setImportPaths($originalImportPaths);
        }
        
        return $compiledCssCode;
    }
    
    /**
     * Get compiler
     * Loads required files on initial request
     * 
     * @return ExtendedScssc
     */
    public function getCompiler()
    {
        if (!$this->scssc) {
            if (is_readable($this->compilerPath)) {
                require_once $this->compilerPath;
            }
            require_once dirname(__FILE__) . '/ExtendedScssc.php';
            $this->scssc = new ExtendedScssc();
            $this->setImportPaths($this->importPaths);
            if ($this->enableCompass) {
                if (is_readable($this->compassPath)) {
                    require_once $this->compassPath;
                }
                new scss_compass($this->scssc);
            }
            $this->setupOutputFormatting($this->scssc);
        }
        return $this->scssc;
    }
    
    /**
     * Publish compiled CSS file inside specific published directory
     * Helpful when CSS code has relative references to other
     * resources (images/fonts) and when these resources are also published
     * using Yii asset manager
     * 
     * @param string $compiledFile Path to the already compiled CSS file
     * @param string $insidePublishedDirectory Path to the directory with resource files
     * which is published somewhere in the application explicitly.
     * Default is null which means that CSS file will be published separately.
     * @param string $subDirectory Subdirectory for the CSS file within publicly available location. Default is null
     * @param boolean $hashByName Must be the same as in the CAssetManager::publish() call
     * for $insidePublishedDirectory. See CAssetManager::publish() for details. Default is false.
     * @throws CException
     * @return string URL of the published CSS file
     */
    protected function publishInside($compiledFile, $insidePublishedDirectory = null, $subDirectory = null, $hashByName = false)
    {
    	$insidePublishedDirectory = trim($insidePublishedDirectory, '/\\');
    	$insidePublishedDirectoryRealPath = Yii::getPathOfAlias($insidePublishedDirectory);
    	$targetPath = Yii::app()->assetManager->getPublishedPath($insidePublishedDirectoryRealPath) . DIRECTORY_SEPARATOR;
    	if (!$targetPath) {
    		throw new CException('Directory with alias "' . $insidePublishedDirectory . '" doesn\'t exist. ' .
    			'Path with converted aliases: "' . $insidePublishedDirectoryRealPath . '"');
    	}
    	
    	$subDirectoryUrlSection = '';
    	if (!empty($subDirectory)) {
    		$subDirectory = trim($subDirectory, '/\\');
    		$targetPath = $this->getWritableDirectoryPath($targetPath . $subDirectory);
    		$subDirectoryUrlSection = $subDirectory . '/';
    	}
    	
    	$basename = basename($compiledFile);
    	$targetFile = $targetPath . $basename;
    	if (!file_exists($targetFile) or filemtime($compiledFile) !== filemtime($targetFile)) {
	    	if (!copy($compiledFile, $targetFile)) {
	    		throw new CException('Can not copy "' . $compiledFile . '" to the "' . $targetPath . '" directory');
	    	}
    	}
    	
    	return Yii::app()->assetManager->getPublishedUrl($insidePublishedDirectoryRealPath) .
    		'/' . $subDirectoryUrlSection . $basename;
    }

    /**
     * Setup compiler output formatting
     * @param ExtendedScssc $compiler
     * @throws CException
     */
    protected function setupOutputFormatting($compiler)
    {
        $formatters = array(
            self::OUTPUT_FORMATTING_SIMPLE => 'scss_formatter',
            self::OUTPUT_FORMATTING_NESTED => 'scss_formatter_nested',
            self::OUTPUT_FORMATTING_COMPRESSED => 'scss_formatter_compressed',
        );
        if (isset($formatters[$this->compilerOutputFormatting])) {
            $compiler->setFormatter($formatters[$this->compilerOutputFormatting]);
        } else {
            throw new CException('Unknown output formatting: ' . $this->compilerOutputFormatting);
        }
    }
    
    /**
     * Set import paths for compiler.
     * Paths will be used for @import Sass method.
     * Each path can be a filesystem paths.
     * Or an Yii path with application aliases (like "application").
     * 
     * @param array|string $paths Single import path or list of paths
     */
    protected function setImportPaths($paths)
    {
        $paths = (array) $paths;
        $preparedPaths = array();
        
        foreach ($paths as $originalPath) {
            $preparedPath = YiiBase::getPathOfAlias($originalPath);
            if ($preparedPath !== false) {
                $preparedPaths[] = $preparedPath;
            } else {
                $preparedPaths[] = $originalPath;
            }
        }
        
        $this->scssc->setImportPaths($preparedPaths);
    }
    
    /**
     * Save list of parsed files with the time files were last modified to the cache
     * Must be called right after the compilation.
     * 
     * @param string $sourcePath Path to the source SCSS file
     */
    protected function saveParsedFilesInfoToCache($sourcePath)
    {
        $parsedFiles = $this->compiler->getParsedFiles();
        $parsedFiles[] = $sourcePath;
        foreach ($parsedFiles as $file) {
            $parsedFilesWithTime[$file] = filemtime($file);
        }
        
        $info = array(
            'compiledFiles' => $parsedFilesWithTime,
            'autoAddCurrentDirectoryAsImportPath' => $this->autoAddCurrentDirectoryAsImportPath,
            'enableCompass' => $this->enableCompass,
            'importPaths' => $this->compiler->getImportPaths(),
        	'compilerOutputFormatting' => $this->compilerOutputFormatting,
        );
        
        $this->cacheSet($this->getCacheCompiledPrefix() . $sourcePath, $info);
    }
    
    /**
     * Get path to the compiled CSS file
     * 
     * @param string $sourcePath Path to the source SCSS file
     * @return string
     */
    protected function getCompiledCssFilePath($sourcePath)
    {
        return $this->getWritableDirectoryPath($this->sassCompiledPath) . basename($sourcePath, '.scss') . '.css';
    }
    
    /**
     * Is source SCSS file needs to be compiled/recompiled
     * 
     * @param string $path Path to the source SCSS file
     * @return boolean
     */
    protected function isCompilationNeeded($path)
    {
        if ($this->forceCompilation) {
            return true;
        }
        
        if (!file_exists($this->getCompiledCssFilePath($path))) {
            return true;
        }
        
        if (!$this->allowOverwrite) {
            return false;
        }
        
        if ($this->isLastCompilationEnvironmentChanged($path)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Is last compilation environment changed for specified SCSS file.
     * Check component's settings and modification time of imported files.
     * 
     * @param string $path Path to the source SCSS file 
     * @return boolean
     */
    protected function isLastCompilationEnvironmentChanged($path)
    {
        $compiledInfo = $this->cacheGet($this->getCacheCompiledPrefix() . $path);
        
        $fieldsToCheckForChangedValue = array(
            'autoAddCurrentDirectoryAsImportPath',
            'enableCompass',
            'compilerOutputFormatting',
        );
        foreach ($fieldsToCheckForChangedValue as $field) {
	        if (!isset($compiledInfo[$field]) or
	            $compiledInfo[$field] !== $this->$field) {
	            return true;
	        }
        }
        
        if (!isset($compiledInfo['importPaths']) or
            $compiledInfo['importPaths'] !== $this->compiler->getImportPaths()) {
            return true;
        }
        
        if (empty($compiledInfo['compiledFiles']) or !is_array($compiledInfo['compiledFiles'])) {
            return true;
        }
        
        foreach ($compiledInfo['compiledFiles'] as $compiledFile => $previousModificationTime) {
            if (filemtime($compiledFile) != $previousModificationTime) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get prefix for cache entries
     * 
     * @return string
     */
    protected function getCacheCompiledPrefix()
    {
        return 'sass-compiled-';
    }

    /**
     * Get path of the writable directory
     * Create/chmod directory if needed
     * 
     * @param string $path
     * @throws CException
     * @return string
     */
    protected function getWritableDirectoryPath($path)
    {
        $parsedAlias = YiiBase::getPathOfAlias($path);
        if ($parsedAlias !== false) {
            $path = $parsedAlias;
        }
        if (!is_dir($path)) {
            if (!mkdir($path, $this->writableDirectoryPermissions, true)) {
                throw new CException('Can not create directory: ' . $path);
            }
        }
        if (!is_writable($path)) {
            if (!chmod($path, $this->writableDirectoryPermissions)) {
                throw new CException('Can not chmod(' . decoct($this->writableDirectoryPermissions) . ') directory: ' . $path);
            }
        }
        return rtrim($path, '/\\') . DIRECTORY_SEPARATOR;
    }

    /**
     * Set cache value.
     * Uses Yii cache if available.
     * Writes to the file otherwise.
     * 
     * @param string $name
     * @param mixed $value
     * @throws CException
     * @return boolean
     */
    protected function cacheSet($name, $value)
    {
        if (Yii::app()->cache) {
            return Yii::app()->cache->set($name, $value);
        }
        $path = $this->getCachePathForName($name);
        if (!file_put_contents($path, serialize($value), LOCK_EX)) {
            throw new CException('Can not write to the cache file: ' . $path);
        }
        return true;
    }

    /**
     * Get cache value.
     * Uses Yii cache if available.
     * Writes to the file otherwise.
     * 
     * @param string $name
     * @return mixed Cache value or false if entry is not found
     */
    protected function cacheGet($name)
    {
        if (Yii::app()->cache) {
            return Yii::app()->cache->get($name);
        }
        $path = $this->getCachePathForName($name);
        if (is_readable($path)) {
            return unserialize(file_get_contents($path));
        }
        return false;
    }
    
    /**
     * Get path for the cache entry
     * 
     * @param string $name
     * @return string
     */
    protected function getCachePathForName($name)
    {
        $maxFileLength = 255;
        $suffix = md5($name) . '.bin';
        $convertedName = basename($name);
        $convertedName = preg_replace('/[^A-Za-z0-9\_\.]+/', '-', $convertedName);
        $convertedName = trim($convertedName, '-');
        $convertedName = substr($convertedName, 0, $maxFileLength - strlen('-' . $suffix));
        $convertedName = strtolower($convertedName);
        if ($convertedName) {
            $convertedName .= '-';
        }
        
        return $this->getWritableDirectoryPath($this->cachePath) . $convertedName . $suffix;
    }
}
