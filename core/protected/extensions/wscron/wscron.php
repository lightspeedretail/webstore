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
			$strFrom = _xls_get_conf('EMAIL_FROM');
			if (strlen(_xls_get_conf('ORDER_FROM'))>0)
				$strFrom =_xls_get_conf('ORDER_FROM');

			$orderEmail = _xls_get_conf('ORDER_FROM','');
			$objMail->to = empty($orderEmail) ? _xls_get_conf('EMAIL_FROM') : $orderEmail;

				Yii::import("ext.KEmail.KEmail");

				$blnResult = Yii::app()->email->send(
				$strFrom,
				$objMail->to,
				$objMail->subject,
				$objMail->htmlbody,
				array(
					'MIME-Version: 1.0',
					'Content-type: text/html; charset=utf8'));

			if (!$blnResult)
				EmailQueue::model()->updateByPk($objMail->id,array('sent_attempts'=>($objMail->sent_attempts)+1));
			else
				$objMail->delete();
		}

	}
}