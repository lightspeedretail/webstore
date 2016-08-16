<?php

class wsmailchimp extends ApplicationComponent
{
	public $category = "CEventCustomer";
	public $name = "MailChimp";
	public $version = 1;

	/**
	 * @var wsmailchimpapi
	 */
	protected $api;
	protected $objModule;

	public function init()
	{
		require_once('wsmailchimpapi.php');

		$this->objModule = Modules::LoadByName(get_class($this)); //Load our module entry so we can access settings
		$this->api = new wsmailchimpapi($this->objModule->getConfig('api_key'));
	}

	/**
	 * Attached event for anytime a new customer is created
	 * @param $event
	 * @return bool
	 */
	public function onAddCustomer($event)
	{
		$this->init();

		$objCustomer = $event->objCustomer;

		if ($objCustomer->newsletter_subscribe)
		{
			$listId = $this->getListId($this->objModule->getConfig('list'));

			if (!is_null($listId))
			{
				$this->api->subscribe($listId, $objCustomer);

				if ($this->api->errorCode)
				{
					Yii::log(sprintf("Could not subscribe customer. %s", $this->api->errorMessage), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				Yii::log(sprintf("Mailchimp Mailing List '%s' not found:  ", $this->objModule->getConfig('list')), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			}
		}
		else
		{
			// If the person doesn't want to subscribe, return true.
			// It's not an error result, just do nothing.
			return true;
		}
	}


	/**
	 * Update a customer, which could involve unsubscribing them from the list.
	 * @param $event
	 * @return bool
	 */
	public function onUpdateCustomer($event)
	{
		$this->init();

		$objCustomer = $event->objCustomer;

		$listId = $this->getListId($this->objModule->getConfig('list'));

		if (!is_null($listId))
		{
			// Verify this person is really on the mailing list
			$member = $this->api->findSubscriber($listId, $objCustomer->email);

			if ($this->api->errorCode)
			{
				Yii::log(sprintf("Error encountered attempting to fetch Mailchimp subscriber: %s", $this->api->errorMessage), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			}

			if (!$member)
			{
				Yii::log(sprintf("Mailchimp subscriber %s was not found. Attempting to add to mailing list...", $objCustomer->email), 'info', 'application.'.__CLASS__.'.'.__FUNCTION__);
				return $this->onAddCustomer($event);
			}

			if ($objCustomer->newsletter_subscribe)
			{
				Yii::log("Mailchimp subscriber found: ".print_r($member, true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);

				$updatedMember = $this->api->updateSubscriber($listId, $member['id'], $objCustomer);

				if ($this->api->errorCode)
				{
					Yii::log(sprintf("Error encountered attempting to update Mailchimp subscriber: %s", $this->api->errorMessage), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
					return false;
				}

				Yii::log("Mailchimp subscriber updated: ".print_r($updatedMember, true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);
				return true;
			}
			else
			{
				// Unsubscribe.

				$updatedMember = $this->api->unsubscribe($listId, $member['id']);

				if ($this->api->errorCode)
				{
					Yii::log(sprintf("Could not unsubscribe customer from Mailchimp list: %s", $this->api->errorMessage), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
					return false;
				}

				Yii::log("$objCustomer->email unsubscribed from Mailchimp list successfully: ", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
				return true;
			}
		}

		Yii::log(sprintf("Mailchimp Mailing List '%s' not found:  ", $this->objModule->getConfig('list')), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		return false;
	}

	/**
	 * Remove a customer from the mailing list.
	 *
	 * @param $event
	 * @return bool
	 */
	public function onDeleteCustomer($event)
	{
		$this->init();

		$objCustomer = $event->objCustomer;

		$listId = $this->getListId($this->objModule->getConfig('list'));

		if (!is_null($listId))
		{
			// Verify this person is really on the mailing list
			$member = $this->api->findSubscriber($listId, $objCustomer->email);

			if ($this->api->errorCode)
			{
				Yii::log(sprintf("Error encountered attempting to fetch Mailchimp subscriber: %s", $this->api->errorMessage), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			}

			if (!$member)
			{
				// They are already gone! If they ever existed...
				return true;
			}

			Yii::log("Mailchimp subscriber found: ".print_r($member, true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);

			$deletedMember = $this->api->deleteSubscriber($listId, $member['id']);

			if ($this->api->errorCode)
			{
				Yii::log(sprintf("Could not delete customer from Mailchimp list: %s", $this->api->errorMessage), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			}

			Yii::log("$objCustomer->email deleted from Mailchimp list successfully: ", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			return true;
		}

		Yii::log(sprintf("Mailchimp Mailing List '%s' not found:  ", $this->objModule->getConfig('list')), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		return false;
	}

	/**
	 * Getter for the protected API variable.
	 * @return wsmailchimpapi
	 */
	public function getApi()
	{
		return $this->api;
	}

	/**
	 * Look up ListID on Mailchimp by name
	 * @param $strList
	 * @return null
	 */
	protected function getListId($strList)
	{
		$arrLists = $this->api->getAlllists();
		foreach($arrLists['lists'] as $list)
		{
			if ($list['name'] == $strList)
			{
				return $list['id'];
			}
		}

		return null;
	}
}
