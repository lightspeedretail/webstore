			if ($this-><%= $strControlId %>) {
				$this-><%= $strControlId %>->RemoveAllItems();
				$objAssociatedArray = $this-><%= $strObjectName %>->Get<%= $objManyToManyReference->ObjectDescription; %>Array();
				$<%= $objManyToManyReference->VariableName %>Array = <%= $objManyToManyReference->VariableType %>::LoadAll();
				if ($<%= $objManyToManyReference->VariableName %>Array) foreach ($<%= $objManyToManyReference->VariableName %>Array as $<%= $objManyToManyReference->VariableName %>) {
					$objListItem = new QListItem($<%= $objManyToManyReference->VariableName %>->__toString(), $<%= $objManyToManyReference->VariableName %>-><%= $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName %>);
					foreach ($objAssociatedArray as $objAssociated) {
						if ($objAssociated-><%= $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName %> == $<%= $objManyToManyReference->VariableName %>-><%= $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName %>)
							$objListItem->Selected = true;
					}
					$this-><%=$strControlId %>->AddItem($objListItem);
				}
			}
			if ($this-><%= $strLabelId %>) {
				$objAssociatedArray = $this-><%= $strObjectName %>->Get<%= $objManyToManyReference->ObjectDescription; %>Array();
				$strItems = array();
				foreach ($objAssociatedArray as $objAssociated)
					$strItems[] = $objAssociated->__toString();
				$this-><%= $strLabelId %>->Text = implode($strGlue, $strItems);
			}