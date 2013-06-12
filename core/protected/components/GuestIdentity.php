<?php

/**
 * GuestIdentity is a special identity for creating a user (customer)
 * checking out in guest mode. We can create it without a username/password
 * so we still have a customerid to use in the order process.
 * It's only called during the checkout event if the person explicitly
 * chooses to not create an account or is not logged in.
 * At the completion of checkout, we should immediately log out because we don't
 * want to remain logged in as this guest account.
 */
class GuestIdentity extends CUserIdentity
{

	private $_id;
	private $_fullname;
	private $_firstname;
	/** Override __construct() because we're not passing user/pass
	 */
	public function __construct()
	{
		$user = new Customer;
		$user->record_type = Customer::GUEST;
		$user->last_login = new CDbExpression('NOW()');
		if (!$user->save())
			print_r($user->getErrors());
		$this->setState('fullname', 'Guest');
		$this->setState('firstname', 'Guest');
		$this->_id = $user->id;
	}

	public function getId()
	{
		return $this->_id;
	}


}