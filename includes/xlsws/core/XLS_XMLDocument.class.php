<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

// TODO : What is the origin of this ?

class XLS_XMLDocument {
	var $root;
	var $children;

	function XLS_XMLDocument() {
	}

	function createElement($name) {
		$node = new XLS_Node();
		$node->setName($name);
		$node->setType(XLS_Node::ELEMENT);
		return $node;
	}

	function createTextElement($text) {
		$node = new XLS_Node();
		$node->setType(XLS_Node::TEXTELEMENT);
		$node->setValue($text);
		return $node;
	}

	function getRoot() {
		return $this->root;
	}

	function setRoot($node) {
		$this->root = $node;
	}

	function toString() {
		if ($this->root) {
			return $this->root->toString();
		} else {
			return "DOCUMENT ROOT NOT SET";
		}
	}

	function getValueByPath($path) {
		$pathArray = explode("/", $path);
		if ($pathArray[0] == $this->root->getName()) {
			//print_r("Looking for " . $pathArray[0] . "<br>");
			array_shift($pathArray);
			$newPath = implode("/", $pathArray);
			return $this->root->getValueByPath($newPath);
		}
	}
}

class XLS_Node {
	var $name;
	var $type;
	var $text;
	var $parent;
	var $children;
	var $attributes;

	const ELEMENT = 0;
	const TEXTELEMENT = 1;

	function XLS_Node() {
		$this->children = array();
		$this->attributes = array();
	}

	function getName() {
		return $this->name;
	}

	function setName($name) {
		$this->name = $name;
	}

	function setParent(&$node) {
		$this->parent =& $node;
	}

	function &getParent() {
		return $this->parent;
	}

	function &getChildren() {
		return $this->children;
	}

	function getType() {
		return $this->type;
	}

	function setType($type) {
		$this->type = $type;
	}

	function getElementByName($name) {
		for ($i = 0; $i < count($this->children); $i++) {
			if ($this->children[$i]->getType() == XLS_Node::ELEMENT) {
				if ($this->children[$i]->getName() == $name) {
					return $this->children[$i];
				}
			}
		}
		return null;
	}

	function getElementByPath($path) {
		$pathArray = explode('/', $path);

		$total = count($pathArray);

		for ($i = 0; $i < $total; $i++) {
			if (empty($pathArray[$i])) {
				unset($pathArray[$i]);
				continue;
			}

			if (!$this->getChildren()) {
				return null;
			}

			$children_total = count($this->children);
			for ($k = 0; $k < $children_total; $k++) {
				// last node
				if ($this->children[$k]->getName() == $pathArray[$i] &&
					sizeof($pathArray) == 1) {
					return $this->children[$k];
				} elseif ($this->children[$k]->getName() ==
					$pathArray[$i]) {
					unset($pathArray[$i]);
					return $this->children[$k]->getElementByPath(
						implode('/', $pathArray));
				}
			}
		}

		return null;
	}

	function getElementsByName($name) {
		$elements = array();
		for ($i = 0; $i < count($this->children); $i++) {
			if ($this->children[$i]->getType() == XLS_Node::ELEMENT) {
				if ($this->children[$i]->getName() == $name) {
					$elements[] = $this->children[$i];
				}
			}
		}
		return $elements;
	}

	function getValueByPath($path) {
		$pathArray = explode('/', $path);

		$total = count($pathArray);

		for ($i = 0; $i < $total; $i++) {
			if (empty($pathArray[$i])) {
				unset($pathArray[$i]);
				continue;
			}

			if ($this->getName() == $pathArray[$i]) {
				unset($pathArray[$i]);
				return $this->getValueByPath(implode('/', $pathArray));
			}

			if (!$this->getChildren()) {
				return null;
			}

			$children_total = count($this->children);
			for ($k = 0; $k < $children_total; $k++) {
				// last node
				if ($this->children[$k]->getName() == $pathArray[$i] && sizeof($pathArray) == 1) {
					return $this->children[$k]->getValue();
				} elseif ($this->children[$k]->getName() == $pathArray[$i]) {
					unset($pathArray[$i]);
					return $this->children[$k]->getValueByPath(implode('/', $pathArray));
				}
			}
		}

		return null;

		/*for ($i = 0; $i < count($pathArray); $i++) {
			//print_r("Looking for " . $pathArray[$i] ."<br>");
			if ($node->getChildren()) {
				for ($j = 0; $j < count($node->getChildren()); $j++) {
					if ($node->children[$j]->getType() == ELEMENT) {
						if ($node->children[$j]->getName() == $pathArray[$i]) {
							//print_r("Found " . $pathArray[$i] ."<br>");
							$node = $node->children[$j];
						}
					}
				}
			}
		}
		return $node->getValue();

		*/
	}

