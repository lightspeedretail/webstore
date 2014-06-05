<?php

class m140411_115337_load_countries extends CDbMigration
{
	public function up()
	{
		$this->insert('xlsws_country',array(
				'id'=>1,
				'code' =>'AF',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '100',
				'country' => 'Afghanistan',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>2,
				'code' =>'AL',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Albania',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>3,
				'code' =>'DZ',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Algeria',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>4,
				'code' =>'AS',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'American Samoa',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>6,
				'code' =>'AO',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Angola',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>7,
				'code' =>'AI',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Anguilla',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>8,
				'code' =>'AQ',
				'region' => 'AN',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Antarctica',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>9,
				'code' =>'AG',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Antigua and Barbuda',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>10,
				'code' =>'AR',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Argentina',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>11,
				'code' =>'AM',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Armenia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>12,
				'code' =>'AW',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Aruba',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>13,
				'code' =>'AU',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '4',
				'country' => 'Australia',
				'zip_validate_preg' => '/\d{4}/'
			));

		$this->insert('xlsws_country',array(
				'id'=>14,
				'code' =>'AT',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Austria',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>15,
				'code' =>'AZ',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Azerbaijan',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>16,
				'code' =>'BS',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Bahamas',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>17,
				'code' =>'BH',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Bahrain',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>18,
				'code' =>'BD',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Bangladesh',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>19,
				'code' =>'BB',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Barbados',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>20,
				'code' =>'BY',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Belarus',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>21,
				'code' =>'BE',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Belgium',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>22,
				'code' =>'BZ',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Belize',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>23,
				'code' =>'BJ',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Benin',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>24,
				'code' =>'BM',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Bermuda',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>25,
				'code' =>'BT',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Bhutan',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>26,
				'code' =>'BO',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Bolivia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>27,
				'code' =>'BA',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Bosnia and Herzegowina',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>28,
				'code' =>'BW',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Botswana',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>29,
				'code' =>'BV',
				'region' => 'AN',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Bouvet Island',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>30,
				'code' =>'BR',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Brazil',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>31,
				'code' =>'IO',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'British Indian Ocean Territory',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>32,
				'code' =>'VG',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'British Virgin Islands',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>33,
				'code' =>'BN',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Brunei Darussalam',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>34,
				'code' =>'BG',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Bulgaria',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>35,
				'code' =>'BF',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Burkina Faso',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>36,
				'code' =>'BI',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Burundi',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>37,
				'code' =>'KH',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Cambodia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>38,
				'code' =>'CM',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Cameroon',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>39,
				'code' =>'CA',
				'region' => 'NA',
				'active' => '1',
				'sort_order' => '2',
				'country' => 'Canada',
				'zip_validate_preg' => '/^[ABCEGHJKLMNPRSTVXY]\d[A-Z]( )?\d[A-Z]\d$/'
			));

		$this->insert('xlsws_country',array(
				'id'=>40,
				'code' =>'CV',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Cape Verde',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>41,
				'code' =>'KY',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Cayman Islands',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>42,
				'code' =>'CF',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Central African Republic',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>43,
				'code' =>'TD',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Chad',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>44,
				'code' =>'CL',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Chile',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>45,
				'code' =>'CN',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'China',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>46,
				'code' =>'CX',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Christmas Island',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>47,
				'code' =>'CC',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Cocos (Keeling) Islands',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>48,
				'code' =>'CO',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Colombia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>49,
				'code' =>'KM',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Comoros',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>50,
				'code' =>'CG',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Congo',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>51,
				'code' =>'CK',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Cook Islands',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>52,
				'code' =>'CR',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Costa Rica',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>53,
				'code' =>'CI',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Cote D\'ivoire',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>54,
				'code' =>'HR',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Croatia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>55,
				'code' =>'CU',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Cuba',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>56,
				'code' =>'CY',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Cyprus',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>57,
				'code' =>'CZ',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Czech Republic',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>58,
				'code' =>'DK',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Denmark',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>59,
				'code' =>'DJ',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Djibouti',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>60,
				'code' =>'DM',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Dominica',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>61,
				'code' =>'DO',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Dominican Republic',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>62,
				'code' =>'TP',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'East Timor',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>63,
				'code' =>'EC',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Ecuador',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>64,
				'code' =>'EG',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Egypt',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>65,
				'code' =>'SV',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'El Salvador',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>66,
				'code' =>'GQ',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Equatorial Guinea',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>67,
				'code' =>'ER',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Eritrea',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>68,
				'code' =>'EE',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Estonia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>69,
				'code' =>'ET',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Ethiopia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>70,
				'code' =>'FK',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Falkland Islands (Malvinas)',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>71,
				'code' =>'FO',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Faroe Islands',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>72,
				'code' =>'FJ',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Fiji',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>73,
				'code' =>'FI',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Finland',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>74,
				'code' =>'FR',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'France',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>76,
				'code' =>'GF',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'French Guiana',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>77,
				'code' =>'PF',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'French Polynesia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>78,
				'code' =>'TF',
				'region' => 'AN',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'French Southern Territories',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>79,
				'code' =>'GA',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Gabon',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>80,
				'code' =>'GE',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Georgia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>81,
				'code' =>'GM',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Gambia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>82,
				'code' =>'PS',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Palestine Authority',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>83,
				'code' =>'DE',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Germany',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>84,
				'code' =>'GH',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Ghana',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>85,
				'code' =>'GI',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Gibraltar',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>86,
				'code' =>'GR',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Greece',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>87,
				'code' =>'GL',
				'region' => 'NA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Greenland',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>88,
				'code' =>'GD',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Grenada',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>89,
				'code' =>'GP',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Guadeloupe',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>90,
				'code' =>'GU',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Guam',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>91,
				'code' =>'GT',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Guatemala',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>92,
				'code' =>'GN',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Guinea',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>93,
				'code' =>'GW',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Guinea-Bissau',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>94,
				'code' =>'GY',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Guyana',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>95,
				'code' =>'HT',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Haiti',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>96,
				'code' =>'HM',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Heard and McDonald Islands',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>97,
				'code' =>'HN',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Honduras',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>98,
				'code' =>'HK',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Hong Kong',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>99,
				'code' =>'HU',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Hungary',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>100,
				'code' =>'IS',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Iceland',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>101,
				'code' =>'IN',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'India',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>102,
				'code' =>'ID',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Indonesia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>103,
				'code' =>'IQ',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Iraq',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>104,
				'code' =>'IE',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Ireland',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>105,
				'code' =>'IR',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Iran',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>106,
				'code' =>'IL',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Israel',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>107,
				'code' =>'IT',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Italy',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>108,
				'code' =>'JM',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Jamaica',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>109,
				'code' =>'JP',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Japan',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>110,
				'code' =>'JO',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Jordan',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>111,
				'code' =>'KZ',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Kazakhstan',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>112,
				'code' =>'KE',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Kenya',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>113,
				'code' =>'KI',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Kiribati',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>114,
				'code' =>'KP',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Korea',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>115,
				'code' =>'KR',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Korea, Republic of',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>116,
				'code' =>'KW',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Kuwait',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>117,
				'code' =>'KG',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Kyrgyzstan',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>118,
				'code' =>'LA',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Laos',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>119,
				'code' =>'LV',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Latvia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>120,
				'code' =>'LB',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Lebanon',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>121,
				'code' =>'LS',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Lesotho',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>122,
				'code' =>'LR',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Liberia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>123,
				'code' =>'LY',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Libyan Arab Jamahiriya',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>124,
				'code' =>'LI',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Liechtenstein',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>125,
				'code' =>'LT',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Lithuania',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>126,
				'code' =>'LU',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Luxembourg',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>127,
				'code' =>'MO',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Macau',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>128,
				'code' =>'MK',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Macedonia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>129,
				'code' =>'MG',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Madagascar',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>130,
				'code' =>'MW',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Malawi',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>131,
				'code' =>'MY',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Malaysia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>132,
				'code' =>'MV',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Maldives',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>133,
				'code' =>'ML',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Mali',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>134,
				'code' =>'MT',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Malta',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>135,
				'code' =>'MH',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Marshall Islands',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>136,
				'code' =>'MQ',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Martinique',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>137,
				'code' =>'MR',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Mauritania',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>138,
				'code' =>'MU',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Mauritius',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>139,
				'code' =>'YT',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Mayotte',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>140,
				'code' =>'MX',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Mexico',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>141,
				'code' =>'FM',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Micronesia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>142,
				'code' =>'MD',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Moldova, Republic of',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>143,
				'code' =>'MC',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Monaco',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>144,
				'code' =>'MN',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Mongolia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>145,
				'code' =>'MS',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Montserrat',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>146,
				'code' =>'MA',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Morocco',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>147,
				'code' =>'MZ',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Mozambique',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>148,
				'code' =>'MM',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Myanmar',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>149,
				'code' =>'NA',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Namibia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>150,
				'code' =>'NR',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Nauru',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>151,
				'code' =>'NP',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Nepal',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>152,
				'code' =>'NL',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Netherlands',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>153,
				'code' =>'AN',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Netherlands Antilles',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>154,
				'code' =>'NC',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'New Caledonia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>155,
				'code' =>'NZ',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'New Zealand',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>156,
				'code' =>'NI',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Nicaragua',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>157,
				'code' =>'NE',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Niger',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>158,
				'code' =>'NG',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Nigeria',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>159,
				'code' =>'NU',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Niue',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>160,
				'code' =>'NF',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Norfolk Island',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>161,
				'code' =>'MP',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Northern Mariana Islands',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>162,
				'code' =>'NO',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Norway',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>163,
				'code' =>'OM',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Oman',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>164,
				'code' =>'PK',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Pakistan',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>165,
				'code' =>'PW',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Palau',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>166,
				'code' =>'PA',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Panama',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>167,
				'code' =>'PG',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Papua New Guinea',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>168,
				'code' =>'PY',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Paraguay',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>169,
				'code' =>'PE',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Peru',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>170,
				'code' =>'PH',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Philippines',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>171,
				'code' =>'PN',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Pitcairn',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>172,
				'code' =>'PL',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Poland',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>173,
				'code' =>'PT',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Portugal',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>174,
				'code' =>'PR',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Puerto Rico',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>175,
				'code' =>'QA',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Qatar',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>176,
				'code' =>'RE',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Reunion',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>177,
				'code' =>'RO',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Romania',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>178,
				'code' =>'RU',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Russia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>179,
				'code' =>'RW',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Rwanda',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>180,
				'code' =>'LC',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Saint Lucia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>181,
				'code' =>'WS',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Samoa',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>182,
				'code' =>'SM',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'San Marino',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>183,
				'code' =>'ST',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Sao Tome and Principe',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>184,
				'code' =>'SA',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Saudi Arabia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>185,
				'code' =>'SN',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Senegal',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>186,
				'code' =>'SC',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Seychelles',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>187,
				'code' =>'SL',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Sierra Leone',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>188,
				'code' =>'SG',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Singapore',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>189,
				'code' =>'SK',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Slovakia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>190,
				'code' =>'SI',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Slovenia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>191,
				'code' =>'SB',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Solomon Islands',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>192,
				'code' =>'SO',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Somalia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>193,
				'code' =>'ZA',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'South Africa',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>194,
				'code' =>'ES',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Spain',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>195,
				'code' =>'LK',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Sri Lanka',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>196,
				'code' =>'SH',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'St. Helena',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>197,
				'code' =>'KN',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'St. Kitts and Nevis',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>198,
				'code' =>'PM',
				'region' => 'NA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'St. Pierre and Miquelon',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>199,
				'code' =>'VC',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'St. Vincent and the Grenadines',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>200,
				'code' =>'SD',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Sudan',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>201,
				'code' =>'SR',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Suriname',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>202,
				'code' =>'SJ',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Svalbard and Jan Mayen Islands',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>203,
				'code' =>'SZ',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Swaziland',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>204,
				'code' =>'SE',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Sweden',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>205,
				'code' =>'CH',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Switzerland',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>206,
				'code' =>'SY',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Syrian Arab Republic',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>207,
				'code' =>'TW',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Taiwan',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>208,
				'code' =>'TJ',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Tajikistan',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>209,
				'code' =>'TZ',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Tanzania, United Republic of',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>210,
				'code' =>'TH',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Thailand',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>211,
				'code' =>'TG',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Togo',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>212,
				'code' =>'TK',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Tokelau',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>213,
				'code' =>'TO',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Tonga',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>214,
				'code' =>'TT',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Trinidad and Tobago',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>215,
				'code' =>'TN',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Tunisia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>216,
				'code' =>'TR',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Turkey',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>217,
				'code' =>'TM',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Turkmenistan',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>218,
				'code' =>'TC',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Turks and Caicos Islands',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>219,
				'code' =>'TV',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Tuvalu',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>220,
				'code' =>'UG',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Uganda',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>221,
				'code' =>'UA',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Ukraine',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>222,
				'code' =>'AE',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'United Arab Emirates',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>223,
				'code' =>'GB',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '3',
				'country' => 'United Kingdom (Great Britain)',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>224,
				'code' =>'US',
				'region' => 'NA',
				'active' => '1',
				'sort_order' => '1',
				'country' => 'United States',
				'zip_validate_preg' => '/^([0-9]{5})(-[0-9]{4})?$/i'
			));

		$this->insert('xlsws_country',array(
				'id'=>225,
				'code' =>'VI',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'United States Virgin Islands',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>226,
				'code' =>'UY',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Uruguay',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>227,
				'code' =>'UZ',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Uzbekistan',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>228,
				'code' =>'VU',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Vanuatu',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>229,
				'code' =>'VA',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Vatican City State',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>230,
				'code' =>'VE',
				'region' => 'LA',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Venezuela',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>231,
				'code' =>'VN',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Viet Nam',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>232,
				'code' =>'WF',
				'region' => 'AU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Wallis And Futuna Islands',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>233,
				'code' =>'EH',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Western Sahara',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>234,
				'code' =>'YE',
				'region' => 'AS',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Yemen',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>235,
				'code' =>'CS',
				'region' => 'EU',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Serbia and Montenegro',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>236,
				'code' =>'ZR',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Zaire',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>237,
				'code' =>'ZM',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Zambia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>238,
				'code' =>'ZW',
				'region' => 'AF',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Zimbabwe',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>239,
				'code' =>'AP',
				'region' => '',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Asia-Pacific',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>240,
				'code' =>'RS',
				'region' => '',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Republic of Serbia',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>241,
				'code' =>'AX',
				'region' => '',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Aland Islands',
				'zip_validate_preg' => NULL
			));

		$this->insert('xlsws_country',array(
				'id'=>242,
				'code' =>'EU',
				'region' => '',
				'active' => '1',
				'sort_order' => '10',
				'country' => 'Europe',
				'zip_validate_preg' => NULL
			));

	}

	public function down()
	{
		$this->delete('xlsws_country');

	}

}