				case '<%= $strPropertyName %>Control':
					if (!$this-><%= $strControlId %>) return $this-><%= $strControlId %>_Create();
					return $this-><%= $strControlId %>;
				case '<%= $strPropertyName %>Label':
					if (!$this-><%= $strLabelId %>) return $this-><%= $strLabelId %>_Create();
					return $this-><%= $strLabelId %>;