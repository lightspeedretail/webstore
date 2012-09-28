<?php
	class QQN {
		/**
		 * @return QQNodeCart
		 */
		static public function Cart() {
			return new QQNodeCart('xlsws_cart', null, null);
		}
		/**
		 * @return QQNodeCartItem
		 */
		static public function CartItem() {
			return new QQNodeCartItem('xlsws_cart_item', null, null);
		}
		/**
		 * @return QQNodeCartMessages
		 */
		static public function CartMessages() {
			return new QQNodeCartMessages('xlsws_cart_messages', null, null);
		}
		/**
		 * @return QQNodeCategory
		 */
		static public function Category() {
			return new QQNodeCategory('xlsws_category', null, null);
		}
		/**
		 * @return QQNodeCategoryAddl
		 */
		static public function CategoryAddl() {
			return new QQNodeCategoryAddl('xlsws_category_addl', null, null);
		}
		/**
		 * @return QQNodeConfiguration
		 */
		static public function Configuration() {
			return new QQNodeConfiguration('xlsws_configuration', null, null);
		}
		/**
		 * @return QQNodeCountry
		 */
		static public function Country() {
			return new QQNodeCountry('xlsws_country', null, null);
		}
		/**
		 * @return QQNodeCreditCard
		 */
		static public function CreditCard() {
			return new QQNodeCreditCard('xlsws_credit_card', null, null);
		}
		/**
		 * @return QQNodeCustomPage
		 */
		static public function CustomPage() {
			return new QQNodeCustomPage('xlsws_custom_page', null, null);
		}
		/**
		 * @return QQNodeCustomer
		 */
		static public function Customer() {
			return new QQNodeCustomer('xlsws_customer', null, null);
		}
		/**
		 * @return QQNodeDestination
		 */
		static public function Destination() {
			return new QQNodeDestination('xlsws_destination', null, null);
		}
		/**
		 * @return QQNodeEmail
		 */
		static public function Email() {
			return new QQNodeEmail('xlsws_email', null, null);
		}
		/**
		 * @return QQNodeFamily
		 */
		static public function Family() {
			return new QQNodeFamily('xlsws_family', null, null);
		}
		/**
		 * @return QQNodeGiftRegistry
		 */
		static public function GiftRegistry() {
			return new QQNodeGiftRegistry('xlsws_gift_registry', null, null);
		}
		/**
		 * @return QQNodeGiftRegistryItems
		 */
		static public function GiftRegistryItems() {
			return new QQNodeGiftRegistryItems('xlsws_gift_registry_items', null, null);
		}
		/**
		 * @return QQNodeGiftRegistryReceipents
		 */
		static public function GiftRegistryReceipents() {
			return new QQNodeGiftRegistryReceipents('xlsws_gift_registry_receipents', null, null);
		}
		/**
		 * @return QQNodeGoogleCategories
		 */
		static public function GoogleCategories() {
			return new QQNodeGoogleCategories('xlsws_google_categories', null, null);
		}
		/**
		 * @return QQNodeImages
		 */
		static public function Images() {
			return new QQNodeImages('xlsws_images', null, null);
		}
		/**
		 * @return QQNodeLog
		 */
		static public function Log() {
			return new QQNodeLog('xlsws_log', null, null);
		}
		/**
		 * @return QQNodeModules
		 */
		static public function Modules() {
			return new QQNodeModules('xlsws_modules', null, null);
		}
		/**
		 * @return QQNodeProduct
		 */
		static public function Product() {
			return new QQNodeProduct('xlsws_product', null, null);
		}
		/**
		 * @return QQNodeProductCopy
		 */
		static public function ProductCopy() {
			return new QQNodeProductCopy('xlsws_product_copy', null, null);
		}
		/**
		 * @return QQNodeProductQtyPricing
		 */
		static public function ProductQtyPricing() {
			return new QQNodeProductQtyPricing('xlsws_product_qty_pricing', null, null);
		}
		/**
		 * @return QQNodeProductRelated
		 */
		static public function ProductRelated() {
			return new QQNodeProductRelated('xlsws_product_related', null, null);
		}
		/**
		 * @return QQNodePromoCode
		 */
		static public function PromoCode() {
			return new QQNodePromoCode('xlsws_promo_code', null, null);
		}
		/**
		 * @return QQNodeSessions
		 */
		static public function Sessions() {
			return new QQNodeSessions('xlsws_sessions', null, null);
		}
		/**
		 * @return QQNodeShippingTiers
		 */
		static public function ShippingTiers() {
			return new QQNodeShippingTiers('xlsws_shipping_tiers', null, null);
		}
		/**
		 * @return QQNodeSro
		 */
		static public function Sro() {
			return new QQNodeSro('xlsws_sro', null, null);
		}
		/**
		 * @return QQNodeSroRepair
		 */
		static public function SroRepair() {
			return new QQNodeSroRepair('xlsws_sro_repair', null, null);
		}
		/**
		 * @return QQNodeState
		 */
		static public function State() {
			return new QQNodeState('xlsws_state', null, null);
		}
		/**
		 * @return QQNodeTax
		 */
		static public function Tax() {
			return new QQNodeTax('xlsws_tax', null, null);
		}
		/**
		 * @return QQNodeTaxCode
		 */
		static public function TaxCode() {
			return new QQNodeTaxCode('xlsws_tax_code', null, null);
		}
		/**
		 * @return QQNodeTaxStatus
		 */
		static public function TaxStatus() {
			return new QQNodeTaxStatus('xlsws_tax_status', null, null);
		}
		/**
		 * @return QQNodeViewLog
		 */
		static public function ViewLog() {
			return new QQNodeViewLog('xlsws_view_log', null, null);
		}
		/**
		 * @return QQNodeVisitor
		 */
		static public function Visitor() {
			return new QQNodeVisitor('xlsws_visitor', null, null);
		}
	}
?>