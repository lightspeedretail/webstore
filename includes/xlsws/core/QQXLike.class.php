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

/*
 * Qcodo query builder used to generate the search algorithm
 */
class QQXLike extends QQConditionComparison {
	protected $operator;

	public function __construct(QQNode $objQueryNode, $strValue,
		$strOperator = ADVANCED_SEARCH_DEFAULT_OPERATOR) {

		$this->operator = $strOperator;

		$this->objQueryNode = $objQueryNode;
		if (!$objQueryNode->_ParentNode)
			throw new QInvalidCastException('Unable to cast "' .
				$objQueryNode->_Name . '" table to Column-based QQNode', 3);

		if ($strValue instanceof QQNamedValue)
		$this->mixOperand = $strValue;
		else {
			try {
				$this->mixOperand = QType::Cast($strValue, QType::String);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}

	public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
		if ($this->mixOperand instanceof QQNamedValue)
			$objBuilder->AddWhereItem("LOWER(" .
				$this->objQueryNode->GetColumnAlias($objBuilder) .
					') LIKE ' . $this->mixOperand->Parameter()
			);
		else
			$objBuilder->AddWhereItem("(" .
				$this->search_keyword("LOWER(" .
					$this->objQueryNode->GetColumnAlias($objBuilder) .
					")",
				$this->mixOperand,
				$this->operator) . ")"
			);
	}

	// Parse search string into indivual objects
	protected function parse_search_string($search_str = '',
		&$objects, $search_operator = ADVANCED_SEARCH_DEFAULT_OPERATOR) {

		$search_str = trim(mb_strtolower($search_str));

		// get rid of brackets
		$search_str = str_replace("(","",$search_str);
		$search_str = str_replace(")","",$search_str);

		// Break up $search_str on whitespace; quoted string will be
		// reconstructed later
		$pieces = preg_split('/\s+/', $search_str);
		$objects = array();
		$tmpstring = '';
		$flag = '';

		for ($k=0; $k<count($pieces); $k++) {
			while (substr($pieces[$k], 0, 1) == '(') {
				$objects[] = '(';
				if (strlen($pieces[$k]) > 1) {
					$pieces[$k] = substr($pieces[$k], 1);
				} else {
					$pieces[$k] = '';
				}
			}

			$post_objects = array();

			while (substr($pieces[$k], -1) == ')') {
				$post_objects[] = ')';
				if (strlen($pieces[$k]) > 1) {
					$pieces[$k] = substr($pieces[$k], 0, -1);
				} else {
					$pieces[$k] = '';
				}
			}

			// Check individual words
			if ((substr($pieces[$k], -1) != '"') &&
				(substr($pieces[$k], 0, 1) != '"')) {
				$objects[] = trim($pieces[$k]);

				for ($j=0; $j<count($post_objects); $j++) {
					$objects[] = $post_objects[$j];
				}
			} else {
				/* This means that the $piece is either the beginning or
				 * the end of a string. So, we'll slurp up the $pieces
				 * and stick them together until we get to the end of the
				 * string or run out of pieces.
				 */

				// Add this word to the $tmpstring, starting the $tmpstring
				$tmpstring = trim(preg_replace('/"/', ' ', $pieces[$k]));

				// Check for one possible exception to the rule.
				// That there is a single quoted word.
				if (substr($pieces[$k], -1 ) == '"') {
					// Turn the flag off for future iterations
					$flag = 'off';

					$objects[] = trim($pieces[$k]);

					for ($j=0; $j<count($post_objects); $j++) {
						$objects[] = $post_objects[$j];
					}

					unset($tmpstring);

					// Stop looking for the end of the string and move
					// onto the next word.
					continue;
				}

				// Otherwise, turn on the flag to indicate no quotes have
				// been found attached to this word in the string.
				$flag = 'on';

				// Move on to the next word
				$k++;

				// Keep reading until the end of the string as long as the
				// $flag is on

				while ( ($flag == 'on') && ($k < count($pieces)) ) {
					while (substr($pieces[$k], -1) == ')') {
						$post_objects[] = ')';
						if (strlen($pieces[$k]) > 1) {
							$pieces[$k] = substr($pieces[$k], 0, -1);
						} else {
							$pieces[$k] = '';
						}
					}

					// If the word doesn't end in double quotes, append
					// it to the $tmpstring.
					if (substr($pieces[$k], -1) != '"') {
						// Tack this word onto the current string entity
						$tmpstring .= ' ' . $pieces[$k];

						// Move on to the next word
						$k++;
						continue;
					} else {
						/* If the $piece ends in double quotes, strip the
						 * double quotes, tack the $piece onto the tail
						 * of the string, push the $tmpstring onto the
						 * $haves, kill the $tmpstring, turn the $flag
						 * "off", and return.
						 */
						$tmpstring .= ' ' . trim(preg_replace('/"/', ' ',
							$pieces[$k]));

						// Push the $tmpstring onto the array of stuff
						// to search for
						$objects[] = trim($tmpstring);

						for ($j=0; $j<count($post_objects); $j++) {
							$objects[] = $post_objects[$j];
						}

						unset($tmpstring);

						// Turn off the flag to exit the loop
						$flag = 'off';
					}
				}
			}
		}

		// add default logical operators if needed
		$temp = array();
		for($i=0; $i<(count($objects)-1); $i++) {
			$temp[sizeof($temp)] = $objects[$i];

			if ( ($objects[$i] != 'and') &&
			($objects[$i] != 'or') &&
			($objects[$i] != '(') &&
			($objects[$i] != ')') &&
			($objects[$i+1] != 'and') &&
			($objects[$i+1] != 'or') &&
			($objects[$i+1] != '(') &&
			($objects[$i+1] != ')') ) {
				$temp[sizeof($temp)] = $search_operator;
			}
		}
		$temp[sizeof($temp)] = $objects[$i];
		$objects = $temp;

		$keyword_count = 0;
		$operator_count = 0;
		$balance = 0;
		for($i=0; $i<count($objects); $i++) {
			if ($objects[$i] == '(') $balance --;
			if ($objects[$i] == ')') $balance ++;
			if ( ($objects[$i] == 'and') || ($objects[$i] == 'or') ) {
				$operator_count ++;
			} elseif (($objects[$i]) && (
				$objects[$i] != '(') &&
				($objects[$i] != ')') ) {
					$keyword_count ++;
			}
		}

		if ( ($operator_count < $keyword_count) && ($balance == 0) ) {
			return true;
		} else {
			return false;
		}
	}

	protected function search_keyword($column_name, $keywords,
		$search_operator = ADVANCED_SEARCH_DEFAULT_OPERATOR) {

		$where_str = " ";

		if (!empty($keywords)) {
			if ($this->parse_search_string(stripslashes($keywords),
				$search_keywords, $search_operator)) {

				$where_str .= " (";
				for ($i=0, $n=sizeof($search_keywords); $i<$n; $i++ ) {
					switch ($search_keywords[$i]) {
						case '(':
						case ')':
						case 'and':
						case 'or':
							$where_str .= " " . $search_keywords[$i] . " ";
							break;
						default:
							$where_str .= "($column_name like '%" .
								addslashes($search_keywords[$i]) .  "%') ";
							break;
					}
				}
				$where_str .= " )";
			}
		} else
			return "1=1";
		return $where_str;
	}
}
