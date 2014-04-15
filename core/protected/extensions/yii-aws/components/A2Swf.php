<?php
/**
 * A2Sns class
 *
 * A wrapper class for the Client to interact with Amazon Simple Workflow Service
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with Amazon Simple Workflow Service
 *
 * @method Model countClosedWorkflowExecutions(array $args = array()) {@command Swf CountClosedWorkflowExecutions}
 * @method Model countOpenWorkflowExecutions(array $args = array()) {@command Swf CountOpenWorkflowExecutions}
 * @method Model countPendingActivityTasks(array $args = array()) {@command Swf CountPendingActivityTasks}
 * @method Model countPendingDecisionTasks(array $args = array()) {@command Swf CountPendingDecisionTasks}
 * @method Model deprecateActivityType(array $args = array()) {@command Swf DeprecateActivityType}
 * @method Model deprecateDomain(array $args = array()) {@command Swf DeprecateDomain}
 * @method Model deprecateWorkflowType(array $args = array()) {@command Swf DeprecateWorkflowType}
 * @method Model describeActivityType(array $args = array()) {@command Swf DescribeActivityType}
 * @method Model describeDomain(array $args = array()) {@command Swf DescribeDomain}
 * @method Model describeWorkflowExecution(array $args = array()) {@command Swf DescribeWorkflowExecution}
 * @method Model describeWorkflowType(array $args = array()) {@command Swf DescribeWorkflowType}
 * @method Model getWorkflowExecutionHistory(array $args = array()) {@command Swf GetWorkflowExecutionHistory}
 * @method Model listActivityTypes(array $args = array()) {@command Swf ListActivityTypes}
 * @method Model listClosedWorkflowExecutions(array $args = array()) {@command Swf ListClosedWorkflowExecutions}
 * @method Model listDomains(array $args = array()) {@command Swf ListDomains}
 * @method Model listOpenWorkflowExecutions(array $args = array()) {@command Swf ListOpenWorkflowExecutions}
 * @method Model listWorkflowTypes(array $args = array()) {@command Swf ListWorkflowTypes}
 * @method Model pollForActivityTask(array $args = array()) {@command Swf PollForActivityTask}
 * @method Model pollForDecisionTask(array $args = array()) {@command Swf PollForDecisionTask}
 * @method Model recordActivityTaskHeartbeat(array $args = array()) {@command Swf RecordActivityTaskHeartbeat}
 * @method Model registerActivityType(array $args = array()) {@command Swf RegisterActivityType}
 * @method Model registerDomain(array $args = array()) {@command Swf RegisterDomain}
 * @method Model registerWorkflowType(array $args = array()) {@command Swf RegisterWorkflowType}
 * @method Model requestCancelWorkflowExecution(array $args = array()) {@command Swf RequestCancelWorkflowExecution}
 * @method Model respondActivityTaskCanceled(array $args = array()) {@command Swf RespondActivityTaskCanceled}
 * @method Model respondActivityTaskCompleted(array $args = array()) {@command Swf RespondActivityTaskCompleted}
 * @method Model respondActivityTaskFailed(array $args = array()) {@command Swf RespondActivityTaskFailed}
 * @method Model respondDecisionTaskCompleted(array $args = array()) {@command Swf RespondDecisionTaskCompleted}
 * @method Model signalWorkflowExecution(array $args = array()) {@command Swf SignalWorkflowExecution}
 * @method Model startWorkflowExecution(array $args = array()) {@command Swf StartWorkflowExecution}
 * @method Model terminateWorkflowExecution(array $args = array()) {@command Swf TerminateWorkflowExecution}
 */
class A2Swf extends A2S3
{
	/**
	 * @return Aws\Swf\SwfClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_SWF);
		}
		return $this->_client;
	}
}