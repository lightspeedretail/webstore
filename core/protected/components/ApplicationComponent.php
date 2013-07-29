<?php
/*
 * Empty event handlers so we can extend without causing errors
 */
class ApplicationComponent extends CApplicationComponent {



	//For CEventProduct,CEventPhoto,CEventOrder
	public function onSaveProduct($event) {}
	public function onUpdateInventory($event) {}
	public function onActionProductView($event) {}
	public function onDownloadOrders($event) {}
	public function OnActionUploadProduct($event) {}
	public function OnActionUploadPrice($event) {}

	public function OnActionUploadPhoto($event) {}
	public function OnActionUploadInventory($event) {}
	public function OnActionVerifyProductUpload($event) {}
	public function OnActionVerifyProductUpdate($event) {}
	public function OnActionListOrders($event) {}


}