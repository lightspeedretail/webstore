<?php

class m150526_164239_WS_467_admin_email_settings extends CDbMigration
{
	public function up()
	{
		$this->update(
			'xlsws_configuration',
			array(
				'title' => 'From Address',
				'helper_text' => 'The email address from which Web Store confirmations ' .
					'and receipts are sent to customers. This email address receives ' .
					'copies of customer orders.',
			),
			'key_name = :key',
			array(':key' => 'ORDER_FROM')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'helper_text' => 'If you want a blind carbon copy of confirmations ' .
					'and receipts to be sent to another email address, enter it here.'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_BCC')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'helper_text' => 'The outgoing SMTP server address for your ' .
					'email provider (e.g. smtp.gmail.com).'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_SMTP_SERVER')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'helper_text' => 'The SMTP port for your email provider (e.g. 465, 587).'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_SMTP_PORT')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'helper_text' => 'The SMTP username for your email account. ' .
					'Depending on your provider, it can be your full email address ' .
					'or just your email username.'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_SMTP_USERNAME')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'helper_text' => 'The SMTP password for your email account. ' .
					'In most instances, this is your email account password.'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_SMTP_PASSWORD')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'title' => 'SMTP Server Security Mode',
				'helper_text' => 'Some email providers require a specific ' .
					'security mode for outgoing emails. Unless specified otherwise ' .
					'by your provider, the Autodetect option should be suitable in ' .
					'most instances.'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_SMTP_SECURITY_MODE')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'title' => 'Use Auth Plain Authentication',
				'helper_text' => 'When enabled, your SMTP credentials are sent ' .
					'to your email provider in plain text format as opposed to ' .
					'encrypted. Enable this option only if your email provider ' .
					'requires plain text authentication.'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_SMTP_AUTH_PLAIN')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'title' => 'Send Test Email on Save',
				'helper_text' => 'This option lets you test your email server '.
					'settings. When this option is enabled, click Save to send a ' .
					'test email to the From Address.'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_TEST')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'key_value' => '1'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_TEST')
		);
	}

	public function down()
	{
		$this->update(
			'xlsws_configuration',
			array(
				'title' => 'Order From',
				'helper_text' => 'Order email address from which order notification is sent. ' .
					'This email address also gets the notification of the order'
			),
			'key_name = :key',
			array(':key' => 'ORDER_FROM')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'helper_text' => 'Enter an email address here if you would like to get BCCed on all emails sent by the webstore.'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_BCC')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'helper_text' => 'SMTP Server to send emails'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_SMTP_SERVER')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'helper_text' => 'SMTP Server Port'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_SMTP_PORT')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'helper_text' => 'If your SMTP server requires a username, please enter it here'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_SMTP_USERNAME')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'helper_text' => 'If your SMTP server requires a password, please enter it here.'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_SMTP_PASSWORD')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'title' => 'SMTP Server Security Mode',
				'helper_text' => 'Automatic based on SMTP Port, or force security.'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_SMTP_SECURITY_MODE')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'title' => 'Force AUTH PLAIN Authentication',
				'helper_text' => 'Force plain text password in rare circumstances'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_SMTP_AUTH_PLAIN')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'title' => 'Send Test Email on Save',
				'helper_text' => 'When clicking Save, system will attempt to send a test email through'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_TEST')
		);

		$this->update(
			'xlsws_configuration',
			array(
				'key_value' => '0'
			),
			'key_name = :key',
			array(':key' => 'EMAIL_TEST')
		);
		return true;
	}
}
