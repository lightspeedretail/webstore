-- cd includes/xlsws/codegen
-- DOCUMENT_ROOT=~/code/php php cli/qcodo codegen




To (re-)generate qcodo base classes, you need to:

* ensure your qcodo codebase is up to date

* use the included doc/relations_cart_type_table.sql to create the cart type table, if necessary

* setup a valid(ish) includes/configuration.inc.php which has at least a valid:

  define ('__DOCROOT__', '/path/to/our/webstore/toplevel');
  define ('__VIRTUAL_DIRECTORY__', '');
  define ('__SUBDIRECTORY__', '');


  define('DB_CONNECTION_1', serialize(array(
             'adapter' => 'MySqli5',
             'server' => '127.0.0.1',
             'port' => 3306,
             'database' => 'patrulesdb',
             'username' => 'pat',
             'password' => 's3cr3t',
             'profiling' => false)));


* ensure /path/to/toplevel/cli is present (get from qcodo source if necessary)

* copy codegen.xml from here to /path/to/toplevel/cli/settings

* cd /path/to/toplevel

* you may need to
	cd ..; ln -s toplevel/cli _devtools_cli; cd -

* run ./cli/qcodo codegen

* bask in the glory of an output similar to:
		
		---------------------------------------------------------------------
		There were 29 tables available to attempt code generation:
		Successfully generated DB ORM Class:   Cart (with 4 relationships)
		Successfully generated DB ORM Class:   CartItem (with 3 relationships)
		Successfully generated DB ORM Class:   Category (with 2 relationships)
		Successfully generated DB ORM Class:   Configuration (with no relationships)
		Successfully generated DB ORM Class:   Country (with no relationships)
		Successfully generated DB ORM Class:   CreditCard (with no relationships)
		Successfully generated DB ORM Class:   CustomPage (with no relationships)
		Successfully generated DB ORM Class:   Customer (with no relationships)
		Successfully generated DB ORM Class:   Destination (with no relationships)
		Successfully generated DB ORM Class:   Family (with no relationships)
		Successfully generated DB ORM Class:   GiftRegistry (with 1 relationship)
		Successfully generated DB ORM Class:   GiftRegistryItems (with 2 relationships)
		Successfully generated DB ORM Class:   GiftRegistryReceipents (with 2 relationships)
		Successfully generated DB ORM Class:   Images (with 2 relationships)
		Successfully generated DB ORM Class:   Log (with no relationships)
		Successfully generated DB ORM Class:   Modules (with no relationships)
		Successfully generated DB ORM Class:   Product (with 4 relationships)
		Successfully generated DB ORM Class:   ProductQtyPricing (with 1 relationship)
		Successfully generated DB ORM Class:   ProductRelated (with 2 relationships)
		Successfully generated DB ORM Class:   Sro (with 1 relationship)
		Successfully generated DB ORM Class:   SroRepair (with 1 relationship)
		Successfully generated DB ORM Class:   State (with no relationships)
		Successfully generated DB ORM Class:   Tax (with no relationships)
		Successfully generated DB ORM Class:   TaxCode (with no relationships)
		Successfully generated DB ORM Class:   TaxStatus (with no relationships)
		Successfully generated DB ORM Class:   ViewLog (with 2 relationships)
		Successfully generated DB ORM Class:   Visitor (with 1 relationship)
		Successfully generated DB Type Class:  CartType
		Successfully generated DB Type Class:  ViewLogType
		

You are done, do the dance of joy.

--
2009-12-18
Pat Deegan
Xsilva Systems
