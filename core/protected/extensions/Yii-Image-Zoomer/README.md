**************************************************************** Version 1.0 -- First Release *********************************************************************

	
						********************** Documentation Of The Extension **************************

The Yii-Image-Zoomer is a extension which consists of two type of image zoom.They are:

1) Single Image Zoom: This type of zoom is used when you want to apply zoom effect on single image.

2) Multi-image Zoom: This type of zoom is used when you want to apply zoom effect on Multiple images .

Yii-Image Zoomer uses Featured Image Zoomer v2.1 script from http://www.dynamicdrive.com/dynamicindex4/featuredzoomer.htm

 


##Requirements

 Yii 1.1x

Tested on the following Yii Versions:

Version : 1.1.13,1.1.12,1.1.11,1.1.10,1.1.9,1.1.8 and 1.1.7


##Setup

1) Extract the downloaded file and put the extracted extension files into _[protected/extensions]_ folder of your Yii application.
   Rename the folder from "YiiImageZoomer-master" to "YiiImageZoomer".
   
2) Copy the "spinner.gif" image which comes with this extension into _[/images]_ .This image is used by script .The folder or directory "images" is created by default when you create a new yii application, it is located at "yourapplicationwebroot/images"
	
	For Example: Let's say your application name is "Music_Cart" , than the images folder is located at -[Music_Cart/images].	
				 
				 Directory Structure will be like :  - - Music_Cart/
																- - - images/
																		--- spinner.gif

											
3) To use YII-IMAGE-ZOOMER in a page, include the following code in the page:
   
** For Single Image Zoom Include the following code: **

		<?php
		
		 	$this->widget('ext.YiiImageZoomer.YiiImageZoomer',array(
					'multiple_zoom'=>false,
					'imagefolder'=>'images',
					'single_image'=>array(
							'image'=>'millasmall.jpg',
							'image_large'=>'hayden.jpg',
							'image_alt'=>'Hayden',	
							'image_desc'=>'Hayden'

																			
					),
					
					'cursorshade'=>true,
					'cursorshadecolor'=>'#fff',	
					'cursorshadeopacity'=>0.5,
					'cursorshadeborder'=>'2px solid red',
					'imagevertcenter'=>true,
					'magvertcenter'=>true,
					'magnifierpos'=>'right',
					'magnifiersize'=>array(200,200),
					'width'=>200,
					'height'=>300,
					'zoomrange'=>array(3,10),
					'initzoomablefade'=>true,
					'zoomablefade'=>true,
					'speed'=>300,
					'zIndex'=>4,
				    ));
		
		?>

** For Multi-Image Zoom Include the following code: **


		<?php
		
		 	$this->widget('ext.YiiImageZoomer.YiiImageZoomer',array(
					'multiple_zoom'=>true,
					'imagefolder'=>'images',
					'images'=>array(
							array(
							'image'=>'haydensmall.jpg',
							'image_large'=>'hayden.jpg',
							'image_thumb'=>'hayden_tmb.jpg',
							'image_alt'=>'Hayden',	
							'image_desc'=>'Hayden'),

							array(
							'image'=>'millasmall.jpg',
							'image_large'=>'milla.jpg',
							'image_thumb'=>'milla_tmb.jpg',
							'image_alt'=>'Milla',	
							'image_desc'=>'MIlla'),

							array(
							'image'=>'millasmall.jpg',
							'image_large'=>'milla.jpg',
							'image_thumb'=>'milla_tmb.jpg',
							'image_alt'=>'Milla',	
							'image_desc'=>'MIlla'),
							
												
						       ),
					'cursorshade'=>true,
					'cursorshadecolor'=>'#fff',	
					'cursorshadeopacity'=>0.5,
					'cursorshadeborder'=>'2px solid red',
					'imagevertcenter'=>true,
					'magvertcenter'=>true,
					'width'=>200,
					'height'=>300,
					'magnifierpos'=>'left',
					'magnifiersize'=>array(200,200),
					'zoomrange'=>array(3,10),
					'initzoomablefade'=>true,
					'zoomablefade'=>true,
					'speed'=>300,
					'zIndex'=>4,
				));
		
		?>


** About Variuos Parameters that you can customize according to your needs: **

