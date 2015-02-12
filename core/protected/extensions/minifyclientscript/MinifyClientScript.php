<?php

/*
 * Copyright (c) 2012, "Klaas Sangers"<klaas@webkernel.nl>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
 * THE POSSIBILITY OF SUCH DAMAGE.
 */


/**
 * Changes made for webstore:
 * Disabled minification for already minified files to avoid bug in JSMin
 * Disabled minification when YII_DEBUG is true
 * Avoid creating empty js files which cause 404 errors
 * Replaced output folder from copper/protected/runtime to copper/runtime - there is no point in having 2 runtime folders
 * Replaced all DIRECTORY_SEPARATOR with '/' to avoid path concatenation bug
 *
 */

/**
 * MinifyClientScript class file.
 */

Yii::import('ext.minifyclientscript.wbkrnl.CssCompressor');
Yii::import('ext.minifyclientscript.jsmin.JSMin');

/**
 * MinifyClientScript manages JavaScript and CSS stylesheets for views.
 */
class MinifyClientScript extends CClientScript
{

	/**
	 * Path to the 'original' CSS
	 * @var string
	 */
	private $_relativeCssPath;

	/**
	 * Path to the minify runtime files
	 * @var string
	 */
	private $_minifyPath;

	/**
	 * The POS_* constants that this class will parse
	 * @var array
	 */
	private $_minifyJsPositions;

	/**
	 * Prepare the clientScript
	 */
	public function init() {
		$this->_minifyPath = Yii::app()->getRuntimePath() . '/' . 'MinifyClientScript';
		$this->_minifyJsPositions = array(
			self::POS_BEGIN,
			self::POS_END,
			self::POS_HEAD,
			self::POS_LOAD,
			self::POS_READY,
		);
		parent::init();
	}

	/**
	 * Callback method for preg_replace_callback in renderHead()
	 * @param array $matches
	 * @return array
	 */
	protected function cssReplaceCallback(array $matches) {
		if (substr($matches[1], 0, 1) == "/")
		{
			return $matches[0];
		}

		// Make sure we don't try to minify things coming from the internet or fonts that are in
		// url()
		elseif (strpos($matches[1], "http") === 0 || strpos($matches[1], "data:application") === 0)
		{
			return $matches[0];
		}

		else
		{
			return "url($this->_relativeCssPath/$matches[1])";
		}
	}

