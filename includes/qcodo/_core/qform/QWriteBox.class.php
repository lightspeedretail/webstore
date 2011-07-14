<?php
	class QWriteBox extends QTextBox {
		protected $strTextMode = QTextMode::MultiLine;
		protected $strCrossScripting = QCrossScripting::Allow;

		protected $strCodeCssClass;

		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);

			$this->strInstructions = QApplication::Translate('Tags &lt;b&gt; &lt;u&gt; &lt;i&gt; &lt;br&gt; &lt;code&gt; and &lt;http://...&gt; are allowed.  Use ** at the beginning of any line for a bulleted list.');
		}

		public function __get($strName) {
			switch ($strName) {
				case 'DisplayHtml':
					return QWriteBox::DisplayHtml($this->strText, $this->strCodeCssClass);

				case 'CodeCssClass':
					return $this->strCodeCssClass;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						return $objExc;
					}
			}
		}

		public function __set($strName, $mixValue) {
			switch ($strName) {
				case 'CodeCssClass':
					try {
						return ($this->strCodeCssClass = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						return $objExc;
					}

				default:
					try {
						return parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						return $objExc;
					}
			}
		}

		public static function DisplayHtml($strText, $strCssClass) {
			$strText = trim(str_replace("\r", '', $strText));
			$strToReturn = '';

			$objStateStack = new QStack();
			$objStateStack->Push(QWriteBox::StateText);
			$objStateStack->Push(QWriteBox::StateNewLine);

			$strBufferArray = array();

			for ($intIndex = 0; $intIndex < strlen($strText); $intIndex++) {
				$strChar = $strText[$intIndex];

				switch ($objStateStack->PeekLast()) {
					case QWriteBox::StateNewLine:
						switch($strChar) {
							case '*':
								$objStateStack->Pop();
								$objStateStack->Push(QWriteBox::StateStar);
								break;
							case "\n":
								$strToReturn .= "<br/>\n";
								break;
							default:
								$objStateStack->Pop();
								$intIndex--;
						}
						break;
						
					case QWriteBox::StateStar:
						switch($strChar) {
							case '*':
								$objStateStack->Pop();
								if ($objStateStack->PeekLast() != QWriteBox::StateBulletedList) {
									$objStateStack->Push(QWriteBox::StateBulletedList);
									$strToReturn .= '<ul>';
								}

								$strToReturn .= '<li>';
								$objStateStack->Push(QWriteBox::StateBulletedListItem);
								break;

							default:
								$objStateStack->Pop();
								if ($objStateStack->PeekLast() == QWriteBox::StateBulletedList) {
									$strToReturn .= '</ul>';
									$objStateStack->Pop();
								}
								$strToReturn .= '*';
								$intIndex--;
								break;
						}
						break;

					case QWriteBox::StateBulletedList:
						switch ($strChar) {
							case '*':
								$objStateStack->Push(QWriteBox::StateStar);
								break;
							case "\n":
								$strToReturn .= '<br/>';
								break;
							default:
								$strToReturn .= '</ul>';
								$intIndex--;
								$objStateStack->Pop();
								break;
						}
						break;

					case QWriteBox::StateBulletedListItem:
						if ($strChar == "\n") {
							$strToReturn .= '</li>';
							$objStateStack->Pop();
							break;
						}
						// Otherwise, treat this like regular text...

					case QWriteBox::StateText:
						switch($strChar) {
							case '<':
								$strBufferArray[QWriteBox::StateTag] = '';
								$objStateStack->Push(QWriteBox::StateTag);
								break;
							case "\n":
								$strToReturn .= "<br/>\n";
								$objStateStack->Push(QWriteBox::StateNewLine);
								break;
							case '	':
								$strToReturn .= '&nbsp;&nbsp;&nbsp;&nbsp;';
								break;
							case ' ':
								if ((strlen($strText) > ($intIndex + 1)) &&
									($strText[$intIndex + 1] == ' ')) {
									$strToReturn .= ' &nbsp;';
									$intIndex++;
								} else
									$strToReturn .= ' ';
								break;

							default:
								$strToReturn .= $strChar;
								break;
						}
						break;

					case QWriteBox::StateTag:
						switch ($strChar) {
							case '<':
								$strToReturn .= '&lt;' . $strBufferArray[QWriteBox::StateTag];
								$strBufferArray[QWriteBox::StateTag] = '';
								break;
							case "\n":
								$strToReturn .= '&lt;' . $strBufferArray[QWriteBox::StateTag];
								$objStateStack->Pop();
								$intIndex--;
								break;
							case '>':
								$strTag = strtolower($strBufferArray[QWriteBox::StateTag]);
								switch ($strTag) {
									case 'b':
									case 'i':
									case 'u':
									case 'br/':
									case '/b':
									case '/i':
									case '/u':
										$strToReturn .= '<' . $strTag . '>';
										$objStateStack->Pop();
										break;
									case 'br':
										$strToReturn .= '<br/>';
										$objStateStack->Pop();
										break;
									case 'code':
										$objStateStack->Pop();
										if ($objStateStack->PeekLast() != QWriteBox::StateBulletedListItem) {
											$strBufferArray[QWriteBox::StateCode] = '';
											$objStateStack->Push(QWriteBox::StateCode);
										} else
											$strToReturn .= '&lt;' . $strBufferArray[QWriteBox::StateTag] . '&gt;';
										break;
									default:
										if ((strlen($strTag) >= 8) && ((substr($strTag, 0, 7) == 'http://') || (substr($strTag, 0, 8) == 'https://')) &&
											(strpos($strTag, '"') === false) &&
											(strpos($strTag, ' ') === false) &&
											(strpos($strTag, '	') === false)) {
											$strToReturn .= sprintf('&lt;<a href="%s">%s</a>&gt;',
												$strBufferArray[QWriteBox::StateTag], $strBufferArray[QWriteBox::StateTag]);
										} else
											$strToReturn .= '&lt;' . $strBufferArray[QWriteBox::StateTag] . '&gt;';
										$objStateStack->Pop();
										break;
								}
								break;
							default:
								$strBufferArray[QWriteBox::StateTag] .= $strChar;
								break;
						}
						break;
					
					case QWriteBox::StateCode:
						$strBufferArray[QWriteBox::StateCode] .= $strChar;
						$strBuffer = $strBufferArray[QWriteBox::StateCode];
						if ((strlen($strBuffer) >= 7) && 
							(strtolower(substr($strBuffer, strlen($strBuffer) - 7)) == '</code>')) {
								$objStateStack->Pop();
								$strBuffer = substr($strBuffer, 0, strlen($strBuffer) - 7);
								$strBuffer = highlight_string(trim($strBuffer), true);

								$strToReturn .= sprintf('<div class="%s">%s</div>', $strCssClass, $strBuffer);
								if ((strlen($strText) > ($intIndex + 1)) &&
									($strText[$intIndex + 1] == "\n"))
									$intIndex++;
							}
						break;
				}
			}

			while (!$objStateStack->IsEmpty()) {
				switch ($objStateStack->Pop()) {
					case QWriteBox::StateTag:
						$strToReturn .= '&lt;' . $strBufferArray[QWriteBox::StateTag];
						break;
					case QWriteBox::StateStar:
						$strToReturn .= '*';
						break;

					case QWriteBox::StateBulletedList:
						$strToReturn .= '</ul>';
						break;

					case QWriteBox::StateBulletedListItem:
						$strToReturn .= '</li>';
						break;
					
					case QWriteBox::StateCode:
						$strBuffer = $strBufferArray[QWriteBox::StateCode];
						$strBuffer = highlight_string(trim($strBuffer), true);

						$strToReturn .= sprintf('<div class="%s">%s</div>', $strCssClass, $strBuffer);
						break;
				}
			}
			return $strToReturn;
		}

		const StateText = 1;
		const StateNewLine = 2;
		const StateTag = 3;
		const StateStar = 4;
		const StateBulletedList = 5;
		const StateBulletedListItem = 6;
		const StateCode = 7;
	}
?>