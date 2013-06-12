<?php
/**
 * Recommendations class file.
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
 * The Recommendations plugin shows personalized recommendations to your users.
 *
 * @see http://developers.facebook.com/docs/reference/plugins/recommendations
 */
class Recommendations extends SPluginBase
{
	/**
	 * @var string The domain to show activity for. Defaults to the current domain.
	 */
	public $site;
  /**
	 * @var string a comma separated list of actions to show recommendations for
	 */
	public $action;
  /**
	 * @var integer will display recommendations for all types of actions, custom and global, associated with this app_id
	 */
	public $app_id;
	/**
	 * @var integer The height of the plugin in pixels. Default height: 300px.
	 */
	public $height;
	/**
	 * @var integer The width of the plugin in pixels. Default width: 300px.
	 */
	public $width;
	/**
	 * @var boolean Specifies whether to show the Facebook header.
	 */
	public $header;
	/**
	 * @var string The color scheme for the plugin. Options: 'light', 'dark'
	 */
	public $colorscheme;
	/**
	 * @var string The font to display in the plugin. Options: 'arial', 'lucida grande',
	 * 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'
	 */
	public $font;
	/**
	 * @var string The border color of the plugin.
	 */
	public $border_color;
  /**
	 * @var string This specifies the context in which content links are opened. By
   * default all links within the plugin will open a new window. If you want the
   * content links to open in the same window, you can set this parameter to _top
   * or _parent. Links to Facebook URLs will always open in a new window
	 */
	public $linktarget;
	/**
	 * @var string A label for tracking referrals; must be less than 50
	 * characters and can contain alphanumeric characters and some punctuation
	 * (currently +/=-.:_). Specifying a value for the ref attribute adds the 'fb_ref'
   * parameter to the any links back to your site which are clicked from within the
   * plugin. Using different values for the ref parameter for different positions and
   * configurations of this plugin within your pages allows you to track which
   * instances are performing the best.
	 */
	public $ref;
  /**
	 * @var integer a limit on recommendation and creation time of articles that are
   * surfaced in the plugins, the default is 0 (we don’t take age into account).
   * Otherwise the valid values are 1-180, which specifies the number of days.
	 */
	public $max_age;

	public function run()
	{
		parent::run();
		$params = $this->getParams();
        $this->renderTag('recommendations',$params);
	}

}
