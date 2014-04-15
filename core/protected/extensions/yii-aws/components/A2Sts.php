<?php
/**
 * A2Sts class
 *
 * A wrapper class for the Client to interact with AWS Security Token Service
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with AWS Security Token Service
 *
 * @method Model assumeRole(array $args = array()) {@command Sts AssumeRole}
 * @method Model getFederationToken(array $args = array()) {@command Sts GetFederationToken}
 * @method Model getSessionToken(array $args = array()) {@command Sts GetSessionToken}
 */
class A2Sts extends A2S3
{
	/**
	 * @return Aws\Sts\StsClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_STS);
		}
		return $this->_client;
	}
}