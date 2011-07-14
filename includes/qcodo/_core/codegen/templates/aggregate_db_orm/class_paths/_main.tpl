<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<%= __DATAGEN_CLASSES__ %>" TargetFileName="_class_paths.inc.php"/>
<?php 
<% foreach ($objTableArray as $objTable) { %>
	// ClassPaths for the <%= $objTable->ClassName %> class
	<% if (__DATA_CLASSES__) { %>
		QApplicationBase::$ClassFile['<%= strtolower($objTable->ClassName) %>'] = __DATA_CLASSES__ . '/<%= $objTable->ClassName %>.class.php';
		QApplicationBase::$ClassFile['qqnode<%= strtolower($objTable->ClassName) %>'] = __DATA_CLASSES__ . '/<%= $objTable->ClassName %>.class.php';
		QApplicationBase::$ClassFile['qqreversereferencenode<%= strtolower($objTable->ClassName) %>'] = __DATA_CLASSES__ . '/<%= $objTable->ClassName %>.class.php';
	<% } %><% if (__DATA_META_CONTROLS__) { %>
		QApplicationBase::$ClassFile['<%= strtolower($objTable->ClassName) %>metacontrol'] = __DATA_META_CONTROLS__ . '/<%= $objTable->ClassName %>MetaControl.class.php';
		QApplicationBase::$ClassFile['<%= strtolower($objTable->ClassName) %>datagrid'] = __DATA_META_CONTROLS__ . '/<%= $objTable->ClassName %>DataGrid.class.php';
	<% } %>

<% } %>
?>