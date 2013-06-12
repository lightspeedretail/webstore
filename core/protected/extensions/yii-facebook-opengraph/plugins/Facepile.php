<?php
/**
 * Facepile class file.
 *
 * @author Evan Johnson <thaddeusmt - AT - gmail - DOT - com>
 * @author Ianaré Sévi (original author) www.digitick.net
 * @link https://github.com/splashlab/yii-facebook-opengraph
 * @copyright &copy; Digitick <www.digitick.net> 2011
 * @copyright Copyright &copy; 2012 SplashLab Social  http://splashlabsocial.com
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License v3.0
 *
 */

require_once 'SPluginBase.php';

/**
 * The Facepile plugin displays the Facebook profile pictures of users who
 * have liked your page or have signed up for your site.
 *
 * @see http://developers.facebook.com/docs/reference/plugins/facepile/
 */
class Facepile extends SPluginBase
{
	/**
	 * @var string The URL of the page.
	 *
	 * The plugin will display photos of users who have liked this page.
	 */
	public $href;
  /**
	 * @var string The plugin will display photos of users who have connected to your app via this action
	 */
	public $action;
	/**
	 * @var integer The maximum number of rows of faces to display.
	 *
	 * Height is dynamically sized; if you specify a maximum of four rows of
	 * faces, but there are only enough friends to fill two rows, the plugin
	 * will set its height for two rows of faces. Default: 1.
	 */
	public $max_rows;
	/**
	 * @var integer Width of the plugin in pixels. Default width: 200px.
	 */
	public $width;
  /**
	 * @var string size of the photos and social context. Default size: small.
	 */
	public $size;
  /**
	 * @var string the color scheme for the like button. Options: 'light', 'dark'.
	 */
	public $colorscheme;


	public function run()
	{
		parent::run();
		$params = $this->getParams();
		$this->renderTag('facepile',$params);
	}

}
