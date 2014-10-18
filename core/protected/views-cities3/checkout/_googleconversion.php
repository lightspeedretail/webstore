<?php
//We use PHP here to "build" our javascript
?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?= _xls_get_conf('GOOGLE_ANALYTICS')?>']);
  _gaq.push(['_trackPageview']);

	 _gaq.push(['_addTrans',
	    '<?= $model->id_str?>',           // order ID - required
	    '<?= _xls_jssafe_name(_xls_get_conf('STORE_NAME','')) ?>',  // affiliation or store name
	    '<?= $model->total ?>',          // total - required
	    '<?= $model->TaxTotal ?>',           // tax
	    '<?= $model->shipping->shipping_sell ?>',              // shipping
	    '<?= _xls_jssafe_name($model->shipaddress->city) ?>',       // city
	    '<?= $model->shipaddress->state ?>',     // state or province
	    '<?= $model->shipaddress->country ?>'             // country
	  ]);

	  _gaq.push(['_trackTrans']); //submits transaction to the Analytics servers



	<?php foreach ($model->cartItems as $item): ?>
        _gaq.push(['_addItem',
		'<?=$model->id_str?>',           // order ID - required
		'<?=_xls_jssafe_name($item->code)?>',           // SKU/code - required
		'<?=_xls_jssafe_name($item->description)?>',        // product name
		'<?=_xls_jssafe_name($item->product->Class)?>',   // category or variation
		'<?=($item->sell-$item->discount)?>',          // unit price - required
		'<?=$item->qty?>'               // quantity - required
		]);

	<?php endforeach; ?>


  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

<?php if (_xls_get_conf('GOOGLE_ADWORDS','') != ''): ?>
		<script type="text/javascript">

	    /* <![CDATA[ */
	    var google_conversion_id = <?= _xls_get_conf('GOOGLE_ADWORDS','0'); ?>
	    var google_conversion_language = "<?=  Yii::app()->language ?>";
	    var google_conversion_format = "3";
	    var google_conversion_color = "ffffff";
	    var google_conversion_label = "purchase";
	    var google_conversion_value = <?= $model->subtotal ?>
	    /* ]]> */
	</script>
	<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
    </script>
	<noscript>
        <div style="display:inline;">
            <img height="1" width="1" style="border-style:none;" alt=""
                 src="http://www.googleadservices.com/pagead/conversion/<?= _xls_get_conf('GOOGLE_ADWORDS','0'); ?>/?value=<?= $model->subtotal ?>&amp;label=purchase&amp;guid=ON&amp;script=0"/>
        </div>
    </noscript>

<?php endif;