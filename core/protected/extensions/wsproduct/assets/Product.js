'use strict';
/* globals alert: false, $: false */

/**
 * @class Product
 * @classdesc Handles adding products to cart and to wishlist.
 * @param options
 *
 * @property {object} options options passed from the view
 * @property {string} productId id of the product
 * @property {jQuery object} $productSize product size dropdown css id
 * @property {jQuery object} $productColor product color dropdown css id
 * @property {string} productQty product selected quantity
 * @property {number} afterAddCart
 * @property {string} addToCartUrl
 * @property {string} addToWishListUrl
 * @property {string} editCartUrl
 * @property {boolean} isAddable whether a product can be added to cart.
 * @property {boolean} isMaster
 *
 * @constructor
 */
function Product (options) {
	this.options = options || {};
	this.productId = this.options.id || null;
	this.$productSize = $('#SelectSize');
	this.$productColor = $('#SelectColor');
	this.$productQty = $('#' + this.options.qty);
	this.afterAddCart = this.options.afterAddCart;
	this.addToCartUrl = this.options.addToCartUrl;
	this.addToWishListUrl = this.options.addToWishListUrl;
	this.editCartUrl = this.options.editCartUrl;
	this.isAddable = this.options.isAddable;
	this.isMaster = this.options.isMaster;

	$('#addToCart').on('click', this.addToCart.bind(this));
	$('#addToWishList').on('click', this.addToWishList.bind(this));
}

/**
 * Add to cart. Makes a call to the back end to add a product
 * to the cart.
 * alert: Something required an alert to the customer.
 * success: The product was added to the cart.
 */
Product.prototype.addToCart = function () {
	var addToCartSuccess = function(response) {
		if (response.action === 'alert') {
			alert(response.errormsg);
		} else if (response.action === 'success') {
			if (this.afterAddCart === 1) {
				window.location.href = this.editCartUrl;
			} else {
				$('#shoppingcart').replaceWith(response.shoppingcart);
				$('#cartItemsTotal').text(response.totalItemCount);
				window.sleep(250, window.showModal);
			}
		}
	}.bind(this);

	var matrixProduct = {};

	if (this.isAddable === true ) {
		if (this.isMaster === true) {
			matrixProduct = {
				'product_size':  this.$productSize.val(),
				'product_color': this.$productColor.val(),
				'id': this.productId,
				'qty': this.$productQty.val()
			};
		} else {
			matrixProduct = {
				'id': this.productId,
				'qty': this.$productQty.val()
			};
		}
	}

	if (($.isEmptyObject(matrixProduct)) === false) {
		$.post(this.addToCartUrl, matrixProduct, function(response) {
				addToCartSuccess(response);
			}
		);
	}
};

/**
 * Add to wish list. Makes a call to the back end to add a product
 * to a logged in user's wish list.
 * alert: Something required an alert to the customer.
 * success: The product was added to the wish list.
 */
Product.prototype.addToWishList = function() {
	// Note that for adding a product to the cart we send product_size and
	// product_color but when adding it to the wishlist we send size and color.
	//
	// TODO: Modify WishlistAddForm to be consistent with
	// Cart::actionAddToCart. Due to theme copies, we must support the old
	// interface.
	var wishListProduct = {
		'size': this.$productSize.val(),
		'color': this.$productColor.val(),
		'id': this.productId,
		'qty': this.$productQty.val()
	};

	$.post(this.addToWishListUrl, wishListProduct, function(response) {
		if (response === 'multiple') {
			$('#WishitemShare').dialog('open');
		}
		else {
			alert(response);
		}
	});
};
