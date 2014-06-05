<?php

class m140430_013326_credit_cards extends CDbMigration
{
	public function up()
	{
		$this->update('xlsws_credit_card',array('validfunc' =>  'AMERICAN_EXPRESS'),'id = :id',array(':id' => 1));
		$this->update('xlsws_credit_card',array('validfunc' =>  'CARTE_BLANCHE'),'id = :id',array(':id' => 2));
		$this->update('xlsws_credit_card',array('validfunc' =>  'DINERS_CLUB'),'id = :id',array(':id' => 3));
		$this->update('xlsws_credit_card',array('validfunc' =>  'DISCOVER'),'id = :id',array(':id' => 4));
		$this->update('xlsws_credit_card',array('validfunc' =>  'ENROUTE'),'id = :id',array(':id' => 5));
		$this->update('xlsws_credit_card',array('validfunc' =>  'JCB'),'id = :id',array(':id' => 6));
		$this->update('xlsws_credit_card',array('validfunc' =>  'MAESTRO'),'id = :id',array(':id' => 7));
		$this->update('xlsws_credit_card',array('validfunc' =>  'MASTERCARD'),'id = :id',array(':id' => 8));
		$this->update('xlsws_credit_card',array('validfunc' =>  'SOLO'),'id = :id',array(':id' => 9));
		$this->update('xlsws_credit_card',array('validfunc' =>  'SWITCH'),'id = :id',array(':id' => 10));
		$this->update('xlsws_credit_card',array('validfunc' =>  'VISA'),'id = :id',array(':id' => 11));
		$this->update('xlsws_credit_card',array('validfunc' =>  'ELECTRON'),'id = :id',array(':id' => 12));

		$this->delete('xlsws_credit_card','validfunc = :key',array('key'=>'ENROUTE'));
		$this->delete('xlsws_credit_card','validfunc = :key',array('key'=>'CARTE_BLANCHE'));
	}

	public function down()
	{
		echo "m140430_013326_credit_cards does not support migration down.\n";

	}

}