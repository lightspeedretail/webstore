<?php

    class XLSSessionHandler extends XLSSessionHandlerBase {
        public static $SessionHandler = 'DB';

        public function __construct() {
            parent::__construct();

            session_set_save_handler(
                array(&$this,"Open"),
	            array(&$this,"Close"),
	            array(&$this,"Read"),
	            array(&$this,"Write"),
	            array(&$this,"Destroy"),
	            array(&$this,"GarbageCollect")
            );
	    }

        public function Open($strSavePath, $strName) {
            $this->TriggerEvent('Open', array($strSavePath, $strName));

	    	return true;
    	}

        public function Close() {
            $this->TriggerEvent('Close', array());

            if (self::GetGarbageCollection())
                $this->GarbageCollect();

	    	return true;
	    }
	
    	public function Read($strName) {
    		$session = Sessions::LoadByVchName($strName);

            if ($session) return $session->TxtData;
	    	else return '';
    	}

    	public function Write($strName, $unkData) {
	    	$session = Sessions::LoadByVchName($strName);

		    if (!$session) {
			    $session = new Sessions();
    			$session->VchName = $strName;
	    	}
		    $session->UxtExpires = time() + self::GetSessionLifetime();
    		$session->TxtData = $unkData;
            $session->Save();

		    return true;
    	}

        function Destroy($strName) {
            $this->TriggerEvent('Destroy', array($strName));

		    $db = Sessions::GetDatabase();
            $db->NonQuery("DELETE FROM xlsws_sessions" . 
                " WHERE vchName = '" . $strName . "'");
	    }

        function GarbageCollect($intMaxLifetime = 0) {
            if ($intMaxLifetime = 0)
                $intMaxLifetime = self::GetSessionLifetime();

            $intExpiry = time() - $intMaxLifetime;

            $this->TriggerEvent('GarbageCollect', array($intExpiry));

            $db = Sessions::GetDatabase();
            $db->NonQuery("DELETE FROM xlsws_sessions" . 
                " WHERE uxtExpires < '" . $intExpiry . "'");
    	}
    }	
?>
