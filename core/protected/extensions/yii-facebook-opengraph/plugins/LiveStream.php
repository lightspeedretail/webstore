<?php
/**
 * LiveStream class file.
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
 * The Live Stream plugin lets your users share activity and comments in
 * real-time as they interact during a live event.
 *
 * @see http://developers.facebook.com/docs/reference/plugins/live-stream
 */
class LiveStream extends SPluginBase
{
	/**
	 * @var integer Width of the plugin in pixels. Default width: 400px.
	 */
	public $width;
	/**
	 * @var integer The height of the plugin in pixels. Default height: 500px.
	 */
	public $height;
  /**
	 * @var integer Id of the app to display
	 */
	public $app_id;
	/**
	 * @var string The URL that users are redirected to when they click on your
	 * app name on a status (if not specified, your Connect URL is used).
	 */
	public $via_url;
	/**
	 * @var integer If you have multiple live stream boxes on the same page,
	 * specify a unique xid for each.
	 */
	public $xid;
	/**
	 * @var bool If set, all user posts will always go to their profile. This option
   * should only be used when users' posts are likey to make sense outside of the
   * context of the event.
	 */
	public $always_post_to_friends;

	public function run()
	{
		parent::run();
		$params = $this->getParams();
		$this->renderTag('live-stream',$params);
	}

}
