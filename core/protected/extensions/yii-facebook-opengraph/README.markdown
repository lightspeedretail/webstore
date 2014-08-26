### This is mainly a wrapper for the Facebook PHP SDK class.

### You can also use it to include the Facebook JS SDK on your pages, and easily set Open Graph meta tags.

### Also included are helper widgets for all of the Facebook Social Plugins.

Facebook PHP SDK:
http://developers.facebook.com/docs/reference/php/
https://github.com/facebook/facebook-php-sdk

Facebook JS SDK:
http://developers.facebook.com/docs/reference/javascript/

Facebook Social Plugins:
http://developers.facebook.com/docs/reference/plugins

Open Graph Protocol:
http://developers.facebook.com/docs/opengraph/

A lot of this comes from forking ianare's faceplugs Yii extension:
http://www.yiiframework.com/extension/faceplugs
https://github.com/digitick/yii-faceplugs

* * *

INSTALLATION:
---------------------------------------------------------------------------

Copy the file "facebook-channel.php" to your project root.

Copy the rest of this extension in to your project's protected/extensions directory.

Include the extension in your Yii config:

    'components'=>array(
      'facebook'=>array(
        'class' => 'ext.yii-facebook-opengraph.SFacebook',
        'appId'=>'YOUR_FACEBOOK_APP_ID', // needed for JS SDK, Social Plugins and PHP SDK
        'secret'=>'YOUR_FACEBOOK_APP_SECRET', // needed for the PHP SDK
        //'fileUpload'=>false, // needed to support API POST requests which send files
        //'trustForwarded'=>false, // trust HTTP_X_FORWARDED_* headers ?
        //'locale'=>'en_US', // override locale setting (defaults to en_US)
        //'jsSdk'=>true, // don't include JS SDK
        //'async'=>true, // load JS SDK asynchronously
        //'jsCallback'=>false, // declare if you are going to be inserting any JS callbacks to the async JS SDK loader
        //'status'=>true, // JS SDK - check login status
        //'cookie'=>true, // JS SDK - enable cookies to allow the server to access the session
        //'oauth'=>true,  // JS SDK - enable OAuth 2.0
        //'xfbml'=>true,  // JS SDK - parse XFBML / html5 Social Plugins
        //'frictionlessRequests'=>true, // JS SDK - enable frictionless requests for request dialogs
        //'html5'=>true,  // use html5 Social Plugins instead of XFBML
        //'ogTags'=>array(  // set default OG tags
            //'og:title'=>'MY_WEBSITE_NAME',
            //'og:description'=>'MY_WEBSITE_DESCRIPTION',
            //'og:image'=>'URL_TO_WEBSITE_LOGO',
        //),
      ),
    ),

Then, in your base Controller, add this function to override the afterRender callback:

    protected function afterRender($view, &$output) {
      parent::afterRender($view,$output);
      //Yii::app()->facebook->addJsCallback($js); // use this if you are registering any $js code you want to run asyc
      Yii::app()->facebook->initJs($output); // this initializes the Facebook JS SDK on all pages
      Yii::app()->facebook->renderOGMetaTags(); // this renders the OG tags
      return true;
    }

* * *

USAGE:
---------------------------------------------------------------------------

Setting OG tags on a page (in view or action):

    <?php Yii::app()->facebook->ogTags['og:title'] = "My Page Title"; ?>

Render Facebook Social Plugins using helper Yii widgets:

    <?php $this->widget('ext.yii-facebook-opengraph.plugins.LikeButton', array(
       //'href' => 'YOUR_URL', // if omitted Facebook will use the OG meta tag
       'show_faces'=>true,
       'send' => true
    )); ?>

You can, of course, just use the code for this as well if loading the JS SDK on all pages
using the initJs() call in afterRender():

    <div class="fb-like" data-send="true" data-width="450" data-show-faces="true"></div>

To use the PHP SDK anywhere in your application, just call it like so (there pass-through the Facebook class):

    <?php $userid = Yii::app()->facebook->getUser() ?>
    <?php $loginUrl = Yii::app()->facebook->getLoginUrl() ?>
    <?php $results = Yii::app()->facebook->api('/me') ?>

I also created a couple of little helper functions:

    <?php $userinfo = Yii::app()->facebook->getInfo() // gets the Graph info of the current user ?>
    <?php $imageUrl = Yii::app()->facebook->getProfilePicture('large') // gets the Facebook picture URL of the current user ?>
    <?php $imageUrl = Yii::app()->facebook->getProfilePicture(array('height'=>300,'width'=>300)) // $size can also be specific ?>
    <?php $userinfo = Yii::app()->facebook->getInfoById($openGraphId) // gets the Graph info of a given OG entity ?>
    <?php $imageUrl = Yii::app()->facebook->getProfilePictureById($openGraphId, $size) // gets the Facebook picture URL of a given OG entity ?>

* * *

BREAKING CHANGES:
---------------------------------------------------------------------------
* __Before version 0.6__ you didn't need to include the full OG meta tag (including the "og:" bit). Now you need to, to allow for new custom graph objects and actions. If you were setting OG meta tags like this `Yii::app()->facebook->ogTags['title']` it now needs to be like this `Yii::app()->facebook->ogTags['og:title']`.

* * *

CHANGE LOG:
---------------------------------------------------------------------------

* 0.6 Added support for custom OG meta tags, upgraded PHP SDK to 3.2, +some bugfixes
* 0.6.1 Bugfix for the custom OG meta tags, which were overwriting the type, title and url tags
* 0.7 Upgraded to PHP SDK 3.2.2
* 0.8 Fixed error with LoginButton plugin where inherited CWidget 'skin' property caused display error, added missing attributes to LoginButton, and added ability to specify custom profile image size
* 0.8.1 Updated the PHP SDK cert chain bundle which was not update to date for some reason, and copied in a security fix from the PHP SDK as well
* 0.9 Upgraded to PHP SDK 3.2.3
* 0.10 Updated Social Plugins - removed LiveStream and added RecommendationsBar, EmbeddedPost and FollowButton (used to be SubscribeButton)

* * *

I plan on continuing to update and bugfix this extension as needed.

Please log bugs to the GitHub tracker.

Extension is posted on Yii website also:
http://www.yiiframework.com/extension/facebook-opengraph/

Updated Nov 27th 2013 by Evan Johnson
http://splashlabsocial.com
