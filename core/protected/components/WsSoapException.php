<?php
/**
 * Custom exception class for SoapController
 */

class WsSoapException extends Exception
{
	const ERROR_AUTH = 'FAIL_AUTH';
	const ERROR_NOT_FOUND = 'NOT_FOUND';
	const ERROR_INVALID_PARAM = 'INVALID_PARAM';
	const ERROR_UNKNOWN = 'UNKNOWN_ERROR';

	public function __construct($message, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}
}
