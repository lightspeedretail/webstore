<?php
/**
 * SFacebook class file.
 *
 * @author Evan Johnson <thaddeusmt - A T - gmail.com>
 * @author Ianaré Sévi (original author) www.digitick.net
 * @link https://github.com/splashlab/yii-facebook-opengraph
 * @copyright &copy; Digitick <www.digitick.net> 2011
 * @copyright Copyright &copy; 2012 SplashLab Social  http://splashlabsocial.com
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License v3.0
 *
 */

Yii::import("ext.yii-facebook-opengraph.SBaseFacebook");
class SFacebook extends CApplicationComponent
{
  /**
   * @var bool instance of the Facebook library
   */
  private $_facebook;

  /**
   * @var string Facebook Application ID
   */
  public $appId;

  /**
   * @var string Facebook Application secret
   */
  public $secret;

  /**
   * @var bool Indicates if the CURL based @ syntax for file uploads is enabled in the PHP SDK
   */
  public $fileUpload = false;

  /**
   * @var boolean Indicates if we trust HTTP_X_FORWARDED_* headers.
   */
  public $trustForwarded = false;

  /**
   * @var boolean Indicates if we trust HTTP_X_FORWARDED_* headers.
   */
  public $allowSignedRequest = true;

  /**
   * @var bool whether or not to check login status
   */
  public $status = true;

  /**
   * @var bool whether or not use a cookie
   */
  public $cookie = true;

  /**
   * @var bool whether or not to enable OAuth 2.0
   */
  public $oauth = true;

  /**
   * @var bool whether or not to parse XFBML tags with the JS library (will render Plugins with iframes if false)
   */
  public $xfbml = true;

  /**
   * @var bool whether or not to look for an run JS callbacks in the async JS loader
   */
  public $jsCallback = false;

  /**
   * @var string JavaScript to run after the Facebook JS library loads asynchronously
   */
  private $callbackScripts = '';

  /**
   * @var bool whether or not to use frictionlessRequests on request dialogs
   */
  public $frictionlessRequests = false;

  /**
   * @var bool turn on or off the Facebook JS
   */
  public $jsSdk = true;

  /**
   * @var bool whether or not to use html5 social plugins instead of xfbml
   */
  public $html5 = true;

  /**
   * @var bool Load the Facebook init script asynchronously.
   *
   * This speeds up page loads because loading the plugin does not block
   * loading other elements of the page.
   */
  public $async = true;

  /**
   * @var string Override default locale for the widget.
   *
   * Normally locale is set automatically based on the Yii language settings,
   * setting it here allows a specific locale to be used.
   */
  public $locale;
  private $_locale;

  /**
   * @var array Open Graph Meta Tags
   */
  public $ogTags = array();

  /**
   * @var array Valid Facebook locales.
   */
  protected $locales = array(
    'az_AZ',
    'be_BY',
    'bg_BG',
    'bn_IN',
    'bs_BA',
    'ca_ES',
    'ck_US',
    'cs_CZ',
    'cy_GB',
    'da_DK',
    'de_DE',
    'eu_ES',
    'en_GB',
    'en_PI',
    'en_UD',
    'en_US',
    'es_LA',
    'es_CL',
    'es_CO',
    'es_ES',
    'es_MX',
    'es_VE',
    'fb_FI',
    'fi_FI',
    'fr_FR',
    'gl_ES',
    'hu_HU',
    'it_IT',
    'ja_JP',
    'ko_KR',
    'nb_NO',
    'nn_NO',
    'nl_NL',
    'pl_PL',
    'pt_BR',
    'pt_PT',
    'ro_RO',
    'ru_RU',
    'sk_SK',
    'sl_SI',
    'sv_SE',
    'th_TH',
    'tr_TR',
    'ku_TR',
    'zh_CN',
    'zh_HK',
    'zh_TW',
    'fb_LT',
    'af_ZA',
    'sq_AL',
    'hy_AM',
    'hr_HR',
    'nl_BE',
    'eo_EO',
    'et_EE',
    'fo_FO',
    'fr_CA',
    'ka_GE',
    'el_GR',
    'gu_IN',
    'hi_IN',
    'is_IS',
    'id_ID',
    'ga_IE',
    'jv_ID',
    'kn_IN',
    'kk_KZ',
    'la_VA',
    'lv_LV',
    'li_NL',
    'lt_LT',
    'mk_MK',
    'mg_MG',
    'ms_MY',
    'mt_MT',
    'mr_IN',
    'mn_MN',
    'ne_NP',
    'pa_IN',
    'rm_CH',
    'sa_IN',
    'sr_RS',
    'so_SO',
    'sw_KE',
    'tl_PH',
    'ta_IN',
    'tt_RU',
    'te_IN',
    'ml_IN',
    'uk_UA',
    'uz_UZ',
    'vi_VN',
    'xh_ZA',
    'zu_ZA',
    'km_KH',
    'tg_TJ',
    'ar_AR',
    'he_IL',
    'ur_PK',
    'fa_IR',
    'sy_SY',
    'yi_DE',
    'gn_PY',
    'qu_PE',
    'ay_BO',
    'se_NO',
    'ps_AF',
    'tl_ST',
  );

