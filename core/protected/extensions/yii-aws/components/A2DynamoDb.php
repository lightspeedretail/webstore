<?php
/**
 * A2DynamoDb class
 *
 * A wrapper class of the Client to interact with Amazon DynamoDB
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with Amazon DynamoDB
 *
 * @method Model batchGetItem(array $args = array()) {@command DynamoDb BatchGetItem}
 * @method Model batchWriteItem(array $args = array()) {@command DynamoDb BatchWriteItem}
 * @method Model createTable(array $args = array()) {@command DynamoDb CreateTable}
 * @method Model deleteItem(array $args = array()) {@command DynamoDb DeleteItem}
 * @method Model deleteTable(array $args = array()) {@command DynamoDb DeleteTable}
 * @method Model describeTable(array $args = array()) {@command DynamoDb DescribeTable}
 * @method Model getItem(array $args = array()) {@command DynamoDb GetItem}
 * @method Model listTables(array $args = array()) {@command DynamoDb ListTables}
 * @method Model putItem(array $args = array()) {@command DynamoDb PutItem}
 * @method Model query(array $args = array()) {@command DynamoDb Query}
 * @method Model scan(array $args = array()) {@command DynamoDb Scan}
 * @method Model updateItem(array $args = array()) {@command DynamoDb UpdateItem}
 * @method Model updateTable(array $args = array()) {@command DynamoDb UpdateTable}
 * @method waitUntilTableExists(array $input) Wait until a table exists and can be accessed The input array uses the parameters of the DescribeTable operation and waiter specific settings
 * @method waitUntilTableNotExists(array $input) Wait until a table is deleted The input array uses the parameters of the DescribeTable operation and waiter specific settings
 */
class A2DynamoDb extends A2Base
{
	/**
	 * @return Aws\CloudFront\CloudFrontClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_DYNAMODB);
		}
		return $this->_client;
	}
}