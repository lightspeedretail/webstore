<?php

/**
 * Class ProductGrid
 * This class generates the array needed to display web store products in a
 * grid. This class also contains the pagination information for customers to
 * navigated products displayed. The product grid is based on certain criteria
 * applied against the xlsws_product table.
 */
class ProductGrid
{
	/**
	 * This holds the criteria that the grid needs
	 * to display itself. This criteria runs against
	 * the product model and the pagination object.
	 *
	 * @var CDbCriteria
	 */
	private $_productGridCriteria;

	/**
	 * This variable holds the pagination information
	 * for the grid. The criteria is passed to it to know
	 * how many pages are needed to display all the products.
	 *
	 * @var CPagination
	 */
	private $_pages;

	/**
	 * This contains a subset of the products. Only the products
	 * in the current page will be in that array.
	 *
	 * @var Product[]
	 */
	private $_productsGrid;

	/**
	 * Holds the number of products that the entire grid holds.
	 * This is a count of all the products based on the criteria.
	 *
	 * @var int
	 */
	private $_numberOfRecords;

	/**
	 * Based on a criteria, this will generate the products
	 * grid to be displayed on the page.
	 * @param CDbCriteria $criteria A criteria object that determines
	 * which products to get from the DB.
	 */
	public function __construct($criteria)
	{
		if ($criteria instanceof CDbCriteria === false)
		{
			$criteria = new CDbCriteria();
		}

		$this->_productGridCriteria = $criteria;
		$this->generateProductGrid();
	}

	/**
	 * This function generates the products grid
	 * based on the criteria. It also generates the
	 * pagination object need for the users to navigate
	 * through products.
	 *
	 * @return void
	 */
	public function generateProductGrid()
	{
		$this->_numberOfRecords = Product::model()->count(
			$this->_productGridCriteria
		);

		$this->_pages = new CPagination($this->_numberOfRecords);
		$this->_pages->setPageSize(
			CPropertyValue::ensureInteger(Yii::app()->params['PRODUCTS_PER_PAGE'])
		);
		$this->_pages->applyLimit($this->_productGridCriteria);

		$this->_productsGrid = self::createBookends(
			Product::model()->findAll($this->_productGridCriteria)
		);
	}

	/**
	 * This function returns a CPagination object needed to display
	 * pagination for the products grid
	 *
	 * @return CPagination
	 */
	public function getPages()
	{
		return $this->_pages;
	}

	/**
	 * This function returns an object containing the product
	 * grid.
	 *
	 * @return mixed
	 */
	public function getProductGrid()
	{
		return $this->_productsGrid;
	}

	/**
	 * This function returns the number of products return by the query.
	 * This count is based on the product grid criteria.
	 *
	 * @return int
	 */
	public function getNumberOfRecords()
	{
		return $this->_numberOfRecords;
	}

	/**
	 * Cycle through Product model for page and mark beginning and end of each row.
	 *
	 * Used for <div row> formatting in the view layer.
	 *
	 * @param $model
	 * @return mixed
	 */
	public static function createBookends($objProducts)
	{
		$gridProductsPerRow = _xls_get_conf('PRODUCTS_PER_ROW', 3);
		if (count($objProducts) == 0 || Yii::app()->theme->config->disableGridRowDivs)
		{
			return $objProducts;
		}

		foreach ($objProducts as $idx => $item)
		{
			switch ($idx % $gridProductsPerRow)
			{
				case 0:
					$objProducts[$idx]->rowBookendFront = true;
					break;
				case $gridProductsPerRow - 1:
					$objProducts[$idx]->rowBookendBack = true;
					break;
			}
		}

		end($objProducts)->rowBookendBack = true; //Last item must always close div
		return $objProducts;
	}
}