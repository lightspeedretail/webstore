<?php
	abstract class QEvent extends QBaseClass {
		protected $strJavaScriptEvent;
		protected $strCondition = null;
		protected $intDelay = 0;

		public function __construct($intDelay = 0, $strCondition = null) {
			try {
				if ($intDelay)
					$this->intDelay = QType::Cast($intDelay, QType::Integer);
				if ($strCondition) {
					if ($this->strCondition)
						$this->strCondition = sprintf('(%s) && (%s)', $this->strCondition, $strCondition);
					else
						$this->strCondition = QType::Cast($strCondition, QType::String);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		public function __get($strName) {
			switch ($strName) {
				case 'JavaScriptEvent':
					return $this->strJavaScriptEvent;
				case 'Condition':
					return $this->strCondition;
				case 'Delay':
					return $this->intDelay;
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

	class QBlurEvent extends QEvent {
		const EventName = 'onblur';
		protected $strJavaScriptEvent = 'onblur';
	}

	class QChangeEvent extends QEvent {
		const EventName = 'onchange';
		protected $strJavaScriptEvent = 'onchange';
	}

	class QClickEvent extends QEvent {
		const EventName = 'onclick';
		protected $strJavaScriptEvent = 'onclick';
	}

	class QDoubleClickEvent extends QEvent {
		const EventName = 'ondblclick';
		protected $strJavaScriptEvent = 'ondblclick';
	}

	class QDragDropEvent extends QEvent {
		const EventName = 'ondragdrop';
		protected $strJavaScriptEvent = 'ondragdrop';
	}

	class QFocusEvent extends QEvent {
		const EventName = 'onfocus';
		protected $strJavaScriptEvent = 'onfocus';
	}

	class QKeyDownEvent extends QEvent {
		const EventName = 'onkeydown';
		protected $strJavaScriptEvent = 'onkeydown';
	}

	class QKeyPressEvent extends QEvent {
		const EventName = 'onkeypress';
		protected $strJavaScriptEvent = 'onkeypress';
	}

	class QKeyUpEvent extends QEvent {
		const EventName = 'onkeyup';
		protected $strJavaScriptEvent = 'onkeyup';
	}

	class QMouseDownEvent extends QEvent {
		const EventName = 'onmousedown';
		protected $strJavaScriptEvent = 'onmousedown';
	}

	class QMouseMoveEvent extends QEvent {
		const EventName = 'onmousemove';
		protected $strJavaScriptEvent = 'onmousemove';
	}

	class QMouseOutEvent extends QEvent {
		const EventName = 'onmouseout';
		protected $strJavaScriptEvent = 'onmouseout';
	}

	class QMouseOverEvent extends QEvent {
		const EventName = 'onmouseover';
		protected $strJavaScriptEvent = 'onmouseover';
	}

	class QMouseUpEvent extends QEvent {
		const EventName = 'onmouseup';
		protected $strJavaScriptEvent = 'onmouseup';
	}

	class QMoveEvent extends QEvent {
		const EventName = 'onqcodomove';
		protected $strJavaScriptEvent = 'onqcodomove';
	}

	class QResizeEvent extends QEvent {
		const EventName = 'onqcodoresize';
		protected $strJavaScriptEvent = 'onqcodoresize';
	}

	class QSelectEvent extends QEvent {
		const EventName = 'onselect';
		protected $strJavaScriptEvent = 'onselect';
	}

	// Key-Specific Events (EnterKey, EscapeKey, UpArrowKey, DownArrowKey, etc.)
	if (QApplication::IsBrowser(QBrowserType::Macintosh)) {
		class QEnterKeyEvent extends QKeyPressEvent {
			protected $strCondition = 'event.keyCode == 13';
		}
		class QEscapeKeyEvent extends QKeyPressEvent {
			protected $strCondition = 'event.keyCode == 27';
		}
		class QUpArrowKeyEvent extends QKeyPressEvent {
			protected $strCondition = 'event.keyCode == 38';
		}
		class QDownArrowKeyEvent extends QKeyPressEvent {
			protected $strCondition = 'event.keyCode == 40';
		}
	} else {
		class QEnterKeyEvent extends QKeyDownEvent {
			protected $strCondition = 'event.keyCode == 13';
		}
		class QEscapeKeyEvent extends QKeyDownEvent {
			protected $strCondition = 'event.keyCode == 27';
		}
		class QUpArrowKeyEvent extends QKeyDownEvent {
			protected $strCondition = 'event.keyCode == 38';
		}
		class QDownArrowKeyEvent extends QKeyDownEvent {
			protected $strCondition = 'event.keyCode == 40';
		}
	}
?>