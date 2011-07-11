///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
<% foreach ($objTable->ColumnArray as $objColumn) { %>
		/**
		 * Protected member variable that maps to the database <% if ($objColumn->PrimaryKey) return 'PK '; %><% if ($objColumn->Identity) return 'Identity '; %>column <%= $objTable->Name %>.<%= $objColumn->Name %>
		 * @var <%= $objColumn->VariableType %> <%= $objColumn->VariableName %>
		 */
		protected $<%= $objColumn->VariableName %>;
	<% if (($objColumn->VariableType == QType::String) && (is_numeric($objColumn->Length))) { %>
		const <%= $objColumn->PropertyName %>MaxLength = <%= $objColumn->Length %>;
	<% } %>
		const <%= $objColumn->PropertyName %>Default = <%
	if (is_null($objColumn->Default))
		return 'null';
	else if (is_numeric($objColumn->Default))
		return $objColumn->Default;
	else
		return "'" . addslashes($objColumn->Default) . "'";
%>;

	<% if ((!$objColumn->Identity) && ($objColumn->PrimaryKey)) { %>

		/**
		 * Protected internal member variable that stores the original version of the PK column value (if restored)
		 * Used by Save() to update a PK column during UPDATE
		 * @var <%= $objColumn->VariableType %> __<%= $objColumn->VariableName %>;
		 */
		protected $__<%= $objColumn->VariableName %>;
	<% } %>

<% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objReference) { %>
		/**
		 * Private member variable that stores a reference to a single <%= $objReference->ObjectDescription %> object
		 * (of type <%= $objReference->VariableType %>), if this <%= $objTable->ClassName %> object was restored with
		 * an expansion on the <%= $objReference->Table %> association table.
		 * @var <%= $objReference->VariableType %> _obj<%=$objReference->ObjectDescription %>;
		 */
		private $_obj<%=$objReference->ObjectDescription %>;

		/**
		 * Private member variable that stores a reference to an array of <%= $objReference->ObjectDescription %> objects
		 * (of type <%= $objReference->VariableType %>[]), if this <%= $objTable->ClassName %> object was restored with
		 * an ExpandAsArray on the <%= $objReference->Table %> association table.
		 * @var <%= $objReference->VariableType %>[] _obj<%=$objReference->ObjectDescription %>Array;
		 */
		private $_obj<%=$objReference->ObjectDescription %>Array = array();

<% } %>
<% foreach ($objTable->ReverseReferenceArray as $objReference) { %><% if (!$objReference->Unique) { %>
		/**
		 * Private member variable that stores a reference to a single <%= $objReference->ObjectDescription %> object
		 * (of type <%= $objReference->VariableType %>), if this <%= $objTable->ClassName %> object was restored with
		 * an expansion on the <%= $objReference->Table %> association table.
		 * @var <%= $objReference->VariableType %> _obj<%=$objReference->ObjectDescription %>;
		 */
		private $_obj<%=$objReference->ObjectDescription %>;

		/**
		 * Private member variable that stores a reference to an array of <%= $objReference->ObjectDescription %> objects
		 * (of type <%= $objReference->VariableType %>[]), if this <%= $objTable->ClassName %> object was restored with
		 * an ExpandAsArray on the <%= $objReference->Table %> association table.
		 * @var <%= $objReference->VariableType %>[] _obj<%=$objReference->ObjectDescription %>Array;
		 */
		private $_obj<%=$objReference->ObjectDescription %>Array = array();

<% } %><% } %>
		/**
		 * Protected array of virtual attributes for this object (e.g. extra/other calculated and/or non-object bound
		 * columns from the run-time database query result for this object).  Used by InstantiateDbRow and
		 * GetVirtualAttribute.
		 * @var string[] $__strVirtualAttributeArray
		 */
		protected $__strVirtualAttributeArray = array();

		/**
		 * Protected internal member variable that specifies whether or not this object is Restored from the database.
		 * Used by Save() to determine if Save() should perform a db UPDATE or INSERT.
		 * @var bool __blnRestored;
		 */
		protected $__blnRestored;
