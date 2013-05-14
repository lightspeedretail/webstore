<?php

/**
 * This is the model class for table "{{document_item}}".
 *
 * @package application.models
 * @name DocumentItem
 *
 */
class DocumentItem extends BaseDocumentItem
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return DocumentItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		if ($this->isNewRecord)
			$this->datetime_added = new CDbExpression('NOW()');
		$this->datetime_mod = new CDbExpression('NOW()');


		return parent::beforeValidate();
	}


		public function __get($strName) {
			switch ($strName) {

				case 'Discounted':
					return $this->product->IsDiscounted();

				case 'Price':
					return $this->product->GetPriceValue();

				case 'link':
				case 'Link':
					return $this->product->link;
				default:
					return parent::__get($strName);
			}
		}
}