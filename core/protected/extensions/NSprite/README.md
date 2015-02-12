

NSprite is an extension for the Yii Framework.
It can be found here: http://www.yiiframework.com/extension/nsprite/

The NSprite Yii extension aims to help solve the problem of generating sprites for your application.

There are many great icon libraries that are ubiquitous on the web, - famfamfam - fugue Each of these have thousands of icons, a typical project uses a handfull of these.

This class allows you to dump the individual image files you want into a folder or set of folders. The class then generates the sprite from all the images in these folder(s) and publishes the sprite.png plus the generated sprite.css file into the assets folder.

## Requirements 

 - Tested with Yii 1.8. relies on the CAssetManager and the CFileHelper so it should work on Yii 1.0 and above.
 - GD php library
 - Tested on PHP 5.2 and 5.3, may also work on previous versions

## Installation 

 1 Download the sprite.zip package
 2 Extract it and place the sprite folder into your extensions folder
 3 The assets folder must be writable.
 4 Add the sprite component to you Yii app's confirguration

## Usage 

In you main config

    'components'=>array(
        'sprite'=>array(
        'class'=>'ext.sprite.NSprite',
        // if you remove the imageFolderPath setting it will use the icon folder within
        // the sprite package (ext.sprite.icons)
        'imageFolderPath'=>array(
            Yii::getPathOfAlias('modules.project.images'),
            'path/to/another/folder'
        )
    ),

Then somewhere in your application you just have to register the css file.

    Yii::app()->sprite->registerSpriteCss();

The css classes generated follow a convention. Each class will have a parent class (defaults to ".sprite", or ".icon") that set the background image to the generated sprite. Each icon/image in your set of image folders (set by "imageFolderPath") will have a class name equivalent to their relative file path within the imageFolderPath.

For example an image folder containing:

    fam/add.png
    myicons/groovy.png
    fugue/pencil.png

Will generate the following css classes:

    .sprite{background-image:url(sprite.png);}
    .icon{display:inline;overflow:hidden;padding-left:18px;background-repeat:no-repeat;background-image:url(sprite.png);}
    .fam-add{background-position:-16px 0px;width:6px;height:16px;}
    .myicons-groovy{background-position:-16px -16px;width:16px;height:16px;}
    .fugue-pencil{background-position:-16px -32px;width:16px;height:16px;}

And can be used like:

    <div class="sprite fam-add"></div> <!-- use on block elements -->
    <span class="icon fam-add"></span> <!-- use with 16px x 16px image inline -->

The **.sprite** class is the general purpose class that adds the sprite as a background image. Nothing more. The **.icon** class is a helper for sprite icons typically for famfamfam and fugue 16 x 16 pixel images for use with images 16px by 16px, the sprite generator does support all sizes of images but you may have to add css to suite your situation.

Obviously you can override the .sprite and .icon classes (and change their name) to provide the css trickery required for your application.

