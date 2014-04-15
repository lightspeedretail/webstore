<?php
/*
 * Empty event handlers so we can extend without causing errors
 */
class ApplicationComponent extends CApplicationComponent {



	//For CEventProduct,CEventPhoto,CEventOrder
	public function onSaveProduct($event) {}
	public function onUpdateInventory($event) {}
	public function onActionProductView($event) {}
	public function onBeforeCreateOrder($event) {}
	public function onCreateOrder($event) {}
	public function onDownloadOrders($event) {}
	public function onActionUploadProduct($event) {}
	public function onActionUploadPrice($event) {}

	public function onDeletePhoto($event) {}
	public function onActionUploadPhoto($event) {}
	public function onActionUploadInventory($event) {}
	public function onActionVerifyProductUpload($event) {}
	public function onActionVerifyProductUpdate($event) {}
	public function onActionListOrders($event) {}

	public function onFlushTable($event) {}


}