<?php if(_xls_facebook_login() && _xls_get_conf('FACEBOOK_CHECKOUT')): ?>
<div class="row-fluid">
	<div id='fb-root'></div>
	<script src='http://connect.facebook.net/en_US/all.js'></script>

	<div class="span10 clearfix facebook_wall">
		<div class="row-fluid">
			<div class="span2"><?php echo CHtml::image(Yii::app()->baseUrl."/images/facebook.png","Facebook"); ?></div>
			<div class="span10"><h4><?= Yii::t('facebook',_xls_get_conf('FACEBOOK_WALL_PUBLISH')) ?></h4>
				<?php echo CHtml::textArea('msg',Yii::t('facebook',_xls_get_conf('FACEBOOK_WALL_CAPTION'),
				array("{storename}"=>_xls_get_conf('STORE_NAME')))); ?></div>
		</div>
		<div class="row-fluid">
			<div id="submitSpinner" class="span3" style="display:none">
				<?php echo CHtml::image(Yii::app()->getBaseUrl(true).'/images/wait_animated.gif')?>
			</div>
			<div class="span5 pull-right">
				<?php echo CHtml::Button (
					Yii::t('facebook',_xls_get_conf('FACEBOOK_WALL_PUBLISH')),
					array(
						'onclick'=>'js:submitPublish()',
					), array('id'=>'btnPost'));
				?>
			</div>
		</div>
	</div>
	<p id='msg'></p>

	<script>
		function submitPublish()
		{
			$("#submitSpinner").show();
			FB.init({appId: "<?php echo _xls_get_conf('FACEBOOK_APPID') ?>", status: true, cookie: true});

			FB.login(function(response) {
				if (response.authResponse) {
					PublishToWall();
				} else {
					$("#submitSpinner").hide();
					alert('Not logged in');
				}
			});
		}

		function PublishToWall()
		{

			var publish = {
				method: 'stream.publish',
				message: '',
				picture : '<?php echo CController::createAbsoluteUrl(_xls_get_conf('HEADER_IMAGE')); ?>',
				link : '<?php echo CController::createAbsoluteUrl("/"); ?>',
				name: '<?php echo _xls_get_conf('STORE_NAME') ?>',
				caption: $("#msg").val(),
				description: "",
				actions : { name : '<?php echo _xls_get_conf('STORE_NAME') ?>', link : '<?php echo CController::createAbsoluteUrl("/"); ?>'}
			};
			FB.api('/me/feed', 'POST', publish, function(response) {
				$("#submitSpinner").hide();
				if (!response || response.error) {
					alert('Error occured');
				} else {
					alert('<?php echo Yii::t('facebook','Thank you for supporting {storename}',
						array("{storename}"=>_xls_get_conf('STORE_NAME'))) ?>');
				}
			});
		}



	</script>

</div>
<?php endif; ?>