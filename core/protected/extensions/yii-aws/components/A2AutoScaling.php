<?php
/**
 * A2AutoScaling class
 *
 * A wrapper class of the Client to interact with Auto Scaling
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with Auto Scaling
 *
 * @method Model createAutoScalingGroup(array $args = array()) {@command AutoScaling CreateAutoScalingGroup}
 * @method Model createLaunchConfiguration(array $args = array()) {@command AutoScaling CreateLaunchConfiguration}
 * @method Model createOrUpdateTags(array $args = array()) {@command AutoScaling CreateOrUpdateTags}
 * @method Model deleteAutoScalingGroup(array $args = array()) {@command AutoScaling DeleteAutoScalingGroup}
 * @method Model deleteLaunchConfiguration(array $args = array()) {@command AutoScaling DeleteLaunchConfiguration}
 * @method Model deleteNotificationConfiguration(array $args = array()) {@command AutoScaling DeleteNotificationConfiguration}
 * @method Model deletePolicy(array $args = array()) {@command AutoScaling DeletePolicy}
 * @method Model deleteScheduledAction(array $args = array()) {@command AutoScaling DeleteScheduledAction}
 * @method Model deleteTags(array $args = array()) {@command AutoScaling DeleteTags}
 * @method Model describeAdjustmentTypes(array $args = array()) {@command AutoScaling DescribeAdjustmentTypes}
 * @method Model describeAutoScalingGroups(array $args = array()) {@command AutoScaling DescribeAutoScalingGroups}
 * @method Model describeAutoScalingInstances(array $args = array()) {@command AutoScaling DescribeAutoScalingInstances}
 * @method Model describeAutoScalingNotificationTypes(array $args = array()) {@command AutoScaling DescribeAutoScalingNotificationTypes}
 * @method Model describeLaunchConfigurations(array $args = array()) {@command AutoScaling DescribeLaunchConfigurations}
 * @method Model describeMetricCollectionTypes(array $args = array()) {@command AutoScaling DescribeMetricCollectionTypes}
 * @method Model describeNotificationConfigurations(array $args = array()) {@command AutoScaling DescribeNotificationConfigurations}
 * @method Model describePolicies(array $args = array()) {@command AutoScaling DescribePolicies}
 * @method Model describeScalingActivities(array $args = array()) {@command AutoScaling DescribeScalingActivities}
 * @method Model describeScalingProcessTypes(array $args = array()) {@command AutoScaling DescribeScalingProcessTypes}
 * @method Model describeScheduledActions(array $args = array()) {@command AutoScaling DescribeScheduledActions}
 * @method Model describeTags(array $args = array()) {@command AutoScaling DescribeTags}
 * @method Model describeTerminationPolicyTypes(array $args = array()) {@command AutoScaling DescribeTerminationPolicyTypes}
 * @method Model disableMetricsCollection(array $args = array()) {@command AutoScaling DisableMetricsCollection}
 * @method Model enableMetricsCollection(array $args = array()) {@command AutoScaling EnableMetricsCollection}
 * @method Model executePolicy(array $args = array()) {@command AutoScaling ExecutePolicy}
 * @method Model putNotificationConfiguration(array $args = array()) {@command AutoScaling PutNotificationConfiguration}
 * @method Model putScalingPolicy(array $args = array()) {@command AutoScaling PutScalingPolicy}
 * @method Model putScheduledUpdateGroupAction(array $args = array()) {@command AutoScaling PutScheduledUpdateGroupAction}
 * @method Model resumeProcesses(array $args = array()) {@command AutoScaling ResumeProcesses}
 * @method Model setDesiredCapacity(array $args = array()) {@command AutoScaling SetDesiredCapacity}
 * @method Model setInstanceHealth(array $args = array()) {@command AutoScaling SetInstanceHealth}
 * @method Model suspendProcesses(array $args = array()) {@command AutoScaling SuspendProcesses}
 * @method Model terminateInstanceInAutoScalingGroup(array $args = array()) {@command AutoScaling TerminateInstanceInAutoScalingGroup}
 * @method Model updateAutoScalingGroup(array $args = array()) {@command AutoScaling UpdateAutoScalingGroup}
 */
class A2AutoScaling extends A2Base
{
	/**
	 * @return Aws\AutoScaling\AutoScalingClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_AUTOSCALING);
		}
		return $this->_client;
	}
}