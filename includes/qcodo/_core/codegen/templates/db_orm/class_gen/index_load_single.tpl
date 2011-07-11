	<% $objColumnArray = $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray); %>
		/**
		 * Load a single <%= $objTable->ClassName %> object,
		 * by <%= $objCodeGen->ImplodeObjectArray(', ', '', '', 'PropertyName', $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray)) %> Index(es)
<% foreach ($objColumnArray as $objColumn) { %> 
		 * @param <%= $objColumn->VariableType %> $<%= $objColumn->VariableName %>
<% } %>
		 * @return <%= $objTable->ClassName %>
		*/
		public static function LoadBy<%= $objCodeGen->ImplodeObjectArray('', '', '', 'PropertyName', $objColumnArray); %>(<%= $objCodeGen->ParameterListFromColumnArray($objColumnArray); %>) {
			return <%= $objTable->ClassName %>::QuerySingle(
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