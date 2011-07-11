This directory is for customizations and additions of the Qcodo Codegen
Templates and Subtemplates.  If there are any template files in this directory which have the
same name as a template or subtemplate file in /includes/qcodo/codegen/templates, then the
file in this directory will be used *instead* of the one there.

If there are any template files in this directory in *addition* to the ones in
/includes/qcodo/codegen/templates, these additional template files will be
processed as well.

Feel free to add as you wish.  Just remember the naming structure for CodeGen
template files:

/includes/qcodo/codegen/templates/[TYPE]/[MODULE]/[FILE]

Where [TYPE] is the object being generated, for example:
	* db_orm
	* db_type

And [MODULE] is the category of file being generated, for example:
	* class_gen - templates and subtemplates for the Data Class Gen file
	* class_subclass - templates and subtemplates for the Data Class customizable subclass
	* drafts - templates and subtemplates for all things with regards to draft forms/panels
	* meta_control - templates and subtemplates for the metacontrol
	* meta_datagrid - templates and subtemplates for the metadatagrid

And [FILE] is the filename of the  template or subtemplate, itself.
Note that any file with a "_" prefix is considered a template and will
be processed by the code generator.  All other files are considered
subtemplates, and are only processed if envoked by a template.
