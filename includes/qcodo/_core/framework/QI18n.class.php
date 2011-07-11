<?php
	class QPoParserException extends QCallerException {}

	class QI18n extends QBaseClass {
		public static function Initialize() {
			if (QApplication::$LanguageCode) {
				if (QApplication::$CountryCode) {
					$strCode = sprintf('%s_%s', QApplication::$LanguageCode, QApplication::$CountryCode);
					$strLanguageFiles = array(
						__QCODO_CORE__ . '/i18n/' . QApplication::$LanguageCode . '.po',
						__QCODO_CORE__ . '/i18n/' . $strCode . '.po',
						__QCODO__ . '/i18n/' . QApplication::$LanguageCode . '.po',
						__QCODO__ . '/i18n/' . $strCode . '.po'
					);
				} else {
					$strCode = QApplication::$LanguageCode;
					$strLanguageFiles = array(
						__QCODO_CORE__ . '/i18n/' . QApplication::$LanguageCode . '.po',
						__QCODO__ . '/i18n/' . QApplication::$LanguageCode . '.po'
					);
				}

				// Setup the LanguageFileObject cache mechanism
				$objCache = new QCache('i18n', $strCode, 'i18n', $strLanguageFiles);
				
				// If cached data exists and is valid, use it
				$strData = $objCache->GetData();
				if ($strData)
					QApplication::$LanguageObject = unserialize($strData);
					
				// Otherwise, reload all langauge files and update the cache
				else {
					$objLanguage = new QI18n();
					
					foreach ($strLanguageFiles as $strLanguageFile)
						if (file_exists($strLanguageFile)) {
							try {
								$objLanguage->ParsePoData(file_get_contents($strLanguageFile));							
							} catch (QPoParserException $objExc) {
								$objExc->setMessage('Invalid Language File: ' . $strLanguageFile . ': ' . $objExc->getMessage());
								$objExc->IncrementOffset();
								throw $objExc;
							}
						}
					QApplication::$LanguageObject = $objLanguage;
					$objCache->SaveData(serialize($objLanguage));
				}
			}
		}

		const PoParseStateNone = 0;
		const PoParseStateMessageIdStart = 1;
		const PoParseStateMessageId = 2;
		const PoParseStateMessageStringStart = 3;
		const PoParseStateMessageString = 4;
		
		protected static function UnescapeContent($strContent) {
			$intLength = strlen($strContent);
			$strToReturn = '';
			$blnEscape = false;

			for ($intIndex = 0; $intIndex < $intLength; $intIndex++) {
				if ($blnEscape) {
					switch ($strContent[$intIndex]) {
						case 'n':
							$blnEscape = false;
							$strToReturn .= "\n";
							break;
						case 'r':
							$blnEscape = false;
							$strToReturn .= "\r";
							break;
						case 't':
							$blnEscape = false;
							$strToReturn .= "	";
							break;
						case '\\':
							$blnEscape = false;
							$strToReturn .= '\\';
							break;
						case '"':
							$blnEscape = false;
							$strToReturn .= '"';
							break;
						case "'":
							$blnEscape = false;
							$strToReturn .= "'";
							break;
						default:
							$blnEscape = false;
							$strToReturn .= '\\' . $strContent[$intIndex];
							break;
					}
				} else {
					if ($strContent[$intIndex] == '\\')
						$blnEscape = true;
					else
						$strToReturn .= $strContent[$intIndex];
				}
			}

			if ($blnEscape)
				return false;

			$strToReturn = str_replace("\r", '', $strToReturn);
			return $strToReturn;
		}

		protected function ParsePoData($strPoData) {
			$strPoData = str_replace("\r", '', trim($strPoData));
			$strPoLines = explode("\n", $strPoData);

			$strMatches = array();

			$intState = QI18n::PoParseStateNone;
			$intLineCount = count($strPoLines);

			if (strlen($strPoLines[0]) == 0)
				return;

			for ($intLineNumber = 0; $intLineNumber < $intLineCount; $intLineNumber++) {
				$strPoLine = $strPoLines[$intLineNumber] = trim($strPoLines[$intLineNumber]);

				if (strlen($strPoLine) && (QString::FirstCharacter($strPoLine) != '#')) {
					switch ($intState) {
						case QI18n::PoParseStateNone:
							$intCount = preg_match_all('/msgid(_[a-z0-9]+)?[\s]+"([\S 	]*)"/i', $strPoLine, $strMatches);
							if ($intCount && ($strMatches[0][0] == $strPoLine)) {
								$intLineNumber--;
								$intState = QI18n::PoParseStateMessageIdStart;
							} else
								throw new QPoParserException('Invalid content for PoParseStateNone on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);
							break;

						case QI18n::PoParseStateMessageIdStart:
							$intCount = preg_match_all('/msgid(_[a-z0-9]+)?[\s]+"([\S 	]*)"/i', $strPoLine, $strMatches);
							if ($intCount && ($strMatches[0][0] == $strPoLine)) {
								$strMessageId = array('', '', '', '', '', '', '');
								$strMessageString = array('', '', '', '', '', '', '');
								$intArrayIndex = 0;

								$strContent = QI18n::UnescapeContent($strMatches[2][0]);
								if ($strContent === false)
									throw new QPoParserException('Invalid content on Line ' . ($intLineNumber + 1));
								$strMessageId[$intArrayIndex] = $strContent;
								$intState = QI18n::PoParseStateMessageId;
							} else
								throw new QPoParserException('Invalid content for PoParseStateMessageIdStart on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);
							break;

						case QI18n::PoParseStateMessageId:
							$intCount = preg_match_all('/msgid(_[a-z0-9]+)[\s]+"([\S 	]*)"/i', $strPoLine, $strMatches);
							if ($intCount && ($strMatches[0][0] == $strPoLine)) {
								if (strlen(trim($strMessageId[$intArrayIndex])) == 0)
									throw new QPoParserException('No MsgId content for current MsgId on Line ' . ($intLineNumber) . ': ' . $strPoLine);
								$intArrayIndex++;
								$strContent = QI18n::UnescapeContent($strMatches[2][0]);
								if ($strContent === false)
									throw new QPoParserException('Invalid content on Line ' . ($intLineNumber + 1));
								$strMessageId[$intArrayIndex] = $strContent;
								break;
							}

							$intCount = preg_match_all('/"([\S 	]*)"/', $strPoLine, $strMatches);
							if ($intCount && ($strMatches[0][0] == $strPoLine)) {
								$strContent = QI18n::UnescapeContent($strMatches[1][0]);
								if ($strContent === false)
									throw new QPoParserException('Invalid content on Line ' . ($intLineNumber + 1));
								$strMessageId[$intArrayIndex] .= $strContent;
								break;
							}

							$intCount = preg_match_all('/msgstr(\[[0-9]+\])?[\s]+"([\S 	]*)"/i', $strPoLine, $strMatches);
							if ($intCount && ($strMatches[0][0] == $strPoLine)) {
								if (strlen(trim($strMessageId[$intArrayIndex])) == 0)
									throw new QPoParserException('No MsgId content for current MsgId on Line ' . ($intLineNumber) . ': ' . $strPoLine);
								$intLineNumber--;
								$intState = QI18n::PoParseStateMessageStringStart;
								break;
							}

							throw new QPoParserException('Invalid content for PoParseStateMessageId on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);

						case QI18n::PoParseStateMessageStringStart:
							$intCount = preg_match_all('/msgstr(\[[0-9]+\])?[\s]+"([\S 	]*)"/i', $strPoLine, $strMatches);
							if ($intCount && ($strMatches[0][0] == $strPoLine)) {
								$intArrayIndex = 0;

								if (strlen($strMatches[1][0]))
									$intArrayIndex = intval(substr($strMatches[1][0], 1, strlen($strMatches[1][0]) - 2));

								$strContent = QI18n::UnescapeContent($strMatches[2][0]);
								if ($strContent === false)
									throw new QPoParserException('Invalid content on Line ' . ($intLineNumber + 1));
								$strMessageString[$intArrayIndex] = $strContent;
								$intState = QI18n::PoParseStateMessageString;
							} else 
								throw new QPoParserException('Invalid content for PoParseStateMessageStringStart on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);
							break;


						case QI18n::PoParseStateMessageString:
							$intCount = preg_match_all('/msgid(_[a-z0-9]+)?[\s]+"([\S 	]*)"/i', $strPoLine, $strMatches);
							if ($intCount && ($strMatches[0][0] == $strPoLine)) {
								for ($intIndex = 0; $intIndex < count($strMessageId); $intIndex++)
									if (strlen(trim($strMessageId[$intIndex]))) {
										if (!strlen(trim($strMessageString[$intIndex]))) {
											throw new QPoParserException('No MsgStr defined for MsgId at index ' . $intIndex . ' prior to Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);
										}

										$this->SetTranslation($strMessageId[$intIndex], $strMessageString[$intIndex]);
									}

									$intLineNumber--;
									$intState = QI18n::PoParseStateMessageIdStart;
								break;
							}

							$intCount = preg_match_all('/"([\S 	]*)"/', $strPoLine, $strMatches);
							if ($intCount && ($strMatches[0][0] == $strPoLine)) {
								$strContent = QI18n::UnescapeContent($strMatches[1][0]);
								if ($strContent === false)
									throw new QPoParserException('Invalid content on Line ' . ($intLineNumber + 1));
								$strMessageString[$intArrayIndex] .= $strContent;
								break;
							}

							$intCount = preg_match_all('/msgstr(\[[0-9]+\])?[\s]+"([\S 	]*)"/i', $strPoLine, $strMatches);
							if ($intCount && ($strMatches[0][0] == $strPoLine)) {

								if (strlen($strMatches[1][0]))
									$intArrayIndex = intval(substr($strMatches[1][0], 1, strlen($strMatches[1][0]) - 2));
								else
									throw new QPoParserException('No index specified for alternate MsgStr for PoParseStateMessageString on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);

								if (strlen(trim($strMessageId[$intArrayIndex])) == 0)
									throw new QPoParserException('No MsgId for MsgStr' . $strMatches[1][0] . ' for PoParseStateMessageString on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);

								$strContent = QI18n::UnescapeContent($strMatches[2][0]);
								if ($strContent === false)
									throw new QPoParserException('Invalid content on Line ' . ($intLineNumber + 1));
								$strMessageString[$intArrayIndex] = $strContent;
								break;
							}

							throw new QPoParserException('Invalid content for PoParseStateMessageString on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);

						default:
							throw new QPoParserException('Invalid PoParseState on Line ' . ($intLineNumber + 1) . ': ' . $strPoLine);
					}
				}
			}

			for ($intIndex = 0; $intIndex < count($strMessageId); $intIndex++)
				if (strlen(trim($strMessageId[$intIndex]))) {
					if (!strlen(trim($strMessageString[$intIndex]))) {
						throw new QPoParserException('No MsgStr defined for MsgId at index ' . $intIndex . ' at the End of the File');
					}

					$this->SetTranslation($strMessageId[$intIndex], $strMessageString[$intIndex]);
				}
		}

		protected $strTranslationArray = array();

		protected function SetTranslation($strToken, $strTranslatedText) {
			$this->strTranslationArray[$strToken] = $strTranslatedText;
		}

		public function TranslateToken($strToken) {
			$strCleanToken = str_replace("\r", '', $strToken);
			if (array_key_exists($strCleanToken, $this->strTranslationArray))
				return $this->strTranslationArray[$strCleanToken];
			else
				return $strToken;				
		}

		public function VarDump() {
			$strToReturn = '';
			foreach ($this->strTranslationArray as $strKey=>$strValue) {
				$strKey = str_replace("\n", '\\n', addslashes(QApplication::HtmlEntities($strKey)));
				$strValue = str_replace("\n", '\\n', addslashes(QApplication::HtmlEntities($strValue)));
				$strToReturn .= sprintf("\"%s\"\n\"%s\"\n\n", $strKey, $strValue);
			}
			return $strToReturn;
		}
	}
?>