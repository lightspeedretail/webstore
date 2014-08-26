<?php
/**
 * ShareButton class file.
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
 * The Share button lets a user share your content with friends on Facebook.
 *
 * When the user clicks the Share button on your site, the Share dialog pops,
 * and the user can choose to post the link (along with a comment) to their
 * Timeline, a friend's Timeline, a Group or a Facebook Page
 *
 * @see https://developers.facebook.com/docs/plugins/share-button/
 */

class ShareButton extends SPluginBase
{
	/**
	 * @var string The URL to share
	 */
	public $href;
  /**
	 * @var string the layout of the button: box_count, button_count, button,
   * icon_link, icon, link
   * Default: button_count
	 */
	public $type;
	/**
	 * @var integer Width of the Share button, defaults to ~100px
	 */
	public $width;


	public function run()
	{
		parent::run();
		$params = $this->getParams();
        $this->renderTag('share-button',$params);
	}
}
