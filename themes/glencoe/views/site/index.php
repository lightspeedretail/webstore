<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<?php if (Yii::app()->params['LIGHTSPEED_MT']==0): ?>
	<p>You may change the content of this page by modifying :</p>
<?php endif; ?>
<ul>
	<li>View file: <tt><?php echo __FILE__; ?></tt></li>

</ul>