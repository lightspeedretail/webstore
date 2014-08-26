<?php
/**
 * RecommendationsBar class file.
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
 * The Recommendations bar lets people 'like' content, get recommendations, and share what they're reading with their friends.
 *
 * @see https://developers.facebook.com/docs/plugins/recommendations-bar/
 */
class RecommendationsBar extends SPluginBase
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
	 * @var he absolute URL of the page that will be liked.
	 */
	public $href;
	/**
	 * @var boolean The side of the screen where the plugin will be displayed.
   * This will automatically adjust based on the language, or can be set
   * explicitly. Options are "left" or "right"
	 */
	public $side;
	/**
	 * @var string Determines when the plugin expands to show recommendations.
   * See the FAQ for full details on each option:
   * https://developers.facebook.com/docs/plugins/recommendations-bar/
	 */
	public $trigger;
  /**
	 * @var integer a limit on recommendation and creation time of articles that are
   * surfaced in the plugins, the default is 0 (we don’t take age into account).
   * Otherwise the valid values are 1-180, which specifies the number of days.
	 */
	public $max_age;
  /**
	 * @var string The number of recommendations to display. The maximum value is 5.
	 */
	public $num_recommendations;
  /**
	 * @var string The number of seconds before the plugin will expand. Minimum is 10. Default: 30
	 */
	public $read_time;
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

	public function run()
	{
		parent::run();
		$params = $this->getParams();
        $this->renderTag('recommendations-bar',$params);
	}

}
