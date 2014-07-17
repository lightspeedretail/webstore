<?php

class m140411_120952_load_strings extends CDbMigration
{
	public function up()
	{
		$this->insert('xlsws_stringsource',array(
				'id'=>7,
				'category' =>'global',
				'message' => '{description} : {storename}'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>8,
				'category' =>'global',
				'message' => '{longdescription}'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>9,
				'category' =>'global',
				'message' => 'Hover over image to zoom'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>10,
				'category' =>'product',
				'message' => 'Regular Price'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>11,
				'category' =>'product',
				'message' => '{qty} Available'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>12,
				'category' =>'product',
				'message' => 'Add to Wish List'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>13,
				'category' =>'product',
				'message' => 'Add to Cart'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>14,
				'category' =>'global',
				'message' => 'The following related products will be added to your cart automatically with this purchase:'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>15,
				'category' =>'product',
				'message' => 'Product Description'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>16,
				'category' =>'global',
				'message' => 'Other items you may be interested in:'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>17,
				'category' =>'wishlist',
				'message' => 'Add to Wish List'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>18,
				'category' =>'wishlist',
				'message' => 'Add to what list'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>19,
				'category' =>'global',
				'message' => 'Submit'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>20,
				'category' =>'checkout',
				'message' => 'Shopping Cart'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>21,
				'category' =>'cart',
				'message' => 'Qty'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>22,
				'category' =>'cart',
				'message' => 'SubTotal'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>23,
				'category' =>'cart',
				'message' => 'Checkout'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>24,
				'category' =>'cart',
				'message' => 'Edit Cart'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>25,
				'category' =>'global',
				'message' => 'Order Lookup'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>26,
				'category' =>'global',
				'message' => 'Wish Lists'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>27,
				'category' =>'global',
				'message' => 'View all my wish lists'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>28,
				'category' =>'global',
				'message' => 'Create a Wish List'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>29,
				'category' =>'global',
				'message' => 'Search for a wish list'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>30,
				'category' =>'global',
				'message' => 'Logout'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>31,
				'category' =>'global',
				'message' => 'Products'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>32,
				'category' =>'tabs',
				'message' => 'Products'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>51,
				'category' =>'tabs',
				'message' => 'New Products'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>52,
				'category' =>'tabs',
				'message' => 'Top Products'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>53,
				'category' =>'tabs',
				'message' => 'Promotions'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>54,
				'category' =>'tabs',
				'message' => 'Contact Us'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>55,
				'category' =>'global',
				'message' => 'SEARCH'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>56,
				'category' =>'global',
				'message' => 'About Us'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>57,
				'category' =>'global',
				'message' => 'Terms and Conditions'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>58,
				'category' =>'global',
				'message' => 'Privacy Policy'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>59,
				'category' =>'global',
				'message' => 'Sitemap'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>60,
				'category' =>'global',
				'message' => 'Copyright'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>61,
				'category' =>'global',
				'message' => 'All Rights Reserved'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>62,
				'category' =>'global',
				'message' => '{storename} : {storetagline}'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>63,
				'category' =>'global',
				'message' => 'First'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>64,
				'category' =>'global',
				'message' => 'Last'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>65,
				'category' =>'global',
				'message' => 'Previous'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>66,
				'category' =>'global',
				'message' => 'Next'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>67,
				'category' =>'global',
				'message' => 'Size'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>68,
				'category' =>'global',
				'message' => 'Select {label}...'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>69,
				'category' =>'global',
				'message' => 'Color'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>70,
				'category' =>'global',
				'message' => 'Edit Cart'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>71,
				'category' =>'global',
				'message' => 'Checkout'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>72,
				'category' =>'global',
				'message' => 'Fields with {*} are required.'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>73,
				'category' =>'checkout',
				'message' => 'Choose your shipping address'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>74,
				'category' =>'checkout',
				'message' => 'Or enter a new address'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>75,
				'category' =>'checkout',
				'message' => 'Shipping Address'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>76,
				'category' =>'global',
				'message' => 'You must accept Terms and Conditions'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>77,
				'category' =>'CheckoutForm',
				'message' => 'Label for this address (i.e. Home, Work)'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>78,
				'category' =>'CheckoutForm',
				'message' => 'First Name'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>79,
				'category' =>'CheckoutForm',
				'message' => 'Last Name'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>80,
				'category' =>'CheckoutForm',
				'message' => 'Address'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>81,
				'category' =>'CheckoutForm',
				'message' => 'Address 2 (optional)'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>82,
				'category' =>'CheckoutForm',
				'message' => 'City'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>83,
				'category' =>'CheckoutForm',
				'message' => 'Country'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>84,
				'category' =>'CheckoutForm',
				'message' => 'State/Province'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>85,
				'category' =>'CheckoutForm',
				'message' => 'Zip/Postal'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>86,
				'category' =>'CheckoutForm',
				'message' => 'This is a residential address'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>87,
				'category' =>'CheckoutForm',
				'message' => 'My shipping address is also my billing address'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>88,
				'category' =>'checkout',
				'message' => 'Choose your billing address'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>89,
				'category' =>'checkout',
				'message' => 'Billing Address'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>90,
				'category' =>'checkout',
				'message' => 'Promo Code'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>91,
				'category' =>'checkout',
				'message' => 'Enter a Promotional Code here to receive a discount.'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>92,
				'category' =>'checkout',
				'message' => 'Apply Promo Code'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>93,
				'category' =>'checkout',
				'message' => 'Shipping'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>94,
				'category' =>'CheckoutForm',
				'message' => 'Shipping Method'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>95,
				'category' =>'CheckoutForm',
				'message' => 'Delivery Speed'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>96,
				'category' =>'checkout',
				'message' => 'Calculate Shipping'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>97,
				'category' =>'cart',
				'message' => 'Description'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>98,
				'category' =>'cart',
				'message' => 'Price'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>99,
				'category' =>'cart',
				'message' => 'Total'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>100,
				'category' =>'cart',
				'message' => 'Shipping'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>101,
				'category' =>'checkout',
				'message' => 'Payment'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>102,
				'category' =>'CheckoutForm',
				'message' => 'Payment Provider'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>103,
				'category' =>'CheckoutForm',
				'message' => 'Card Type'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>104,
				'category' =>'CheckoutForm',
				'message' => 'Card Number'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>105,
				'category' =>'CheckoutForm',
				'message' => 'CVV'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>106,
				'category' =>'CheckoutForm',
				'message' => 'Expiry Month'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>107,
				'category' =>'CheckoutForm',
				'message' => 'Expiry Year'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>108,
				'category' =>'CheckoutForm',
				'message' => 'Cardholder Name'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>109,
				'category' =>'checkout',
				'message' => 'Submit your order'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>110,
				'category' =>'CheckoutForm',
				'message' => 'Comments'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>111,
				'category' =>'checkout',
				'message' => 'I hereby agree to the Terms and Conditions of shopping with {storename}'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>112,
				'category' =>'CheckoutForm',
				'message' => 'Accept Terms'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>113,
				'category' =>'global',
				'message' => '{label} ({price})'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>114,
				'category' =>'global',
				'message' => 'Available during normal business hours'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>115,
				'category' =>'global',
				'message' => 'Welcome'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>116,
				'category' =>'global',
				'message' => 'Edit Profile'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>117,
				'category' =>'global',
				'message' => 'My Addresses'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>118,
				'category' =>'global',
				'message' => 'Add new address'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>119,
				'category' =>'global',
				'message' => 'Default Billing Address'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>120,
				'category' =>'global',
				'message' => 'Default Shipping Address'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>121,
				'category' =>'global',
				'message' => 'My Orders'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>122,
				'category' =>'global',
				'message' => 'Awaiting Processing'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>123,
				'category' =>'global',
				'message' => 'My Wish Lists'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>124,
				'category' =>'global',
				'message' => 'Click here to create a wish list.'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>125,
				'category' =>'global',
				'message' => 'You have not created any wish list yet.'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>126,
				'category' =>'global',
				'message' => 'Create a new address book entry'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>127,
				'category' =>'global',
				'message' => 'Update your account'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>128,
				'category' =>'checkout',
				'message' => 'Customer Contact'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>129,
				'category' =>'global',
				'message' => 'Enter a new password here to change your password'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>130,
				'category' =>'cart',
				'message' => 'Thank you for your order!'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>131,
				'category' =>'cart',
				'message' => 'Order ID'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>132,
				'category' =>'cart',
				'message' => 'Date'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>133,
				'category' =>'cart',
				'message' => 'Status'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>134,
				'category' =>'cart',
				'message' => 'Payment'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>135,
				'category' =>'cart',
				'message' => 'Authorization'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>136,
				'category' =>'cart',
				'message' => 'Notes'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>137,
				'category' =>'cart',
				'message' => 'Promo Code {code} Applied'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>138,
				'category' =>'global',
				'message' => 'New Wish List'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>139,
				'category' =>'wishlist',
				'message' => 'Click on the wish list name to view list contents, or click on edit to make changes to settings.'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>140,
				'category' =>'global',
				'message' => 'Name'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>141,
				'category' =>'global',
				'message' => 'Contains'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>142,
				'category' =>'global',
				'message' => 'Description'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>143,
				'category' =>'global',
				'message' => 'Edit'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>144,
				'category' =>'global',
				'message' => 'Create a new Wish List'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>145,
				'category' =>'wishlist',
				'message' => 'Name your Wish List'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>146,
				'category' =>'wishlist',
				'message' => 'Description (Optional)'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>147,
				'category' =>'wishlist',
				'message' => 'Event Date (Optional)'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>148,
				'category' =>'wishlist',
				'message' => 'Visibility'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>149,
				'category' =>'wishlist',
				'message' => 'Public, searchable by my email address'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>150,
				'category' =>'wishlist',
				'message' => 'Personal, shared only by a special URL'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>151,
				'category' =>'wishlist',
				'message' => 'Private, only viewable with my login'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>152,
				'category' =>'wishlist',
				'message' => 'None'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>153,
				'category' =>'wishlist',
				'message' => 'Ship Option'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>154,
				'category' =>'wishlist',
				'message' => 'Leave the item in the Wish List, marked as Purchased'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>155,
				'category' =>'wishlist',
				'message' => 'Delete the item automatically from Wish List'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>156,
				'category' =>'wishlist',
				'message' => 'After purchase'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>157,
				'category' =>'wishlist',
				'message' => 'My Wish List'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>158,
				'category' =>'wishlist',
				'message' => 'Item has been added to your Wish List.'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>159,
				'category' =>'global',
				'message' => '{items} item|{items} items'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>160,
				'category' =>'wishlist',
				'message' => 'Please check out my Wish List at {url}'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>161,
				'category' =>'global',
				'message' => 'Wish List'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>162,
				'category' =>'global',
				'message' => 'View All Lists'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>163,
				'category' =>'global',
				'message' => 'Settings'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>164,
				'category' =>'global',
				'message' => 'Share'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>165,
				'category' =>'global',
				'message' => 'Qty'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>166,
				'category' =>'global',
				'message' => 'Status'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>167,
				'category' =>'wishlist',
				'message' => 'You can share this wish list with anyone using the URL: {url}'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>168,
				'category' =>'wishlist',
				'message' => 'Edit Wish List Item'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>169,
				'category' =>'wishlist',
				'message' => 'Qty Desired'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>170,
				'category' =>'wishlist',
				'message' => 'Qty Received'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>171,
				'category' =>'wishlist',
				'message' => 'Priority'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>172,
				'category' =>'wishlist',
				'message' => 'Item Comment (max 500 characters)'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>173,
				'category' =>'wishlist',
				'message' => 'Low Priority'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>174,
				'category' =>'wishlist',
				'message' => 'Normal Priority'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>175,
				'category' =>'wishlist',
				'message' => 'High Priority'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>176,
				'category' =>'global',
				'message' => 'Update'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>177,
				'category' =>'global',
				'message' => 'DELETE THIS ITEM'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>178,
				'category' =>'wishlist',
				'message' => 'Share my Wish List'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>179,
				'category' =>'wishlist',
				'message' => 'Share via email'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>180,
				'category' =>'global',
				'message' => 'Send'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>181,
				'category' =>'global',
				'message' => 'Wish List Search'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>182,
				'category' =>'wishlist',
				'message' => 'Click on the wish list name to view.'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>183,
				'category' =>'global',
				'message' => 'Search for a wish list by email address'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>184,
				'category' =>'global',
				'message' => 'Promo Code'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>185,
				'category' =>'global',
				'message' => 'Promo Code applied at {amount}.'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>186,
				'category' =>'email',
				'message' => 'Dear'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>187,
				'category' =>'email',
				'message' => 'Thank you for your order with'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>188,
				'category' =>'checkout',
				'message' => 'Billing'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>189,
				'category' =>'global',
				'message' => 'Item'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>190,
				'category' =>'global',
				'message' => 'Price'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>191,
				'category' =>'global',
				'message' => 'SubTotal'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>192,
				'category' =>'global',
				'message' => 'Total'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>193,
				'category' =>'global',
				'message' => 'Payment Data'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>194,
				'category' =>'email',
				'message' => 'This email is a confirmation for the order. To view details or track your order, click on the visit link:'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>195,
				'category' =>'email',
				'message' => 'Please refer to your order ID '
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>196,
				'category' =>'email',
				'message' => ' if you want to contact us about this order.'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>197,
				'category' =>'email',
				'message' => 'Thank you, {storename}'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>198,
				'category' =>'CheckoutForm',
				'message' => 'Phone'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>199,
				'category' =>'email',
				'message' => '{storename} Order Notification {orderid}'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>200,
				'category' =>'global',
				'message' => '{name} : {storename}'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>201,
				'category' =>'wishlist',
				'message' => 'Please check out my shopping cart at {url}'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>202,
				'category' =>'cart',
				'message' => 'Clear Cart'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>203,
				'category' =>'cart',
				'message' => 'Are you sure you want to erase your cart items?'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>204,
				'category' =>'cart',
				'message' => 'Email Cart'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>205,
				'category' =>'cart',
				'message' => 'Continue Shopping'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>206,
				'category' =>'cart',
				'message' => 'Update Cart'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>207,
				'category' =>'wishlist',
				'message' => 'Share my Cart'
			));

		$this->insert('xlsws_stringsource',array(
				'id'=>208,
				'category' =>'wishlist',
				'message' => 'No publicly searchable wish lists for this email address.'
			));

	}

	public function down()
	{
		$this->delete('xlsws_stringsource');

	}
}