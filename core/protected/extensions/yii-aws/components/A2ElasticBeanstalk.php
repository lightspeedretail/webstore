<?php
/**
 * A2ElasticBeanstalk class
 *
 * A wrapper class of the Client to interact with AWS Elastic Beanstalk
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with AWS Elastic Beanstalk
 *
 * @method Model checkDNSAvailability(array $args = array()) {@command ElasticBeanstalk CheckDNSAvailability}
 * @method Model createApplication(array $args = array()) {@command ElasticBeanstalk CreateApplication}
 * @method Model createApplicationVersion(array $args = array()) {@command ElasticBeanstalk CreateApplicationVersion}
 * @method Model createConfigurationTemplate(array $args = array()) {@command ElasticBeanstalk CreateConfigurationTemplate}
 * @method Model createEnvironment(array $args = array()) {@command ElasticBeanstalk CreateEnvironment}
 * @method Model createStorageLocation(array $args = array()) {@command ElasticBeanstalk CreateStorageLocation}
 * @method Model deleteApplication(array $args = array()) {@command ElasticBeanstalk DeleteApplication}
 * @method Model deleteApplicationVersion(array $args = array()) {@command ElasticBeanstalk DeleteApplicationVersion}
 * @method Model deleteConfigurationTemplate(array $args = array()) {@command ElasticBeanstalk DeleteConfigurationTemplate}
 * @method Model deleteEnvironmentConfiguration(array $args = array()) {@command ElasticBeanstalk DeleteEnvironmentConfiguration}
 * @method Model describeApplicationVersions(array $args = array()) {@command ElasticBeanstalk DescribeApplicationVersions}
 * @method Model describeApplications(array $args = array()) {@command ElasticBeanstalk DescribeApplications}
 * @method Model describeConfigurationOptions(array $args = array()) {@command ElasticBeanstalk DescribeConfigurationOptions}
 * @method Model describeConfigurationSettings(array $args = array()) {@command ElasticBeanstalk DescribeConfigurationSettings}
 * @method Model describeEnvironmentResources(array $args = array()) {@command ElasticBeanstalk DescribeEnvironmentResources}
 * @method Model describeEnvironments(array $args = array()) {@command ElasticBeanstalk DescribeEnvironments}
 * @method Model describeEvents(array $args = array()) {@command ElasticBeanstalk DescribeEvents}
 * @method Model listAvailableSolutionStacks(array $args = array()) {@command ElasticBeanstalk ListAvailableSolutionStacks}
 * @method Model rebuildEnvironment(array $args = array()) {@command ElasticBeanstalk RebuildEnvironment}
 * @method Model requestEnvironmentInfo(array $args = array()) {@command ElasticBeanstalk RequestEnvironmentInfo}
 * @method Model restartAppServer(array $args = array()) {@command ElasticBeanstalk RestartAppServer}
 * @method Model retrieveEnvironmentInfo(array $args = array()) {@command ElasticBeanstalk RetrieveEnvironmentInfo}
 * @method Model swapEnvironmentCNAMEs(array $args = array()) {@command ElasticBeanstalk SwapEnvironmentCNAMEs}
 * @method Model terminateEnvironment(array $args = array()) {@command ElasticBeanstalk TerminateEnvironment}
 * @method Model updateApplication(array $args = array()) {@command ElasticBeanstalk UpdateApplication}
 * @method Model updateApplicationVersion(array $args = array()) {@command ElasticBeanstalk UpdateApplicationVersion}
 * @method Model updateConfigurationTemplate(array $args = array()) {@command ElasticBeanstalk UpdateConfigurationTemplate}
 * @method Model updateEnvironment(array $args = array()) {@command ElasticBeanstalk UpdateEnvironment}
 * @method Model validateConfigurationSettings(array $args = array()) {@command ElasticBeanstalk ValidateConfigurationSettings}
 * @method waitUntilEnvironmentReady(array $input) Wait using the EnvironmentReady waiter. The input array uses the parameters of the DescribeEnvironments operation and waiter specific settings
 * @method waitUntilEnvironmentTerminated(array $input) Wait using the EnvironmentTerminated waiter. The input array uses the parameters of the DescribeEnvironments operation and waiter specific settings
 */
class A2ElasticBeanstalk extends A2Base
{
	/**
	 * @return Aws\ElasticBeanstalk\ElasticBeanstalkClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_ELASTIC_BEANSTALK);
		}
		return $this->_client;
	}
}