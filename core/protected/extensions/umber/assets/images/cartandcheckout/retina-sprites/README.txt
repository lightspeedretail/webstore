

Modified version of Steve Obrien NSprite yii based sprite generator extension https://github.com/steve-obrien/NSprite

// ---Please note the sprites and retina-sprites directories are not meant to be modified unless you’ve read and understood
the following instructions. You can use any other non sprite directory in the images folders already available to you if you want to add assets but
are not ready to use the sprite feature.

### README for Brooklyn2014 sprites and retina-sprites directories theme

Thank you for taking the time to read this file.
Below are some important notes on how to use the sprites and retina-sprites directories to generate a sprite set for your web
store. Sprite sets are an advanced, optional feature that allows you to group all of your small image assets into a
single sheet of images. The advantage of having all your image assets grouped is that a single file will be loaded when
your customer open their website instead of multiple separate files. Limiting the amount of loaded files is an efficient
way to make your website faster.

If you are using this default Brooklyn2014 theme and are not adding a significant amount of image assets you do not need
to worry about generating sprite sheets. We already took care of spriting the default icons and images for you.
Please also note that sprite sheets are not meant to be used on your product images directories but only on the small icons
and images that you are adding to personalize your website design.
If you are an advanced user, who added a significant amount of images to your web store and are looking to generate sprite
sheets to improve your website performance, please read the following documentation carefully.
Generating sprite sheets is an easy and seamless process as long as you follow all the following rules.

Note that failing to follow the instructions will result in undesirable effects both for the existing and new image
assets across your website.

## You need a set of two image assets for any image that you will add to your sprite sheets.

We only support retina display ready high quality sprite sheets. Hence if you want to add image assets you will have to
provide them in two sizes. The first size will correspond to the size that your image need to be displayed on your website
the second image will have to be exactly double the size of the first image. Place the regular size image in the sprite
directory and the retina ready double sized images in the retina-sprites directories. Note that both images need to have the
exact same filename.

## Generating sprite sheets on the fly

Sprite sheets will be generated automatically. They can be found in one of your temporary assets directories on the assets
root directory of your website. Regular sprites can be seen in the sprite.png file, Retina ready sprites can be seen in the
retina-sprite.png file. To refresh those assets delete them, and they will be re-generated on the fly.

## Using the sprite sheets images in your website’s markup.

To see those images on your website you will need to reference them. Thankfully the sprite.css file is automatically
generating css classes for you to use. The sprite.css file can be found in the same assets directory as the png sprite
sheets. All the classes in sprite.css map your images while respecting their original file name.

## Typically if you want to use a cat.png image that you put in your sprites and retina-sprites directories you will simply
use this html markup:

<div class="sprite cat"></div> <!-- use on block elements -->

sprite being the parent class common to all sprite assets and cat being the name of your image file.
Those classes are meant to be used on block elements.