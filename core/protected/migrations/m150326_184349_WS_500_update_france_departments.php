<?php

class m150326_184349_WS_500_update_france_departments extends CDbMigration
{
	public function up()
	{
		$existingStates = array(
			array('order' => 1, 'code' => '01', 'name' => 'Ain'),
			array('order' => 2, 'code' => '02', 'name' => 'Aisne'),
			array('order' => 3, 'code' => '03', 'name' => 'Allier'),
			array('order' => 4, 'code' => '04', 'name' => 'Alpes-de-Haute-Provence'),
			array('order' => 6, 'code' => '06', 'name' => 'Alpes-Maritimes'),
			array('order' => 7, 'code' => '07', 'name' => 'Ardèche'),
			array('order' => 8, 'code' => '08', 'name' => 'Ardennes'),
			array('order' => 9, 'code' => '09', 'name' => 'Ariège'),
			array('order' => 10, 'code' => '10', 'name' => 'Aube'),
			array('order' => 11, 'code' => '11', 'name' => 'Aude'),
			array('order' => 12, 'code' => '12', 'name' => 'Aveyron'),
			array('order' => 13, 'code' => '13', 'name' => 'Bouches-du-Rhône'),
			array('order' => 14, 'code' => '14', 'name' => 'Calvados'),
			array('order' => 15, 'code' => '15', 'name' => 'Cantal'),
			array('order' => 16, 'code' => '16', 'name' => 'Charente'),
			array('order' => 17, 'code' => '17', 'name' => 'Charente-Maritime'),
			array('order' => 18, 'code' => '18', 'name' => 'Cher'),
			array('order' => 19, 'code' => '19', 'name' => 'Corrèze'),
			array('order' => 20, 'code' => '2A', 'name' => 'Corse-du-Sud'),
			array('order' => 21, 'code' => '2B', 'name' => 'Haute-Corse'),
			array('order' => 22, 'code' => '21', 'name' => "Côte-d'Or"),
			array('order' => 23, 'code' => '22', 'name' => "Côtes-d'Armor"),
			array('order' => 24, 'code' => '23', 'name' => 'Creuse'),
			array('order' => 25, 'code' => '24', 'name' => 'Dordogne'),
			array('order' => 26, 'code' => '25', 'name' => 'Doubs'),
			array('order' => 27, 'code' => '26', 'name' => 'Drôme'),
			array('order' => 28, 'code' => '27', 'name' => 'Eure'),
			array('order' => 29, 'code' => '28', 'name' => 'Eure-et-Loir'),
			array('order' => 30, 'code' => '29', 'name' => 'Finistère'),
			array('order' => 31, 'code' => '30', 'name' => 'Gard'),
			array('order' => 32, 'code' => '31', 'name' => 'Haute-Garonne'),
			array('order' => 33, 'code' => '32', 'name' => 'Gers'),
			array('order' => 34, 'code' => '33', 'name' => 'Gironde'),
			array('order' => 35, 'code' => '34', 'name' => 'Hérault'),
			array('order' => 36, 'code' => '35', 'name' => 'Ille-et-Vilaine'),
			array('order' => 37, 'code' => '36', 'name' => 'Indre'),
			array('order' => 38, 'code' => '37', 'name' => 'Indre-et-Loire'),
			array('order' => 39, 'code' => '38', 'name' => 'Isère'),
			array('order' => 40, 'code' => '39', 'name' => 'Jura'),
			array('order' => 41, 'code' => '40', 'name' => 'Landes'),
			array('order' => 42, 'code' => '41', 'name' => 'Loir-et-Cher'),
			array('order' => 43, 'code' => '42', 'name' => 'Loire'),
			array('order' => 44, 'code' => '43', 'name' => 'Haute-Loire'),
			array('order' => 45, 'code' => '44', 'name' => 'Loire-Atlantique'),
			array('order' => 46, 'code' => '45', 'name' => 'Loiret'),
			array('order' => 47, 'code' => '46', 'name' => 'Lot'),
			array('order' => 48, 'code' => '47', 'name' => 'Lot-et-Garonne'),
			array('order' => 49, 'code' => '48', 'name' => 'Lozère'),
			array('order' => 50, 'code' => '49', 'name' => 'Maine-et-Loire'),
			array('order' => 51, 'code' => '50', 'name' => 'Manche'),
			array('order' => 52, 'code' => '51', 'name' => 'Marne'),
			array('order' => 53, 'code' => '52', 'name' => 'Haute-Marne'),
			array('order' => 76, 'code' => '75', 'name' => 'Paris'),
			array('order' => 81, 'code' => '80', 'name' => 'Somme'),
			array('order' => 82, 'code' => '81', 'name' => 'Tarn'),
			array('order' => 83, 'code' => '82', 'name' => 'Tarn-et-Garonne'),
			array('order' => 84, 'code' => '83', 'name' => 'Var'),
			array('order' => 85, 'code' => '84', 'name' => 'Vaucluse'),
			array('order' => 86, 'code' => '85', 'name' => 'Vendée'),
			array('order' => 87, 'code' => '86', 'name' => 'Vienne'),
			array('order' => 89, 'code' => '88', 'name' => 'Vosges'),
			array('order' => 90, 'code' => '89', 'name' => 'Yonne'),
			array('order' => 91, 'code' => '90', 'name' => 'Territoire de Belfort'),
			array('order' => 92, 'code' => '91', 'name' => 'Essonne'),
			array('order' => 93, 'code' => '92', 'name' => 'Hauts-de-Seine'),
			array('order' => 94, 'code' => '93', 'name' => 'Seine-Saint-Denis'),
			array('order' => 95, 'code' => '94', 'name' => 'Val-de-Marne'),
			array('order' => 96, 'code' => '95', 'name' => "Val-d'Oise"),
		);

		foreach ($existingStates as $state)
		{
			$this->update(
				'xlsws_state',
				array('code' => $state['code'], 'sort_order' => $state['order']),
				'state = :name',
				array(':name' => $state['name'])
			);
		}

		// the table has two entries with the same state name (Hautes-Viennes), most likely an error.
		// For the correct entry, we update the sort order
		$this->update(
			'xlsws_state',
			array('sort_order' => 88),
			'country_id = :id AND code = :code',
			array(':id' => 74, ':code' => '87')
		);

		// For the incorrect entry we must update the name, code and sort order.
		// For whatever reason, a single update call would not update all 3 attributes,
		// despite not throwing any errors. So two separate update calls are made
		$this->update(
			'xlsws_state',
			array('state' => 'Hautes-Alpes'),
			'country_id = :id AND code = :code',
			array(':id' => 74, ':code' => '5')
		);

		$this->update(
			'xlsws_state',
			array('code' => '05', 'sort_order' => 5),
			'state = :name',
			array(':name' => 'Hautes-Alpes')
		);

		// now we add the missing states
		$newStates = array(
			array('order' => 54, 'code' => '53', 'name' => 'Mayenne'),
			array('order' => 55, 'code' => '54', 'name' => 'Meurthe-et-Moselle'),
			array('order' => 56, 'code' => '55', 'name' => 'Meuse'),
			array('order' => 57, 'code' => '56', 'name' => 'Morbihan'),
			array('order' => 58, 'code' => '57', 'name' => 'Moselle'),
			array('order' => 59, 'code' => '58', 'name' => 'Nièvre'),
			array('order' => 60, 'code' => '59', 'name' => 'Nord'),
			array('order' => 61, 'code' => '60', 'name' => 'Oise'),
			array('order' => 62, 'code' => '61', 'name' => 'Orne'),
			array('order' => 63, 'code' => '62', 'name' => 'Pas-de-Calais'),
			array('order' => 64, 'code' => '63', 'name' => 'Puy-de-Dôme'),
			array('order' => 65, 'code' => '64', 'name' => 'Pyrénées-Atlantiques'),
			array('order' => 66, 'code' => '65', 'name' => 'Hautes-Pyrénées'),
			array('order' => 67, 'code' => '66', 'name' => 'Pyrénées-Orientales'),
			array('order' => 68, 'code' => '67', 'name' => 'Bas-Rhin'),
			array('order' => 69, 'code' => '68', 'name' => 'Haut-Rhin'),
			array('order' => 70, 'code' => '69', 'name' => 'Rhône'),
			array('order' => 71, 'code' => '70', 'name' => 'Haute-Saône'),
			array('order' => 72, 'code' => '71', 'name' => 'Saône-et-Loire'),
			array('order' => 73, 'code' => '72', 'name' => 'Sarthe'),
			array('order' => 74, 'code' => '73', 'name' => 'Savoie'),
			array('order' => 75, 'code' => '74', 'name' => 'Haute-Savoie'),
			array('order' => 77, 'code' => '76', 'name' => 'Seine-Maritime'),
			array('order' => 78, 'code' => '77', 'name' => 'Seine-et-Marne'),
			array('order' => 79, 'code' => '78', 'name' => 'Yvelines'),
			array('order' => 80, 'code' => '79', 'name' => 'Deux-Sèvres'),
		);

		foreach ($newStates as $state)
		{
			$this->insert(
				'xlsws_state',
				array(
					'country_id' => 74,
					'country_code' => 'FR',
					'code' => $state['code'],
					'active' => 1,
					'sort_order' => $state['order'],
					'state' => $state['name']
				)
			);
		}
	}

	public function down()
	{
		echo "m150326_184349_WS_500_update_france_departments does not support migration down.\n";
		return false;
	}
}