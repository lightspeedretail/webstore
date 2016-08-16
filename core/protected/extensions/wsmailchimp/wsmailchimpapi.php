<?php

/**
 * http://developer.mailchimp.com/documentation/mailchimp/guides/get-started-with-mailchimp-api-3/
 * Proprietary class created especially for Web Store using Mailchimp's API v3.0
 * Created August 2, 2016 11:41 AM
 */
class wsmailchimpapi
{
	public $version = '3.0';

	public $apiKey;
	public $apiUrl;
	public $username;

	public $errorMessage;
	public $errorCode;

	/**
	 * Set the api key, url and username upon instantiation.
	 * @param string $apiKey
	 */
	public function __construct($apiKey)
	{
		$dataCenter = "us1";
		if (strstr($apiKey, "-"))
		{
			list($key, $dataCenter) = explode("-", $apiKey, 2);
			if (!$dataCenter)
			{
				$dataCenter = "us1";
			}
		}

		$this->apiKey = $apiKey;
		$this->apiUrl = "https://$dataCenter.api.mailchimp.com/$this->version";
		$this->username = XLSWS_BUILDDATE;
	}

	/**
	 * Send the request to MailChimp and get the response.
	 *
	 * @param string $action
	 * @param string $path
	 * @param array $body
	 * @return bool|array
	 */
	public function callServer($action, $path, array $body = [])
	{
		$headers = [];
		$url = $this->apiUrl . $path;
		$ch = curl_init($url);

		if (!empty($body))
		{
			$headers[] = 'Content-type: application/json';
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		}

		switch ($action)
		{
			case 'GET':
				break;

			case 'POST':
				curl_setopt($ch, CURLOPT_POST, true);
				break;

			default:
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $action);
		}

		// Basic Authentication
		curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->apiKey");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = json_decode(curl_exec($ch), true);
		$headers = curl_getinfo($ch);
		curl_close($ch);

		if (is_array($response) && array_key_exists('status', $response))
		{
			if ($response['status'] >= 400)
			{
				$this->errorCode = $response['status'];
				$this->errorMessage = $response['status'] . ' ' . $response['title'] . ': ' . $response['detail'];
				return false;
			}
		}

		return $response;
	}

	/**
	 * Retrieve all of the mailing lists defined for the user account.
	 * @return mixed
	 */
	public function getAllLists()
	{
		return $this->callServer('GET', '/lists');
	}

	/**
	 * Add a member to the mailing list.
	 *
	 * @param $listId
	 * @param Customer $customer
	 * @return void
	 */
	public function subscribe($listId, Customer $customer)
	{
		$body = [
			'email_address' => $customer->email,
			'status' => 'subscribed',
			'merge_fields' => [
				'FNAME' => $customer->first_name,
				'LNAME' => $customer->last_name,
			],
			'ip_signup' => _xls_get_ip(),
			'timestamp_signup' => date("Y-m-d H:i:s")
		];

		$response = $this->callServer('POST', "/lists/$listId/members", $body);
	}

	/**
	 * Find and return a specific member.
	 * The API doesn't support filters it seems so we have to grab the
	 * entire member list for that mailing list and loop through it.
	 *
	 * @param $listId
	 * @param $email
	 * @return bool|wsmailchimp
	 */
	public function findSubscriber($listId, $email)
	{
		$members = $this->callServer('GET', "/lists/$listId/members");

		foreach ($members['members'] as $member)
		{
			if ($member['email_address'] === $email)
			{
				return $member;
			}
		}

		return false;
	}

	/**
	 * Update the member's status to 'unsubscribed'.
	 *
	 * @param string $listId
	 * @param string $subscriberId
	 * @return array|bool
	 */
	public function unsubscribe($listId, $subscriberId)
	{
		$body = ['status' => 'unsubscribed'];
		return $this->callServer('PATCH', "/lists/$listId/members/$subscriberId", $body);
	}

	/**
	 * We don't update the status even if it might not be set to 'subscribed'.
	 * The merchant can update the status directly in their Mailchimp account
	 * and we do not wish to override those changes.
	 *
	 * @param string $listId
	 * @param string $subscriberId
	 * @param Customer $customer
	 * @return array|bool
	 */
	public function updateSubscriber($listId, $subscriberId, Customer $customer)
	{
		$body = [
			'email_address' => $customer->email,
			'merge_fields' => [
				'FNAME' => $customer->first_name,
				'LNAME' => $customer->last_name,
			]
		];

		return $this->callServer('PATCH', "/lists/$listId/members/$subscriberId", $body);
	}

	/**
	 * http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/#delete-delete_lists_list_id_members_subscriber_hash
	 * We get a null response upon successful deletion.
	 *
	 * @param $listId
	 * @param $subscriberId
	 * @return bool
	 */
	public function deleteSubscriber($listId, $subscriberId)
	{
		return is_null($this->callServer('DELETE', "/lists/$listId/members/$subscriberId"));
	}
}
