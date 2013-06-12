<?php
/**
 * LikeBox class file.
 *
 * @author Evan Johnson <thaddeusmt - AT - gmail - DOT - com>
 * @author Ianaré Sévi (original author) www.digitick.net
 * @link https://github.com/splashlab/yii-facebook-opengraph
 * @copyright &copy; Digitick <www.digitick.net> 2011
 * @copyright Copyright &copy; 2012 SplashLab Social  http://splashlabsocial.com
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License v3.0
 *
 */

require_once "php-sdk-3.2.2/base_facebook.php";

/**
 * Extends the BaseFacebook class with the intent of using
 * PHP sessions to store user ids and access tokens.
 */
class SBaseFacebook extends BaseFacebook
{
  const FBSS_COOKIE_NAME = 'fbss';

  // We can set this to a high number because the main session
  // expiration will trump this.
  const FBSS_COOKIE_EXPIRE = 31556926; // 1 year

  // Stores the shared session ID if one is set.
  protected $sharedSessionID;

  /**
   * Identical to the parent constructor, except that
   * we start a PHP session to store the user ID and
   * access token if during the course of execution
   * we discover them.
   *
   * @param Array $config the application configuration. Additionally
   * accepts "sharedSession" as a boolean to turn on a secondary
   * cookie for environments with a shared session (that is, your app
   * shares the domain with other apps).
   * @see BaseFacebook::__construct in facebook.php
   */
  public function __construct($config) {
    parent::__construct($config);
    if (!empty($config['sharedSession'])) {
      $this->initSharedSession();
    }
  }

  protected static $kSupportedKeys =
      array('state', 'code', 'access_token', 'user_id');

  protected function initSharedSession() {
    $cookie_name = $this->getSharedSessionCookieName();
    if (isset(Yii::app()->request->cookies[$cookie_name])) {
      $data = $this->parseSignedRequest(Yii::app()->request->cookies[$cookie_name]);
      if ($data && !empty($data['domain']) &&
          self::isAllowedDomain($this->getHttpHost(), $data['domain'])) {
        // good case
        $this->sharedSessionID = $data['id'];
        return;
      }
      // ignoring potentially unreachable data
    }
    // evil/corrupt/missing case
    $base_domain = $this->getBaseDomain();
    $this->sharedSessionID = md5(uniqid(mt_rand(), true));
    $cookie_value = $this->makeSignedRequest(
      array(
        'domain' => $base_domain,
        'id' => $this->sharedSessionID,
      )
    );
    Yii::app()->request->cookies[$cookie_name] = $cookie_value;
    if (!headers_sent()) {
      $expire = time() + self::FBSS_COOKIE_EXPIRE;
      //setcookie($cookie_name, $cookie_value, $expire, '/', '.'.$base_domain);
      Yii::app()->request->cookies[$cookie_name] =
          new CHttpCookie(
            $cookie_name,
            $cookie_value,
            array(
              'expire'=>$expire,
              'path'=>'/',
              'domain'=>'.'.$base_domain,
            )
          );
    } else {
      // @codeCoverageIgnoreStart
      self::errorLog(
        'Shared session ID cookie could not be set! You must ensure you '.
            'create the Facebook instance before headers have been sent. This '.
            'will cause authentication issues after the first request.'
      );
      // @codeCoverageIgnoreEnd
    }
  }

  /**
   * Provides the implementations of the inherited abstract
   * methods.  The implementation uses PHP sessions to maintain
   * a store for authorization codes, user ids, CSRF states, and
   * access tokens.
   */
  protected function setPersistentData($key, $value) {
    if (!in_array($key, self::$kSupportedKeys)) {
      self::errorLog('Unsupported key passed to setPersistentData.');
      return;
    }

    $session_var_name = $this->constructSessionVariableName($key);
    Yii::app()->session[$session_var_name] = $value;
  }

  protected function getPersistentData($key, $default = false) {
    if (!in_array($key, self::$kSupportedKeys)) {
      self::errorLog('Unsupported key passed to getPersistentData.');
      return $default;
    }

    $session_var_name = $this->constructSessionVariableName($key);
    return isset(Yii::app()->session[$session_var_name]) ?
        Yii::app()->session[$session_var_name] : $default;
  }

  protected function clearPersistentData($key) {
    if (!in_array($key, self::$kSupportedKeys)) {
      self::errorLog('Unsupported key passed to clearPersistentData.');
      return;
    }

    $session_var_name = $this->constructSessionVariableName($key);
    unset(Yii::app()->session[$session_var_name]);
  }

  protected function clearAllPersistentData() {
    foreach (self::$kSupportedKeys as $key) {
      $this->clearPersistentData($key);
    }
    if ($this->sharedSessionID) {
      $this->deleteSharedSessionCookie();
    }
  }

  protected function deleteSharedSessionCookie() {
    $cookie_name = $this->getSharedSessionCookieName();
    unset(Yii::app()->request->cookies[$cookie_name]);
    $base_domain = $this->getBaseDomain();
    //setcookie($cookie_name, '', 1, '/', '.'.$base_domain);
    Yii::app()->request->cookies[$cookie_name] = new CHttpCookie(
      $cookie_name,
      '',
      array(
        'expire'=>1,
        'path'=>'/',
        'domain'=>'.'.$base_domain,
      )
    );
  }

  protected function getSharedSessionCookieName() {
    return self::FBSS_COOKIE_NAME . '_' . $this->getAppId();
  }

  protected function constructSessionVariableName($key) {
    $parts = array('fb', $this->getAppId(), $key);
    if ($this->sharedSessionID) {
      array_unshift($parts, $this->sharedSessionID);
    }
    return implode('_', $parts);
  }

  /**
   * Prints to the error log if you aren't in command line mode.
   * Overidden to use the Yii log instead of the default server log.
   *
   * @param string $msg Log message
   */
  protected static function errorLog($msg) {
    // disable error log if we are running in a CLI environment
    // @codeCoverageIgnoreStart
    if (php_sapi_name() != 'cli') {
      //error_log($msg);
      Yii::log($msg,CLogger::LEVEL_ERROR,'ext.SBaseFacebook');
    }
    // uncomment this if you want to see the errors on the page
    // print 'error_log: '.$msg."\n";
    // @codeCoverageIgnoreEnd
  }

}
