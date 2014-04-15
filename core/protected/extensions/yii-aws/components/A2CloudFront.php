<?php
/**
 * A2CloudFront class
 *
 * A wrapper class of the Client to interact with Amazon CloudFront
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with Amazon CloudFront
 *
 * @method Model createCloudFrontOriginAccessIdentity(array $args = array()) {@command CloudFront CreateCloudFrontOriginAccessIdentity}
 * @method Model createDistribution(array $args = array()) {@command CloudFront CreateDistribution}
 * @method Model createInvalidation(array $args = array()) {@command CloudFront CreateInvalidation}
 * @method Model createStreamingDistribution(array $args = array()) {@command CloudFront CreateStreamingDistribution}
 * @method Model deleteCloudFrontOriginAccessIdentity(array $args = array()) {@command CloudFront DeleteCloudFrontOriginAccessIdentity}
 * @method Model deleteDistribution(array $args = array()) {@command CloudFront DeleteDistribution}
 * @method Model deleteStreamingDistribution(array $args = array()) {@command CloudFront DeleteStreamingDistribution}
 * @method Model getCloudFrontOriginAccessIdentity(array $args = array()) {@command CloudFront GetCloudFrontOriginAccessIdentity}
 * @method Model getCloudFrontOriginAccessIdentityConfig(array $args = array()) {@command CloudFront GetCloudFrontOriginAccessIdentityConfig}
 * @method Model getDistribution(array $args = array()) {@command CloudFront GetDistribution}
 * @method Model getDistributionConfig(array $args = array()) {@command CloudFront GetDistributionConfig}
 * @method Model getInvalidation(array $args = array()) {@command CloudFront GetInvalidation}
 * @method Model getStreamingDistribution(array $args = array()) {@command CloudFront GetStreamingDistribution}
 * @method Model getStreamingDistributionConfig(array $args = array()) {@command CloudFront GetStreamingDistributionConfig}
 * @method Model listCloudFrontOriginAccessIdentities(array $args = array()) {@command CloudFront ListCloudFrontOriginAccessIdentities}
 * @method Model listDistributions(array $args = array()) {@command CloudFront ListDistributions}
 * @method Model listInvalidations(array $args = array()) {@command CloudFront ListInvalidations}
 * @method Model listStreamingDistributions(array $args = array()) {@command CloudFront ListStreamingDistributions}
 * @method Model updateCloudFrontOriginAccessIdentity(array $args = array()) {@command CloudFront UpdateCloudFrontOriginAccessIdentity}
 * @method Model updateDistribution(array $args = array()) {@command CloudFront UpdateDistribution}
 * @method Model updateStreamingDistribution(array $args = array()) {@command CloudFront UpdateStreamingDistribution}
 * @method waitUntilStreamingDistributionDeployed(array $input) Wait until a streaming distribution is deployed. The input array uses the parameters of the GetStreamingDistribution operation and waiter specific settings
 * @method waitUntilDistributionDeployed(array $input) Wait until a distribution is deployed. The input array uses the parameters of the GetDistribution operation and waiter specific settings
 * @method waitUntilInvalidationCompleted(array $input) Wait until an invalidation has completed. The input array uses the parameters of the GetInvalidation operation and waiter specific settings
 */
class A2CloudFront extends A2Base
{
	/**
	 * @return Aws\CloudFront\CloudFrontClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_CLOUDFRONT);
		}
		return $this->_client;
	}
}