<?php

/**
 * For display of the tooltip that allows the user to select their shipping
 * option.
 */
class WsShippingEstimatorTooltip extends CWidget
{
	/**
	* @var string CSS class for the shipping estimator on the page. Using this
	* can minimise the chance of accidentally modifying other page elements and
	* allows more than one shipping estimator on the page.
	*/
	public $cssClass = null;

	/**
	 * Run the widget. Renders the shipping estimator tooltip.
	 */
	public function run()
	{
		$assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets', false, -1, true);

		$this->render(
			'_shippingestimatortooltip',
			array(
				'cssClass' => WsShippingEstimator::CSS_CLASS
			)
		);
	}
}
