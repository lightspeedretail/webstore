<?php

/**
 * Register the script required to track page views specific to the analytics
 * version that the store owner has set in the Admin Panel (Universal vs Classic).
 */

class wspageviews extends CWidget
{
	public $isUniversal;

	public function init()
	{
		$this->isUniversal = false;

		if (Yii::app()->params['GOOGLE_UA'] == 1)
		{
			$this->isUniversal = true;
		}
	}

	public function run()
	{
		$pageViewScript = $this->setPageViewScript();

		Yii::app()->clientScript->registerScript(
			'google pageview analytics',
			$pageViewScript,
			CClientScript::POS_HEAD
		);
	}


	/**
	 * https://developers.google.com/analytics/devguides/collection/analyticsjs/index#quickstart
	 * https://developers.google.com/analytics/devguides/collection/gajs/#quickstart
	 *
	 * @return string
	 */
	protected function setPageViewScript()
	{
		if ($this->isUniversal)
		{
			return sprintf(
				"(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

				ga('create', '%s', 'auto');
				ga('send', 'pageview');

				",
				Yii::app()->params['GOOGLE_ANALYTICS']
			);
		}

		return sprintf(
			"(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();

				var _gaq = _gaq || [];
				_gaq.push(['_setAccount', '%s']);
				_gaq.push(['_setDomainName', '%s']);
				_gaq.push(['_trackPageview']);

				",
			Yii::app()->params['GOOGLE_ANALYTICS'],
			$_SERVER['SERVER_NAME']
		);
	}
}