<?php
/**
 * A2Sns class
 *
 * A wrapper class for the Client to interact with Amazon Simple Notification Service
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with Amazon Simple Notification Service
 *
 * @method Model addPermission(array $args = array()) {@command Sns AddPermission}
 * @method Model confirmSubscription(array $args = array()) {@command Sns ConfirmSubscription}
 * @method Model createTopic(array $args = array()) {@command Sns CreateTopic}
 * @method Model deleteTopic(array $args = array()) {@command Sns DeleteTopic}
 * @method Model getSubscriptionAttributes(array $args = array()) {@command Sns GetSubscriptionAttributes}
 * @method Model getTopicAttributes(array $args = array()) {@command Sns GetTopicAttributes}
 * @method Model listSubscriptions(array $args = array()) {@command Sns ListSubscriptions}
 * @method Model listSubscriptionsByTopic(array $args = array()) {@command Sns ListSubscriptionsByTopic}
 * @method Model listTopics(array $args = array()) {@command Sns ListTopics}
 * @method Model publish(array $args = array()) {@command Sns Publish}
 * @method Model removePermission(array $args = array()) {@command Sns RemovePermission}
 * @method Model setSubscriptionAttributes(array $args = array()) {@command Sns SetSubscriptionAttributes}
 * @method Model setTopicAttributes(array $args = array()) {@command Sns SetTopicAttributes}
 * @method Model subscribe(array $args = array()) {@command Sns Subscribe}
 * @method Model unsubscribe(array $args = array()) {@command Sns Unsubscribe}
 */
class A2Sns extends A2S3
{
	/**
	 * @return Aws\Sns\SnsClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_SNS);
		}
		return $this->_client;
	}
}