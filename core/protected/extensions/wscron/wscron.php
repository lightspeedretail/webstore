<?php


class wscron extends CApplicationComponent {


	/*
	* Runs any cron jobs necessary
	*/
	public function run()
	{

		//Garbage collection
		Log::GarbageCollect();
		Wishlist::GarbageCollect();

		//Check for new version
		_xls_check_version();

		//Pending Emails
		$this->sendQueueEmails();
	}


	public function sendQueueEmails()
	{

		$objMails = EmailQueue::model()->findAll("`sent_attempts` < 20 and `to` IS NOT NULL LIMIT 10");

		foreach ($objMails as $objMail)
		{
			$blnResult = _xls_send_email($objMail->id,true);

			if (!$blnResult)
				EmailQueue::model()->updateByPk($objMail->id,array('sent_attempts'=>($objMail->sent_attempts)+1));
			else
				$objMail->delete();
		}

	}
}