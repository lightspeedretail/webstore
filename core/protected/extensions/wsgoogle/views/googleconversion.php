<?php ?>
<!-- Google Conversion Page -->
<script type="text/javascript">
	/* <![CDATA[ */
	var google_conversion_id = <?= $conversionID ?>;
	var google_conversion_language = "<?= Yii::app()->language ?>";
	var google_conversion_format = "3";
	var google_conversion_color = "ffffff";
	var google_conversion_label = "<?= $conversionLabel ?>";
	var google_conversion_value = <?= $conversionValue ?>;
	var google_conversion_currency = "<?= $currency ?>";
	var google_remarketing_only = false;
	/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
	<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt=""
		     src="//www.googleadservices.com/pagead/conversion/<?= $conversionID ?>/?value=<?= $conversionValue ?>&amp;currency_code=<?= $currency ?>&amp;label=<?= $conversionLabel ?>&amp;guid=ON&amp;script=0"/>
	</div>
</noscript>
<!-- End Google Conversion Page -->
