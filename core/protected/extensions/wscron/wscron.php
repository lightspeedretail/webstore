<?php
/**
 * WsCron is called when an order is downloaded to retail.   It is also called
 * when an order is downloaded to onsite.  Tasks that are put here will be run
 * every time an order is downloaded.
 */
class wscron extends CApplicationComponent
{
	/**
	 * Runs any "cron" jobs necessary
	 * @return void
	*/
	public function run()
	{
		// Garbage collection
		Log::garbageCollect();
		Cart::garbageCollect();
		Wishlist::garbageCollect();

		// Check for new version
		_xls_check_version();

		// Attemp to send pending emails
		$this->_sendQueueEmails();
	}


	/**
	 * Try sending e-mails in the queue.
	 *
	 * @return void
	 * @throws CDbException
	 */
	private function _sendQueueEmails()
	{
		$objMails = EmailQueue::model()->findAll("`sent_attempts` < 20 and `sent_attempts` > 0 and `to` IS NOT NULL LIMIT 10");

		foreach ($objMails as $objMail)
		{
			$blnResult = _xls_send_email($objMail->id, true);

			if (!$blnResult)
			{
				EmailQueue::model()->updateByPk($objMail->id, array('sent_attempts' => ($objMail->sent_attempts) + 1));
			}
			else
			{
				$objMail->delete();
			}
		}
	}
}
