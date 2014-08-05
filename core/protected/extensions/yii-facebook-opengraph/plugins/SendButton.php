<?php
/**
 * SendButton class file.
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
 * The Like Box is a social plugin that enables Facebook Page owners to
 * attract and gain Likes from their own website.
 *
 * The Like Box enables users to:
 * <ul>
 * <li>See how many users already like this page, and which of their friends like it too
 * <li>Read recent posts from the page
 * <li>Like the page with one click, without needing to visit the page
 * </ul>
 *
 * @see http://developers.facebook.com/docs/reference/plugins/like
 */
class SendButton extends SPluginBase
{
	/**
	 * @var string The URL of the Facebook page for this Like Box.
	 */
	public $href;
  /**
	 * @var integer Width of the Send button, defaults to 51px
	 */
	public $width;
  /**
	 * @var integer Height of the Send button, defaults to 450px
	 */
	public $height;
  /**
	 * @var string The color scheme for the plugin. Options: 'light', 'dark'
	 */
	public $colorscheme;
  /**
	 * @var string If your web site or online service, or a portion of your service,
   * is directed to children under 13 you must enable this
	 */
	public $kid_directed_site;
  /**
	 * @var string a label for tracking referrals; must be less than 50 characters
   * and can contain alphanumeric characters and some punctuation (currently +/=-.:_).
   * The ref attribute causes two parameters to be added to the referrer URL when
   * a user clicks a link from a stream story about a Send action:
   * fb_ref and fb_source
	 */
	public $ref;

	public function run()
	{
		parent::run();
		$params = $this->getParams();
		$this->renderTag('send',$params);
	}
}
