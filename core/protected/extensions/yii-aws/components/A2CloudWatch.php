<?php
/**
 * A2CloudWatch class
 *
 * A wrapper class of the Client to interact with Amazon CloudWatch
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with Amazon CloudWatch
 *
 * @method Model deleteAlarms(array $args = array()) {@command CloudWatch DeleteAlarms}
 * @method Model describeAlarmHistory(array $args = array()) {@command CloudWatch DescribeAlarmHistory}
 * @method Model describeAlarms(array $args = array()) {@command CloudWatch DescribeAlarms}
 * @method Model describeAlarmsForMetric(array $args = array()) {@command CloudWatch DescribeAlarmsForMetric}
 * @method Model disableAlarmActions(array $args = array()) {@command CloudWatch DisableAlarmActions}
 * @method Model enableAlarmActions(array $args = array()) {@command CloudWatch EnableAlarmActions}
 * @method Model getMetricStatistics(array $args = array()) {@command CloudWatch GetMetricStatistics}
 * @method Model listMetrics(array $args = array()) {@command CloudWatch ListMetrics}
 * @method Model putMetricAlarm(array $args = array()) {@command CloudWatch PutMetricAlarm}
 * @method Model putMetricData(array $args = array()) {@command CloudWatch PutMetricData}
 * @method Model setAlarmState(array $args = array()) {@command CloudWatch SetAlarmState}
 */
class A2CloudWatch extends A2Base
{
	/**
	 * @return Aws\CloudFront\CloudFrontClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_CLOUDWATCH);
		}
		return $this->_client;
	}
}