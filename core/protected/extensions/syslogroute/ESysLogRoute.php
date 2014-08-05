<?php

/**
 * This is a slightly modified version of the following Yii extension
 *
 * http://www.yiiframework.com/extension/syslogroute/
 *
 */
class ESysLogRoute extends CLogRoute
{

	/**
	 * @var array levels
	 */
	public $levels;

	/**
	 * @var array categories
	 */
	public $categories;

	/**
	 * @var string logName
	 */
	private $_logName;

	/**
	 * @var string logFacility
	 */
	private $_logFacility;
	 
	/**
	 * Initializes the route.
	 * This method is invoked after the route is created by the route manager.
	 */
	public function init()
	{
		parent::init();

		$this->setLogName('webstore_yii_logs');
		if( null === $this->getLogName() )
			$this->setLogName('YiiApp');
		
		$this->setLogFacility(Yii::app()->params['logFacility']);
		if( null === $this->getLogFacility() )
			$this->setLogFacility(LOG_USER);
	}

	/**
	 * @return string _logName used for identifying our log messages.
	 */
	public function getLogName()
	{
		return $this->_logName;
	}

	/**
	 * @param string logname used for identifying our log messages.
	 */
	public function setLogName($logname)
	{
		$this->_logName = $logname;
	}

	/**
	 * @return constant _logFacility used for syslog facility selection.
	 */
	public function getLogFacility()
	{
		return $this->_logFacility;
	}

	/**
	 * @param constant logfacility used for syslog facility selection.
	 */
	public function setLogFacility($logfacility)
	{
		$this->_logFacility = $logfacility;
	}

	/**
	 * Saves log messages in files.
	 * @param array list of log messages
	 */
	protected function processLogs($logs)
	{
		foreach($logs as $log) {
			switch($log[1]) {
				case 'trace':
					$pri = LOG_DEBUG;
					break;
				case 'info':
					$pri = LOG_INFO;
					break;
				case 'profile':
					$pri = LOG_NOTICE;
					break;
				case 'warning':
					$pri = LOG_WARNING;
					break;
				case 'error':
					$pri = LOG_ERR;
					break;
			}

			if(openlog($this->getLogName(), LOG_ODELAY | LOG_PID, $this->getLogFacility()))
			{
				$strLogId = $_SERVER['HTTP_HOST'];

				if (!empty(Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL']))
					$strLogId = Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'];

				syslog($pri, $strLogId . ': ' . $log[1] . ' - (' . $log[2] . ') - ' . $log[0]);

				closelog();
			}
		}
	}
}
