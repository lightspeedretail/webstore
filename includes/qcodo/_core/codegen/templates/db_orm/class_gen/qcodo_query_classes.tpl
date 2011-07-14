/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

<% foreach ($objTable->ManyToManyReferenceArray as $objReference) { %>
	class QQNode<%= $objTable->ClassName %><%= $objReference->ObjectDescription %> extends QQAssociationNode {
		protected $strType = 'association';
		protected $strName = '<%= strtolower($objReference->ObjectDescription); %>';

		protected $strTableName = '<%= $objReference->Table %>';
		protected $strPrimaryKey = '<%= $objReference->Column %>';
		protected $strClassName = '<%= $objReference->VariableType %>';

		public function __get($strName) {
			switch ($strName) {
				case '<%= $objReference->OppositePropertyName %>':
					return new QQNode('<%= $objReference->OppositeColumn %>', '<%= $objReference->OppositePropertyName %>', '<%= $objReference->OppositeVariableType %>', $this);
				case '<%= $objReference->VariableType %>':
					return new QQNode<%=$objReference->VariableType %>('<%= $objReference->OppositeColumn %>', '<%= $objReference->OppositePropertyName %>', '<%= $objReference->OppositeVariableType %>', $this);
				case '_ChildTableNode':
					return new QQNode<%=$objReference->VariableType %>('<%= $objReference->OppositeColumn %>', '<%= $objReference->OppositePropertyName %>', '<%= $objReference->OppositeVariableType %>', $this);
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

<% } %>
	class QQNode<%= $objTable->ClassName %> extends QQNode {
		protected $strTableName = '<%= $objTable->Name %>';
		protected $strPrimaryKey = '<%= $objTable->PrimaryKeyColumnArray[0]->Name %>';
		protected $strClassName = '<%= $objTable->ClassName %>';
		public function __get($strName) {
			switch ($strName) {
<% foreach ($objTable->ColumnArray as $objColumn) { %>
				case '<%= $objColumn->PropertyName %>':
					return new QQNode('<%= $objColumn->Name %>', '<%= $objColumn->PropertyName %>', '<%= $objColumn->VariableType %>', $this);
	<% if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { %>
				case '<%= $objColumn->Reference->PropertyName %>':
					return new QQNode<%= $objColumn->Reference->VariableType; %>('<%= $objColumn->Name %>', '<%= $objColumn->Reference->PropertyName %>', '<%= $objColumn->VariableType %>', $this);
	<% } %>
<% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objReference) { %>
				case '<%= $objReference->ObjectDescription %>':
					return new QQNode<%= $objTable->ClassName %><%= $objReference->ObjectDescription %>($this);
<% } %><% foreach ($objTable->ReverseReferenceArray as $objReference) { %>
				case '<%= $objReference->ObjectDescription %>':
					return new QQReverseReferenceNode<%= $objReference->VariableType %>($this, '<%= strtolower($objReference->ObjectDescription); %>', 'reverse_reference', '<%= $objReference->Column %>'<%= ($objReference->Unique) ? ", '" . $objReference->ObjectDescription . "'" : null; %>);
<% } %><% $objPkColumn = $objTable->PrimaryKeyColumnArray[0]; %>
				case '_PrimaryKeyNode':
					return new QQNode<% if (($objPkColumn->Reference) && (!$objPkColumn->Reference->IsType)) return $objPkColumn->Reference->VariableType; %>('<%= $objPkColumn->Name %>', '<%= $objPkColumn->PropertyName %>', '<%= $objPkColumn->VariableType %>', $this);
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

	class QQReverseReferenceNode<%= $objTable->ClassName %> extends QQReverseReferenceNode {
		protected $strTableName = '<%= $objTable->Name %>';
		protected $strPrimaryKey = '<%= $objTable->PrimaryKeyColumnArray[0]->Name %>';
		protected $strClassName = '<%= $objTable->ClassName %>';
		public function __get($strName) {
			switch ($strName) {
<% foreach ($objTable->ColumnArray as $objColumn) { %>
				case '<%= $objColumn->PropertyName %>':
					return new QQNode('<%= $objColumn->Name %>', '<%= $objColumn->PropertyName %>', '<%= $objColumn->VariableType %>', $this);
	<% if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { %>
				case '<%= $objColumn->Reference->PropertyName %>':
					return new QQNode<%= $objColumn->Reference->VariableType; %>('<%= $objColumn->Name %>', '<%= $objColumn->Reference->PropertyName %>', '<%= $objColumn->VariableType %>', $this);
	<% } %>
<% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objReference) { %>
				case '<%= $objReference->ObjectDescription %>':
					return new QQNode<%= $objTable->ClassName %><%= $objReference->ObjectDescription %>($this);
<% } %><% foreach ($objTable->ReverseReferenceArray as $objReference) { %>
				case '<%= $objReference->ObjectDescription %>':
					return new QQReverseReferenceNode<%= $objReference->VariableType %>($this, '<%= strtolower($objReference->ObjectDescription); %>', 'reverse_reference', '<%= $objReference->Column %>'<%= ($objReference->Unique) ? ", '" . $objReference->ObjectDescription . "'" : null; %>);
<% } %><% $objPkColumn = $objTable->PrimaryKeyColumnArray[0]; %>
				case '_PrimaryKeyNode':
					return new QQNode<% if (($objPkColumn->Reference) && (!$objPkColumn->Reference->IsType)) return $objPkColumn->Reference->VariableType; %>('<%= $objPkColumn->Name %>', '<%= $objPkColumn->PropertyName %>', '<%= $objPkColumn->VariableType %>', $this);
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}
