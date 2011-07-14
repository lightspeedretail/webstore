<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<%= __DATAGEN_CLASSES__ %>" TargetFileName="<%= $objTypeTable->ClassName %>Gen.class.php"/>
<?php
	/**
	 * The <%= $objTypeTable->ClassName %> class defined here contains
	 * code for the <%= $objTypeTable->ClassName %> enumerated type.  It represents
	 * the enumerated values found in the "<%= $objTypeTable->Name %>" table
	 * in the database.
	 * 
	 * To use, you should use the <%= $objTypeTable->ClassName %> subclass which
	 * extends this <%= $objTypeTable->ClassName %>Gen class.
	 * 
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the <%= $objTypeTable->ClassName %> class.
	 * 
	 * @package <%= QCodeGen::$ApplicationName; %>
	 * @subpackage GeneratedDataObjects
	 */
	abstract class <%= $objTypeTable->ClassName %>Gen extends QBaseClass {
<%= ($intKey = 0) == 1; %><% foreach ($objTypeTable->TokenArray as $intKey=>$strValue) { %>
		const <%= $strValue %> = <%= $intKey %>;
<% } %>

		const MaxId = <%= $intKey %>;

		public static $NameArray = array(<% if (count($objTypeTable->NameArray)) { %>

<% foreach ($objTypeTable->NameArray as $intKey=>$strValue) { %>
			<%= $intKey %> => '<%= $strValue %>',
<% } %><%--%><%}%>);

		public static $TokenArray = array(<% if (count($objTypeTable->TokenArray)) { %>

<% foreach ($objTypeTable->TokenArray as $intKey=>$strValue) { %>
			<%= $intKey %> => '<%= $strValue %>',
<% } %><%--%><%}%>);

<% if (count($objTypeTable->ExtraFieldNamesArray)) { %>
		public static $ExtraColumnNamesArray = array(
<% foreach ($objTypeTable->ExtraFieldNamesArray as $strColName) { %>
			'<%= $strColName %>',
<% } %><%--%>);

		public static $ExtraColumnValuesArray = array(
<% foreach ($objTypeTable->ExtraPropertyArray as $intKey=>$arrColumns) { %>
			<%= $intKey %> => array (
<% foreach ($arrColumns as $strColName=>$strColValue) { %>
						'<%= $strColName %>' => '<%= str_replace("'", "\\'", $strColValue) %>',
<% } %><%--%>),
<% } %><%--%>);


<%}%>
		public static function ToString($int<%= $objTypeTable->ClassName %>Id) {
			switch ($int<%= $objTypeTable->ClassName %>Id) {
<% foreach ($objTypeTable->NameArray as $intKey=>$strValue) { %>
				case <%= $intKey %>: return '<%= $strValue %>';
<% } %>
				default:
					throw new QCallerException(sprintf('Invalid int<%= $objTypeTable->ClassName %>Id: %s', $int<%= $objTypeTable->ClassName %>Id));
			}
		}

		public static function ToToken($int<%= $objTypeTable->ClassName %>Id) {
			switch ($int<%= $objTypeTable->ClassName %>Id) {
<% foreach ($objTypeTable->TokenArray as $intKey=>$strValue) { %>
				case <%= $intKey %>: return '<%= $strValue %>';
<% } %>
				default:
					throw new QCallerException(sprintf('Invalid int<%= $objTypeTable->ClassName %>Id: %s', $int<%= $objTypeTable->ClassName %>Id));
			}
		}

<% foreach ($objTypeTable->ExtraFieldNamesArray as $strColName) { %>
		public static function To<%= $strColName %>($int<%= $objTypeTable->ClassName %>Id) {
			if (array_key_exists($int<%= $objTypeTable->ClassName %>Id, <%= $objTypeTable->ClassName %>::$ExtraColumnValuesArray))
				return <%= $objTypeTable->ClassName %>::$ExtraColumnValuesArray[$int<%= $objTypeTable->ClassName %>Id]['<%= $strColName %>'];
			else
				throw new QCallerException(sprintf('Invalid int<%= $objTypeTable->ClassName %>Id: %s', $int<%= $objTypeTable->ClassName %>Id));
		}

<% } %>
	}
?>