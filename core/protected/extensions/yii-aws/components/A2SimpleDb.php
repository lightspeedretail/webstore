<?php
/**
 * A2SimpleDb class
 *
 * A wrapper class for the Client to interact with Amazon SimpleDB
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with Amazon SimpleDB
 *
 * @method Model batchDeleteAttributes(array $args = array()) {@command SimpleDb BatchDeleteAttributes}
 * @method Model batchPutAttributes(array $args = array()) {@command SimpleDb BatchPutAttributes}
 * @method Model createDomain(array $args = array()) {@command SimpleDb CreateDomain}
 * @method Model deleteAttributes(array $args = array()) {@command SimpleDb DeleteAttributes}
 * @method Model deleteDomain(array $args = array()) {@command SimpleDb DeleteDomain}
 * @method Model domainMetadata(array $args = array()) {@command SimpleDb DomainMetadata}
 * @method Model getAttributes(array $args = array()) {@command SimpleDb GetAttributes}
 * @method Model listDomains(array $args = array()) {@command SimpleDb ListDomains}
 * @method Model putAttributes(array $args = array()) {@command SimpleDb PutAttributes}
 * @method Model select(array $args = array()) {@command SimpleDb Select}
 */
class A2SimpleDb extends A2S3
{
	/**
	 * @return Aws\SimpleDb\SimpleDbClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_SIMPLEDB);
		}
		return $this->_client;
	}
}