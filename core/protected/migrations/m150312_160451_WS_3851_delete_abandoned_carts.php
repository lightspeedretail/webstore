<?php

class m150312_160451_WS_3851_delete_abandoned_carts extends CDbMigration
{
	public function up()
	{
		$this->execute(
			'delete c from
				xlsws_cart c left join xlsws_cart_item ci
				on c.id = ci.cart_id
			where
				ci.id is null and
				c.id_str is null and
				c.customer_id is null and
				c.shipaddress_id is null and
				c.billaddress_id is null and
				c.shipping_id is null and
				c.payment_id is null and
				c.document_id is null and
				c.po is null and
				c.cart_type = 1 and
				c.status is null and
				c.currency is null and
				c.printed_notes is null and
				c.subtotal is null and
				c.total is null and
				c.item_count = 0 and
				c.downloaded = 0 and
				c.lightspeed_user is null and
				c.origin is null and
				c.gift_registry is null and
				c.send_to is null and
				c.submitted is null and
				c.datetime_cre < now() - interval 1 day'
		);
	}

	public function down()
	{
		echo "m150312_160451_WS_3851_delete_abandoned_carts does not support migration down.\n";
		return false;
	}
}
