<?php
/**
 * A2S3 class
 *
 * A wrapper class to the Amazon S3 storage client
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with Amazon Simple Storage Service
 *
 * @method Model abortMultipartUpload(array $args = array()) {@command S3 AbortMultipartUpload}
 * @method Model completeMultipartUpload(array $args = array()) {@command S3 CompleteMultipartUpload}
 * @method Model copyObject(array $args = array()) {@command S3 CopyObject}
 * @method Model createBucket(array $args = array()) {@command S3 CreateBucket}
 * @method Model createMultipartUpload(array $args = array()) {@command S3 CreateMultipartUpload}
 * @method Model deleteBucket(array $args = array()) {@command S3 DeleteBucket}
 * @method Model deleteBucketCors(array $args = array()) {@command S3 DeleteBucketCors}
 * @method Model deleteBucketLifecycle(array $args = array()) {@command S3 DeleteBucketLifecycle}
 * @method Model deleteBucketPolicy(array $args = array()) {@command S3 DeleteBucketPolicy}
 * @method Model deleteBucketTagging(array $args = array()) {@command S3 DeleteBucketTagging}
 * @method Model deleteBucketWebsite(array $args = array()) {@command S3 DeleteBucketWebsite}
 * @method Model deleteObject(array $args = array()) {@command S3 DeleteObject}
 * @method Model deleteObjects(array $args = array()) {@command S3 DeleteObjects}
 * @method Model getBucketAcl(array $args = array()) {@command S3 GetBucketAcl}
 * @method Model getBucketCors(array $args = array()) {@command S3 GetBucketCors}
 * @method Model getBucketLifecycle(array $args = array()) {@command S3 GetBucketLifecycle}
 * @method Model getBucketLocation(array $args = array()) {@command S3 GetBucketLocation}
 * @method Model getBucketLogging(array $args = array()) {@command S3 GetBucketLogging}
 * @method Model getBucketNotification(array $args = array()) {@command S3 GetBucketNotification}
 * @method Model getBucketPolicy(array $args = array()) {@command S3 GetBucketPolicy}
 * @method Model getBucketRequestPayment(array $args = array()) {@command S3 GetBucketRequestPayment}
 * @method Model getBucketTagging(array $args = array()) {@command S3 GetBucketTagging}
 * @method Model getBucketVersioning(array $args = array()) {@command S3 GetBucketVersioning}
 * @method Model getBucketWebsite(array $args = array()) {@command S3 GetBucketWebsite}
 * @method Model getObject(array $args = array()) {@command S3 GetObject}
 * @method Model getObjectAcl(array $args = array()) {@command S3 GetObjectAcl}
 * @method Model getObjectTorrent(array $args = array()) {@command S3 GetObjectTorrent}
 * @method Model headBucket(array $args = array()) {@command S3 HeadBucket}
 * @method Model headObject(array $args = array()) {@command S3 HeadObject}
 * @method Model listBuckets(array $args = array()) {@command S3 ListBuckets}
 * @method Model listMultipartUploads(array $args = array()) {@command S3 ListMultipartUploads}
 * @method Model listObjectVersions(array $args = array()) {@command S3 ListObjectVersions}
 * @method Model listObjects(array $args = array()) {@command S3 ListObjects}
 * @method Model listParts(array $args = array()) {@command S3 ListParts}
 * @method Model putBucketAcl(array $args = array()) {@command S3 PutBucketAcl}
 * @method Model putBucketCors(array $args = array()) {@command S3 PutBucketCors}
 * @method Model putBucketLifecycle(array $args = array()) {@command S3 PutBucketLifecycle}
 * @method Model putBucketLogging(array $args = array()) {@command S3 PutBucketLogging}
 * @method Model putBucketNotification(array $args = array()) {@command S3 PutBucketNotification}
 * @method Model putBucketPolicy(array $args = array()) {@command S3 PutBucketPolicy}
 * @method Model putBucketRequestPayment(array $args = array()) {@command S3 PutBucketRequestPayment}
 * @method Model putBucketTagging(array $args = array()) {@command S3 PutBucketTagging}
 * @method Model putBucketVersioning(array $args = array()) {@command S3 PutBucketVersioning}
 * @method Model putBucketWebsite(array $args = array()) {@command S3 PutBucketWebsite}
 * @method Model putObject(array $args = array()) {@command S3 PutObject}
 * @method Model putObjectAcl(array $args = array()) {@command S3 PutObjectAcl}
 * @method Model restoreObject(array $args = array()) {@command S3 RestoreObject}
 * @method Model uploadPart(array $args = array()) {@command S3 UploadPart}
 * @method Model uploadPartCopy(array $args = array()) {@command S3 UploadPartCopy}
 * @method waitUntilBucketExists(array $input) Wait until a bucket exists. The input array uses the parameters of the HeadBucket operation and waiter specific settings
 * @method waitUntilBucketNotExists(array $input) Wait until a bucket does not exist. The input array uses the parameters of the HeadBucket operation and waiter specific settings
 * @method waitUntilObjectExists(array $input) Wait until an object exists. The input array uses the parameters of the HeadObject operation and waiter specific settings
 */

class A2S3 extends A2Base
{
	/**
	 * @return Aws\S3\S3Client
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_S3);
		}
		return $this->_client;
	}
}