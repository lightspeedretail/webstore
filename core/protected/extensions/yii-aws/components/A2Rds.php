<?php
/**
 * A2Rds class
 *
 * A wrapper class for the Client to interact with Amazon Relational Database Service
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with Amazon Relational Database Service
 *
 * @method Model authorizeDBSecurityGroupIngress(array $args = array()) {@command Rds AuthorizeDBSecurityGroupIngress}
 * @method Model copyDBSnapshot(array $args = array()) {@command Rds CopyDBSnapshot}
 * @method Model createDBInstance(array $args = array()) {@command Rds CreateDBInstance}
 * @method Model createDBInstanceReadReplica(array $args = array()) {@command Rds CreateDBInstanceReadReplica}
 * @method Model createDBParameterGroup(array $args = array()) {@command Rds CreateDBParameterGroup}
 * @method Model createDBSecurityGroup(array $args = array()) {@command Rds CreateDBSecurityGroup}
 * @method Model createDBSnapshot(array $args = array()) {@command Rds CreateDBSnapshot}
 * @method Model createDBSubnetGroup(array $args = array()) {@command Rds CreateDBSubnetGroup}
 * @method Model createOptionGroup(array $args = array()) {@command Rds CreateOptionGroup}
 * @method Model deleteDBInstance(array $args = array()) {@command Rds DeleteDBInstance}
 * @method Model deleteDBParameterGroup(array $args = array()) {@command Rds DeleteDBParameterGroup}
 * @method Model deleteDBSecurityGroup(array $args = array()) {@command Rds DeleteDBSecurityGroup}
 * @method Model deleteDBSnapshot(array $args = array()) {@command Rds DeleteDBSnapshot}
 * @method Model deleteDBSubnetGroup(array $args = array()) {@command Rds DeleteDBSubnetGroup}
 * @method Model deleteOptionGroup(array $args = array()) {@command Rds DeleteOptionGroup}
 * @method Model describeDBEngineVersions(array $args = array()) {@command Rds DescribeDBEngineVersions}
 * @method Model describeDBInstances(array $args = array()) {@command Rds DescribeDBInstances}
 * @method Model describeDBParameterGroups(array $args = array()) {@command Rds DescribeDBParameterGroups}
 * @method Model describeDBParameters(array $args = array()) {@command Rds DescribeDBParameters}
 * @method Model describeDBSecurityGroups(array $args = array()) {@command Rds DescribeDBSecurityGroups}
 * @method Model describeDBSnapshots(array $args = array()) {@command Rds DescribeDBSnapshots}
 * @method Model describeDBSubnetGroups(array $args = array()) {@command Rds DescribeDBSubnetGroups}
 * @method Model describeEngineDefaultParameters(array $args = array()) {@command Rds DescribeEngineDefaultParameters}
 * @method Model describeEvents(array $args = array()) {@command Rds DescribeEvents}
 * @method Model describeOptionGroupOptions(array $args = array()) {@command Rds DescribeOptionGroupOptions}
 * @method Model describeOptionGroups(array $args = array()) {@command Rds DescribeOptionGroups}
 * @method Model describeOrderableDBInstanceOptions(array $args = array()) {@command Rds DescribeOrderableDBInstanceOptions}
 * @method Model describeReservedDBInstances(array $args = array()) {@command Rds DescribeReservedDBInstances}
 * @method Model describeReservedDBInstancesOfferings(array $args = array()) {@command Rds DescribeReservedDBInstancesOfferings}
 * @method Model modifyDBInstance(array $args = array()) {@command Rds ModifyDBInstance}
 * @method Model modifyDBParameterGroup(array $args = array()) {@command Rds ModifyDBParameterGroup}
 * @method Model modifyDBSubnetGroup(array $args = array()) {@command Rds ModifyDBSubnetGroup}
 * @method Model modifyOptionGroup(array $args = array()) {@command Rds ModifyOptionGroup}
 * @method Model purchaseReservedDBInstancesOffering(array $args = array()) {@command Rds PurchaseReservedDBInstancesOffering}
 * @method Model rebootDBInstance(array $args = array()) {@command Rds RebootDBInstance}
 * @method Model resetDBParameterGroup(array $args = array()) {@command Rds ResetDBParameterGroup}
 * @method Model restoreDBInstanceFromDBSnapshot(array $args = array()) {@command Rds RestoreDBInstanceFromDBSnapshot}
 * @method Model restoreDBInstanceToPointInTime(array $args = array()) {@command Rds RestoreDBInstanceToPointInTime}
 * @method Model revokeDBSecurityGroupIngress(array $args = array()) {@command Rds RevokeDBSecurityGroupIngress}
 */
class A2Rds extends A2S3
{
	/**
	 * @return Aws\Rds\RdsClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_RDS);
		}
		return $this->_client;
	}
}