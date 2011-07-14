<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<%= __DATAGEN_CLASSES__ %>" TargetFileName="<%= $objTable->ClassName %>Gen.class.php"/>
<?php
	/**
	 * The abstract <%= $objTable->ClassName %>Gen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the <%= $objTable->ClassName %> subclass which
	 * extends this <%= $objTable->ClassName %>Gen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the <%= $objTable->ClassName %> class.
	 * 
	 * @package <%= QCodeGen::$ApplicationName; %>
	 * @subpackage GeneratedDataObjects
<%@ property_comments('objTable'); %>
	 */
	class <%= $objTable->ClassName %>Gen extends QBaseClass {

		<%@ protected_member_variables('objTable'); %>



		<%@ protected_member_objects('objTable'); %>



		<%@ class_load_and_count_methods('objTable'); %>



		<%@ qcodo_query_methods('objTable'); %>



		<%@ instantiation_methods('objTable'); %>



		<%@ index_load_methods('objTable'); %>



		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		<%@ object_save('objTable'); %>

		<%@ object_delete('objTable'); %>

		<%@ object_reload('objTable'); %>



		////////////////////
		// PUBLIC OVERRIDERS
		////////////////////

		<%@ property_get('objTable'); %>

		<%@ property_set('objTable'); %>

		/**
		 * Lookup a VirtualAttribute value (if applicable).  Returns NULL if none found.
		 * @param string $strName
		 * @return string
		 */
		public function GetVirtualAttribute($strName) {
			if (array_key_exists($strName, $this->__strVirtualAttributeArray))
				return $this->__strVirtualAttributeArray[$strName];
			return null;
		}



		<%@ associated_objects_methods('objTable'); %>



		<%@ soap_methods('objTable'); %>



<% if ($this->blnManualQuerySupport) { %>
		<%@ manual_query_methods('objTable'); %>
<% } %>
	}



	<%@ qcodo_query_classes('objTable'); %>
?>