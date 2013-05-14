<?php
/**
 * LikeButton class file.
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
 * The Like button lets a user share your content with friends on Facebook.
 *
 * When the user clicks the Like button on your site, a story appears in the
 * user's friends' News Feed with a link back to your website.
 *
 * @see http://developers.facebook.com/docs/reference/plugins/like
 */

class LikeButton extends SPluginBase
{
	/**
	 * @var string The URL of the Facebook page for this Like button.
	 */
	public $href;
  /**
	 * @var boolean Specifies whether to include a Send button with the Like
	 * button.
	 */
	public $send;
  /**
	 * @var string Three options : 'standard', 'button_count', 'box_count'
	 */
	public $layout;
	/**
	 * @var boolean Display profile photos below the button (standard layout only).
	 */
	public $show_faces;
	/**
	 * @var integer Width of the Like button, defults to 450px
	 */
	public $width;
	/**
	 * @var string The verb to display on the button. Options: 'like', 'recommend'
	 */
	public $action;
	/**
	 * @var string The font to display in the button. Options: 'arial',
	 * 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'
	 */
	public $font;
	/**
	 * @var string The color scheme for the plugin. Options: 'light', 'dark'
	 */
	public $colorscheme;
	/**
	 * @var string A label for tracking referrals; must be less than 50
	 * characters and can contain alphanumeric characters and some punctuation
	 * (currently +/=-.:_). The ref attribute causes two parameters to be added to the
   * referrer URL when a user clicks a link from a stream story about a Like action:
   * fb_ref and fb_source
	 */
	public $ref;


	public function run()
	{
		parent::run();
		$params = $this->getParams();
        $this->renderTag('like',$params);
	}
}
