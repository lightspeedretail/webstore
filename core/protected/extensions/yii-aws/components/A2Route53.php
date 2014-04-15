<?php
/**
 * A2Route53 class
 *
 * A wrapper class for the Client to interact with Amazon Route 53
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with Amazon Route 53
 *
 * @method Model changeResourceRecordSets(array $args = array()) {@command Route53 ChangeResourceRecordSets}
 * @method Model createHealthCheck(array $args = array()) {@command Route53 CreateHealthCheck}
 * @method Model createHostedZone(array $args = array()) {@command Route53 CreateHostedZone}
 * @method Model deleteHealthCheck(array $args = array()) {@command Route53 DeleteHealthCheck}
 * @method Model deleteHostedZone(array $args = array()) {@command Route53 DeleteHostedZone}
 * @method Model getChange(array $args = array()) {@command Route53 GetChange}
 * @method Model getHealthCheck(array $args = array()) {@command Route53 GetHealthCheck}
 * @method Model getHostedZone(array $args = array()) {@command Route53 GetHostedZone}
 * @method Model listHealthChecks(array $args = array()) {@command Route53 ListHealthChecks}
 * @method Model listHostedZones(array $args = array()) {@command Route53 ListHostedZones}
 * @method Model listResourceRecordSets(array $args = array()) {@command Route53 ListResourceRecordSets}
 */
class A2Redshift extends A2S3
{
	/**
	 * @return Aws\Route53\Route53Client
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_ROUTE53);
		}
		return $this->_client;
	}
}