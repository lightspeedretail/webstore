<?php

/**
 * Class FBIdentity
 * Identity structure for Facebook
 */
class FBIdentity extends CUserIdentity
{

	const ERROR_DUAL_ACCOUNTS = 100;
	const ERROR_NOT_APPROVED=101;

	//inherit username, password
    /**
     * 
     * @var type int
     */
    public $_id;

    /**
     * Overrided parent method
     * @return type 
     */
    public function getId() 
    {
        return $this->_id;
    }

    /**
     * Authenticate user
     * @return type 
     */
    public function authenticate() 
    {

	    $user = Customer::model()->findByAttributes(array('facebook'=>$this->password));
	    if($user instanceof Customer)
	    {

		    $this->loginHousekeeping($user);
	    }
	    else
	    {
		    //We didn't find the Facebook ID in the database, but let's see if the user has an account already

		    $user = Customer::model()->findByAttributes(array('email'=>$this->username));
		    if($user instanceof Customer ) //We found an existing account under this email
		    {
			    if(is_null($user->facebook))
			    {//We found an account, merge them
				    $user->facebook = $this->password;
				    $user->save();
				    $this->loginHousekeeping($user);
			    } else {
				    //Somehow we've found an existing account with an email from facebook but not the same ID
				    //Clear Facebook ID and bail, this is a serious conflict
				    $user->facebook = $this->password;
				    $user->save();
				    $this->redirect(Yii::app()->homeUrl);
			    }

		    } else {

			    //New user to our site using Facebook, so set up an account
			    $model = new Customer();
			    $model->scenario = 'createfb';
			    $results = Yii::app()->facebook->api('/me');
			    $model->first_name = $results['first_name'];
			    $model->last_name = $results['last_name'];
			    $model->email = $results['email'];
			    $model->email_repeat = $results['email'];
			    $model->record_type = Customer::REGISTERED;
				$model->newsletter_subscribe = 1;
			    $model->facebook = $results['id'];

			    if(_xls_get_conf('MODERATE_REGISTRATION',0)==1)
			    {
				    $this->errorCode = self::ERROR_NOT_APPROVED;
				    $model->allow_login=Customer::UNAPPROVED_USER;
			    }
			    else
			    {
				    $model->allow_login=Customer::NORMAL_USER;
				    if(!$model->save())
				    {
					    Yii::log("Error creating Facebook account ".
						    print_r($model->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);

					    die("A serious error has occurred creating a Facebook account");
				    }

				    $this->loginHousekeeping($model);
			    }

		    }


	    }
        
	return $this->errorCode;
    }


	protected function loginHousekeeping($user)
    {

	    $this->_id       = $user->id;

	    $this->setState('fullname', $user->first_name.' '.$user->last_name);
	    $this->setState('firstname', $user->first_name);
	    $this->setState('profilephoto',Yii::app()->facebook->getProfilePicture('square'));
	    $this->setState('facebook',true);

	    $user->last_login = new CDbExpression('NOW()');

	    if ($user->allow_login == Customer::ADMIN_USER)
		    $this->setState('role', 'admin');
	    else
		    $this->setState('role', 'user');

	    $user->save();
	    $this->errorCode = self::ERROR_NONE;
    }
}