  /**
   * Get the proper http URL prefix depending on if this was a secure page request or not
   *
   * @return string https or https
   */
  public function getProtocol() {
    if(Yii::app()->request->isSecureConnection)
      return 'https';
    return 'http';
  }

  /**
   * Load Facebook JS and Open Graph meta tags
   * http://developers.facebook.com/docs/reference/javascript/
   * http://developers.facebook.com/docs/opengraph/
   * @return void
   */
  public function initJs(&$output)
  {
    if (!$this->appId) {
      throw new CException('Facebook Application ID not specified.');
    }
    // initialize the Facebook JS
    if ($this->jsSdk) {
      $script = '//connect.facebook.net/'.$this->getLocale().'/all.js';
      $init = $this->registerSDKScript('init', array(
          'appId' => $this->appId, // application ID
          'status' => $this->status, // check login status
          'cookie' => $this->cookie, // enable cookies to allow the server to access the session
          'xfbml' => $this->xfbml, // parse XFBML
          'oauth' => $this->oauth, // enable OAuth 2.0
          'frictionlessRequests' => $this->frictionlessRequests, // Enable frictionless requests on requests dialog
          // TODO follow up on this bug: http://developers.facebook.com/bugs/258868920826496
          // IE won't login with this channelUrl line uncommented
          //'channelUrl' => $this->getChannelUrl(), // Channel File
        )
      );
      if ($this->async) {
        $init = "window.fbAsyncInit = function(){{$init}};
                (function(d){
                 var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
                 js = d.createElement('script'); js.id = id; js.async = true;
                 js.src = '{$script}';
                 d.getElementsByTagName('head')[0].appendChild(js);
                }(document));";
      }
      else {
        Yii::app()->clientScript->registerScriptFile($script, CClientScript::POS_END);
      }
      Yii::app()->getClientScript()->registerScript('fb-script', $init, CClientScript::POS_END);
      $this->insertFbRoot($output);
      $this->registerAsyncCallback();
    }
  }

  /**
   * This function adds the fb-root div tag to the bottom of the <body> tag
   * THe code is borrowed from Yii's own CClientScript::renderBodyEnd() function
   * @param $output (passed by reference) the final page HTML render, so insert the tag into
   */
  protected function insertFbRoot(&$output) {
    $fbRoot = '<div id="fb-root"></div>';
    $fullPage=0;
    $output=preg_replace('/(<\\/body\s*>)/is','<###end###>$1',$output,1,$fullPage);
    if($fullPage)
      $output=str_replace('<###end###>',$fbRoot,$output);
    else
      $output=$output.$fbRoot;
  }

  /**
   * http://developers.facebook.com/docs/reference/javascript/
   * @return string url of the FB JS SDK channel file
   */
  protected function getChannelUrl() {
    return '//'.Yii::app()->request->getServerName().'/facebook-channel.php';
  }

  /**
   * Registers all of the Open Graph meta tags declared
   * @return void
   */
  public function renderOGMetaTags() {
    $this->ogTags['fb:app_id'] = $this->appId; // set this app ID og tag, for Facebook insights and administration
    if (!isset($this->ogTags['og:type']))
      $this->ogTags['og:type'] = 'website'; // set website as the default type
    if (!isset($this->ogTags['og:title']))
      $this->ogTags['og:title'] = Yii::app()->name; // default to App name
    if (!isset($this->ogTags['og:url']))
      $this->ogTags['og:url'] = $this->getProtocol()."://".Yii::app()->request->serverName.Yii::app()->request->requestUri; // defaults to current URL
    foreach ($this->ogTags as $type => $value) { // loop through any other OG tags declared
      $this->registerOpenGraph($type, $value);
    }
  }

