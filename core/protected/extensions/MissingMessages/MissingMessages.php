<?php
/**
 * Handle onMissingTranslation event
 * //Based on db-missing-translations at http://www.yiiframework.com/extension/db-missing-translations/
 * //http://opensource.org/licenses/bsd-license.php
 */
class MissingMessages extends CApplicationComponent
{
	/**
	 * Add missing translations to the source table and 
	 * If we are using a different translation then the original one
	 * Then add the same message to the translation table.
	 */
	static public function load($event)
	{

		// Load the messages		
		$objSource = Stringsource::model()->find('message=:message AND category=:category',
			array(':message'=>$event->message, ':category'=>$event->category));
		
		// If we didn't find one then add it
		if( !$objSource )
		{
			// Add it
			$objSource = new Stringsource;

			$objSource->category = $event->category;
			$objSource->message = $event->message;
			$objSource->save();

			$lastID = Yii::app()->db->lastInsertID;
		}
		
		if( $event->language != Yii::app()->sourceLanguage && $event->language != _xls_get_conf('LANG_CODE') )
		{
			// Do the same thing with the messages	
			$objTranslate = Stringtranslate::model()->find('language=:language AND id=:id',
				array(':language'=>$event->language, ':id'=>$objSource->id));
		
			// If we didn't find one then add it
			if( !$objTranslate )
			{
				// Add it
				$objTranslate = new Stringtranslate;

				$objTranslate->id = $objSource->id;
				$objTranslate->language = $event->language;
				$objTranslate->translation = $event->message;
				$objTranslate->save();

			}
		}
		
	}
}