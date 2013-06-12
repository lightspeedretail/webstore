<?php


/**
 * Extending the built-in Yii Web User class
 */
class AdminUser extends CWebUser {


	public function hasFlashes()
	{
		if (Yii::app()->user->hasFlash('success') ||
			Yii::app()->user->hasFlash('info') ||
			Yii::app()->user->hasFlash('warning') ||
			Yii::app()->user->hasFlash('error'))
			return true; else return false;
	}


	/**
	 * Overrides a Yii method that is used for roles in controllers (accessRules).
	 *
	 * @param string $operation Name of the operation required (here, a role).
	 * @param mixed $params (opt) Parameters for this operation, usually the object to access.
	 * @return bool Permission granted?
	 */
	public function checkAccess($operation, $params=array())
	{
		if (empty($this->id)) {
			// Not identified => no rights
			return false;
		}
		$role = $this->getState("role");

		if ($role === 'admin') {
			return true; // admin role has access to everything
		}
		// allow access if the operation request is the current user's role
		return ($operation === $role);
	}


	public function shouldLogOut()
	{
		//If we're using regular safari but we're logged in as special LS, log out
		$browserString = $_SERVER['HTTP_USER_AGENT'];
		if (stripos($browserString,"AppleWebKit") > 0 &&
			stripos($browserString,"Safari") >0 &&
			$this->getState('internal', false))
		return true;

		return false;

	}

}
