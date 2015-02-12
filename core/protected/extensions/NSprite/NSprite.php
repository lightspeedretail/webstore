<?php

/**
 * NSprite class file.
 *
 * @author Steven OBrien <steven.obrien@newicon.net>
 * @link http://www.newicon.net/
 * @copyright Copyright &copy; 2008-2011 Newicon Ltd
 * @license http://www.yiiframework.com/license/
 */

/**
 * generate a sprite from the icon set
 * Principles of operation: (or... how to use it)
 * So you have lots of icons and images floating around.
 * Goals of this class.
 * - I specify which out of a bunch of icons I use in my application
 * - This class generates a nice sprite.png file with all the images together
 * - This class generates a nice sprite.css file with all the necessary classes
 *   following the convention: .icon .name-of-icon
 *   some notes on naming. All underscores in image names are converted to "-"
 *   for the css classes, (can't stand "_" in css class names, is this just me?)
 *   the extension is removed. if the file is in a folder hierarchy
 *   then this is reflected in the naming, for example .icon .folder-icon-name
 * - it then, like a true gentleman, publishes them for me, using the yii asset manager
 * - if you ad more images simple delete the asset folder and next page refresh
 *   a new sprite will spawn into existence
 * - Bob is now your uncle.
 *
 * @property $cssParentClass
 * @property $sprites populated automatically if empty
 * @property array $imageFolderPath an array of filepaths to find images to be added to the sprite
 * @author Steven OBrien <steven.obrien@newicon.net>
 * @package nii
 */

/**
 * Class NSprite
 * Changes made for webstore:
 * Added support for retina images. The extension can now output two sprites: one for retina displays
 * and one for normal displays. Also added a media query in the sprite.css that targets retina displays
 * two sprite images but a single CSS file
 */
