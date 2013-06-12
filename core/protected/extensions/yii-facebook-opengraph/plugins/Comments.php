<?php
/**
 * Comments class file.
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
 * The Comments Box easily enables your users to comment on your site's content —
 * whether it's for a web page, article, photo, or other piece of content.
 *
 * @see http://developers.facebook.com/docs/reference/plugins/comments
 */
class Comments extends SPluginBase
{
  /**
	 * @var string the URL for this Comments plugin. News feed stories on Facebook will link to this URL
	 */
	public $href;
  /**
	 * @var integer The width of the widget.
	 */
	public $width;
  /**
	 * @var string the color scheme for the plugin. Options: 'light', 'dark'
	 */
	public $colorscheme;
	/**
	 * @var integer the number of comments to show by default. Default: 10. Minimum: 1
	 */
	public $num_posts;
  /**
	 * @var string whether to show the mobile-optimized version. Default: auto-detect
	 */
	public $mobile;

	public function run()
	{
		parent::run();
		$params = $this->getParams();
        $this->renderTag('comments',$params);
	}

}