  /**
   * Add JS to run after Facebook initalizes
   * @param JavaScript that needs to run right after the Facebook Asynchronous loader finishes
   * @return void
   */
  public function addJsCallback($script) {
    $this->callbackScripts .= $script;
  }

  /**
   * Creates the Facebook JS init call
   * @param string $method
   * @param array $args args to use in the method
   * @return string the js created
   */
  protected function registerSDKScript($method, $args=array())
  {
    $args = CJavaScript::encode($args);// Initalize Facebook JS
    if ($this->jsCallback)
      return "FB.{$method}({$args});asyncCallback();";
    else
      return "FB.{$method}({$args});";
  }

  /**
   * This method adds your scripts to the callback method
   * Call this function in afterRender after you have added scripts with the addScript method
   * @return void
   */
  protected function registerAsyncCallback() {
    if ($this->jsCallback) {
      $script = "function asyncCallback() {
        {$this->callbackScripts}
      }";
      Yii::app()->getClientScript()->registerScript('fb-async-callback', $script, CClientScript::POS_END);
    }
  }

  /**
   * Register an opengraph property.
   * @param string $property
   * @param string $data
   */
  public function registerOpenGraph($property, $data)
  {
    Yii::app()->clientScript->registerMetaTag($data, null, null, array('property' => $property));
  }

  /**
   * Determine the script locale to load
   * Looks at $locale variable declared in this file first
   * Then looks at the Yii application language
   * Defaults to en_US
   * @return string locale code
   */
  protected function getLocale()
  {
    if($this->_locale === null) {
      if (isset($this->locale)) {
        $locale = strtolower($this->locale);
      }
      elseif(Yii::app()->language) {
        $locale = Yii::app()->language;
      } else {
        $locale = 'en_US'; // default
      }
      // Adjustments, mainly because facebook doesn't have all countries
      // of the same language translated.
      $lang = substr($locale, 0, 2);
      $adjust = array(
        'de' => 'de_de',
        'nl' => 'nl_nl',
        'ru' => 'ru_ru',
        'ar' => 'ar_ar', // non standard
        'ku' => 'ku_tr',
      );
      // single check languages, array above ...
      if (isset($adjust[$lang])) {
        $locale = $adjust[$lang];
      }
      // english
      else if ($lang === 'en' && !in_array($locale, array('en_us','en_pi','en_ud'))) {
        // closer to US english
        if ($locale === 'en_ca') {
          $locale = 'en_us';
        }
        // closer to UK english
        else {
          $locale = 'en_gb';
        }
      }
      // french
      else if ($lang === 'fr' && $locale !== 'fr_ca') {
        $locale = 'fr_fr';
      }
      // spanish
      else if ($lang === 'es' && !in_array($locale, array('es_es','es_cl','es_co','es_mx','es_ve'))) {
        $locale = 'es_la'; // non standard
      }
      // portuguese
      else if ($lang === 'pt' && $locale !== 'pt_br') {
        $locale = 'pt_pt';
      }
      $c = explode('_', $locale);
      if (!isset($c[1])) {
        throw new CException('Locale for Facebook plugins must be in the following format : ll_CC');
      }
      $locale = $c[0] . '_' . strtoupper($c[1]);
      if (!in_array($locale, $this->locales)) {
        throw new CException('Invalid Facebook locale');
      }
      $this->_locale = $locale;
    }
    return $this->_locale;
  }

  /*** PHP SDK functions **/

  /**
   * @throws CException if the Facebook PHP SDK cannot be loaded
   * @return instance of Facebook PHP SDK class
   */
  protected function _getFacebook()
  {
    if (is_null($this->_facebook)) {
      if ($this->appId && $this->secret) {
        $this->_facebook = new SBaseFacebook(
          array(
            'appId' => $this->appId,
            'secret' => $this->secret,
            'fileUpload' => $this->fileUpload,
            'trustForwarded' => $this->trustForwarded,
            'allowSignedRequest' => $this->allowSignedRequest,
          ));
      } else {
        if (!$this->appId)
          throw new CException('Facebook application ID not specified.');
        elseif (!$this->secret)
          throw new CException('Facebook application secret not specified.');
      }
    }
    if(!is_object($this->_facebook)) {
      throw new CException('Facebook API could not be initialized.');
    }
    return $this->_facebook;
  }

  /**
   * Get the user object. This will automatically look for a signed session
   * sent via the signed_request, Cookie or Query Parameters if needed.
   *
   * @return Array the session
   */
  public function getUser(){
    return $this->_getFacebook()->getUser();
  }

  /**
   * Destroy the current session
   */
  public function destroySession(){
    return $this->_getFacebook()->destroySession();
  }

  /**
   * Sets the access token for api calls.  Use this if you get
   * your access token by other means and just want the SDK
   * to use it.
   *
   * @param string $access_token an access token.
   * @return BaseFacebook
   */
  public function setAccessToken($access_token) {
    return $this->_getFacebook()->setAccessToken($access_token);
  }

  /**
   * Extend an access token, while removing the short-lived token that might
   * have been generated via client-side flow. Thanks to http://bit.ly/b0Pt0H
   * for the workaround.
   */
  public function setExtendedAccessToken() {
    return $this->_getFacebook()->setExtendedAccessToken();
  }

  /**
   * Gets a OAuth access token.
   *
   * @return String the access token
   */
  public function getAccessToken(){
    return $this->_getFacebook()->getAccessToken();
  }

  /**
   * Get the data from a signed_request token
   *
   * @return String the base domain
   */
  public function getSignedRequest(){
    return $this->_getFacebook()->getSignedRequest();
  }

  /**
   * Get a Login URL for use with redirects. By default, full page redirect is
   * assumed. If you are using the generated URL with a window.open() call in
   * JavaScript, you can pass in display=popup as part of the $params.
   *
   * The parameters:
   * - redirect_uri: the url to go to after a successful login
   * - scope: comma separated list of requested extended perms
   *
   * @param array $params Provide custom parameters
   * @return string The URL for the login flow
   */
  public function getLoginUrl($params=array()) {
    return $this->_getFacebook()->getLoginUrl($params);
  }

  /**
   * Get a Logout URL suitable for use with redirects.
   *
   * The parameters:
   * - next: the url to go to after a successful logout
   *
   * @param array $params Provide custom parameters
   * @return string The URL for the logout flow
   */
  public function getLogoutUrl($params=array()) {
    return $this->_getFacebook()->getLogoutUrl($params);
  }

  /**
   * Get a login status URL to fetch the status from Facebook.
   *
   * The parameters:
   * - ok_session: the URL to go to if a session is found
   * - no_session: the URL to go to if the user is not connected
   * - no_user: the URL to go to if the user is not signed into facebook
   *
   * @param array $params Provide custom parameters
   * @return string The URL for the logout flow
   */
  public function getLoginStatusUrl($params=array()) {
    return $this->_getFacebook()->getLoginStatusUrl($params);
  }

  /**
   * Make an API call - direct pass-through to the facebook SDK
   *
   * @param Array $params the API call parameters
   * @return the decoded response
   */
  public function api(/* polymorphic */) {
    $args = func_get_args();
    $result = false;
    try {
      $result = call_user_func_array(array($this->_getFacebook(), 'api'), $args);
    } catch (CurlException $e) { //timeout so try to resend
      $result = call_user_func_array(array($this->_getFacebook(), 'api'), $args);
    } /*catch (Exception $e) {
            Yii::log('Failed to make Facebook API call. Exception: '.$e->getMessage());
        }*/
    return $result;
  }

  /**
   * Get the Facebook profile picture for the currently logged in user
   *
   * @param mixed size facebook image size (square, small, normal, large)
   * @return url of Facebook profile picture
   */
  public function getProfilePicture($size = null){
    $id = $this->_getFacebook()->getUser();
    return $this->getProfilePictureById($id,$size);
  }

  /**
   * Get the Facebook user profile picture for a given Open Graph object
   *
   * @param mixed id Facebook user id
   * @param mixed size (optional) size of the facebook image to return (square, small, normal, large),
   * or an array specifying width and height
   * @return url of Facebook profile picture
   */
  public function getProfilePictureById($id, $size = null) {
    $params = null;
    if ($size && is_array($size) && isset($size['height']) && isset($size['width'])) {
      $params = '?width='.$size['width'].'&height='.$size['height'];
    } elseif ($size) {
      $params = '?type='.$size;
    }
    return $this->getProtocol().'://graph.facebook.com/'.$id.'/picture'.$params;
  }

  /**
   * Get the Facebook Open Graph info for the current user
   *
   * @return array of Facebook Open Graph user data for the logged in user "me"
   */
  public function getInfo() {
    return $this->getInfoById('me');
  }

  /*
  * @param Facebook user id
  * @return array of Facebook Open Graph user data
  */
  public function getInfoById($id) {
    if ($this->getUser()) {
      return $this->api('/'.$id);
    } else {
      return false;
    }
  }

}
