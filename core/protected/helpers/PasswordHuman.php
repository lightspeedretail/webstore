<?php
/*
 * http://www.anyexample.com/programming/php/php__password_generation.xml
 */
class PasswordHuman {

	private $password;
	private $use_prefix;
	private $syllables;


	public function __construct($syllables=2, $use_prefix=false) {

		$this->syllables = $syllables;
		$this->use_prefix = $use_prefix;
		
	}



	public function generate()
	{


		// Define function unless it is already exists
		if (!function_exists('ae_arr')) {
			// This function returns random array element
			function ae_arr(&$arr)
			{
				return $arr[rand(0, sizeof($arr) - 1)];
			}
		}

		// 20 prefixes
		$prefix = array('aero', 'anti', 'auto', 'bi', 'bio',
			'cine', 'deca', 'demo', 'dyna', 'eco',
			'ergo', 'geo', 'gyno', 'hypo', 'kilo',
			'mega', 'tera', 'mini', 'nano', 'duo');

		// 10 random suffixes
		$suffix = array('dom', 'ity', 'ment', 'sion', 'ness',
			'ence', 'er', 'ist', 'tion', 'or');

		// 8 vowel sounds
		$vowels = array('a', 'o', 'e', 'i', 'y', 'u', 'ou', 'oo');

		// 20 random consonants
		$consonants = array('w', 'r', 't', 'p', 's', 'd', 'f', 'g', 'h', 'j',
			'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm', 'qu');

		$password = $this->use_prefix ? ae_arr($prefix) : '';
		$password_suffix = ae_arr($suffix);

		for ($i = 0; $i < $this->syllables; $i++) {
		// selecting random consonant
			$doubles = array('n', 'm', 't', 's');
			$c = ae_arr($consonants);
			if (in_array($c, $doubles) && ($i != 0)) { // maybe double it
				if (rand(0, 2) == 1) // 33% probability
				$c .= $c;
			}
			$password .= $c;


			// selecting random vowel
			$password .= ae_arr($vowels);

			if ($i == $this->syllables - 1) // if suffix begin with vovel
			if (in_array($password_suffix[0], $vowels)) // add one more consonant
			$password .= ae_arr($consonants);

		}

		// selecting random suffix
		$password .= $password_suffix;


		$this->password = $password;
		return $this->password;
	}


	/**
	 * Returns the last generated password. If there is none, a new one will be generated.
	 */
	public function __toString() {
		return (empty($this->password) ? $this->generate() : $this->password);
	}

}
