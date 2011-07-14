<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<%= __DATAGEN_CLASSES__ %>" TargetFileName="_type_class_paths.inc.php"/>
<?php 
<% foreach ($objTableArray as $objTable) { %>
	// ClassPaths for the <%= $objTable->ClassName %> type class
	<% if (__DATA_CLASSES__) { %>
		QApplicationBase::$ClassFile['<%= strtolower($objTable->ClassName) %>'] = __DATA_CLASSES__ . '/<%= $objTable->ClassName %>.class.php';
	<% } %>
<% } %>
?>