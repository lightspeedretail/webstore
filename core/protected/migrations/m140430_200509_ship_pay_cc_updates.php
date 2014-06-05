<?php

class m140430_200509_ship_pay_cc_updates extends CDbMigration
{
	public function up()
	{
		$this->addColumn('xlsws_cart_payment', 'payment_status', 'VARCHAR(100) DEFAULT NULL  AFTER `payment_amount`');
		$this->addColumn('xlsws_cart_shipping', 'shipping_taxable', 'INT  DEFAULT NULL AFTER `shipping_sell`');
		$this->addColumn('xlsws_cart_shipping', 'shipping_sell_taxed', 'DOUBLE DEFAULT NULL AFTER `shipping_sell`');

		$this->dropColumn('xlsws_credit_card', 'numeric_length');
		$this->dropColumn('xlsws_credit_card', 'prefix');

		$this->alterColumn('xlsws_tax','tax','varchar(255)');
		$this->alterColumn('xlsws_tax_code','code','varchar(255) NOT NULL');

		$this->addColumn('xlsws_modules', 'mt_compatible', 'TINYINT(1) UNSIGNED DEFAULT \'0\'');
		$this->addColumn('xlsws_cart_item', 'discount_type', 'INT DEFAULT NULL AFTER `sell_total`');


	}

	public function down()
	{
		$this->dropColumn('xlsws_cart_payment', 'payment_status');
		$this->dropColumn('xlsws_cart_shipping', 'shipping_taxable');
		$this->dropColumn('xlsws_cart_shipping', 'shipping_sell_taxed');
		$this->addColumn('xlsws_credit_card', 'numeric_length', 'int(11) NOT NULL');
		$this->addColumn('xlsws_credit_card', 'prefix', 'int(11) NOT NULL');

		$this->dropColumn('xlsws_modules', 'mt_compatible');
		$this->dropColumn('xlsws_cart_item', 'discount_type');

	}


}