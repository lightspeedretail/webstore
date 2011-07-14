	<% $objColumnArray = $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray); %>
		/**
		 * Load an array of <%= $objTable->ClassName %> objects,
		 * by <%= $objCodeGen->ImplodeObjectArray(', ', '', '', 'PropertyName', $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray)) %> Index(es)
<% foreach ($objColumnArray as $objColumn) { %> 
		 * @param <%= $objColumn->VariableType %> $<%= $objColumn->VariableName %>
<% } %>
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return <%= $objTable->ClassName %>[]
		*/
		public static function LoadArrayBy<%= $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objColumnArray); %>(<%= $objCodeGen->ParameterListFromColumnArray($objColumnArray); %>, $objOptionalClauses = null) {
			// Call <%= $objTable->ClassName %>::QueryArray to perform the LoadArrayBy<%= $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objColumnArray); %> query
			try {
				return <%= $objTable->ClassName; %>::QueryArray(
<% if (count($objColumnArray) > 1) { %>
					QQ::AndCondition(
<% } %>
<% foreach ($objColumnArray as $objColumn) { %>
					QQ::Equal(QQN::<%= $objTable->ClassName %>()-><%= $objColumn->PropertyName %>, $<%= $objColumn->VariableName %>),
<% } %><%--%>
<% if (count($objColumnArray) > 1) { %>
					)
<% } %><%-%>,
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count <%= $objTable->ClassNamePlural %>
		 * by <%= $objCodeGen->ImplodeObjectArray(', ', '', '', 'PropertyName', $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray)) %> Index(es)
<% foreach ($objColumnArray as $objColumn) { %> 
		 * @param <%= $objColumn->VariableType %> $<%= $objColumn->VariableName %>
<% } %>
		 * @return int
		*/
		public static function CountBy<%= $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objColumnArray); %>(<%= $objCodeGen->ParameterListFromColumnArray($objColumnArray); %>) {
			// Call <%= $objTable->ClassName %>::QueryCount to perform the CountBy<%= $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objColumnArray); %> query
			return <%= $objTable->ClassName %>::QueryCount(
<% if (count($objColumnArray) > 1) { %>
				QQ::AndCondition(
<% } %>
<% foreach ($objColumnArray as $objColumn) { %>
				QQ::Equal(QQN::<%= $objTable->ClassName %>()-><%= $objColumn->PropertyName %>, $<%= $objColumn->VariableName %>),
<% } %><%--%>
<% if (count($objColumnArray) > 1) { %>
				)
<% } %>
			);
		}