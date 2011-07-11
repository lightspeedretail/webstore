			if ($this-><%= $strControlId %>) {
					$this-><%=$strControlId %>->RemoveAllItems();
<% if ($objColumn->NotNull) { %>
				if (!$this->blnEditMode)
					$this-><%=$strControlId %>->AddItem(QApplication::Translate('- Select One -'), null);
<% } %><% if (!$objColumn->NotNull) { %>
				$this-><%=$strControlId %>->AddItem(QApplication::Translate('- Select One -'), null);
<% } %>
				$<%= $objColumn->Reference->VariableName %>Array = <%= $objColumn->Reference->VariableType %>::LoadAll();
				if ($<%= $objColumn->Reference->VariableName %>Array) foreach ($<%= $objColumn->Reference->VariableName %>Array as $<%= $objColumn->Reference->VariableName %>) {
					$objListItem = new QListItem($<%= $objColumn->Reference->VariableName %>->__toString(), $<%= $objColumn->Reference->VariableName %>-><%= $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName %>);
					if (($this-><%= $strObjectName %>-><%= $objColumn->Reference->PropertyName %>) && ($this-><%= $strObjectName %>-><%= $objColumn->Reference->PropertyName %>-><%= $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName %> == $<%= $objColumn->Reference->VariableName %>-><%= $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName %>))
						$objListItem->Selected = true;
					$this-><%=$strControlId %>->AddItem($objListItem);
				}
			}
			if ($this-><%= $strLabelId %>) $this-><%= $strLabelId %>->Text = ($this-><%= $strObjectName %>-><%= $objColumn->Reference->PropertyName %>) ? $this-><%= $strObjectName %>-><%= $objColumn->Reference->PropertyName %>->__toString() : null;