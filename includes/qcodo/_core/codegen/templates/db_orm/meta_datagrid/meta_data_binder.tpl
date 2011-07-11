/**
		 * Default / simple DataBinder for this Meta DataGrid.  This can easily be overridden
		 * by calling SetDataBinder() on this DataGrid with another DataBinder of your choice.
		 *
		 * If a paginator is set on this DataBinder, it will use it.  If not, then no pagination will be used.
		 * It will also perform any sorting (if applicable).
		 */
		public function MetaDataBinder() {
			// Remember!  We need to first set the TotalItemCount, which will affect the calcuation of LimitClause below
			if ($this->Paginator) {
				$this->TotalItemCount = <%= $objTable->ClassName %>::CountAll();
			}

			// Setup the $objClauses Array
			$objClauses = array();

			// If a column is selected to be sorted, and if that column has a OrderByClause set on it, then let's add
			// the OrderByClause to the $objClauses array
			if ($objClause = $this->OrderByClause)
				array_push($objClauses, $objClause);

			// Add the LimitClause information, as well
			if ($objClause = $this->LimitClause)
				array_push($objClauses, $objClause);

			// Set the DataSource to be a Query result from <%= $objTable->ClassName %>, given the clauses above
			$this->DataSource = <%= $objTable->ClassName %>::LoadAll($objClauses);
		}