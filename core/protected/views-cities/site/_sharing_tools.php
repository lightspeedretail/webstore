<div id="sharingtools">
    <div id="pinterest">
    <a href="http://pinterest.com/pin/create/button/?url=<?= $this->getCanonicalUrl(); ?>&media=<?=
		_xls_site_url($product->SmallImage,true); ?>&description=<?= urlencode($product->Title); ?>"
           class="pin-it-button" count-layout="none"><img border="0" src="//assets.pinterest.com/images/PinExt.png"
           title="Pin It"/></a></div>
    <div class="g-plusone" data-size="medium" data-annotation="none" data-width="50"></div>
    <?php if (_xls_facebook_login()): ?><script>(function (d) {
		var js, id = 'facebook-jssdk';
		if (d.getElementById(id)) {
			return;
		}
		js = d.createElement('script');
		js.id = id;
		js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1<?= "&appId="._xls_get_conf('FACEBOOK_APPID'); ?>";
		d.getElementsByTagName('head')[0].appendChild(js);
	}(document));</script>
    <div class="fb-like" data-href="<?= $this->getCanonicalUrl(); ?>" data-send="false" data-layout="button_count"
	     data-width="90" data-show-faces="false" style="vertical-align:top;zoom:1;*display:inline"></div><?php endif; ?>
    <a href="https://twitter.com/share" class="twitter-share-button" data-size="small">Tweet</a>
 <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

</div>