1) Common Parameters between Single Image Zoom and Multi-image Zoom
	
		multiple_zoom= 		@var boolean - used to enable or disable Multi-Image zoom
							when set to 'true' will enable MultiImage zoom
							when set to 'false' will disable MultiImage Zoom which means user wants to use single image zoom
							@default: none

		imagefolder= 		@var string - used to specify the images folder path
							@default : "/images"(By Default it will point to the "images" folder of your yii application )
							Example: It can consists value like "themes/images" which means that the images are inside the "themes/images" folder.
							Note: Make Sure the images folder should not reside inside the protected directory if this is the case, the Yii-Image-Zoomer will not
							be able to access the images.So whatever location your images are located just provide the complete path to the folder in which 
							images are located keeping in mind that folder should not reside in protected directory
				   
		cursorshade=		@var boolean - used to enable or disable cursorshade
							when set to 'true' will enable cursorshade
							when set to 'false' will disable cursorshade
							@default: false
				
				
		cursorshadecolor= 	@var string - used to specify the cursor shade colour
							@default : "#fff" (white color)

		
		cursorshadeopacity= @var decimal - used to specify the cursor shade opacity
							minimum value = 0.1 which is almost transparent.
							maximum value=1 which is fully opaque (as if no opacity is applied).
							@default : 0.1 (almost transparent)

							
		cursorshadeborder=	@var string - used to specify the cursor shade border
							@default: '1px solid black'
							Example: You can set to something like '2px solid red' this will set the border of cursorshade to '2px solid red'. 
		
		
		imagevertcenter=	@var boolean - use this option if you want the image to be vertically centered within it's container
							when set to 'true', the image will centers vertically within its container
							when set to 'false' the image will not centers vertically within its container
							@default: false
							

		magvertcenter=  	@var boolean - use this option if you want the magnified area to be vertically centered in relation to the zoomable image
							when set to 'true',the magnified area centers vertically in relation to the zoomable image
							when set to 'false' the magnified area will not centers vertically in relation to the zoomable image
							@default: false

							
		magnifierpos=		@var string - used to set the position of the magnifying area relative to the original image. 
							when set to "right" ,the position of the magnifying area will be set to right
							when set to "left", the position of the magnifying area will be set to left
							Note: If there's not enough room for it in the window in the desired direction, it will automatically shift to the other direction.
							@default: 'right'
							

		magnifiersize=		@var array - used to set the magnifying area's dimensions in pixels 
							@default: Default is [200, 200] , or 200px wide by 200px tall
							Example: $magnifiersize=array(300,300) will set the magnifying area's dimensions to 300px wide by 300px tall
	
		
    	width=				@var int - this option lets you set the width of the zoomable image 
							@default: undefined (script determines width of the zoomable image)


    	height=				@var int - this option lets you set the height of the zoomable image 
							@default: undefined (script determines height of the zoomable image)

							
		initzoomablefade=	@var boolean - whether or not the zoomable image should fade in automatically when the page loads
							if set to 'true', the zoomable image will fade when the page loads
							if set to 'false', the zoomable image will not fade when the page loads
							Note: See also zoomablefade option. If zoomablefade is set to false, this will also be false.
							If you are using multi-zoom, if zoomablefade is true and this option is set to false, only the first zoomable image will not fade in and rest of the images when loaded will fade in. 
							@default: true
	
		
		zoomablefade=		@var boolean - Sets whether or not the zoomable image within a 
							Image Zoomer should fade in as the page loads and, if this is a multi-zoom,
							when the user switches between zoomable images using the thumbnail links. 
							@default: true
	
		speed=				@var int - sets the duration of fade in for zoomable images (in milliseconds) when zoomablefade is set to true 
							@default: 600

	
	
    	zIndex= 			@var int $zIndex-In most cases the script will determine the optimal z-index to use here, 
							so this property should only be used if there are z-index stacking problems. If there are, 
							use it to set the z-index for created elements. It should be at least as high as the highest
							z-index of the zoomable (midsized) image and, if any its positioned parents	
							@default: script determines the optimal z-index value to use .

		zoomrange=			@var array $zoomrange- used to set the zoom level of the magnified image relative to the original image. 
							The value should be in the form [x, y], where x and y are integers that 
							define the lower(minimum value:3) and upper bounds(maximum value:10) of the zoom level. 
							@default: Default is [3,10]
	


2) Specific Parameters of single image zoom:
	
	All the parameters are same but only one parameter is different in single image zoom as compare to multi-image zoom .It is listed below:
			
			single_image= 	@var array - this is where you specify the image for single image zoom 
							@default: empty array
							* This parameter is an array which further containes Sub-Parameters,they are listed below *
								'image'= It specifies the  specifies name of zoomable image (Example: 'hayden.jpg')
								'image_large'= It specifies the name of the magnified image. 
												   This should be a larger, higher resolution 
												   version of the original image (Example:hayden_large.jpg).
								'image_alt'= It specifies the image alt text	
								'image_desc'= It specifies the image decription to be displayed
								
							Example :  'single_image'=>array(
															'image'=>'millasmall.jpg',
															'image'=>'hayden',
															'image_large'=>'hayden.jpg',
															'image_alt'=>'Hayden',	
															'image_desc'=>'Hayden'				
															)
															

2) Specific Parameters of Multi-image zoom:
	
	All the parameters are same but only one parameter is different in Multi-Image zoom as compare to single image zoom .It is listed below:
		
		images= 		@var array - this is where you specify the image for single image zoom 
							@default: empty array
							* This parameter is an two-dimentional array  which further containes Sub-Parameters,they are listed below *
								'image'= It specifies the  specifies name of zoomable image (Example: 'hayden.jpg')
								'image_large'= It specifies the name of the magnified image. 
												   This should be a larger, higher resolution 
												   version of the original image (Example:hayden_large.jpg).
								'image_thumb'= It specifies the name of the thumb image (Example: hayden_thumb.jpg)
													This should be a smaller, lower resolution 
												   version of the original image (Example:hayden_thumb.jpg).
								'image_alt'= It specifies the image alt text
								'image_desc'= It specifies the image decription to be displayed.
								
							Example: 'images'=>array(
														array(
														'image'=>'haydensmall.jpg',
														'image_large'=>'hayden.jpg',
														'image_thumb'=>'hayden_tmb.jpg',
														'image_alt'=>'Hayden',	
														'image_desc'=>'Hayden'),

														array(
														'image'=>'millasmall.jpg',
														'image_large'=>'milla.jpg',
														'image_thumb'=>'milla_tmb.jpg',
														'image_alt'=>'Milla',	
														'image_desc'=>'MIlla'),

														array(
														'image'=>'millasmall.jpg',
														'image_large'=>'milla.jpg',
														'image_thumb'=>'milla_tmb.jpg',
														'image_alt'=>'Milla',	
														'image_desc'=>'MIlla'),					
													),
				
				








	*********************************** Documentation Of Extension Ends Here*******************************************
