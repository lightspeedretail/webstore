<?php
/**
 * A2Ses class
 *
 * A wrapper class to the client of Amazon Simple Email Service
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with Amazon Simple Email Service
 *
 * @method Model deleteIdentity(array $args = array()) {@command Ses DeleteIdentity}
 * @method Model deleteVerifiedEmailAddress(array $args = array()) {@command Ses DeleteVerifiedEmailAddress}
 * @method Model getIdentityDkimAttributes(array $args = array()) {@command Ses GetIdentityDkimAttributes}
 * @method Model getIdentityNotificationAttributes(array $args = array()) {@command Ses GetIdentityNotificationAttributes}
 * @method Model getIdentityVerificationAttributes(array $args = array()) {@command Ses GetIdentityVerificationAttributes}
 * @method Model getSendQuota(array $args = array()) {@command Ses GetSendQuota}
 * @method Model getSendStatistics(array $args = array()) {@command Ses GetSendStatistics}
 * @method Model listIdentities(array $args = array()) {@command Ses ListIdentities}
 * @method Model listVerifiedEmailAddresses(array $args = array()) {@command Ses ListVerifiedEmailAddresses}
 * @method Model sendEmail(array $args = array()) {@command Ses SendEmail}
 * @method Model sendRawEmail(array $args = array()) {@command Ses SendRawEmail}
 * @method Model setIdentityDkimEnabled(array $args = array()) {@command Ses SetIdentityDkimEnabled}
 * @method Model setIdentityFeedbackForwardingEnabled(array $args = array()) {@command Ses SetIdentityFeedbackForwardingEnabled}
 * @method Model setIdentityNotificationTopic(array $args = array()) {@command Ses SetIdentityNotificationTopic}
 * @method Model verifyDomainDkim(array $args = array()) {@command Ses VerifyDomainDkim}
 * @method Model verifyDomainIdentity(array $args = array()) {@command Ses VerifyDomainIdentity}
 * @method Model verifyEmailAddress(array $args = array()) {@command Ses VerifyEmailAddress}
 * @method Model verifyEmailIdentity(array $args = array()) {@command Ses VerifyEmailIdentity}
 * @method waitUntilIdentityExists(array $input) Wait using the IdentityExists waiter. The input array uses the parameters of the GetIdentityVerificationAttributes operation and waiter specific settings
 *
 * @link http://docs.aws.amazon.com/aws-sdk-php-2/guide/latest/service-ses.html User guide
 * @link http://docs.aws.amazon.com/aws-sdk-php-2/latest/class-Aws.Ses.SesClient.html API docs
 */
class A2Ses extends A2S3
{
	/**
	 * @return Aws\Ses\SesClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_SES);
		}
		return $this->_client;
	}
}
