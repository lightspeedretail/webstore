<?php if (_xls_facebook_login()): ?><div id="fb-root"></div>
<script>(function (d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s);
	js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1<?= "&appId="._xls_get_conf('FACEBOOK_APPID'); ?>";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php endif; ?>