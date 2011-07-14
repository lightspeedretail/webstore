<?php
	/**
	 * An DataType class for the Stack data type.
	 */
	class QStack extends QBaseClass {
		private $objArray = array();

		public function Push($mixValue) {
			array_push($this->objArray, $mixValue);
//			$this->objArray[count($this->objArray)] = $mixValue;
			return $mixValue;
		}

		public function PushFirst($mixValue) {
			if ($this->Size() > 0) {
				$this->objArray = array_reverse($this->objArray);
				array_push($this->objArray, $mixValue);
//				$this->objArray[count($this->objArray)] = $mixValue;
				$this->objArray = array_reverse($this->objArray);
			} else
				$this->objArray[0] = $mixValue;

			return $mixValue;
		}
		
		public function AppendStringToTop($strContent) {
			if (is_string($this->objArray[$this->Size() - 1]))
				$this->objArray[$this->Size() - 1] .= $strContent;
			else
				throw new QInvalidCastException('Topmost object is not a string');
		}

		public function Pop() {
			if (!$this->IsEmpty())
				return array_pop($this->objArray);
			else
				throw new QCallerException("Cannot pop off of an empty Stack");
		}

		public function PopFirst() {
			if (!$this->IsEmpty()) {
				$this->objArray = array_reverse($this->objArray);
				$mixToReturn = array_pop($this->objArray);
				$this->objArray = array_reverse($this->objArray);
				return $mixToReturn;
			} else
				throw new QCallerException("Cannot pop off of an empty Stack");
		}

		public function Peek($intIndex) {
			if (array_key_exists($intIndex, $this->objArray))
				return $this->objArray[$intIndex];
			else
				throw new QIndexOutOfRangeException($intIndex, "Index on stack does not exist");
		}

		public function PeekLast() {
			if ($intCount = count($this->objArray))
				return $this->objArray[$intCount - 1];
			else
				throw new QIndexOutOfRangeException($intCount - 1, "Stack is empty");
		}

		public function IsEmpty() {
			return (count($this->objArray) == 0);
		}

		public function Size() {
			return count($this->objArray);
		}

		public function ConvertToArray() {
			return $this->objArray;
		}
	}
?>