'use strict';
/* globals $: false */

/**
 * @class Helper
 * @param options
 * @constructor
 */
function Helper (options) {
	this.options = options || {};
	this.formErrorSelector = '.form-error';
	this.$formError = $(this.formErrorSelector);
	this.errorHolderSelector = '.error-holder';
	this.$errorHolder = $(this.errorHolderSelector);
	this.$yiiCsrfToken = $('#yii-csrf-token');
}


/**
 * Get element from array by ID.
 * @param arr array of elements
 * @param id ID of elements to retrieve
 * @returns element to retrieve, or empty object if not found
 */
Helper.prototype.getArrayElementById = function(arr, id) {
	return $.grep(arr, function(obj) {
		return obj.id === id;
	})[0] || {};
};

/**
 * Get list of errors as an HTML string.
 * @param errors array of error strings
 * @returns {string} html string
 */
Helper.prototype.getErrorString = function(errors) {
	var errorString = '';
	if (typeof errors === 'string') {
		errorString = '<p>' + errors + '</p>';
	}
	else {
		for (var errorKey in errors) {
			if (errors.hasOwnProperty(errorKey)) {
				errorString += '<p>' + errors[errorKey][0] + '</p>';
			}
		}
	}
	return errorString;
};

/**
 * Show errors.
 * @param parentSelector selector of container where to show the error
 * @param errors list of errors to show
 */
Helper.prototype.showErrors = function(parentSelector, errors) {
	var $parentSelector = $(parentSelector);
	$parentSelector.find(this.formErrorSelector).html(this.getErrorString(errors));
	$parentSelector.find(this.errorHolderSelector).show();
};

/**
 * Clear errors.
 */
Helper.prototype.clearErrors = function() {
	this.$formError.html('');
	this.$errorHolder.hide();
};

/**
 * Convert Form into a Yii-friendly JavaScript object.
 * @param objectName name of object. e.g. 'Customer' or 'CustomerAddress'
 * @param $form jQuery object selecting the form to serialize e.g. $('form')
 * @param objToExtend optional existing object to extend with new data coming
 * from the form
 * @returns {Object} object containing the YII_CSRF_TOKEN and the
 * serialized object with name {objectName}
 */
Helper.prototype.convertFormToObject = function(objectName, $form, objToExtend) {
	var result, formData;
	objToExtend = objToExtend || {};
	formData = $form.serializeObject();
	$.extend(objToExtend, formData, this.fixCheckboxes($form));
	result = {'YII_CSRF_TOKEN': this.$yiiCsrfToken.val()};
	result[objectName] = objToExtend;
	return result;
};

/**
 * By default, the jQuery.serializeArray() and $.fn.serializeObject() functions
 * do not serialize checkboxes the way we would expect them to. Checked
 * checkboxes will be included as checkbox = "on" and unchecked boxes won't be
 * included at all. This function replaces "on" values with true and adds
 * unchecked boxes as false.
 * Reference:
 * //stackoverflow.com/questions/7335281/how-can-i-serializearray-for-unchecked-checkboxes
 * @param $form with checkboxes to fix
 * @returns object with fixed checkboxes as key/value pairs
 */
Helper.prototype.fixCheckboxes = function ($form) {
	var obj = {};
	$.each($form.find('input:checkbox'), function() {
		obj[this.name] = this.checked;
	});
	return obj;
};

/**
 * jQuery plugin to serialize a form into a JavaScript object.
 * Reference: https://github.com/hongymagic/jQuery.serializeObject
 * @returns serialized form as key/value pair object
 */
$.fn.serializeObject = function () {
	var result = {};
	var extend = function (i, element) {
		var node = result[element.name];

		// If node with same name exists already, need to convert it to an array
		// as it is a multi-value field (i.e., checkboxes).
		if (typeof node !== 'undefined' && node !== null) {
			if ($.isArray(node)) {
				node.push(element.value);
			} else {
				result[element.name] = [node, element.value];
			}
		} else {
			result[element.name] = element.value;
		}
	};

	$.each(this.serializeArray(), extend);
	return result;
};