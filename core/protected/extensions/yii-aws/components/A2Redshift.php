<?php
/**
 * A2Redshift class
 *
 * A wrapper class for the Client to interact with Amazon Redshift
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with Amazon Redshift
 *
 * @method Model authorizeClusterSecurityGroupIngress(array $args = array()) {@command Redshift AuthorizeClusterSecurityGroupIngress}
 * @method Model copyClusterSnapshot(array $args = array()) {@command Redshift CopyClusterSnapshot}
 * @method Model createCluster(array $args = array()) {@command Redshift CreateCluster}
 * @method Model createClusterParameterGroup(array $args = array()) {@command Redshift CreateClusterParameterGroup}
 * @method Model createClusterSecurityGroup(array $args = array()) {@command Redshift CreateClusterSecurityGroup}
 * @method Model createClusterSnapshot(array $args = array()) {@command Redshift CreateClusterSnapshot}
 * @method Model createClusterSubnetGroup(array $args = array()) {@command Redshift CreateClusterSubnetGroup}
 * @method Model deleteCluster(array $args = array()) {@command Redshift DeleteCluster}
 * @method Model deleteClusterParameterGroup(array $args = array()) {@command Redshift DeleteClusterParameterGroup}
 * @method Model deleteClusterSecurityGroup(array $args = array()) {@command Redshift DeleteClusterSecurityGroup}
 * @method Model deleteClusterSnapshot(array $args = array()) {@command Redshift DeleteClusterSnapshot}
 * @method Model deleteClusterSubnetGroup(array $args = array()) {@command Redshift DeleteClusterSubnetGroup}
 * @method Model describeClusterParameterGroups(array $args = array()) {@command Redshift DescribeClusterParameterGroups}
 * @method Model describeClusterParameters(array $args = array()) {@command Redshift DescribeClusterParameters}
 * @method Model describeClusterSecurityGroups(array $args = array()) {@command Redshift DescribeClusterSecurityGroups}
 * @method Model describeClusterSnapshots(array $args = array()) {@command Redshift DescribeClusterSnapshots}
 * @method Model describeClusterSubnetGroups(array $args = array()) {@command Redshift DescribeClusterSubnetGroups}
 * @method Model describeClusterVersions(array $args = array()) {@command Redshift DescribeClusterVersions}
 * @method Model describeClusters(array $args = array()) {@command Redshift DescribeClusters}
 * @method Model describeDefaultClusterParameters(array $args = array()) {@command Redshift DescribeDefaultClusterParameters}
 * @method Model describeEvents(array $args = array()) {@command Redshift DescribeEvents}
 * @method Model describeOrderableClusterOptions(array $args = array()) {@command Redshift DescribeOrderableClusterOptions}
 * @method Model describeReservedNodeOfferings(array $args = array()) {@command Redshift DescribeReservedNodeOfferings}
 * @method Model describeReservedNodes(array $args = array()) {@command Redshift DescribeReservedNodes}
 * @method Model describeResize(array $args = array()) {@command Redshift DescribeResize}
 * @method Model modifyCluster(array $args = array()) {@command Redshift ModifyCluster}
 * @method Model modifyClusterParameterGroup(array $args = array()) {@command Redshift ModifyClusterParameterGroup}
 * @method Model modifyClusterSubnetGroup(array $args = array()) {@command Redshift ModifyClusterSubnetGroup}
 * @method Model purchaseReservedNodeOffering(array $args = array()) {@command Redshift PurchaseReservedNodeOffering}
 * @method Model rebootCluster(array $args = array()) {@command Redshift RebootCluster}
 * @method Model resetClusterParameterGroup(array $args = array()) {@command Redshift ResetClusterParameterGroup}
 * @method Model restoreFromClusterSnapshot(array $args = array()) {@command Redshift RestoreFromClusterSnapshot}
 * @method Model revokeClusterSecurityGroupIngress(array $args = array()) {@command Redshift RevokeClusterSecurityGroupIngress}
 * @method waitUntilClusterAvailable(array $input) Wait using the ClusterAvailable waiter. The input array uses the parameters of the DescribeClusters operation and waiter specific settings
 * @method waitUntilClusterDeleted(array $input) Wait using the ClusterDeleted waiter. The input array uses the parameters of the DescribeClusters operation and waiter specific settings
 * @method waitUntilSnapshotAvailable(array $input) Wait using the SnapshotAvailable waiter. The input array uses the parameters of the DescribeClusterSnapshots operation and waiter specific settings
 */
class A2Redshift extends A2S3
{
	/**
	 * @return Aws\Redshift\RedshiftClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_REDSHIFT);
		}
		return $this->_client;
	}
}