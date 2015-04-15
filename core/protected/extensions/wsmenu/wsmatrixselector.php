<?php

/**
 * Matrix selector
 */
class wsmatrixselector extends CApplicationComponent
{

	const SIZE_COLOR = '1x1x0';
	const SIZE_ONLY = '1x0x0';
	const COLOR_ONLY = '0x1x0';
	const THREE_DIMENSIONAL = '1x1x1';

	/**
	 * Product model passed from view layer call
	 * @var
	 */
	public $model;

	/**
	 * Form model passed from view layer call
	 * @var
	 */
	public $form;

	/**
	 * Dimension Type
	 *
	 * Possible values:
	 * (1x1x0 - size/color)
	 * (1x0x0 - size only)
	 * (0x1x0 - color only)
	 * (1x1x1 - three dimensional array)
	 *
	 * @var
	 */
	protected $dimensionType = self::SIZE_COLOR;

	/**
	 * Attribute 1 (usually size)
	 * @var string
	 */
	public $sizes;

	/**
	 * Attribute 2 (usually colors)
	 * @var string
	 */
	public $colors;

	/**
	 * Attribute 3 (Cloud only)
	 * @var string
	 */
	public $attr3;

	/**
	 * Control style for Size
	 * @var string
	 */
	public $sizeType = "dropdown";

	/**
	 * Control style for Color
	 * @var string
	 */
	public $colorType = "dropdown";

	/**
	 * First selector label
	 * @var string
	 */
	public $firstLabel;

	/**
	 * Second selector label
	 * @var string
	 */
	public $secondLabel;

	/**
	 * Control style for third attriute (LS Cloud 3 attribute product)
	 * @var string
	 */
	public $attr3Type = "dropdown";

	/**
	 * Array of selections, based on availability
	 * @var
	 */
	public $arrSelections;

	public $successFirstSelector;
	public $successSecondSelector;


	/**
	 * Renders the selectors for a matrix product
	 * Since we expect this as part of a Form and using a Product, these must be passed to this extension
	 */
	public function run()
	{

		if(!isset($this->form) || !isset($this->model))
		{
			Yii::log("Extension called without parameters", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return;
		}

		if(!isset($this->sizes))
			$this->sizes = $this->model->Sizes;

		if(!isset($this->colors))
			$this->colors = $this->model->Colors;

		if(!isset($this->firstLabel))
			$this->firstLabel = $this->model->SizeLabel;

		if(!isset($this->secondLabel))
			$this->secondLabel = $this->model->ColorLabel;

		$this->dimensionType = $this->getDimensionType($this->sizes, $this->colors, $this->attr3);

		switch ($this->dimensionType)
		{
			case self::SIZE_COLOR:
				$this->successFirstSelector = $this->createSuccessAttribute1();
				$this->successSecondSelector = $this->createSuccessAddToCart();
				$this->firstSelector();
				$this->secondSelector();
				break;

			case self::SIZE_ONLY:
				$this->successFirstSelector = $this->createSuccessAddToCart();
				$this->firstSelector();
				break;

			case self::COLOR_ONLY:
				$this->successSecondSelector = $this->createSuccessAddToCart();
				$this->secondSelector();
				break;

			default:
				Yii::log("Unknown dimension type", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				break;
		}


	}

	/**
	 * Short Description.
	 *
	 * @return void
	 */
	protected function firstSelector()
	{
		echo $this->form->dropDownList(
			$this->model,
			'product_size',
			$this->sizes,
			array(
				'id' => 'SelectSize',
				'prompt' => Yii::t('global', 'Select {label}...', array('{label}' => $this->model->SizeLabel)),
				'ajax' => array(
					'type' => 'POST',
					'dataType' => 'json',
					'data' => 'js:{"' . 'product_size' . '": $("#SelectSize option:selected").val(),"' . 'id' . '": ' . $this->model->id . '}',
					'url' => $this->dimensionType === self::SIZE_ONLY ? Yii::app()->createUrl('product/getmatrixproduct') : Yii::app()->createUrl('product/getcolors'),
					'success' => $this->successFirstSelector
				))
		);

	}

	/**
	 * Create secondSelector.
	 *
	 * @param null $sizeSelection
	 * @return void
	 */
	protected function secondSelector($sizeSelection = null)
	{
		$model = $this->model;
		$arrColor = array();

		//Because first selector may contain empty string when using 1 dimension
		if(is_null($sizeSelection))
		{
			$sizeSelection = '": $("#SelectSize option:selected").val(),"';
			$arrColor = $this->colors;
		}


		echo $this->form->dropDownList(
			$model,
			'product_color',
			$arrColor,
			array(
				'id' => 'SelectColor',
				'prompt' => Yii::t('global', 'Select {label}...', array('{label}' => $this->secondLabel)),
				'ajax' => array(
					'type' => 'POST',
					'dataType' => 'json',
					'data' => 'js:{"' .
						'product_size' . $sizeSelection .
						'product_color' . '": $("#SelectColor option:selected").val(),"' .
						'id' . '": ' . $model->id .
						'}',
					'url' => Yii::app()->createUrl('product/getmatrixproduct'),
					'success' => $this->successSecondSelector,
				))
		);
	}

	/**
	 * Short Description.
	 *
	 * @return void
	 */
	protected function secondDummy()
	{



	}

	protected function createSuccessAttribute1()
	{
		return 'js:function(data) {
						data.product_colors = "<option value=\'\'>' . Yii::t('global', 'Select {label}...', array('{label}' => $this->model->ColorLabel)) . '</option>" + data.product_colors;
						$("#SelectColor").empty();
						$("#SelectColor").html(data.product_colors);
						$("#WishlistAddForm_size").val($("#SelectSize option:selected").val());
					}';
	}

	protected function createSuccessAddToCart()
	{
		$model = $this->model;
		return 'js:function(data) {
						$("#' . CHtml::activeId($model,'FormattedPrice') . '").html(data.FormattedPrice);
						$("#' . CHtml::activeId($model,'FormattedRegularPrice') . '").html(data.FormattedRegularPrice);
						if (data.FormattedRegularPrice != null) $("#' . CHtml::activeId($model,'FormattedRegularPrice') . '_wrap").show();
							else $("#' . CHtml::activeId($model,'FormattedRegularPrice') . '_wrap").hide();
						$("#' . CHtml::activeId($model,'description_long') . '").html(data.description_long);
						$("#' . CHtml::activeId($model,'description_short') . '").html(data.description_short);
						$("#' . CHtml::activeId($model,'image_id') . '").html(data.image_id);
						$("#' . CHtml::activeId($model,'InventoryDisplay') . '").html(data.InventoryDisplay);
						$("#' . CHtml::activeId($model,'title') . '").html(data.title);
						$("#' . CHtml::activeId($model,'code') . '").html(data.code);
						$("#photos").html(data.photos);
						if($.isFunction(bindZoom)) bindZoom();
						$("#WishlistAddForm_color").val($("#SelectColor option:selected").val());
					}';
	}

	protected function getDimensionType($sizes, $colors, $attr3)
	{
		if (count($sizes) > 1 &&
			count($colors) < 2 &&
			!count($attr3))
			return self::SIZE_ONLY;

		if (count($sizes) < 2 &&
			count($colors) > 1 &&
			!count($attr3))
			return self::COLOR_ONLY;

		if (count($sizes) > 1 &&
			count($colors) > 1 &&
			count($attr3) < 2)
			return self::SIZE_COLOR;

		if (count($sizes) > 1 &&
			count($colors) > 1 &&
			count($attr3) > 1)
			return self::THREE_DIMENSIONAL;

		return self::SIZE_COLOR;

	}
}
