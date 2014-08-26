<?php
/**
 * FollowButton class file.
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
 * The Follow button lets people subscribe to the public updates of others on Facebook.
 *
 * @see https://developers.facebook.com/docs/plugins/follow-button/
 */
class FollowButton extends SPluginBase
{
  /**
	 * @var string URL of Facebook Profile to follow
	 */
	public $href;
  /**
	 * @var string the layout of the button: standard, box_count, button_count, button
   * Default: standard
	 */
	public $layout;
  /**
	 * @var boolean Display profile photos below the button (standard layout only).
	 */
	public $show_faces;
	/**
	 * @var string The color scheme for the plugin. Options: 'light', 'dark'
	 */
	public $colorscheme;
	/**
	 * @var integer Width of the Share button, defaults to 450px
	 */
	public $width;
	/**
	 * @var integer Height of the Share button, defaults to 61px
	 */
	public $height;

	public function run()
	{
		parent::run();
		$params = $this->getParams();
        $this->renderTag('follow',$params);
	}

}
