<?php

/**
 * ShippingRestrictionForm class.
 * For setting free shipping restrictions (based on our existing Restriction form
 */
class ShippingRestrictionForm extends RestrictionForm
{

	public function getExceptionList()
	{

		return array(
			0=>'ALL cart products match any of the following criteria',
			2=>'at least ONE cart product matches any of these criteria',
			1=>'all cart products DO NOT match any of these criteria'
		);

	}

}