	/**
	 * Minify the css for optimal client-side performance
	 */
	protected function minifyCss() {
		$cssArray = array();
		// remember the original pcre backtrace limit, we're going to put it back to default
		$pcreBacktrackLimitOriginal = ini_get('pcre.backtrack_limit');

		// Get the hashed file names that will contain the CSS
		$hashedFileNamesByMedia = MinifyClientScript::getHashedFileNamesByMedia($this->cssFiles);

		// Checks if for a media type the CSS needs to be minified again.
		$compileCss = array();
		foreach ($hashedFileNamesByMedia as $media => $filename)
		{
			$mediaType = empty($media) ? 'default' : $media;
			$compileCss[$mediaType] = (
				(isset($compileCss[$mediaType]) && $compileCss[$mediaType]) ||
				!file_exists($this->_minifyPath . '/' . $hashedFileNamesByMedia[$mediaType])
			);
		}

		// append css files to the minified css store
		foreach ($this->cssFiles as $url => $media)
		{
			$cssPath = Yii::getPathOfAlias('webroot') . '/' . substr($url, strlen(Yii::app()->homeUrl));
			// skip if it's an external css
			if (strpos($url, "http") === 0 || strpos($url, "//") === 0)
			{
				continue;
			}

			// make sure $media is defined
			if (empty($media))
			{
				$media = 'default';
			}

			// check if the file can be read
			if (strpos($url, Yii::app()->homeUrl) === false)
			{
				continue;
			}

			if (is_readable(Yii::getPathOfAlias('webroot') . '/' . substr($url, strlen(Yii::app()->homeUrl))) === false)
			{
				continue;
			}
			else
			{
				unset($this->cssFiles[$url]);
			}

			if (isset($compileCss[$media]) && $compileCss[$media] === false)
			{
				continue;
			}

			// initialize the css per media if necessary
			if (!isset($cssArray[$media]))
			{
				$cssArray[$media] = "";
			}

			$this->_relativeCssPath = pathinfo($url, PATHINFO_DIRNAME);
			$css = trim(file_get_contents($cssPath));
			// don't parse this file if it's empty
			if (empty($css))
			{
				continue;
			}

			$cssLength = strlen($css);
			// when the css is bigger than the original pcre backtrace limit increase the limit
			if ($pcreBacktrackLimitOriginal < $cssLength)
			{
				ini_set('pcre.backtrack_limit', $cssLength);
			}

			// replace url()'s in the css
			if (preg_match("#url\s*\(\s*['\"]?([^'\"\)]+)['\"]?\)#", $css) > 0)
			{
				$css = preg_replace_callback("#url\s*\(\s*['\"]?([^'\"\)]+)['\"]?\)#", array($this, "cssReplaceCallback"), $css);
			}

			// decrease the pcre backtrace limit if required
			if ($pcreBacktrackLimitOriginal < $cssLength)
			{
				ini_set('pcre.backtrack_limit', $pcreBacktrackLimitOriginal);
			}

			// append the non-minified css
			$cssArray[$media] .= $css;
		}

		foreach ($hashedFileNamesByMedia as $media => $filename)
		{
			if ($compileCss[$media])
			{
				// Production environment
				$content = CssCompressor::deflate($cssArray[$media]);

				file_put_contents("$this->_minifyPath/$hashedFileNamesByMedia[$media]", $content, LOCK_EX);
			}

			$this->registerLinkTag(
				"stylesheet",
				"text/css",
				Yii::app()->assetManager->getPublishedUrl($this->_minifyPath, true) . "/$hashedFileNamesByMedia[$media]",
				$media == "default" ? null : $media
			);
		}
	}

	/**
	 * Minify the javascript files for optimal client-side performance
	 */
	protected function minifyScriptFileByPosition($position) {
		$jsCore = "";
		$js = "";
		$coreScripts = array();
		$filename = '';

		// generate filename for core scripts
		if ($position === self::POS_HEAD)
		{
			$filenameCore = "core-" . hash('sha256', serialize($this->coreScripts));
		}

		// generate filename for other scripts
		if (isset($this->scriptFiles[$position]))
		{
			$filename = serialize($this->scriptFiles[$position]);
		}

		$filename = hash('sha256', $filename);

		$publishedMinifyPath = Yii::app()->assetManager->getPublishedUrl($this->_minifyPath, true);

		// determine if the JS still needs to be 'compiled'
		$compileCoreJs = ($position === self::POS_HEAD && !file_exists($this->_minifyPath . '/' . "$filenameCore.js"));
		$compileJs = !file_exists($this->_minifyPath . '/' . $filename . '.js');

		if ($compileCoreJs)
		{
			foreach ($this->coreScripts as $coreScript)
			{
				foreach ($coreScript["js"] as $file)
				{
					$coreScripts[] = $coreScript["baseUrl"] . '/' . $file;
				}
			}
		}

		if (isset($this->scriptFiles[$position]))
		{
			foreach ($this->scriptFiles[$position] as $key => $scriptFile)
			{
				if (strpos($scriptFile, "http") === 0 || strpos($scriptFile, "//") === 0)
				{
					continue;
				}

				unset($this->scriptFiles[$position][$key]);

				if (!$compileJs && !$compileCoreJs)
				{
					continue;
				}

				// check if the file can be read
				if (!is_readable(Yii::getPathOfAlias('webroot') . '/' . substr($scriptFile, strlen(Yii::app()->homeUrl))))
				{
					continue;
				}

				// don't parse this file if it's empty
				$jsContent = trim(file_get_contents(Yii::getPathOfAlias('webroot') . '/' . substr($scriptFile, strlen(Yii::app()->homeUrl))));

				if (empty($jsContent))
				{
					continue;
				}

				// Don't minify if it's already minified. This is to prevent a bug in JSMin.
				if (strpos($scriptFile, 'min.js') === false &&
					strpos($scriptFile, 'jquery.history.js') === false)
				{
					$jsContent = JSMin::minify($jsContent);
				}

				// only 'compile' non-core JavaScript
				if ($compileCoreJs && in_array($scriptFile, $coreScripts))
				{
					$jsCore .= "$jsContent\n";
				}
				elseif ($compileJs)
				{
					$js .= $jsContent . "\n";
				}
			}

			if ($compileCoreJs)
			{
				file_put_contents($this->_minifyPath . '/' . "$filenameCore.js", $jsCore, LOCK_EX);
			}

			if ($compileJs && strlen($js) > 0)
			{
				file_put_contents($this->_minifyPath . '/' . "$filename.js", $js, LOCK_EX);
			}

			if ($position === self::POS_HEAD)
			{
				$this->registerScriptFile($publishedMinifyPath . "/$filenameCore.js", $position);
			}

			$this->registerScriptFile($publishedMinifyPath . "/$filename.js", $position);
		}

	}

