<?php
/**
 * A2ElasticTranscoder class
 *
 * A wrapper class for the Client to interact with Amazon Elastic Transcoder
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package A2.amazon.components
 */
/**
 * Client to interact with Amazon Elastic Transcoder
 *
 * @method Model cancelJob(array $args = array()) {@command ElasticTranscoder CancelJob}
 * @method Model createJob(array $args = array()) {@command ElasticTranscoder CreateJob}
 * @method Model createPipeline(array $args = array()) {@command ElasticTranscoder CreatePipeline}
 * @method Model createPreset(array $args = array()) {@command ElasticTranscoder CreatePreset}
 * @method Model deletePipeline(array $args = array()) {@command ElasticTranscoder DeletePipeline}
 * @method Model deletePreset(array $args = array()) {@command ElasticTranscoder DeletePreset}
 * @method Model listJobsByPipeline(array $args = array()) {@command ElasticTranscoder ListJobsByPipeline}
 * @method Model listJobsByStatus(array $args = array()) {@command ElasticTranscoder ListJobsByStatus}
 * @method Model listPipelines(array $args = array()) {@command ElasticTranscoder ListPipelines}
 * @method Model listPresets(array $args = array()) {@command ElasticTranscoder ListPresets}
 * @method Model readJob(array $args = array()) {@command ElasticTranscoder ReadJob}
 * @method Model readPipeline(array $args = array()) {@command ElasticTranscoder ReadPipeline}
 * @method Model readPreset(array $args = array()) {@command ElasticTranscoder ReadPreset}
 * @method Model testRole(array $args = array()) {@command ElasticTranscoder TestRole}
 * @method Model updatePipelineNotifications(array $args = array()) {@command ElasticTranscoder UpdatePipelineNotifications}
 * @method Model updatePipelineStatus(array $args = array()) {@command ElasticTranscoder UpdatePipelineStatus}
 */
class A2ElasticTranscoder extends A2S3
{
	/**
	 * @return Aws\Ses\SesClient
	 */
	public function getClient()
	{
		if(null === $this->_client)
		{
			$this->_client = $this->getAws()->get(self::AWS_ELASTIC_TRANSCODER);
		}
		return $this->_client;
	}
}