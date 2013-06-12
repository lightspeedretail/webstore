<?php
/******************************************************************************
 * Copyright (c) 2010 Jevon Wright and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Jevon Wright - initial API and implementation
 ****************************************************************************/
/*
LightSpeed Notice: This file has been modified to incorporate functionality into a class.
The original file can be sourced from http://journals.jevon.org/users/jevon-phd/entry/19818
*/
/**
 * Tries to convert the given HTML into a plain text format - best suited for
 * e-mail display, etc.
 *
 * <p>In particular, it tries to maintain the following features:
 * <ul>
 *   <li>Links are maintained, with the 'href' copied over
 *   <li>Information in the &lt;head&gt; is lost
 * </ul>
 *
 * @param html the input HTML
 * @return the HTML converted, as best as possible, to text
 */
class HtmlToText {


	public function convert_html_to_text($html) {
		$html = $this->fix_newlines($html);

		$doc = new DOMDocument();
		try {
			$doc->loadHTML($html);
		}
		catch (Exception $e)
		{
			Yii::log("Error creating plain text email from receipt ".$e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		$output = $this->iterate_over_node($doc);

		// remove leading and trailing spaces on each line
		$output = preg_replace("/[ \t]*\n[ \t]*/im", "\n", $output);

		// remove leading and trailing whitespace
		$output = trim($output);

		return $output;
	}

	/**
	 * Unify newlines; in particular, \r\n becomes \n, and
	 * then \r becomes \n. This means that all newlines (Unix, Windows, Mac)
	 * all become \ns.
	 *
	 * @param text text with any number of \r, \r\n and \n combinations
	 * @return the fixed text
	 */
	public function fix_newlines($text) {
		// replace \r\n to \n
		$text = str_replace("\r\n", "\n", $text);
		// remove \rs
		$text = str_replace("\r", "\n", $text);

		return $text;
	}

	public function next_child_name($node) {
		// get the next child
		$nextNode = $node->nextSibling;
		while ($nextNode != null) {
			if ($nextNode instanceof DOMElement) {
				break;
			}
			$nextNode = $nextNode->nextSibling;
		}
		$nextName = null;
		if ($nextNode instanceof DOMElement && $nextNode != null) {
			$nextName = strtolower($nextNode->nodeName);
		}

		return $nextName;
	}
	public function prev_child_name($node) {
		// get the previous child
		$nextNode = $node->previousSibling;
		while ($nextNode != null) {
			if ($nextNode instanceof DOMElement) {
				break;
			}
			$nextNode = $nextNode->previousSibling;
		}
		$nextName = null;
		if ($nextNode instanceof DOMElement && $nextNode != null) {
			$nextName = strtolower($nextNode->nodeName);
		}

		return $nextName;
	}

	public function iterate_over_node($node) {
		if ($node instanceof DOMText) {
			return preg_replace("/\\s+/im", " ", $node->wholeText);
		}
		if ($node instanceof DOMDocumentType) {
			// ignore
			return "";
		}

		$nextName = $this->next_child_name($node);
		$prevName = $this->prev_child_name($node);

		$name = strtolower($node->nodeName);

		// start whitespace
		switch ($name) {
			case "hr":
				return "------\n";

			case "style":
			case "head":
			case "title":
			case "meta":
			case "script":
				// ignore these tags
				return "";

			case "h1":
			case "h2":
			case "h3":
			case "h4":
			case "h5":
			case "h6":
				// add two newlines
				$output = "\n";
				break;

			case "p":
			case "div":
				// add one line
				$output = "\n";
				break;

			default:
				// print out contents of unknown tags
				$output = "";
				break;
		}

		// debug
		//$output .= "[$name,$nextName]";

		if($node->childNodes)
			for ($i = 0; $i < $node->childNodes->length; $i++) {
			$n = $node->childNodes->item($i);

			$text = $this->iterate_over_node($n);

			$output .= $text;
		}

		// end whitespace
		switch ($name) {
			case "style":
			case "head":
			case "title":
			case "meta":
			case "script":
				// ignore these tags
				return "";

			case "h1":
			case "h2":
			case "h3":
			case "h4":
			case "h5":
			case "h6":
				$output .= "\n";
				break;

			case "p":
			case "br":
				// add one line
				if ($nextName != "div")
					$output .= "\n";
				break;

			case "div":
				// add one line only if the next child isn't a div
				if ($nextName != "div" && $nextName != null)
					$output .= "\n";
				break;

			case "a":
				// links are returned in [text](link) format
				$href = $node->getAttribute("href");
				if ($href == null) {
					// it doesn't link anywhere
					if ($node->getAttribute("name") != null) {
						$output = "[$output]";
					}
				} else {
					if ($href == $output) {
						// link to the same address: just use link
						$output;
					} else {
						// replace it
						$output = "[$output]($href)";
					}
				}

				// does the next node require additional whitespace?
				switch ($nextName) {
					case "h1": case "h2": case "h3": case "h4": case "h5": case "h6":
					$output .= "\n";
					break;
				}

			default:
				// do nothing
		}

		return $output;
	}
}

