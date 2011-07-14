		protected function <%= $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); %>_Update() {
			if ($this-><%= $strControlId %>) {
				$this-><%= $strObjectName %>->UnassociateAll<%= $objManyToManyReference->ObjectDescriptionPlural %>();
				$objSelectedListItems = $this-><%= $strControlId %>->SelectedItems;
				if ($objSelectedListItems) foreach ($objSelectedListItems as $objListItem) {
					$this-><%= $strObjectName %>->Associate<%= $objManyToManyReference->ObjectDescription %>(<%= $objManyToManyReference->VariableType %>::Load($objListItem->Value));
				}
			}
		}