Class NSprite extends CApplicationComponent
{
	/**
	 * This defines the parent class to use on all elements that use the icon sprite
	 * for example defining an icon to appear on an element you would write the
	 * following: class="icon name-of-icon"
	 *
	 * @property string
	 */
	public $cssSpriteClass = 'sprite';

	/**
	 * class name of convenient icon class,
	 * easily display an icon inline that is size 16x16
	 */
	public $cssIconClass = 'icon';

	/**
	 * array of image paths relative to the NSprite::$imageFolderPath to include in the sprite, without a preceding slash
	 * this is automatically populated if empty, by NSprite::findFiles()
	 * @property array
	 */
	public $sprites = array();

	/**
	 * array of image paths relative to the NSprite::$imageFolderPath to include in the sprite, without a preceding slash
	 * this is automatically populated if empty, by NSprite::findFiles()
	 * @property array
	 */
	public $retinaSprites = array();

	/**
	 * Array of folder paths where images (to be added to the sprite) are stored.
	 * @property mixed
	 */
	private $_imageFolderPath = array();

	/**
	 * Array of folder paths where images (to be added to the sprite) are stored.
	 * @property mixed
	 */
	private $_retinaImageFolderPath = array();

	private $_spritePath;

	/**
	 * get the filepath to the components asset folder
	 *
	 * @return string
	 */
	public function getAssetFolder()
	{
		if (isset($this->_spritePath) === false)
		{
			$this->_spritePath = Yii::app()->getRuntimePath() . '/' . 'NSprite';
		}

		if (is_dir($this->_spritePath) === false)
		{
			mkdir($this->_spritePath, 0775, true);
		}

		return $this->_spritePath;
	}

	/**
	 * get the url path to the sprite.css file
	 *
	 * @return string
	 */
	public function getSpriteCssFile()
	{
		return $this->getAssetsUrl() . '/sprite.css';
	}

	/**
	 * gets the url to the components published assets folder
	 * if the assets folder does not exist it wil re generate the sprite
	 * and publish the assets folder
	 *
	 * @return string
	 */
	public function getAssetsUrl()
	{
		// check if we need to generate the sprite
		// if the asset folder exists we will assume we do not
		// want to regenerate the sprite
		if (!file_exists($this->getPublishedAssetsPath() . '/sprite.png')) {
			$this->generate();
		}
		return Yii::app()->getAssetManager()->publish($this->getAssetFolder());
	}

	/**
	 * uses CClientScript to register the sprite css script
	 */
	public function registerSpriteCss()
	{
		Yii::app()->clientScript->registerCssFile($this->getAssetsUrl() . '/sprite.css');
	}

	/**
	 * returns the file path to the published asset folder
	 *
	 * @return string the published asset folder file path
	 */
	public function getPublishedAssetsPath()
	{
		$a = Yii::app()->getAssetManager();
		$a->publish($this->getAssetFolder());
		return $a->getPublishedPath($this->getAssetFolder());
	}

	/**
	 * Generates the sprite.png and sprite.css files and publishes
	 * them to the appropriate published assets folder
	 *
	 * @return void
	 */
	public function generate()
	{

		// Normal images
		if (empty($this->sprites))
		{
			$this->sprites = $this->findFiles($this->imageFolderPath);
		}

		$images = $this->_generateImageData($this->sprites);
		$this->_generateImage($images, "");

		// Retina images
		if (empty($this->retinaSprites))
		{
			$this->retinaSprites = $this->findFiles($this->retinaImageFolderPath);
		}

		$retinaImages = $this->_generateImageData($this->retinaSprites);
		$this->_generateImage($retinaImages, "retina-");

		// Generate CSS
		$this->_generateCss($images);
	}

	/**
	 * Generates the sprite from all the items in the NSprite::image array
	 * and publishes the sprite to the published asset folder.
	 * @param array $images array of images to generate
	 * @param string $spritePrefix is the prefix to prepend to the sprite image file name
	 *
	 * @return array
	 */
	private function _generateImage($images, $spritePrefix)
	{
		$total = $this -> _totalSize($images);
		if ($total['width'] > 0 && $total['height'] > 0)
		{
			$sprite = imagecreatetruecolor($total['width'], $total['height']);
			imagesavealpha($sprite, true);
			$transparent = imagecolorallocatealpha($sprite, 0, 0, 0, 127);
			imagefill($sprite, 0, 0, $transparent);
			$top = 0;
			foreach ($images as $image) {
				$img = imagecreatefrompng($image['path']);
				imagecopy($sprite, $img, ($total['width'] - $image['width']), $top, 0, 0, $image['width'], $image['height']);
				$top += $image['height'];
			}
			$fp = $this->getPublishedAssetsPath() . DIRECTORY_SEPARATOR . $spritePrefix . 'sprite.png';
			imagepng($sprite, $fp);
			ImageDestroy($sprite);
		}
	}

	/**
	 * generates css code for all the items in the $images array
	 * and publishes the sprite.css file into the published assets folder
	 * @param array $images array of images to generate
	 *
	 * @return void
	 */
	private function _generateCss($images)
	{
		$total = $this->_totalSize($images);
		$top = $total['height'];
		$css = '.' . $this->cssSpriteClass . '{background-image:url(sprite.png);}' . "\n";
		// for 16x16 icons
		$css .= '.' . $this->cssIconClass . '{display:inline;overflow:hidden;padding-left:18px;background-repeat:no-repeat;background-image:url(sprite.png);}' . "\n";

		foreach ($images as $image) {
			$css .= '.' . $image['name'] . '{';
			$css .= 'background-position:' . ($image['width'] - $total['width']) . 'px ' . ($top - $total['height']) . 'px;';
			$css .= 'width:' . $image['width'] . 'px;';
			$css .= 'height:' . $image['height'] . 'px;';
			$css .= '}' . "\n";
			$top -= $image['height'];
		}

		// Retina stuff
		$css .= "@media only screen and (-webkit-min-device-pixel-ratio: 1.5), only screen ad (min--moz-device-pixel-ratio: 1.5), only screen and (min-resolution: 240dpi) { .sprite { background-image:url(retina-sprite.png);  background-size: " . $total['width'] . "px " . $total['height'] . "px;  } }";

		$fp = $this->getPublishedAssetsPath() . DIRECTORY_SEPARATOR . 'sprite.css';
		file_put_contents($fp, $css);
	}

	/**
	 * Calculate the total size of the sprite image
	 * @param array $images array of images to generate
	 * @return array
	 */
	private function _totalSize($images)
	{
		$arr = array('width' => 0, 'height' => 0);
		foreach ($images as $image) {
			if ($arr['width'] < $image['width'])
				$arr['width'] = $image['width'];
			$arr['height'] += $image['height'];
		}
		return $arr;
	}

	/**
	 * create an array with specific individual image information in
	 * populates @see $images
	 * @param array $sprites array of image paths relative to the NSprite::$imageFolderPath
	 * @throws CException if the image file's path '$imgPath' does not exist.
	 * @throws CException if the image is not the correct format
	 * @return array
	 */
	private function _generateImageData($sprites)
	{
		$images = array();
		foreach ($sprites as $i => $s) {
			$imgPath = $s['imageFolder'] . '/' . $s['path'];
			if (!file_exists($imgPath))
			{
				throw new CException("The image file's path '$imgPath' does not exist.");
			}
			$info = getimagesize($imgPath);
			if (!is_array($info))
			{
				throw new CException("The image '$imgPath' is not a correct image format.");
			}
			$images[$i]['path'] = $imgPath;
			$images[$i]['width'] = $info[0];
			$images[$i]['height'] = $info[1];
			$images[$i]['mime'] = $info['mime'];
			$type = explode('/', $info['mime']);
			$images[$i]['type'] = $type[1];
			// convert the relative path into the class name
			// replace slashes with dashes and remove extension from file name
			$p = pathinfo($imgPath);
			$name = str_replace(array('/', '\\', '_'), '-', $s['path']);
			$images[$i]['name'] = str_replace(array($p['extension'], '.'), '', $name);
		}

		return $images;
	}

	/**
	 * returns the string file path to the icons folder that holds the individual images
	 * that may be used to generate the sprite.
	 * @param array $imageFolderPath an array of image folder paths
	 *
	 * @return array
	 */
	public function getIconPath($imageFolderPath)
	{
		if (empty($imageFolderPath))
		{
			$imageFolderPath = array(
				dirname(__FILE__) . DIRECTORY_SEPARATOR . 'icons',
			);
		}
		return $imageFolderPath;
	}

	/**
	 * Finds all the image files within the $imageFolderPath
	 * and populates the sprites array
	 * @param array $imageFolderPath an array of image folder paths
	 * @throws CException if the folder path does not exist
	 *
	 * @return array
	 */
	public function findFiles($imageFolderPath)
	{
		$options = array('fileTypes' => array('png', 'gif', 'jpeg', 'jpg'));
		$sprites = array();
		// must be an array of folders
		foreach ($this->getIconPath($imageFolderPath) as $iFolder) {
			if (!is_dir($iFolder))
			{
				throw new CException("The folder path '$iFolder' does not exist.");
			}
			$files = CFileHelper::findFiles($iFolder, $options);
			foreach ($files as $p) {
				array_push($sprites, array(
					'imageFolder' => $iFolder,
					'path' => trim(str_replace(realpath($iFolder), '', realpath($p)), DIRECTORY_SEPARATOR)
				));
			}
		}
		return $sprites;
	}

	/**
	 * Get the array of imageFolderPaths
	 */
	public function getImageFolderPath()
	{
		return $this->_imageFolderPath;
	}

	/**
	 * Get the array of retinaImageFolderPaths
	 */
	public function getRetinaImageFolderPath()
	{
		return $this->_retinaImageFolderPath;
	}

	/**
	 * Set the imageFolderPath property
	 *
	 * @param array $arrayPaths an array of paths array('/file/path/1', 'file/path/2')
	 * @param boolean $merge
	 * @return void
	 */
	public function setImageFolderPath($arrayPaths, $merge = true)
	{
		if ($merge)
		{
			$this->_imageFolderPath = array_merge($this->_imageFolderPath, $arrayPaths);
		}
		else
		{
			$this->_imageFolderPath = $arrayPaths;
		}
	}

	/**
	 * Set the imageFolderPath property
	 * @param boolean $merge
	 * @param array $arrayPaths an array of paths array('/file/path/1', 'file/path/2')
	 * @return void
	 */
	public function setRetinaImageFolderPath($arrayPaths, $merge = true)
	{
		if ($merge)
		{
			$this->_retinaImageFolderPath = array_merge($this->_retinaImageFolderPath, $arrayPaths);
		}
		else
		{
			$this->_retinaImageFolderPath = $arrayPaths;
		}
	}

	/**
	 * Add one path to the list of image paths.
	 * @param string $path
	 */
	public function addImageFolderPath($path)
	{
		$this->_imageFolderPath[] = $path;
	}

	/**
	 * Add one path to the list of image paths.
	 * @param string $path
	 */
	public function addRetinaImageFolderPath($path)
	{
		$this->_retinaImageFolderPath[] = $path;
	}

}
