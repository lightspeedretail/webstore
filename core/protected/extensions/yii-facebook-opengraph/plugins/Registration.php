<?php
/**
 * Registration class file.
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
 * The registration plugin allows users to easily sign up for your website with
 * their Facebook account.
 *
 * @see http://developers.facebook.com/docs/plugins/registration
 */
class Registration extends SPluginBase
{
  /**
	 * @var integer Your App ID.
	 */
	public $client_id;
  /**
	 * @var string The URI that will process the signed_request. It must be
	 * prefixed by your Site URL.
	 */
	public $redirect_uri;
	/**
	 * @var string If the user arrives logged into Facebook, but has not registered for
	 * your site, the button will say Register and clicking it will take the
	 * user to your registration-url.
	 */
	public $registration_url;
	/**
	 * @var string Comma separated list of Named Fields, or JSON of Custom
	 * Fields.
	 */
	public $fields;
	/**
	 * @var boolean Only allow users to register by linking their Facebook profile.
	 * Use this if you do not have your own registration system. Default: false.
	 */
	public $fb_only;
  /**
	 * @var boolean Allow users to register for Facebook during the registration process.
   * Use this if you do not have your own registration system. Default: false.
	 */
	public $fb_register;
	/**
	 * @var integer The width in pixels. If the width is < 520 the plugin will
	 * render in a small layout.
	 */
	public $width;
  /**
	 * @var integer Optional. The border color of the plugin
	 */
	public $border_color;
  /**
	 * @var integer Optional. The target of the form submission: _top (default), _parent, or _self.
	 */
	public $target;

	public function run()
	{
		parent::run();
		$this->client_id = Yii::app()->facebook->appId;
		$params = $this->getParams();
		$this->renderTag('registration',$params);
	}

}
