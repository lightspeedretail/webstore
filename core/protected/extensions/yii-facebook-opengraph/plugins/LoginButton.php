<?php
/**
 * LoginButton class file.
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
 * The Login Button shows profile pictures of the user's friends who have
 * already signed up for your site in addition to a login button.
 *
 * @see http://developers.facebook.com/docs/reference/plugins/login
 */
class LoginButton extends SPluginBase
{
	/**
	 * @var string The URL of the page.
	 *
	 * The plugin will display photos of users who have liked this page.
	 */
	public $show_faces;
  /**
	 * @var integer The width of the plugin in pixels. Default width: 200px.
	 */
	public $width;
	/**
	 * @var integer The maximum number of rows of profile pictures to display.
	 * Default value: 1.
	 */
	public $max_rows;
	/**
	 * @var string A comma separated list of extended permissions.
	 *
	 * By default the Login button prompts users for their public information.
	 * If your application needs to access other parts of the user's profile
	 * that may be private, your application can request extended permissions.
	 *
	 * @see http://developers.facebook.com/docs/authentication/permissions/
	 */
	public $scope;
  /**
	 * @var string registration page url. If the user has not registered for your
   * site, they will be redirected to the URL you specify in the registration-url
   * parameter.
	 */
	public $registration_url;
  /**
	 * @var string Different sized buttons: small, medium, large, xlarge (default: medium)
	 */
	public $size;
  /**
	 * @var string When a user logs into Facebook with the Login Button, and the user
   * has already authorized your application, the function specified for on-login
   * will be called. Otherwise the user will be redirected to the specified registration URL.
   *
   * @see http://developers.facebook.com/docs/user_registration/flows/
	 */
	public $on_login;
  /**
	 * @var string text of the login button
	 */
	public $text;

	public function run()
	{
		parent::run();
		$params = $this->getParams();
		$this->renderTag('login-button',$params);
	}

}
