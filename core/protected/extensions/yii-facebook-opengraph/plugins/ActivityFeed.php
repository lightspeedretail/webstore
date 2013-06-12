<?php
/**
 * ActivityFeed class file.
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
 * The Activity Feed plugin displays the most interesting recent activity
 * taking place on your site.
 *
 * @see http://developers.facebook.com/docs/reference/plugins/activity
 */
class ActivityFeed extends SPluginBase
{
	/**
	 * @var string The domain to show activity for. Defaults to the current
	 * domain.
	 */
	public $site;
  /**
	 * @var string a comma separated list of actions to show activities for
	 */
	public $action;
  /**
	 * @var integer The width of the plugin in pixels. Default width: 300px.
	 */
	public $width;
	/**
	 * @var integer The height of the plugin in pixels. Default height: 300px.
	 */
	public $height;
	/**
	 * @var boolean Specifies whether to show the Facebook header.
	 */
	public $header;
	/**
	 * @var string The color scheme for the plugin. Options: 'light', 'dark'
	 */
	public $colorscheme;
	/**
	 * @var string The font to display in the plugin. Options: 'arial',
	 * 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'
	 */
	public $font;
	/**
	 * @var string The border color of the plugin.
	 */
	public $border_color;
	/**
	 * @var boolean Specifies whether to always show recommendations in the plugin.
	 *
	 * If set to true, the plugin will display recommendations in the bottom
	 * half.
	 */
	public $recomendations;
	/**
	 * @var string Allows you to filter which URLs are shown in the plugin.
	 *
	 * The plugin will only include URLs which contain the filter in the first
	 * two path parameters of the URL. If nothing in the first two path
	 * parameters of the URL matches the filter, the URL will not be included.
	 */
	public $filter;
  /**
	 * @var string  This specifies the context in which content links are opened.
   * By default all links within the plugin will open a new window. If you want
   * the content links to open in the same window, you can set this parameter to
   * _top or _parent. Links to Facebook URLs will always open in a new window.
	 */
	public $linktarget;
	/**
	 * @var string A label for tracking referrals; must be less than 50
	 * characters and can contain alphanumeric characters and some punctuation
	 * (currently +/=-.:_).
	 */
	public $ref;
  /**
	 * @var integer a limit on recommendation and creation time of articles that
   * are surfaced in the plugins, the default is 0 (we don’t take age into account).
   * Otherwise the valid values are 1-180, which specifies the number of days.
	 */
	public $max_age;

	public function run()
	{
		parent::run();
		$params = $this->getParams();
        $this->renderTag('activity',$params);
	}

}
