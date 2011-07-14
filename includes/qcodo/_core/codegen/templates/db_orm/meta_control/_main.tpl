<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<%= __DATAGEN_META_CONTROLS__ %>" TargetFileName="<%= $objTable->ClassName %>MetaControlGen.class.php"/>
<?php
	/**
	 * This is a MetaControl class, providing a QForm or QPanel access to event handlers
	 * and QControls to perform the Create, Edit, and Delete functionality
	 * of the <%= $objTable->ClassName %> class.  This code-generated class
	 * contains all the basic elements to help a QPanel or QForm display an HTML form that can
	 * manipulate a single <%= $objTable->ClassName %> object.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QForm or QPanel which instantiates a <%= $objTable->ClassName %>MetaControl
	 * class.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent
	 * code re-generation.
	 * 
	 * @package <%= QCodeGen::$ApplicationName; %>
	 * @subpackage MetaControls
<%@ property_comments('objTable'); %>
	 */

	class <%= $objTable->ClassName %>MetaControlGen extends QBaseClass {
		<%@ variable_declarations('objTable'); %>

		<%@ constructor('objTable'); %>



		///////////////////////////////////////////////
		// PUBLIC CREATE and REFRESH METHODS
		///////////////////////////////////////////////

<%@ create_methods('objTable'); %>

<%@ refresh_methods('objTable'); %>



		///////////////////////////////////////////////
		// PROTECTED UPDATE METHODS for ManyToManyReferences (if any)
		///////////////////////////////////////////////

<%@ update_methods('objTable'); %>



		///////////////////////////////////////////////
		// PUBLIC <%= strtoupper($objTable->ClassName); %> OBJECT MANIPULATORS
		///////////////////////////////////////////////

		<%@ save_object('objTable'); %>

		<%@ delete_object('objTable'); %>		



		///////////////////////////////////////////////
		// PUBLIC GETTERS and SETTERS
		///////////////////////////////////////////////

		<%@ property_get('objTable'); %>

		<%@ property_set('objTable'); %>
	}
?>