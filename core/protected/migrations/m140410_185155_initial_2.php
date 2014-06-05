<?php

class m140410_185155_initial_2 extends CDbMigration
{
	public function up()
	{


		$this->addForeignKey('fk_bill', 'xlsws_cart', 'billaddress_id', 'xlsws_customer_address', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_payrecord', 'xlsws_cart', 'payment_id', 'xlsws_cart_payment', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_ship', 'xlsws_cart', 'shipaddress_id', 'xlsws_customer_address', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_shiprecord', 'xlsws_cart', 'shipping_id', 'xlsws_cart_shipping', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('xlsws_cart_ibfk_1', 'xlsws_cart', 'customer_id', 'xlsws_customer', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('xlsws_cart_ibfk_2', 'xlsws_cart', 'tax_code_id', 'xlsws_tax_code', 'lsid', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('xlsws_cart_ibfk_3', 'xlsws_cart', 'document_id', 'xlsws_document', 'id', 'NO ACTION', 'NO ACTION');

		$this->addForeignKey('fk_xlsws_cart_item_xlsws_cart_cart_id', 'xlsws_cart_item', 'cart_id', 'xlsws_cart', 'id', 'NO ACTION', 'NO ACTION');

		$this->addForeignKey('fk_xlsws_cart_item_xlsws_product_product_id', 'xlsws_cart_item', 'product_id', 'xlsws_product', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_cart_item_xlsws_wishlist_item_wishlist_item', 'xlsws_cart_item', 'wishlist_item', 'xlsws_wishlist_item', 'id', 'NO ACTION', 'NO ACTION');

		$this->addForeignKey('fk_xlsws_category_xlsws_category_parent', 'xlsws_category', 'parent', 'xlsws_category', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_category_xlsws_custom_page_custom_page', 'xlsws_category', 'custom_page', 'xlsws_custom_page', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_category_integration_xlsws_category_category_id', 'xlsws_category_integration', 'category_id', 'xlsws_category', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_customer_xlsws_customer_address_default_billing_id', 'xlsws_customer', 'default_billing_id', 'xlsws_customer_address', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_customer_xlsws_customer_address_default_shipping_id', 'xlsws_customer', 'default_shipping_id', 'xlsws_customer_address', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_customer_xlsws_pricing_levels_pricing_level', 'xlsws_customer', 'pricing_level', 'xlsws_pricing_levels', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_customer_address_xlsws_customer_customer_id', 'xlsws_customer_address', 'customer_id', 'xlsws_customer', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_customer_address_xlsws_state_state_id', 'xlsws_customer_address', 'state_id', 'xlsws_state', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_customer_address_xlsws_country_country_id', 'xlsws_customer_address', 'country_id', 'xlsws_country', 'id', 'NO ACTION', 'NO ACTION');

		$this->addForeignKey('xlsws_destination_ibfk_1', 'xlsws_destination', 'state', 'xlsws_state', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('xlsws_destination_ibfk_2', 'xlsws_destination', 'country', 'xlsws_country', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('xlsws_destination_ibfk_3', 'xlsws_destination', 'taxcode', 'xlsws_tax_code', 'lsid', 'NO ACTION', 'NO ACTION');

		$this->addForeignKey('fk_xlsws_document_xlsws_customer_address_billaddress_id', 'xlsws_document', 'billaddress_id', 'xlsws_customer_address', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_document_xlsws_customer_address_shipaddress_id', 'xlsws_document', 'shipaddress_id', 'xlsws_customer_address', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_document_xlsws_customer_customer_id', 'xlsws_document', 'customer_id', 'xlsws_customer', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_document_xlsws_document_shipping_shipping_id', 'xlsws_document', 'shipping_id', 'xlsws_document_shipping', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_document_xlsws_document_payment_payment_id', 'xlsws_document', 'payment_id', 'xlsws_document_payment', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_document_xlsws_cart_cart_id', 'xlsws_document', 'cart_id', 'xlsws_cart', 'id', 'NO ACTION', 'NO ACTION');

		$this->addForeignKey('fk_xlsws_document_item_xlsws_product_product_id', 'xlsws_document_item', 'product_id', 'xlsws_product', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_document_item_xlsws_document_document_id', 'xlsws_document_item', 'document_id', 'xlsws_document', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_email_queue_xlsws_customer_customer_id', 'xlsws_email_queue', 'customer_id', 'xlsws_customer', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_email_queue_xlsws_cart_cart_id', 'xlsws_email_queue', 'cart_id', 'xlsws_cart', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_images_xlsws_product_product_id', 'xlsws_images', 'product_id', 'xlsws_product', 'id', 'NO ACTION', 'NO ACTION');

		$this->addForeignKey('xlsws_product_ibfk_1', 'xlsws_product', 'parent', 'xlsws_product', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('xlsws_product_ibfk_2', 'xlsws_product', 'image_id', 'xlsws_images', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('xlsws_product_ibfk_3', 'xlsws_product', 'family_id', 'xlsws_family', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('xlsws_product_ibfk_4', 'xlsws_product', 'class_id', 'xlsws_classes', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('xlsws_product_ibfk_5', 'xlsws_product', 'tax_status_id', 'xlsws_tax_status', 'lsid', 'NO ACTION', 'NO ACTION');

		$this->addForeignKey('fk_xlsws_product_category_assn_xlsws_product_product_id', 'xlsws_product_category_assn', 'product_id', 'xlsws_product', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_product_category_assn_xlsws_category_category_id', 'xlsws_product_category_assn', 'category_id', 'xlsws_category', 'id', 'NO ACTION', 'NO ACTION');

		$this->addForeignKey('fk_xlsws_product_qty_pricing_xlsws_pricing_levels_pricing_level', 'xlsws_product_qty_pricing', 'pricing_level', 'xlsws_pricing_levels', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_product_qty_pricing_xlsws_product_product_id', 'xlsws_product_qty_pricing', 'product_id', 'xlsws_product', 'id', 'NO ACTION', 'NO ACTION');

		$this->addForeignKey('fk_xlsws_product_related_xlsws_product_related_id', 'xlsws_product_related', 'related_id', 'xlsws_product', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_product_related_xlsws_product_product_id', 'xlsws_product_related', 'product_id', 'xlsws_product', 'id', 'NO ACTION', 'NO ACTION');

		$this->addForeignKey('fk_xlsws_product_tags_xlsws_product_product_id', 'xlsws_product_tags', 'product_id', 'xlsws_product', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_product_tags_xlsws_tags_tag_id', 'xlsws_product_tags', 'tag_id', 'xlsws_tags', 'id', 'NO ACTION', 'NO ACTION');

		$this->addForeignKey('fk_xlsws_sro_xlsws_customer_customer_id', 'xlsws_sro', 'customer_id', 'xlsws_customer', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_sro_item_xlsws_product_product_id', 'xlsws_sro_item', 'product_id', 'xlsws_product', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_sro_item_xlsws_sro_sro_id', 'xlsws_sro_item', 'sro_id', 'xlsws_sro', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_sro_repair_xlsws_sro_sro_id', 'xlsws_sro_repair', 'sro_id', 'xlsws_sro', 'id', 'NO ACTION', 'NO ACTION');

		$this->addForeignKey('fk_country', 'xlsws_state', 'country_id', 'xlsws_country', 'id', 'NO ACTION', 'NO ACTION');

		$this->addForeignKey('fk_xlsws_stringtranslate', 'xlsws_stringtranslate', 'id', 'xlsws_stringsource', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_task_queue_xlsws_product_product_id', 'xlsws_task_queue', 'product_id', 'xlsws_product', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_transaction_log_xlsws_cart_cart_id', 'xlsws_transaction_log', 'cart_id', 'xlsws_cart', 'id', 'NO ACTION', 'NO ACTION');

		$this->addForeignKey('fk_xlsws_wishlist_xlsws_customer_customer_id', 'xlsws_wishlist', 'customer_id', 'xlsws_customer', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_wishlist_item_xlsws_wishlist_registry_id', 'xlsws_wishlist_item', 'registry_id', 'xlsws_wishlist', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_wishlist_item_xlsws_product_product_id', 'xlsws_wishlist_item', 'product_id', 'xlsws_product', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_wishlist_item_xlsws_cart_item_cart_item_id', 'xlsws_wishlist_item', 'cart_item_id', 'xlsws_cart_item', 'id', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('fk_xlsws_wishlist_item_xlsws_customer_purchased_by', 'xlsws_wishlist_item', 'purchased_by', 'xlsws_customer', 'id', 'NO ACTION', 'NO ACTION');

	}

	public function down()
	{

		$this->dropForeignKey('fk_bill', 'xlsws_cart');
		$this->dropForeignKey('fk_payrecord', 'xlsws_cart');
		$this->dropForeignKey('fk_ship', 'xlsws_cart');
		$this->dropForeignKey('fk_shiprecord', 'xlsws_cart');
		$this->dropForeignKey('xlsws_cart_ibfk_1', 'xlsws_cart');
		$this->dropForeignKey('xlsws_cart_ibfk_2', 'xlsws_cart');
		$this->dropForeignKey('xlsws_cart_ibfk_3', 'xlsws_cart');

		$this->dropForeignKey('fk_xlsws_cart_item_xlsws_cart_cart_id', 'xlsws_cart_item');

		$this->dropForeignKey('fk_xlsws_cart_item_xlsws_product_product_id', 'xlsws_cart_item');
		$this->dropForeignKey('fk_xlsws_cart_item_xlsws_wishlist_item_wishlist_item', 'xlsws_cart_item');

		$this->dropForeignKey('fk_xlsws_category_xlsws_category_parent', 'xlsws_category');
		$this->dropForeignKey('fk_xlsws_category_xlsws_custom_page_custom_page', 'xlsws_category');
		$this->dropForeignKey('fk_xlsws_category_integration_xlsws_category_category_id', 'xlsws_category_integration');
		$this->dropForeignKey('fk_xlsws_customer_xlsws_customer_address_default_billing_id', 'xlsws_customer');
		$this->dropForeignKey('fk_xlsws_customer_xlsws_customer_address_default_shipping_id', 'xlsws_customer');
		$this->dropForeignKey('fk_xlsws_customer_xlsws_pricing_levels_pricing_level', 'xlsws_customer');
		$this->dropForeignKey('fk_xlsws_customer_address_xlsws_customer_customer_id', 'xlsws_customer_address');
		$this->dropForeignKey('fk_xlsws_customer_address_xlsws_state_state_id', 'xlsws_customer_address');
		$this->dropForeignKey('fk_xlsws_customer_address_xlsws_country_country_id', 'xlsws_customer_address');

		$this->dropForeignKey('xlsws_destination_ibfk_1', 'xlsws_destination');
		$this->dropForeignKey('xlsws_destination_ibfk_2', 'xlsws_destination');
		$this->dropForeignKey('xlsws_destination_ibfk_3', 'xlsws_destination');

		$this->dropForeignKey('fk_xlsws_document_xlsws_customer_address_billaddress_id', 'xlsws_document');
		$this->dropForeignKey('fk_xlsws_document_xlsws_customer_address_shipaddress_id', 'xlsws_document');
		$this->dropForeignKey('fk_xlsws_document_xlsws_customer_customer_id', 'xlsws_document');
		$this->dropForeignKey('fk_xlsws_document_xlsws_document_shipping_shipping_id', 'xlsws_document');
		$this->dropForeignKey('fk_xlsws_document_xlsws_document_payment_payment_id', 'xlsws_document');
		$this->dropForeignKey('fk_xlsws_document_xlsws_cart_cart_id', 'xlsws_document');

		$this->dropForeignKey('fk_xlsws_document_item_xlsws_product_product_id', 'xlsws_document_item');
		$this->dropForeignKey('fk_xlsws_document_item_xlsws_document_document_id', 'xlsws_document_item');
		$this->dropForeignKey('fk_xlsws_email_queue_xlsws_customer_customer_id', 'xlsws_email_queue');
		$this->dropForeignKey('fk_xlsws_email_queue_xlsws_cart_cart_id', 'xlsws_email_queue', 'cart_id');
		$this->dropForeignKey('fk_xlsws_images_xlsws_product_product_id', 'xlsws_images');

		$this->dropForeignKey('xlsws_product_ibfk_1', 'xlsws_product');
		$this->dropForeignKey('xlsws_product_ibfk_2', 'xlsws_product');
		$this->dropForeignKey('xlsws_product_ibfk_3', 'xlsws_product');
		$this->dropForeignKey('xlsws_product_ibfk_4', 'xlsws_product');
		$this->dropForeignKey('xlsws_product_ibfk_5', 'xlsws_product');

		$this->dropForeignKey('fk_xlsws_product_category_assn_xlsws_product_product_id', 'xlsws_product_category_assn');
		$this->dropForeignKey('fk_xlsws_product_category_assn_xlsws_category_category_id', 'xlsws_product_category_assn');

		$this->dropForeignKey('fk_xlsws_product_qty_pricing_xlsws_pricing_levels_pricing_level', 'xlsws_product_qty_pricing');
		$this->dropForeignKey('fk_xlsws_product_qty_pricing_xlsws_product_product_id', 'xlsws_product_qty_pricing');

		$this->dropForeignKey('fk_xlsws_product_related_xlsws_product_related_id', 'xlsws_product_related');
		$this->dropForeignKey('fk_xlsws_product_related_xlsws_product_product_id', 'xlsws_product_related');

		$this->dropForeignKey('fk_xlsws_product_tags_xlsws_product_product_id', 'xlsws_product_tags');
		$this->dropForeignKey('fk_xlsws_product_tags_xlsws_tags_tag_id', 'xlsws_product_tags');

		$this->dropForeignKey('fk_xlsws_sro_xlsws_customer_customer_id', 'xlsws_sro');
		$this->dropForeignKey('fk_xlsws_sro_item_xlsws_product_product_id', 'xlsws_sro_item');
		$this->dropForeignKey('fk_xlsws_sro_item_xlsws_sro_sro_id', 'xlsws_sro_item');
		$this->dropForeignKey('fk_xlsws_sro_repair_xlsws_sro_sro_id', 'xlsws_sro_repair');

		$this->dropForeignKey('fk_country', 'xlsws_state');

		$this->dropForeignKey('fk_xlsws_stringtranslate', 'xlsws_stringtranslate');
		$this->dropForeignKey('fk_xlsws_task_queue_xlsws_product_product_id', 'xlsws_task_queue');
		$this->dropForeignKey('fk_xlsws_transaction_log_xlsws_cart_cart_id', 'xlsws_transaction_log');

		$this->dropForeignKey('fk_xlsws_wishlist_xlsws_customer_customer_id', 'xlsws_wishlist');
		$this->dropForeignKey('fk_xlsws_wishlist_item_xlsws_wishlist_registry_id', 'xlsws_wishlist_item');
		$this->dropForeignKey('fk_xlsws_wishlist_item_xlsws_product_product_id', 'xlsws_wishlist_item');
		$this->dropForeignKey('fk_xlsws_wishlist_item_xlsws_cart_item_cart_item_id', 'xlsws_wishlist_item');
		$this->dropForeignKey('fk_xlsws_wishlist_item_xlsws_customer_purchased_by', 'xlsws_wishlist_item');
		
		
		

	}
}