<?php
/**
 * A2OpsWorks class
 *
 * A wrapper class for the Client to interact with AWS OpsWorks
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with AWS OpsWorks
 *
 * @method Model cloneStack(array $args = array()) {@command OpsWorks CloneStack}
 * @method Model createApp(array $args = array()) {@command OpsWorks CreateApp}
 * @method Model createDeployment(array $args = array()) {@command OpsWorks CreateDeployment}
 * @method Model createInstance(array $args = array()) {@command OpsWorks CreateInstance}
 * @method Model createLayer(array $args = array()) {@command OpsWorks CreateLayer}
 * @method Model createStack(array $args = array()) {@command OpsWorks CreateStack}
 * @method Model createUserProfile(array $args = array()) {@command OpsWorks CreateUserProfile}
 * @method Model deleteApp(array $args = array()) {@command OpsWorks DeleteApp}
 * @method Model deleteInstance(array $args = array()) {@command OpsWorks DeleteInstance}
 * @method Model deleteLayer(array $args = array()) {@command OpsWorks DeleteLayer}
 * @method Model deleteStack(array $args = array()) {@command OpsWorks DeleteStack}
 * @method Model deleteUserProfile(array $args = array()) {@command OpsWorks DeleteUserProfile}
 * @method Model describeApps(array $args = array()) {@command OpsWorks DescribeApps}
 * @method Model describeCommands(array $args = array()) {@command OpsWorks DescribeCommands}
 * @method Model describeDeployments(array $args = array()) {@command OpsWorks DescribeDeployments}
 * @method Model describeElasticIps(array $args = array()) {@command OpsWorks DescribeElasticIps}
 * @method Model describeInstances(array $args = array()) {@command OpsWorks DescribeInstances}
 * @method Model describeLayers(array $args = array()) {@command OpsWorks DescribeLayers}
 * @method Model describeLoadBasedAutoScaling(array $args = array()) {@command OpsWorks DescribeLoadBasedAutoScaling}
 * @method Model describePermissions(array $args = array()) {@command OpsWorks DescribePermissions}
 * @method Model describeRaidArrays(array $args = array()) {@command OpsWorks DescribeRaidArrays}
 * @method Model describeServiceErrors(array $args = array()) {@command OpsWorks DescribeServiceErrors}
 * @method Model describeStacks(array $args = array()) {@command OpsWorks DescribeStacks}
 * @method Model describeTimeBasedAutoScaling(array $args = array()) {@command OpsWorks DescribeTimeBasedAutoScaling}
 * @method Model describeUserProfiles(array $args = array()) {@command OpsWorks DescribeUserProfiles}
 * @method Model describeVolumes(array $args = array()) {@command OpsWorks DescribeVolumes}
 * @method Model getHostnameSuggestion(array $args = array()) {@command OpsWorks GetHostnameSuggestion}
 * @method Model rebootInstance(array $args = array()) {@command OpsWorks RebootInstance}
 * @method Model setLoadBasedAutoScaling(array $args = array()) {@command OpsWorks SetLoadBasedAutoScaling}
 * @method Model setPermission(array $args = array()) {@command OpsWorks SetPermission}
 * @method Model setTimeBasedAutoScaling(array $args = array()) {@command OpsWorks SetTimeBasedAutoScaling}
 * @method Model startInstance(array $args = array()) {@command OpsWorks StartInstance}
 * @method Model startStack(array $args = array()) {@command OpsWorks StartStack}
 * @method Model stopInstance(array $args = array()) {@command OpsWorks StopInstance}
 * @method Model stopStack(array $args = array()) {@command OpsWorks StopStack}
 * @method Model updateApp(array $args = array()) {@command OpsWorks UpdateApp}
 * @method Model updateInstance(array $args = array()) {@command OpsWorks UpdateInstance}
 * @method Model updateLayer(array $args = array()) {@command OpsWorks UpdateLayer}
 * @method Model updateStack(array $args = array()) {@command OpsWorks UpdateStack}
 * @method Model updateUserProfile(array $args = array()) {@command OpsWorks UpdateUserProfile}
 */
class A2OpsWorks extends A2S3
{
	/**
	 * @return Aws\OpsWorks\OpsWorksClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_OPSWORKS);
		}
		return $this->_client;
	}
}