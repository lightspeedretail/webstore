<?php
/**
 * A2ElasticTranscoder class
 *
 * A wrapper class for the Client to interact with Amazon Glacier
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with Amazon Glacier
 *
 * @method Model abortMultipartUpload(array $args = array()) {@command Glacier AbortMultipartUpload}
 * @method Model completeMultipartUpload(array $args = array()) {@command Glacier CompleteMultipartUpload}
 * @method Model createVault(array $args = array()) {@command Glacier CreateVault}
 * @method Model deleteArchive(array $args = array()) {@command Glacier DeleteArchive}
 * @method Model deleteVault(array $args = array()) {@command Glacier DeleteVault}
 * @method Model deleteVaultNotifications(array $args = array()) {@command Glacier DeleteVaultNotifications}
 * @method Model describeJob(array $args = array()) {@command Glacier DescribeJob}
 * @method Model describeVault(array $args = array()) {@command Glacier DescribeVault}
 * @method Model getJobOutput(array $args = array()) {@command Glacier GetJobOutput}
 * @method Model getVaultNotifications(array $args = array()) {@command Glacier GetVaultNotifications}
 * @method Model initiateJob(array $args = array()) {@command Glacier InitiateJob}
 * @method Model initiateMultipartUpload(array $args = array()) {@command Glacier InitiateMultipartUpload}
 * @method Model listJobs(array $args = array()) {@command Glacier ListJobs}
 * @method Model listMultipartUploads(array $args = array()) {@command Glacier ListMultipartUploads}
 * @method Model listParts(array $args = array()) {@command Glacier ListParts}
 * @method Model listVaults(array $args = array()) {@command Glacier ListVaults}
 * @method Model setVaultNotifications(array $args = array()) {@command Glacier SetVaultNotifications}
 * @method Model uploadArchive(array $args = array()) {@command Glacier UploadArchive}
 * @method Model uploadMultipartPart(array $args = array()) {@command Glacier UploadMultipartPart}
 * @method waitUntilVaultExists(array $input) Wait until a vault can be accessed. The input array uses the parameters of the DescribeVault operation and waiter specific settings
 * @method waitUntilVaultNotExists(array $input) Wait until a vault is deleted. The input array uses the parameters of the DescribeVault operation and waiter specific settings
 */
class A2Glacier extends A2S3
{
	/**
	 * @return Aws\Ses\SesClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_GLACIER);
		}
		return $this->_client;
	}
}