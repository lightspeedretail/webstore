<?php

class m140429_221951_customer_billing extends CDbMigration
{
	public function up()
	{
		$this->execute("update xlsws_customer as a set default_billing_id=(select id from xlsws_customer_address as b where customer_id=a.id  order by b.id desc limit 1)");
		$this->execute("update xlsws_customer as a set default_shipping_id=(select id from xlsws_customer_address as b where customer_id=a.id  order by b.id desc limit 1)");
		$this->execute("UPDATE `xlsws_customer` SET `pricing_level`=1 where pricing_level is null");
	}

	public function down()
	{
		echo "m140429_221951_customer_billing does not support migration down.\n";

	}


}