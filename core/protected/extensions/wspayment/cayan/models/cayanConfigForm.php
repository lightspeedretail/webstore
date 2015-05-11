<?php


/**
 * We use this class to handle the customizable options that Cayan offers store owners.
 * It allows us to easily offer these options through a popup modal rather than display them
 * all to the user on the traditional admin panel page with the other required attributes.
 *
 */

class cayanConfigForm extends CFormModel
{

	/**
	 * Background color of the form's main div container
	 */
	public $colorContainerBackground;

	/**
	 * Border color of the form's main div container
	 */
	public $colorContainerBorder;

	/**
	 * Background color of the div that contains the logo.
	 * The div is not rendered in the absence of a logo URL.
	 */
	public $colorLogoBackground;

	/**
	 * Border color of the div that contains the logo.
	 * The div is not rendered in the absence of a logo URL.
	 */
	public $colorLogoBorder;

	/**
	 * Border color of the form's text input elements
	 */
	public $colorTextBoxBorder;

	/**
	 * Border color of the form's text input element on focus of the input
	 */
	public $colorTextBoxBorderFocus;

	/**
	 * The Card number input field, by default, is a password field.
	 * So the input is 'masked'. Instead of making this ludicrous
	 * option the default, we do the opposite and offer store owners
	 * the option of enabling the mask.
	 */
	public $maskCardNumber;

	/**
	 * Boolean to show or hide the green box with the message
	 * "Enter your credit card information...".
	 */
	public $hideInstructions;

	/**
	 * There is a message on the form that says "Not entering the street address
	 * or zip code may result in additional fees". This is the boolean to toggle
	 * that message on or off.
	 */
	public $hideDowngradeMessage;

	public function rules()
	{
		return array(
			array('colorContainerBackground, colorContainerBorder, colorLogoBackground, colorLogoBorder,
			colorTextBoxBorder, colorTextBoxBorderFocus, maskCardNumber, hideInstructions, hideDowngradeMessage', 'safe'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'colorContainerBackground' => 'Container Background color',
			'colorContainerBorder' => 'Container Border color',
			'colorLogoBackground' => 'Logo Background color',
			'colorLogoBorder' => 'Logo Border color',
			'colorTextBoxBorder' => 'Textbox Border color',
			'colorTextBoxBorderFocus' => 'Textbox Border on focus color',
			'maskCardNumber' => 'Mask the Card Number input',
			'hideInstructions' => 'Hide the green Instructions message',
			'hideDowngradeMessage' => 'Hide the "additional fees" message',
		);
	}

	public function getAdminForm()
	{
		return array(
			'elements' => array(
				'colorContainerBackground' => array(
					'type' => 'text',
				),
				'colorContainerBorder' => array(
					'type' => 'text',
				),
				'colorLogoBackground' => array(
					'type' => 'text',
				),
				'colorLogoBorder' => array(
					'type' => 'text',
				),
				'colorTextBoxBorder' => array(
					'type' => 'text',
				),
				'colorTextBoxBorderFocus' => array(
					'type' => 'text',
				),
				'maskCardNumber' => array(
					'type' => 'checkbox',
				),
				'hideInstructions' => array(
					'type' => 'checkbox',
				),
				'hideDowngradeMessage' => array(
					'type' => 'checkbox',
				),
			)
		);
	}

}