	function getText() {
		return $this->text();
	}

	function setValue($text) {
		$this->text = $text;
	}

	function getValue() {
		$value = NULL;
		if ($this->getType() == XLS_Node::ELEMENT) {
			for ($i = 0; $i < count($this->children); $i++) {
				$value .= $this->children[$i]->getValue();
			}
		} elseif ($this->getType() == XLS_Node::TEXTELEMENT) {
			$value .= $this->text;
		}
		return $value;
	}

	function setAttribute($name, $value) {
		$this->attributes[$name] = $value;
	}

	function getAttribute($name) {
		return $this->attributes[$name];
	}

	function addNode(&$node) {
		$this->children[] =& $node;
		$node->parent =& $this;
	}

	function parentToString($node) {
		while($node->parent) {
			//print_r("Node " . $node->name . " has parent<br>");
			$node = $node->parent;
		}
		//print_r("Node contents from root: " . $node->toString() . "<br>");
	}

	function toString() {
		$string = NULL;
		//print_r("toString child count " . $this->name . " contains " . count($this->children) . "<br>");
		if ($this->type == XLS_Node::ELEMENT) {
			$string .= '{' . $this->name . '}';
			for ($i = 0; $i < count($this->children); $i++) {
				$string .= $this->children[$i]->toString();
			}
			$string .= '{/' . $this->name . '}';
		} else {
			$string .= $this->getValue();
		}
		return $string;
	}
}

//**************
class XLS_XMLParser {
	var $xp;
	var $document;
	var $current;
	var $error;

	function XLS_XMLParser() {
		$this->document = new XLS_XMLDocument();
		$this->error = array();
	}

	function setDocument($document) {
		$this->document = $document;
	}

	function getDocument() {
		return $this->document;
	}

	function destruct(){
		xml_parser_free($this->xp);
	}

	// return 1 for an error, 0 for no error
	function hasErrors() {
		if (sizeof($this->error) > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	// return array of error messages
	function getError() {
		return $this->error;
	}

	// process xml start tag
	function startElement($xp, $name, $attrs) {
		//print_r("Found Start Tag: " . $name . "<br>");
		$node =& $this->document->createElement($name);
		if (!empty($attrs)) {
			foreach ($attrs as $k => $v) {
				$node->setAttribute($k, $v);
			}
		}
		if ($this->document->getRoot()) {
			$this->current->addNode($node);
		} else {
			$this->document->root =& $node;
		}
		$this->current =& $node;
	}

	// process xml end tag
	function endElement($xp, $name) {
		//print_r("Found End Tag: " . $name . "<br>");
		if ($this->current->getParent()) {
			$this->current =& $this->current->getParent();
		}
	}

	// process data between xml tags
	function dataHandler($xp, $text) {
		//print_r("Adding Data: \"" . $text . "\"<br>");
		$node =& $this->document->createTextElement($text);
		$this->current->addNode($node);
	}

	// parse xml document from string
	function parse($xmlString) {
		if(!($this->xp = @xml_parser_create())) {
			$this->error['description'] = 'Could not create xml parser';
		}

		if(!$this->hasErrors()) {
			if(!@xml_set_object($this->xp, $this)) {
				$this->error['description'] = 'Could not set xml parser for object';
			}
		}

		if(!$this->hasErrors()) {
			if(!@xml_set_element_handler($this->xp, 'startElement', 'endElement')) {
				$this->error['description'] = 'Could not set xml element handler';
			}
		}

		if(!$this->hasErrors()) {
			if(!@xml_set_character_data_handler($this->xp, 'dataHandler')) {
				$this->error['description'] = 'Could not set xml character handler';
			}
		}

		xml_parser_set_option($this->xp, XML_OPTION_CASE_FOLDING, false);

		if (!$this->hasErrors()) {
			if(!@xml_parse($this->xp, $xmlString)) {
				$this->error['description'] = xml_error_string(xml_get_error_code($this->xp));
				$this->error['line'] = xml_get_current_line_number($this->xp);
			}
		}
	}

	function generateDocument($xml) {
		$this->parse($xml);

		if (!empty($this->error)) {
			return null;
		}

		return $this->document->getRoot();
	}
}
