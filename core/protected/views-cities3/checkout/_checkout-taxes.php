<?php
if ($selectedCartScenario === null)
{
	$estimatedTax1 = _xls_currency($cart->tax1);
	$estimatedTax2 = _xls_currency($cart->tax2);
	$estimatedTax3 = _xls_currency($cart->tax3);
	$estimatedTax4 = _xls_currency($cart->tax4);
	$estimatedTax5 = _xls_currency($cart->tax5);
} else {
	$estimatedTax1 = $selectedCartScenario['formattedCartTax1'];
	$estimatedTax2 = $selectedCartScenario['formattedCartTax2'];
	$estimatedTax3 = $selectedCartScenario['formattedCartTax3'];
	$estimatedTax4 = $selectedCartScenario['formattedCartTax4'];
	$estimatedTax5 = $selectedCartScenario['formattedCartTax5'];
}

if ($cart->tax_total > 0)
{
	if ($cart->tax1 > 0)
	{
		echo CHtml::tag(
			'tr',
			array(),
			CHtml::tag(
				'th',
				$confirmation ? array('colspan' => 3) : array(),
				$cart->tax1Name
			).
			CHtml::tag('td', array('class' => 'tax1-estimate', 'id' => 'cartTax1'), $estimatedTax1)
		);
	}

	if ($cart->tax2 > 0)
	{
		echo CHtml::tag(
			'tr',
			array(),
			CHtml::tag(
				'th',
				$confirmation ? array('colspan' => 3) : array(),
				$cart->tax2Name
			).
			CHtml::tag('td', array('class' => 'tax2-estimate', 'id' => 'cartTax2'), $estimatedTax2)
		);
	}

	if ($cart->tax3 > 0)
	{
		echo CHtml::tag(
			'tr',
			array(),
			CHtml::tag(
				'th',
				$confirmation ? array('colspan' => 3) : array(),
				$cart->tax3Name
			).
			CHtml::tag('td', array('class' => 'tax3-estimate', 'id' => 'cartTax3'), $estimatedTax3)
		);
	}

	if ($cart->tax4 > 0)
	{
		echo CHtml::tag(
			'tr',
			array(),
			CHtml::tag(
				'th',
				$confirmation ? array('colspan' => 3) : array(),
				$cart->tax4Name
			).
			CHtml::tag('td', array('class' => 'tax4-estimate', 'id' => 'cartTax4'), $estimatedTax4)
		);
	}

	if ($cart->tax5 > 0)
	{
		echo CHtml::tag(
			'tr',
			array(),
			CHtml::tag(
				'th',
				$confirmation ? array('colspan' => 3) : array(),
				$cart->tax5Name
			).
			CHtml::tag('td', array('class' => 'tax5-estimate', 'id' => 'cartTax5'), $estimatedTax5)
		);
	}
}