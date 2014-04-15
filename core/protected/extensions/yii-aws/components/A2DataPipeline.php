<?php
/**
 * A2DataPipeline class
 *
 * A wrapper class for the Client to interact with AWS Data Pipeline
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with AWS Data Pipeline
 *
 * @method Model activatePipeline(array $args = array()) {@command DataPipeline ActivatePipeline}
 * @method Model createPipeline(array $args = array()) {@command DataPipeline CreatePipeline}
 * @method Model deletePipeline(array $args = array()) {@command DataPipeline DeletePipeline}
 * @method Model describeObjects(array $args = array()) {@command DataPipeline DescribeObjects}
 * @method Model describePipelines(array $args = array()) {@command DataPipeline DescribePipelines}
 * @method Model evaluateExpression(array $args = array()) {@command DataPipeline EvaluateExpression}
 * @method Model getPipelineDefinition(array $args = array()) {@command DataPipeline GetPipelineDefinition}
 * @method Model listPipelines(array $args = array()) {@command DataPipeline ListPipelines}
 * @method Model pollForTask(array $args = array()) {@command DataPipeline PollForTask}
 * @method Model putPipelineDefinition(array $args = array()) {@command DataPipeline PutPipelineDefinition}
 * @method Model queryObjects(array $args = array()) {@command DataPipeline QueryObjects}
 * @method Model reportTaskProgress(array $args = array()) {@command DataPipeline ReportTaskProgress}
 * @method Model reportTaskRunnerHeartbeat(array $args = array()) {@command DataPipeline ReportTaskRunnerHeartbeat}
 * @method Model setStatus(array $args = array()) {@command DataPipeline SetStatus}
 * @method Model setTaskStatus(array $args = array()) {@command DataPipeline SetTaskStatus}
 * @method Model validatePipelineDefinition(array $args = array()) {@command DataPipeline ValidatePipelineDefinition}
 */
class A2DataPipeline extends A2Base
{

	/**
	 * @return Aws\DataPipeline\DataPipelineClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_DATAPIPELINE);
		}
		return $this->_client;
	}
}