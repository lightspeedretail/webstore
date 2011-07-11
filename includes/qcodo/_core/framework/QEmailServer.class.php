<?php
	/**
	 * This EmailServer (and its dependent EmailMessage class) allows the application to send
	 * messages via any accessible SMTP server.
	 * 
	 * The QEmailServer class, specifically, is an abstract class and is NOT meant to be instantiated.
	 * It has one public static method, Send, which takes in a QEmailMessage object.
	 */
	abstract class QEmailServer extends QBaseClass {
		/**
		 * Server Hostname or IP Address of the server running the SMTP service.
		 * Using an IP address is slightly faster, but using a Hostname is easier to manage.
		 * Defaults to "localhost".
		 *
		 * @var string SmtpServer
		 */
		public static $SmtpServer = 'localhost';

		/**
		 * Port of the SMTP Service on the SmtpServer, usually 25
		 *
		 * @var integer SmtpPort
		 */
		public static $SmtpPort = 25;

		/**
		 * IP Address of the Originating Server (e.g. the IP address of this server)
		 * used for the EHLO command in the SMTP protocol.  Defaults to the
		 * QApplication::$ServerAddress variable, which uses the PHP $_SERVER
		 * constants to determine the correct IP address.
		 *
		 * @var string OriginatingServerIp
		 */
		public static $OriginatingServerIp;

		/**
		 * Whether or not we are running in Test Mode.  Test Mode allows you
		 * to develop e-mail-based applications without actually having access to
		 * an SMTP server or the Internet.  Instead of messages being sent out,
		 * the messages and corresponding SMTP communication will be saved to disk.
		 *
		 * @var boolean $TestMode
		 */
		public static $TestMode = false;

		/**
		 * The directory where TestMode e-mail files will be saved to.  The process
		 * running the webserver *must* have write access to this directory.  Default
		 * is "/tmp", which makes sense in unix/linux/mac environments.  Windows users
		 * will likely need to set up their own temp directories.
		 *
		 * @var string $TestModeDirectory
		 */		
		public static $TestModeDirectory = '/tmp';

		/**
		 * Boolean flag signifying whether SMTP's AUTH PLAIN should be used
		 * 
		 * @var bool $AuthPlain
		 */
		public static $AuthPlain = false;

		/**
		 * Boolean flag signifying whether SMTP's AUTH LOGIN should be used
		 * 
		 * @var bool $AuthLogin
		 */
		public static $AuthLogin = false;

		/**
		 * SMTP Username to use for AUTH PLAIN or LOGIN
		 * 
		 * @var string $SmtpUsername
		 */
		public static $SmtpUsername = '';

		/**
		 * SMTP Password to use for AUTH PLAIN or LOGIN
		 * 
		 * @var string $SmtpPassword
		 */
		public static $SmtpPassword = '';

		/**
		 * Encoding Type (if null, will default to the QApplication::$EncodingType)
		 * 
		 * @var string $EncodingType
		 */
		public static $EncodingType = null;

		/**
		 * Uses regular expression matching to return an array of valid e-mail addresses
		 *
		 * @param string $strAddresses Single string containing e-mail addresses and anything else
		 * @return string[] An array of e-mail addresses only, or NULL if none
		 */
		public static function GetEmailAddresses($strAddresses) {
			$strAddressArray = null;

			// Address Lines cannot have any linebreaks
			if ((strpos($strAddresses, "\r") !== false) ||
				(strpos($strAddresses, "\n") !== false))
				return null;

			preg_match_all ("/[a-zA-Z0-9_.+-]+[@][\-a-zA-Z0-9_.]+/", $strAddresses, $strAddressArray);
			if ((is_array($strAddressArray)) &&
				(array_key_exists(0, $strAddressArray)) &&
				(is_array($strAddressArray[0])) &&
				(array_key_exists(0, $strAddressArray[0]))) {
				return $strAddressArray[0];
			}
			
			// If we're here, then no addresses were found in $strAddress
			// so return null
			return null;
		}

		/**
		 * Sends a message out via SMTP according to the server, ip, etc. preferences
		 * as set up on the class.  Takes in a QEmailMessage object.
		 *
		 * Will throw a QEmailException exception on any error.
		 *
		 * @param QEmailMessage $objMessage Message to Send
		 * @return void
		 */
		public static function Send(QEmailMessage $objMessage) {
			$objResource = null;

			if (QEmailServer::$TestMode) {
				// Open up a File Resource to the TestModeDirectory
				$strArray = explode(' ', microtime());
				$strFileName = sprintf('%s/email_%s%s.txt', QEmailServer::$TestModeDirectory, $strArray[1], substr($strArray[0], 1));
				$objResource = fopen($strFileName, 'w');
				if (!$objResource)
					throw new QEmailException(sprintf('Unable to open Test SMTP connection to: %s', $strFileName));

				// Clear the Read Buffer
				if (!feof($objResource))
					fgets($objResource, 4096);

				// Write the Connection Command
				fwrite($objResource, sprintf("telnet %s %s\r\n", QEmailServer::$SmtpServer, QEmailServer::$SmtpPort));
			} else {
				$objResource = fsockopen(QEmailServer::$SmtpServer, QEmailServer::$SmtpPort);
				if (!$objResource)
					throw new QEmailException(sprintf('Unable to open SMTP connection to: %s %s', QEmailServer::$SmtpServer, QEmailServer::$SmtpPort));
			}

			// Connect
			$strResponse = null;
			if (!feof($objResource)) {
				$strResponse = fgets($objResource, 4096);

				// Iterate through all "220-" responses (stop at "220 ")
				while ((substr($strResponse, 0, 3) == "220") && (substr($strResponse, 0, 4) != "220 "))
					if (!feof($objResource))
						$strResponse = fgets($objResource, 4096);

				// Check for a "220" response
				if (!QEmailServer::$TestMode)
					if ((strpos($strResponse, "220") === false) || (strpos($strResponse, "220") != 0))
						throw new QEmailException(sprintf('Error Response on Connect: %s', $strResponse));
			}

			// Send: EHLO
			fwrite($objResource, sprintf("EHLO %s\r\n", QEmailServer::$OriginatingServerIp));
			if (!feof($objResource)) {
				$strResponse = fgets($objResource, 4096);

				// Iterate through all "250-" responses (stop at "250 ")
				while ((substr($strResponse, 0, 3) == "250") && (substr($strResponse, 0, 4) != "250 "))
					if (!feof($objResource))
						$strResponse = fgets($objResource, 4096);

				// Check for a "250" response
				if (!QEmailServer::$TestMode)
					if ((strpos($strResponse, "250") === false) || (strpos($strResponse, "250") != 0))
						throw new QEmailException(sprintf('Error Response on EHLO: %s', $strResponse));
			}

			// Send Authentication
			if (QEmailServer::$AuthPlain) {
				fwrite($objResource, "AUTH PLAIN " . base64_encode(QEmailServer::$SmtpUsername . "\0" . QEmailServer::$SmtpUsername . "\0" . QEmailServer::$SmtpPassword) . "\r\n");
				if (!feof($objResource)) {
					$strResponse = fgets($objResource, 4096);
					if ((strpos($strResponse, "235") === false) || (strpos($strResponse, "235") != 0))
						throw new QEmailException(sprintf('Error in response from AUTH PLAIN: %s', $strResponse));
				}
			}

			if (QEmailServer::$AuthLogin) {
				fwrite($objResource,"AUTH LOGIN\r\n");
				if (!feof($objResource)) {
					$strResponse = fgets($objResource, 4096);
					if (!QEmailServer::$TestMode)
						if ((strpos($strResponse, "334") === false) || (strpos($strResponse, "334") != 0))
							throw new QEmailException(sprintf('Error in response from AUTH LOGIN: %s', $strResponse));
				}

				fwrite($objResource, base64_encode(QEmailServer::$SmtpUsername) . "\r\n");
				if (!feof($objResource)) {
					$strResponse = fgets($objResource, 4096);
					if (!QEmailServer::$TestMode)
						if ((strpos($strResponse, "334") === false) || (strpos($strResponse, "334") != 0))
							throw new QEmailException(sprintf('Error in response from AUTH LOGIN: %s', $strResponse));
				}

				fwrite($objResource, base64_encode(QEmailServer::$SmtpPassword) . "\r\n");
				if (!feof($objResource)) {
 					$strResponse = fgets($objResource, 4096);
					if (!QEmailServer::$TestMode)
						if ((strpos($strResponse, "235") === false) || (strpos($strResponse, "235") != 0))
							throw new QEmailException(sprintf('Error in response from AUTH LOGIN: %s', $strResponse));
				}
			}

			// Setup MAIL FROM line
			$strAddressArray = QEmailServer::GetEmailAddresses($objMessage->From);
			if (count($strAddressArray) != 1)
				throw new QEmailException(sprintf('Not a valid From address: %s', $objMessage->From));

			// Send: MAIL FROM line
			fwrite($objResource, sprintf("MAIL FROM: <%s>\r\n", $strAddressArray[0]));			
			if (!feof($objResource)) {
				$strResponse = fgets($objResource, 4096);
				
				// Check for a "250" response
				if (!QEmailServer::$TestMode)
					if ((strpos($strResponse, "250") === false) || (strpos($strResponse, "250") != 0))
						throw new QEmailException(sprintf('Error Response on MAIL FROM: %s', $strResponse));
			}

			// Setup RCPT TO line(s)
			$strAddressToArray = QEmailServer::GetEmailAddresses($objMessage->To);
			if (!$strAddressToArray)
				throw new QEmailException(sprintf('Not a valid To address: %s', $objMessage->To));

			$strAddressCcArray = QEmailServer::GetEmailAddresses($objMessage->Cc);
			if (!$strAddressCcArray)
				$strAddressCcArray = array();

			$strAddressBccArray = QEmailServer::GetEmailAddresses($objMessage->Bcc);
			if (!$strAddressBccArray)
				$strAddressBccArray = array();

			$strAddressCcBccArray = array_merge($strAddressCcArray, $strAddressBccArray);
			$strAddressArray = array_merge($strAddressToArray, $strAddressCcBccArray);

			// Send: RCPT TO line(s)
			foreach ($strAddressArray as $strAddress) {
				fwrite($objResource, sprintf("RCPT TO: <%s>\r\n", $strAddress));
				if (!feof($objResource)) {
					$strResponse = fgets($objResource, 4096);
					
					// Check for a "250" response
					if (!QEmailServer::$TestMode)
						if ((strpos($strResponse, "250") === false) || (strpos($strResponse, "250") != 0))
							throw new QEmailException(sprintf('Error Response on RCPT TO: %s', $strResponse));
				}
			}

			// Send: DATA
			fwrite($objResource, "DATA\r\n");
			if (!feof($objResource)) {
				$strResponse = fgets($objResource, 4096);
				
				// Check for a "354" response
				if (!QEmailServer::$TestMode)
					if ((strpos($strResponse, "354") === false) || (strpos($strResponse, "354") != 0))
						throw new QEmailException(sprintf('Error Response on DATA: %s', $strResponse));
			}

			// Send: Required Headers
			fwrite($objResource, sprintf("Date: %s\r\n", QDateTime::NowToString(QDateTime::FormatRfc822)));
			fwrite($objResource, sprintf("To: %s\r\n", $objMessage->To));
			fwrite($objResource, sprintf("From: %s\r\n", $objMessage->From));

			// Send: Optional Headers
			if ($objMessage->Subject)
				fwrite($objResource, sprintf("Subject: %s\r\n", $objMessage->Subject));
			if ($objMessage->Cc)
				fwrite($objResource, sprintf("Cc: %s\r\n", $objMessage->Cc));

			// Send: Content-Type Header (if applicable)

			// First, setup boundaries (may be needed if multipart)
			$strBoundary = sprintf('==qcodo_mp_mixed_boundary_%s', md5(microtime()));
			$strAltBoundary = sprintf('==qcodo_mp_alt_boundary_%s', md5(microtime()));

			// Send: Other Headers (if any)
			foreach ($objArray = $objMessage->HeaderArray as $strKey => $strValue)
				fwrite($objResource, sprintf("%s: %s\r\n", $strKey, $strValue));			

			// if we are adding an html or files to the message we need these headers.
			if ($objMessage->HasFiles || $objMessage->HtmlBody)  {
				fwrite($objResource, "MIME-Version: 1.0\r\n");
				fwrite($objResource, sprintf("Content-Type: multipart/mixed;\r\n boundary=\"%s\"\r\n", $strBoundary));
				fwrite($objResource, sprintf("This is a multipart message in MIME format.\r\n\r\n", $strBoundary));
				fwrite($objResource, sprintf("--%s\r\n", $strBoundary));				
			}


			// Send: Body

			// Setup Encoding Type (use QEmailServer if specified, otherwise default to QApplication's)
			if (!($strEncodingType = QEmailServer::$EncodingType))
				$strEncodingType = QApplication::$EncodingType;

			if ($objMessage->HtmlBody) {
				fwrite($objResource, sprintf("Content-Type: multipart/alternative;\r\n boundary=\"%s\"\r\n\r\n", $strAltBoundary));
				fwrite($objResource, sprintf("--%s\r\n", $strAltBoundary));
				fwrite($objResource, sprintf("Content-Type: text/plain; charset=\"%s\"\r\n", $strEncodingType));
				fwrite($objResource, sprintf("Content-Transfer-Encoding: 7bit\r\n\r\n"));

				fwrite($objResource, $objMessage->Body);
				fwrite($objResource, "\r\n\r\n");

				fwrite($objResource, sprintf("--%s\r\n", $strAltBoundary));
				fwrite($objResource, sprintf("Content-Type: text/html; charset=\"%s\"\r\n", $strEncodingType));
				fwrite($objResource, sprintf("Content-Transfer-Encoding: quoted-printable\r\n\r\n"));								
		
				fwrite($objResource, $objMessage->HtmlBody);
				fwrite($objResource, "\r\n\r\n");
				
				fwrite($objResource, sprintf("--%s--\r\n", $strAltBoundary));
			} else if($objMessage->HasFiles) {
				fwrite($objResource, sprintf("Content-Type: multipart/alternative;\r\n boundary=\"%s\"\r\n\r\n", $strAltBoundary));				
				fwrite($objResource, sprintf("--%s\r\n", $strAltBoundary));
				fwrite($objResource, sprintf("Content-Type: text/plain; charset=\"%s\"\r\n", $strEncodingType));
				fwrite($objResource, sprintf("Content-Transfer-Encoding: 7bit\r\n\r\n"));
				fwrite($objResource, $objMessage->Body);
				fwrite($objResource, "\r\n\r\n");
				fwrite($objResource, sprintf("--%s--\r\n", $strAltBoundary));
			} else
				fwrite($objResource, "\r\n" . $objMessage->Body);

			// Send: File Attachments
			if($objMessage->HasFiles) {
				foreach ($objArray = $objMessage->FileArray as $objFile) {
					fwrite($objResource, sprintf("--%s\r\n", $strBoundary));
					fwrite($objResource, sprintf("Content-Type: %s;\r\n", $objFile->MimeType ));
					fwrite($objResource, sprintf("      name=\"%s\"\r\n", $objFile->FileName ));
					fwrite($objResource, "Content-Transfer-Encoding: base64\r\n");
					fwrite($objResource, sprintf("Content-Length: %s\r\n", strlen($objFile->EncodedFileData)));
					fwrite($objResource, "Content-Disposition: attachment;\r\n");
					fwrite($objResource, sprintf("      filename=\"%s\"\r\n\r\n", $objFile->FileName));
					fwrite($objResource, $objFile->EncodedFileData);
//					foreach (explode("\n", $objFile->EncodedFileData) as $strLine) {
//						$strLine = trim($strLine);
//						fwrite($objResource, $strLine . "\r\n");
//					}
				}
			}

			// close a message with these boundaries if the message had files or had html
			if($objMessage->HasFiles || $objMessage->HtmlBody)
	   			fwrite($objResource, sprintf("\r\n\r\n--%s--\r\n", $strBoundary)); // send end of file attachments...

			// Send: Message End
			fwrite($objResource, "\r\n.\r\n");
			if (!feof($objResource)) {
				$strResponse = fgets($objResource, 4096);
				
				// Check for a "250" response
				if (!QEmailServer::$TestMode)
					if ((strpos($strResponse, "250") === false) || (strpos($strResponse, "250") != 0))
						throw new QEmailException(sprintf('Error Response on DATA finish: %s', $strResponse));
			}

			// Send: QUIT
			fwrite($objResource, "QUIT\r\n");
			if (!feof($objResource))
				$strResponse = fgets($objResource, 4096);
				
			// Close the Resource
			fclose($objResource);
			if (QEmailServer::$TestMode)
				chmod($strFileName, 0777);
		}
	}

	// PHP does not allow Static Class Variables to be set to non-constants.
	// So we set QEmailServer's OriginatingServerIp to QApplication's ServerAddress here.
	QEmailServer::$OriginatingServerIp = QApplication::$ServerAddress;

	class QEmailException extends QCallerException {}
	
	class QEmailAttachment extends QBaseClass {
		protected $strFilePath;
		protected $strMimeType;
		protected $strFileName;
		protected $strEncodedFileData;

		public function __construct($strFilePath, $strSpecifiedMimeType = null, $strSpecifiedFileName = null) {
			// Set File Path
			if (!is_file(realpath($strFilePath)))
				throw new QCallerException('File Not Found: ' . $strFilePath);
			$this->strFilePath = realpath($strFilePath);


			// Set the File MIME Type -- if Explicitly Set, use it
			if ($strSpecifiedMimeType)
				$this->strMimeType = $strSpecifiedMimeType;
			// otherwise, use QMimeType to determine
			else
				$this->strMimeType = QMimeType::GetMimeTypeForFile($this->strFilePath);


			// Set the File Name -- if explicitly set, use it
			if ($strSpecifiedFileName)
				$this->strFileName = $strSpecifiedFileName;
			// Otherwise, use basename() to determine
			else
				$this->strFileName = basename($this->strFilePath);


			// Read file into a Base64 Encoded Data Stream
			$strFileContents = file_get_contents($this->strFilePath, false);
			$this->strEncodedFileData = chunk_split(base64_encode($strFileContents));
		}

		public function __get($strName) {
			switch ($strName) {
				case 'FilePath': return $this->strFilePath;
				case 'MimeType': return $this->strMimeType; 
				case 'FileName': return $this->strFileName;
				case 'EncodedFileData': return $this->strEncodedFileData;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

	class QEmailMessage extends QBaseClass {
		protected $strFrom;
		protected $strTo;
		protected $strSubject;
		protected $strBody;
		protected $strHtmlBody;

		protected $strCc;
		protected $strBcc;
		protected $strHeaderArray = array();
		protected $objFileArray = array();

		public function AddAttachment(QEmailAttachment $objFile) {						
			$this->objFileArray[$objFile->FileName] = $objFile;
		}

		public function Attach($strFilePath, $strSpecifiedMimeType = null, $strSpecifiedFileName = null) {
			$this->AddAttachment(new QEmailAttachment($strFilePath, $strSpecifiedMimeType, $strSpecifiedFileName));
		}

		public function RemoveAttachment($strFileName) {
			if (array_key_exists($strName, $this->objFileArray))
				unset($this->objFileArray[$strName]);
		}

		public function SetHeader($strName, $strValue) {
			$this->strHeaderArray[$strName] = $strValue;
		}

		public function GetHeader($strName) {
			if (array_key_exists($strName, $this->strHeaderArray))
				return $this->strHeaderArray[$strName];
			return null;
		}

		public function RemoveHeader($strName, $strValue) {
			if (array_key_exists($strName, $this->strHeaderArray))
				unset($this->strHeaderArray[$strName]);
		}

		public function __construct($strFrom = null, $strTo = null, $strSubject = null, $strBody = null) {
			$this->strFrom = $strFrom;
			$this->strTo = $strTo;

			// We must cleanup the Subject and Body -- use the Property to set
			$this->Subject = $strSubject;
			$this->Body = $strBody;
		}

		public function __get($strName) {
			switch ($strName) {
				case 'From': return $this->strFrom;
				case 'To': return $this->strTo;
				case 'Subject': return $this->strSubject;
				case 'Body': return $this->strBody;
				case 'HtmlBody': return $this->strHtmlBody;

				case 'Cc': return $this->strCc;
				case 'Bcc': return $this->strBcc;

				case 'HeaderArray': return $this->strHeaderArray;
				case 'FileArray': return $this->objFileArray;
				case 'HasFiles': return (count($this->objFileArray) > 0) ? true : false;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		public function __set($strName, $mixValue) {
			try {
				switch ($strName) {
					case 'From': return ($this->strFrom = QType::Cast($mixValue, QType::String));
					case 'To': return ($this->strTo = QType::Cast($mixValue, QType::String));
					case 'Subject':
						$strSubject = trim(QType::Cast($mixValue, QType::String));
						$strSubject = str_replace("\r", "", $strSubject);
						$strSubject = str_replace("\n", " ", $strSubject);
						return ($this->strSubject = $strSubject);
					case 'Body':
						$strBody = QType::Cast($mixValue, QType::String);
						$strBody = str_replace("\r", "", $strBody);
						$strBody = str_replace("\n", "\r\n", $strBody);
						$strBody = str_replace("\r\n.", "\r\n..", $strBody);
						return ($this->strBody = $strBody);
					case 'HtmlBody':
						$strHtmlBody = QType::Cast($mixValue, QType::String);
						$strHtmlBody = str_replace("\r", "", $strHtmlBody);
						$strHtmlBody = str_replace("\n", "\r\n", $strHtmlBody);
						$strHtmlBody = str_replace("\r\n.", "\r\n..", $strHtmlBody);
						return ($this->strHtmlBody = $strHtmlBody);

					case 'Cc': return ($this->strCc = QType::Cast($mixValue, QType::String));
					case 'Bcc': return ($this->strBcc = QType::Cast($mixValue, QType::String));

					default: return (parent::__set($strName, $mixValue));
				}
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}
?>