	/**
	 * Given the css files to in the project, this method
	 * will generate a unique hash key filename for all the CSS
	 * contained in a specific media.
	 *
	 * @return String[] containing the file names for each media css
	 * type
	 */
	public static function getHashedFileNamesByMedia($cssFiles)
	{
		$hashedFileNamesByMedia = array();
		foreach ($cssFiles as $url => $media)
		{
			$mediaType = empty($media) ? 'default' : $media;

			// Skip if the file is coming from the web
			if (strpos($url, "http") === 0 || strpos($url, "//") === 0)
			{
				continue;
			}

			$cssPath = Yii::getPathOfAlias('webroot') . '/' . substr($url, strlen(Yii::app()->homeUrl));

			// Skip if the file doesn't exist or is not readable
			if(is_readable($cssPath) === false)
			{
				Yii::log("CSS file '" . $cssPath . "' is not readable.", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				continue;
			}

			if (isset($hashedFileNamesByMedia[$mediaType]) === false)
			{
				$hashedFileNamesByMedia[$mediaType] = md5_file($cssPath);
			}
			else
			{
				$hashedFileNamesByMedia[$mediaType] .= md5_file($cssPath);
			}
		}

		foreach ($hashedFileNamesByMedia as $media => $filename)
		{
			$mediaType = empty($media) ? 'default' : $media;
			$hashedFileNamesByMedia[$mediaType] = preg_replace("/[^a-zA-Z0-9]+/", "-", $media) . "-" . hash('md5', $hashedFileNamesByMedia[$mediaType]) . ".css";
		}

		return $hashedFileNamesByMedia;
	}

	/**
	 * Inserts the scripts in the head section.
	 * @param string $output the output to be inserted with scripts.
	 */
	public function renderHead(&$output) {
		// If YII_DEBUG is set we don't want to minify the assets
		if (YII_DEBUG === false)
		{
			if (is_dir($this->_minifyPath) === false)
			{
				mkdir($this->_minifyPath, 0775, true);
			}

			$this->minifyCss();

			if ($this->enableJavaScript)
			{
				foreach ($this->_minifyJsPositions as $position)
				{
					$this->minifyScriptFileByPosition($position);
				}
			}
			else
			{
				$this->scriptFiles = $this->scripts = null;
			}

			// We have to force the copy of the folder, since it might be possible
			// that another process copies some of the files in the folder first
			// and new ones will not be copied over the assets.
			$forceCopy = $this->_shouldForceCopy($this->_minifyPath);
			Yii::app()->assetManager->publish($this->_minifyPath, true, -1, $forceCopy);
		}

		parent::renderHead($output);
	}

	/**
	 *
	 * @param string $id
	 * @param string $css
	 * @param string $media
	 */
	public function registerCss($id, $css, $media = '') {
		if (YII_DEBUG === true)
		{
			$content = $css;
		}
		else
		{
			$content = CssCompressor::deflate($css);
		}

		return parent::registerCss($id, $content, $media);
	}

	/**
	 *
	 * @param string $id
	 * @param string $script
	 * @param int $position
	 */
	public function registerScript($id, $script, $position = self::POS_READY, array $htmlOptions = array()) {
		if (YII_DEBUG === true)
		{
			$content = $script;
		}
		else
		{
			$content = JSMin::minify($script);
		}

		return parent::registerScript($id, $content, $position);
	}

	/**
	 * It's possible that a force copy is required when all the assets
	 * were not copied at the same time. This method will check if the
	 * destination directory contains all the files from the runtime, if
	 * not force a copy of the files.
	 *
	 * @param $path Path of the minify runtime folder
	 * @return bool True if a force copy is needed. False otherwise.
	 */
	private function _shouldForceCopy($path)
	{
		$dir = new DirectoryIterator($path);
		$assetPath = Yii::app()->assetManager->getPublishedUrl($this->_minifyPath, true);
		foreach($dir as $dirContent)
		{
			if ($dirContent->isDot() === false)
			{
				if (file_exists(Yii::getPathOfAlias('webroot') . $assetPath . '/' .$dirContent->getFilename()) === false)
				{
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @param string $path
	 * @param string $media
	 * @param string $defaultAssetUrl
	 */
	public function registerCssFile($path, $media = '') {
		$parsedPath = $this->_parseAssetsPath($path);
		if ($parsedPath)
		{
			return parent::registerCssFile($this->_getFileUrl($parsedPath['assetsUrl'], $parsedPath['path']), (($media === null) ? '' : $media));
		}
		else
		{
			return parent::registerCssFile($path, $media);
		}
	}

	/**
	 * @param string $path
	 * @param int $position
	 * @param string $defaultAssetUrl
	 */
	public function registerScriptFile($path, $position = 0, array $htmlOptions = array()) {
		$parsedPath = $this->_parseAssetsPath($path);
		if ($parsedPath)
		{
			return parent::registerScriptFile($this->_getFileUrl($parsedPath['assetsUrl'], $parsedPath['path']), $position, $htmlOptions);
		}
		else
		{
			return parent::registerScriptFile($path, $position, $htmlOptions);
		}
	}

	/**
	 * @param string $path
	 * @return array
	 */
	private function _parseAssetsPath($path) {
		if (Yii::app()->theme !== null && strpos($path, Yii::app()->theme->baseUrl) !== false)
		{
			// it's in the themes
			$baseUrl = Yii::app()->theme->baseUrl;
		}
		elseif (strpos($path, Yii::app()->assetManager->baseUrl) !== false)
		{
			// it's in the assets
			$baseUrl = Yii::app()->assetManager->baseUrl;
		}
		else
		{
			// could not be parsed
			return false;
		}

		$truncatedPath = substr($path, strlen($baseUrl) + 1);
		$splitted = explode("/", $truncatedPath);
		return array(
			'assetsUrl' => $baseUrl . '/' . $splitted[0],
			'path' => substr($truncatedPath, strlen($splitted[0])),
		);
	}

	/**
	 * Check if the theme folder contains the same file,
	 * if so load that file instead of the default file
	 * @param string $defaultAssetUrl
	 * @param string $path
	 * @return string
	 */
	private function _getFileUrl($defaultAssetUrl, $path) {
		if (Yii::app()->theme !== null &&
			file_exists(Yii::app()->theme->getBasePath() . '/' . 'assets' . $path))
		{
			$assetsUrl = Yii::app()->getAssetManager()->publish(Yii::app()->theme->getBasePath() . '/' . 'assets');
			return $assetsUrl . $path;
		}

		return $defaultAssetUrl . $path;
	}
}
