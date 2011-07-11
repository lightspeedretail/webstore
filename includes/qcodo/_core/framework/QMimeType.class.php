<?php
	abstract class QMimeType {
		// Constants for Mime Types
		const _Default = 'application/octet-stream';
		const Executable = 'application/octet-stream';
		const Gif = 'image/gif';
		const Gzip = 'application/x-gzip';
		const Html = 'text/html';
		const Jpeg = 'image/jpeg';
		const Mp3 = 'audio/mpeg';
		const MpegVideo = 'video/mpeg';
		const MsExcel = 'application/vnd.ms-excel';
		const MsPowerpoint = 'application/vnd.ms-powerpoint';
		const MsWord = 'application/vnd.ms-word';
		const OoXmlWordProcessing = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
		const OoXmlPresentation = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
		const OoXmlSpreadsheet = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		const Pdf = 'application/pdf';
		const PlainText = 'text/plain';
		const Png = 'image/png';
		const RichText = 'text/richtext';
		const Quicktime = 'video/quicktime';
		const WavAudio = 'audio/x-wav';
		const Xml = 'text/xml';
		const Zip = 'application/x-zip';

		/**
		 * MimeTypeFor array is used in conjunction with GetMimeTypeForFilename()
		 * @var string[]
		 */
		public static $MimeTypeFor = array(
			'doc' => QMimeType::MsWord,
			'docx' => QMimeType::OoXmlWordProcessing,
			'exe' => QMimeType::Executable,
			'gif' => QMimeType::Gif,
			'gz' => QMimeType::Gzip,
			'htm' => QMimeType::Html,
			'html' => QMimeType::Html,
			'jpeg' => QMimeType::Jpeg,
			'jpg' => QMimeType::Jpeg,
			'mov' => QMimeType::Quicktime,
			'mp3' => QMimeType::Mp3,
			'mpeg' => QMimeType::MpegVideo,
			'mpg' => QMimeType::MpegVideo,
			'pdf' => QMimeType::Pdf,
			'php' => QMimeType::PlainText,
			'png' => QMimeType::Png,
			'ppt' => QMimeType::MsPowerpoint,
			'pptx' => QMimeType::OoXmlPresentation,
			'rtf' => QMimeType::RichText,
			'sql' => QMimeType::PlainText,
			'txt' => QMimeType::PlainText,
			'wav' => QMimeType::WavAudio,
			'xls' => QMimeType::MsExcel,
			'xlsx' => QMimeType::OoXmlSpreadsheet,
			'xml' => QMimeType::Xml,
			'zip' => QMimeType::Zip
		);


		/**
		 * the absolute file path of the MIME Magic Database file
		 * @var string
		 */
		public static $MagicDatabaseFilePath = null;


		/**
		 * Returns the suggested MIME type for an actual file.  Using file-based heuristics
		 * (data points in the ACTUAL file), it will utilize either the PECL FileInfo extension
		 * OR the Magic MIME extension (if either are available) to determine the MIME type.  If all
		 * else fails, it will fall back to the basic GetMimeTypeForFilename() method.
		 *
		 * @param string $strFilePath the absolute file path of the ACTUAL file
		 * @return string
		 */
		public static function GetMimeTypeForFile($strFilePath) {
			// Clean up the File Path and pull out the filename
			$strRealPath = realpath($strFilePath);
			if (!is_file($strRealPath))
				throw new QCallerException('File Not Found: ' . $strFilePath);
			$strFilename = basename($strRealPath);
			$strToReturn = null;

			// First attempt using the PECL FileInfo extension
			if (class_exists('finfo')) {
				if (QMimeType::$MagicDatabaseFilePath)
					$objFileInfo = new finfo(FILEINFO_MIME, QMimeType::$MagicDatabaseFilePath);
				else
					$objFileInfo = new finfo(FILEINFO_MIME);
				$strToReturn = $objFileInfo->file($strRealPath);
			}


			// Next, attempt using the legacy MIME Magic extension
			if ((!$strToReturn) && (function_exists('mime_content_type'))) {
				$strToReturn = mime_content_type($strRealPath);
			}


			// Finally, use Qcodo's owns method for determining MIME type
			if (!$strToReturn)
				$strToReturn = QMimeType::GetMimeTypeForFilename($strFilename);


			if ($strToReturn)
				return $strToReturn;
			else
				return QMimeType::_Default;
		}


		/**
		 * Returns the suggested MIME type for a filename by stripping
		 * out the extension and looking it up from QMimeType::$MimeTypeFor
		 *
		 * @param string $strFilename
		 * @return string
		 */
		public static function GetMimeTypeForFilename($strFilename) {
			if (($intPosition = strrpos($strFilename, '.')) !== false) {
				$strExtension = trim(strtolower(substr($strFilename, $intPosition + 1)));
				if (array_key_exists($strExtension, QMimeType::$MimeTypeFor))
					return QMimeType::$MimeTypeFor[$strExtension];
			}

			return QMimeType::_Default;
		}

		/**
		 * To more easily process a file repository based on Mime Types, it's sometimes
		 * easier to tokenize a mimetype and process using the tokens (e.g. if you have a
		 * directory of image icons that you want to map back to a mime type or a 
		 * collection of mime types, a tokenized-version of the mime type would be more
		 * appropriate).
		 * 
		 * Given a string-based mime type, this will return a "tokenized" version
		 * of the mime type, which only consists of lower case characters and underscores (_).
		 * @param string $strMimeType
		 * @return string
		 */
		public static function GetTokenForMimeType($strMimeType) {
			$strMimeType = strtolower($strMimeType);
			$strToReturn = '';
			$intLength = strlen($strMimeType);

			for ($intIndex = 0; $intIndex < $intLength; $intIndex++) {
				$strCharacter = $strMimeType[$intIndex];
				if ((ord($strCharacter) >= ord('a')) &&
					(ord($strCharacter) <= ord('z')))
					$strToReturn .= $strCharacter;
				else if ($strCharacter == '/')
					$strToReturn .= '_';
			}
			return $strToReturn;
		}
	}
?>