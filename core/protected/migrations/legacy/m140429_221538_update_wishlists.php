<?php

class m140429_221538_update_wishlists extends CDbMigration
{
	public function up()
	{
		$this->update(
			'xlsws_wishlist_item',
			array('cart_item_id' => null),
			'cart_item_id = :id',
			array(':id' => '0')
		);

		$this->update(
			'xlsws_wishlist_item',
			array('purchased_by' => null),
			'purchased_by = :id',
			array(':id' => '0')
		);

		$this->update(
			'xlsws_wishlist',
			array('visibility' => 1)
		);

        $table = $this->getDbConnection()->schema->getTable('xlsws_wishlist');

        if(isset($table->columns['registry_password']))
        {
			$this->dropColumn('xlsws_wishlist','registry_password');
		}
	}

	public function down()
	{
		echo "m140429_221538_update_wishlists does not support migration down.\n";

	}


}
