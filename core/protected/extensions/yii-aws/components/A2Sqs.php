<?php
/**
 * A2Sns class
 *
 * A wrapper class for the Client to interact with Amazon Simple Queue Service
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with Amazon Simple Queue Service
 *
 * @method Model addPermission(array $args = array()) {@command Sqs AddPermission}
 * @method Model changeMessageVisibility(array $args = array()) {@command Sqs ChangeMessageVisibility}
 * @method Model changeMessageVisibilityBatch(array $args = array()) {@command Sqs ChangeMessageVisibilityBatch}
 * @method Model createQueue(array $args = array()) {@command Sqs CreateQueue}
 * @method Model deleteMessage(array $args = array()) {@command Sqs DeleteMessage}
 * @method Model deleteMessageBatch(array $args = array()) {@command Sqs DeleteMessageBatch}
 * @method Model deleteQueue(array $args = array()) {@command Sqs DeleteQueue}
 * @method Model getQueueAttributes(array $args = array()) {@command Sqs GetQueueAttributes}
 * @method Model getQueueUrl(array $args = array()) {@command Sqs GetQueueUrl}
 * @method Model listQueues(array $args = array()) {@command Sqs ListQueues}
 * @method Model receiveMessage(array $args = array()) {@command Sqs ReceiveMessage}
 * @method Model removePermission(array $args = array()) {@command Sqs RemovePermission}
 * @method Model sendMessage(array $args = array()) {@command Sqs SendMessage}
 * @method Model sendMessageBatch(array $args = array()) {@command Sqs SendMessageBatch}
 * @method Model setQueueAttributes(array $args = array()) {@command Sqs SetQueueAttributes}
 */
class A2Sqs extends A2S3
{
	/**
	 * @return Aws\Sqs\SqsClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_SQS);
		}
		return $this->_client;
	}
}