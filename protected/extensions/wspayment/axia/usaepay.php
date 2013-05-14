<?php
// USA ePay PHP Library.
//	v1.6.8 - January 5th, 2011
//
// 	Copyright (c) 2001-2008 USAePay
//	For assistance please contact devsupport@usaepay.com
//

define("USAEPAY_VERSION", "1.6.8");


/**
 * USAePay Transaction Class
 *
 */
class umTransaction {
	
	// Required for all transactions
	var $key;			// Source key
	var $pin;			// Source pin (optional)
	var $amount;		// the entire amount that will be charged to the customers card 
							// (including tax, shipping, etc)
	var $invoice;		// invoice number.  must be unique.  limited to 10 digits.  use orderid if you need longer. 
	
	// Required for Commercial Card support
	var $ponum;			// Purchase Order Number
	var $tax;			// Tax
	var $nontaxable;	// Order is non taxable
	
	// Amount details (optional)
	var $tip; 			// Tip
	var $shipping;		// Shipping charge
	var $discount; 	// Discount amount (ie gift certificate or coupon code)
	var $subtotal; 	// if subtotal is set, then 
							// subtotal + tip + shipping - discount + tax must equal amount 
							// or the transaction will be declined.  If subtotal is left blank
							// then it will be ignored
	var $currency;		// Currency of $amount
	var $allowpartialauth; //set to true if a partial authorization (less than the full $amount)  will be accepted
		
	// Required Fields for Card Not Present transacitons (Ecommerce)
	var $card;			// card number, no dashes, no spaces
	var $exp;			// expiration date 4 digits no /
	var $cardholder; 	// name of card holder
	var $street;		// street address
	var $zip;			// zip code
	
	// Fields for Card Present (POS) 
	var $magstripe;  	// mag stripe data.  can be either Track 1, Track2  or  Both  (Required if card,exp,cardholder,street and zip aren't filled in)
	var $cardpresent;   // Must be set to true if processing a card present transaction  (Default is false)
	var $termtype;  	// The type of terminal being used:  Optons are  POS - cash register, StandAlone - self service terminal,  Unattended - ie gas pump, Unkown  (Default:  Unknown)
	var $magsupport;  	// Support for mag stripe reader:   yes, no, contactless, unknown  (default is unknown unless magstripe has been sent)
	var $contactless;  	// Magstripe was read with contactless reader:  yes, no  (default is no)
	var $dukpt;			// DUK/PT for PIN Debit
	var $signature;     // Signature Capture data
		
	// fields required for check transactions
	var $account;		// bank account number
	var $routing;		// bank routing number
	var $ssn;			// social security number
	var $dlnum;			// drivers license number (required if not using ssn)
	var $dlstate;		// drivers license issuing state
	var $checknum;		// Check Number
	var $accounttype;       // Checking or Savings
	var $checkformat;	// Override default check record format
	var $checkimage_front;    // Check front
	var $checkimage_back;		// Check back
	var $auxonus;
	var $epccode;
	
	
	// Fields required for Secure Vault Payments (Direct Pay)
	var $svpbank;		// ID of cardholders bank
	var $svpreturnurl;	// URL that the bank should return the user to when tran is completed
	var $svpcancelurl; 	// URL that the bank should return the user if they cancel
	
	

	// Option parameters
	var $origauthcode;	// required if running postauth transaction.
	var $command;		// type of command to run; Possible values are: 
						// sale, credit, void, preauth, postauth, check and checkcredit. 
						// Default is sale.
	var $orderid;		// Unique order identifier.  This field can be used to reference 
						// the order for which this transaction corresponds to. This field 
						// can contain up to 64 characters and should be used instead of 
						// UMinvoice when orderids longer that 10 digits are needed.
	var $custid;   // Alpha-numeric id that uniquely identifies the customer.
	var $description;	// description of charge
	var $cvv2;			// cvv2 code
	var $custemail;		// customers email address
	var $custreceipt;	// send customer a receipt
	var $custreceiptname;	// name of custom receipt template
	var $ignoreduplicate; // prevent the system from detecting and folding duplicates
	var $ip;			// ip address of remote host
	var $testmode;		// test transaction but don't process it
	var $usesandbox;    // use sandbox server instead of production
	var $timeout;       // transaction timeout.  defaults to 45 seconds
	var $gatewayurl;   	// url for the gateway
	var $proxyurl;		// proxy server to use (if required by network)
	var $ignoresslcerterrors;  // Bypasses ssl certificate errors.  It is highly recommended that you do not use this option.  Fix your openssl installation instead!
	var $cabundle;      // manually specify location of root ca bundle (useful of root ca is not in default location)
	var $transport;     // manually select transport to use (curl or stream), by default the library will auto select based on what is available
		
	// Card Authorization - Verified By Visa and Mastercard SecureCode
	var $cardauth;    	// enable card authentication
	var $pares; 		// 
	
	// Third Party Card Authorization
	var $xid;
	var $cavv;
	var $eci;

	// Customer Database
	var $addcustomer;		//  Save transaction as a recurring transaction:  yes/no
	var $recurring;		// (obsolete,  see the addcustomer)
	
	var $schedule;		//  How often to run transaction: daily, weekly, biweekly, monthly, bimonthly, quarterly, annually.  Default is monthly, set to disabled if you don't want recurring billing
	var $numleft; 		//  The number of times to run. Either a number or * for unlimited.  Default is unlimited.
	var $start;			//  When to start the schedule.  Default is tomorrow.  Must be in YYYYMMDD  format.
	var $end;			//  When to stop running transactions. Default is to run forever.  If both end and numleft are specified, transaction will stop when the earliest condition is met.
	var $billamount;	//  Optional recurring billing amount.  If not specified, the amount field will be used for future recurring billing payments
	var $billtax;
	var $billsourcekey;
	
	// Billing Fields
	var $billfname;
	var $billlname;
	var $billcompany;
	var $billstreet;
	var $billstreet2;
	var $billcity;
	var $billstate;
	var $billzip;
	var $billcountry;
	var $billphone;
	var $email;
	var $fax;
	var $website;

	// Shipping Fields
	var $delivery;		// type of delivery method ('ship','pickup','download')
	var $shipfname;
	var $shiplname;
	var $shipcompany;
	var $shipstreet;
	var $shipstreet2;
	var $shipcity;
	var $shipstate;
	var $shipzip;
	var $shipcountry;
	var $shipphone;
	
	// Custom Fields
	var $custom1;
	var $custom2;
	var $custom3;
	var $custom4;
	var $custom5;
	var $custom6;
	var $custom7;
	var $custom8;
	var $custom9;
	var $custom10;
	var $custom11;
	var $custom12;
	var $custom13;
	var $custom14;
	var $custom15;
	var $custom16;
	var $custom17;
	var $custom18;
	var $custom19;
	var $custom20;
	

	// Line items  (see addLine)
	var $lineitems;
	
	
	var $comments; // Additional transaction details or comments (free form text field supports up to 65,000 chars)
	
	var $software; // Allows developers to identify their application to the gateway (for troubleshooting purposes)

	
	// response fields
	var $rawresult;		// raw result from gateway
	var $result;		// full result:  Approved, Declined, Error
	var $resultcode; 	// abreviated result code: A D E
	var $authcode;		// authorization code
	var $refnum;		// reference number
	var $batch;		// batch number
	var $avs_result;		// avs result
	var $avs_result_code;		// avs result
	var $avs;  					// obsolete avs result
	var $cvv2_result;		// cvv2 result
	var $cvv2_result_code;		// cvv2 result
	var $vpas_result_code;      // vpas result
	var $isduplicate;      // system identified transaction as a duplicate
	var $convertedamount;  // transaction amount after server has converted it to merchants currency
	var $convertedamountcurrency;  // merchants currency
	var $conversionrate;  // the conversion rate that was used
	var $custnum;  //  gateway assigned customer ref number for recurring billing
	var $authamount; // amount that was authorized
	var $balance;  //remaining balance
	var $cardlevelresult;
	var $procrefnum;
	
	// Cardinal Response Fields
	var $acsurl;	// card auth url
	var $pareq;		// card auth request
	var $cctransid; // cardinal transid

	
	// Errors Response Feilds
	var $error; 		// error message if result is an error
	var $errorcode; 	// numerical error code
	var $blank;			// blank response
	var $transporterror; 	// transport error
	
	
	// Constructor
	function umTransaction()
	{
		// Set default values.
		$this->command="sale";
		$this->result="Error";
		$this->resultcode="E";
		$this->error="Transaction not processed yet.";
		$this->timeout=45;
		$this->cardpresent=false;
		$this->lineitems = array();
		if(isset($_SERVER['REMOTE_ADDR'])) $this->ip=$_SERVER['REMOTE_ADDR'];
		$this->software="USAePay PHP API v" . USAEPAY_VERSION;

	}
	
	/**
	 * Add a line item to the transaction
	 *
	 * @param string $sku
	 * @param string $name
	 * @param string $description
	 * @param double $cost
	 * @param string $taxable
	 * @param int $qty
	 */
	function addLine($sku, $name, $description, $cost, $qty, $taxable)
	{		
		$this->lineitems[] = array(
				'sku' => $sku,
				'name' => $name,
				'description' => $description,
				'cost' => $cost,
				'taxable' => $taxable,
				'qty' => $qty
			);
	}

	/**
	 * Remove all line items
	 * 
	 */
	function clearLines()
	{
		$this->lineitems=array();		
	}
	
	/**
	 * Clear all transaction parameters except for the key and pin. Should be run between transactions if not 
	 * reinsantiating the umTransaction class
	 *
	 */
	function clearData()
	{
		$map =$this->getFieldMap();
		foreach($map as $apifield => $classfield)
		{
			if($classfield=='software' || $classfield=='key' || $classfield=='pin') continue;
			unset($this->$classfield);
		}
		
		// Set default values.
		$this->command="sale";
		$this->result="Error";
		$this->resultcode="E";
		$this->error="Transaction not processed yet.";
		$this->cardpresent=false;
		$this->lineitems = array();
		if(isset($_SERVER['REMOTE_ADDR'])) $this->ip=$_SERVER['REMOTE_ADDR'];
		
	}
	
	/**
	 * Verify that all required data has been set
	 *
	 * @return string
	 */
	function CheckData()
	{
		if(!$this->key) return "Source Key is required";
		if(in_array(strtolower($this->command), array("cc:capture", "cc:refund", "refund", "check:refund","capture", "creditvoid", 'quicksale', 'quickcredit')))
		{
			if(!$this->refnum) return "Reference Number is required";		
		}else if(in_array(strtolower($this->command), array("svp")))
		{
			if(!$this->svpbank) return "Bank ID is required";		
			if(!$this->svpreturnurl) return "Return URL is required";		
			if(!$this->svpcancelurl) return "Cancel URL is required";		
		}  else {
			if(in_array(strtolower($this->command), array("check:sale","check:credit", "check", "checkcredit","reverseach") )) {
					if(!$this->account) return "Account Number is required";
					if(!$this->routing) return "Routing Number is required";
			} else {
				if(!$this->magstripe) {
					if(!$this->card) return "Credit Card Number is required ({$this->command})";
					if(!$this->exp) return "Expiration Date is required";
				}
			}
			$this->amount=preg_replace("/[^[:digit:].]/","",$this->amount);
			if(!$this->amount) return "Amount is required";
			if(!$this->invoice && !$this->orderid) return "Invoice number or Order ID is required";
			if(!$this->magstripe) {
				//if(!$this->cardholder) return "Cardholder Name is required";
				//if(!$this->street) return "Street Address is required";
				//if(!$this->zip) return "Zipcode is required";
			}
		}
		return 0;		
	}
	
	/**
	 * Send transaction to the USAePay Gateway and parse response
	 *
	 * @return boolean
	 */
	function Process()
	{
		// check that we have the needed data
		$tmp=$this->CheckData();
		if($tmp)
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error=$tmp;
			$this->errorcode=10129;
			return false;
		}
		
		// populate the data
		$map = $this->getFieldMap();
		$data = array();
		foreach($map as $apifield =>$classfield)
		{
			switch($classfield)
			{
				case 'nontaxable':
					if($this->nontaxable) $data['UMnontaxable'] = 'Y';
					break;
				case 'checkimage_front':
				case 'checkimage_back':
					$data[$apifield] = base64_encode($this->$classfield);
					break;
				case 'billsourcekey':
					if($this->billsourcekey) $data['UMbillsourcekey'] = 'yes';
					break;
				case 'cardpresent':
					if($this->cardpresent) $data['UMcardpresent'] = '1'; 
					break;
				case 'allowpartialauth':
					if($this->allowpartialauth===true) $data['UMallowPartialAuth'] = 'Yes';
					break;
				default: 
					$data[$apifield] = $this->$classfield;
			}
		}

		if(isset($data['UMcheckimagefront']) || isset($data['UMcheckimageback'])) $data['UMcheckimageencoding'] = 'base64';
		
		// tack on custom fields
		for($i=1; $i<=20; $i++)
		{
			if($this->{"custom$i"}) $data["UMcustom$i"] = $this->{"custom$i"};
		}
		
		// tack on line level detail
		$c=1;
		if(!is_array($this->lineitems)) $this->lineitems=array();
		foreach($this->lineitems as $lineitem)
		{
			$data["UMline{$c}sku"] = $lineitem['sku'];
			$data["UMline{$c}name"] = $lineitem['name'];
			$data["UMline{$c}description"] = $lineitem['description'];
			$data["UMline{$c}cost"] = $lineitem['cost'];
			$data["UMline{$c}taxable"] = $lineitem['taxable'];
			$data["UMline{$c}qty"] = $lineitem['qty'];
			$c++;	
		}
				
		// Create hash if pin has been set.
		if(trim($this->pin))
		{
			// generate random seed value
			$seed = microtime(true) . rand();
			
			// assemble prehash data
			$prehash = $this->command . ":" . trim($this->pin) . ":" . $this->amount . ":" . $this->invoice . ":" . $seed;
			
			// if sha1 is available,  create sha1 hash,  else use md5
			if(function_exists('sha1')) $hash = 's/' . $seed . '/' . sha1($prehash) . '/n';
			else $hash = 'm/' . $seed . '/' . md5($prehash) . '/n';
			
			// populate hash value
			$data['UMhash'] = $hash;			
		}				
		
		// Figure out URL
		$url = ($this->gatewayurl?$this->gatewayurl:"https://www.usaepay.com/gate");
		if($this->usesandbox) $url = "https://sandbox.usaepay.com/gate";
			
		// Post data to Gateway
		$result=$this->httpPost($url, $data);

		if($result===false) return false;
				
		// result is in urlencoded format, parse into an array
		parse_str($result,$tmp);

		// check to make sure we received the correct fields
		if(!isset($tmp["UMversion"]) || !isset($tmp["UMstatus"]))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Error parsing data from card processing gateway.";
			$this->errorcode=10132;
			return false;			
		}
		// Store results		
		$this->result=(isset($tmp["UMstatus"])?$tmp["UMstatus"]:"Error");	
		$this->resultcode=(isset($tmp["UMresult"])?$tmp["UMresult"]:"E");	
		$this->authcode=(isset($tmp["UMauthCode"])?$tmp["UMauthCode"]:"");
		$this->refnum=(isset($tmp["UMrefNum"])?$tmp["UMrefNum"]:"");
		$this->batch=(isset($tmp["UMbatch"])?$tmp["UMbatch"]:"");
		$this->avs_result=(isset($tmp["UMavsResult"])?$tmp["UMavsResult"]:"");
		$this->avs_result_code=(isset($tmp["UMavsResultCode"])?$tmp["UMavsResultCode"]:"");
		$this->cvv2_result=(isset($tmp["UMcvv2Result"])?$tmp["UMcvv2Result"]:"");
		$this->cvv2_result_code=(isset($tmp["UMcvv2ResultCode"])?$tmp["UMcvv2ResultCode"]:"");
		$this->vpas_result_code=(isset($tmp["UMvpasResultCode"])?$tmp["UMvpasResultCode"]:"");
		$this->convertedamount=(isset($tmp["UMconvertedAmount"])?$tmp["UMconvertedAmount"]:"");
		$this->convertedamountcurrency=(isset($tmp["UMconvertedAmountCurrency"])?$tmp["UMconvertedAmountCurrency"]:"");
		$this->conversionrate=(isset($tmp["UMconversionRate"])?$tmp["UMconversionRate"]:"");
		$this->error=(isset($tmp["UMerror"])?$tmp["UMerror"]:"");
		$this->errorcode=(isset($tmp["UMerrorcode"])?$tmp["UMerrorcode"]:"10132");
		$this->custnum=(isset($tmp["UMcustnum"])?$tmp["UMcustnum"]:"");
		$this->authamount=(isset($tmp["UMauthAmount"])?$tmp["UMauthAmount"]:"");
		$this->balance=(isset($tmp["UMremainingBalance"])?$tmp["UMremainingBalance"]:"");
		$this->cardlevelresult=(isset($tmp["UMcardLevelResult"])?$tmp["UMcardLevelResult"]:"");
		$this->procrefnum=(isset($tmp["UMprocRefNum"])?$tmp["UMprocRefNum"]:"");

		// Obsolete variable (for backward compatibility) At some point they will no longer be set.
		$this->avs=(isset($tmp["UMavsResult"])?$tmp["UMavsResult"]:"");
		$this->cvv2=(isset($tmp["UMcvv2Result"])?$tmp["UMcvv2Result"]:"");

		
		if(isset($tmp["UMcctransid"])) $this->cctransid=$tmp["UMcctransid"];
		if(isset($tmp["UMacsurl"])) $this->acsurl=$tmp["UMacsurl"];
		if(isset($tmp["UMpayload"])) $this->pareq=$tmp["UMpayload"];
		
		if($this->resultcode == "A") return true;
		return false;
		
	}
	
	function ProcessQuickSale()
	{
		$this->command='quicksale';
		
		// check that we have the needed data
		$tmp=$this->CheckData();
		if($tmp)
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error=$tmp;
			$this->errorcode=10129;
			return false;
		}
		
				// Create hash if pin has been set.
		if(!trim($this->pin))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error='Source key must have pin assigned to run transaction';
			$this->errorcode=999;
			return false;
		}
		
		// generate random seed value
		$seed = microtime(true) . rand();
		
		// assemble prehash data
		$prehash = $this->key . $seed . trim($this->pin);
		
		// hash the data
		$hash = sha1($prehash);
		
		
		$data = '<?xml version="1.0" encoding="UTF-8"?>' .
				'<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="urn:usaepay" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">' . 
				'<SOAP-ENV:Body>' .
				'<ns1:runQuickSale>' .
				'<Token xsi:type="ns1:ueSecurityToken">' .
				'<ClientIP xsi:type="xsd:string">' . $this->ip . '</ClientIP>' .
				'<PinHash xsi:type="ns1:ueHash">' .
				'<HashValue xsi:type="xsd:string">' . $hash . '</HashValue>' .
				'<Seed xsi:type="xsd:string">' . $seed . '</Seed>' . 
				'<Type xsi:type="xsd:string">sha1</Type>' .
				'</PinHash>' .
				'<SourceKey xsi:type="xsd:string">' . $this->key . '</SourceKey>' .
				'</Token>' .
				'<RefNum xsi:type="xsd:integer">' . preg_replace('/[^0-9]/','',$this->refnum) . '</RefNum>' .
				'<Details xsi:type="ns1:TransactionDetail">' . 
				'<Amount xsi:type="xsd:double">' . $this->xmlentities($this->amount) . '</Amount>' . 
				'<Description xsi:type="xsd:string">' . $this->xmlentities($this->description) . '</Description>'.
				'<Discount xsi:type="xsd:double">' . $this->xmlentities($this->discount) . '</Discount>' .
				'<Invoice xsi:type="xsd:string">' . $this->xmlentities($this->invoice) . '</Invoice>' .
				'<NonTax xsi:type="xsd:boolean">' . ($this->nontaxable?'true':'false') . '</NonTax>' .
				'<OrderID xsi:type="xsd:string">' . $this->xmlentities($this->orderid) . '</OrderID>' .
				'<PONum xsi:type="xsd:string">' . $this->xmlentities($this->ponum) . '</PONum>' .
				'<Shipping xsi:type="xsd:double">' . $this->xmlentities($this->shipping) . '</Shipping>' .
				'<Subtotal xsi:type="xsd:double">' . $this->xmlentities($this->subtotal) . '</Subtotal>' .
				'<Tax xsi:type="xsd:double">' . $this->xmlentities($this->tax) . '</Tax>' .
				'<Tip xsi:type="xsd:double">' . $this->xmlentities($this->tip) . '</Tip>' .
				'</Details>' .
				'<AuthOnly xsi:type="xsd:boolean">false</AuthOnly>' .
				'</ns1:runQuickSale>' .
				'</SOAP-ENV:Body>' .
				'</SOAP-ENV:Envelope>';

		// Figure out URL
		$url = ($this->gatewayurl?$this->gatewayurl:"https://www.usaepay.com/soap/gate/15E7FB61");
		if($this->usesandbox) $url = "https://sandbox.usaepay.com/soap/gate/15E7FB61";
		
		// Post data to Gateway
		$result=$this->httpPost($url, array('xml'=>$data));
		
		
		if($result===false) return false;
		
		if(preg_match('~<AuthCode[^>]*>(.*)</AuthCode>~', $result, $m)) $this->authcode=$m[1];
		if(preg_match('~<AvsResult[^>]*>(.*)</AvsResult>~', $result, $m)) $this->avs_result=$m[1];
		if(preg_match('~<AvsResultCode[^>]*>(.*)</AvsResultCode>~', $result, $m)) $this->avs_result_code=$m[1];
		if(preg_match('~<BatchRefNum[^>]*>(.*)</BatchRefNum>~', $result, $m)) $this->batch=$m[1];
		if(preg_match('~<CardCodeResult[^>]*>(.*)</CardCodeResult>~', $result, $m)) $this->cvv2_result=$m[1];
		if(preg_match('~<CardCodeResultCode[^>]*>(.*)</CardCodeResultCode>~', $result, $m)) $this->cvv2_result_code=$m[1];
		//if(preg_match('~<CardLevelResult[^>]*>(.*)</CardLevelResult>~', $result, $m)) $this->cardlevel_result=$m[1];
		//if(preg_match('~<CardLevelResultCode[^>]*>(.*)</CardLevelResultCode>~', $result, $m)) $this->cardlevel_result_code=$m[1];
		if(preg_match('~<ConversionRate[^>]*>(.*)</ConversionRate>~', $result, $m)) $this->conversionrate=$m[1];
		if(preg_match('~<ConvertedAmount[^>]*>(.*)</ConvertedAmount>~', $result, $m)) $this->convertedamount=$m[1];
		if(preg_match('~<ConvertedAmountCurrency[^>]*>(.*)</ConvertedAmountCurrency>~', $result, $m)) $this->convertedamountcurrency=$m[1];
		if(preg_match('~<Error[^>]*>(.*)</Error>~', $result, $m)) $this->error=$m[1];
		if(preg_match('~<ErrorCode[^>]*>(.*)</ErrorCode>~', $result, $m)) $this->errorcode=$m[1];
		//if(preg_match('~<isDuplicate[^>]*>(.*)</isDuplicate>~', $result, $m)) $this->isduplicate=$m[1];
		if(preg_match('~<RefNum[^>]*>(.*)</RefNum>~', $result, $m)) $this->refnum=$m[1];
		if(preg_match('~<Result[^>]*>(.*)</Result>~', $result, $m)) $this->result=$m[1];
		if(preg_match('~<ResultCode[^>]*>(.*)</ResultCode>~', $result, $m)) $this->resultcode=$m[1];
		if(preg_match('~<VpasResultCode[^>]*>(.*)</VpasResultCode>~', $result, $m)) $this->vpas_result_code=$m[1];
		
		
		// Store results
		if($this->resultcode == "A") return true;
		return false;
	}
	
	
	
	
	function ProcessQuickCredit()
	{
		$this->command='quickcredit';
		
		// check that we have the needed data
		$tmp=$this->CheckData();
		if($tmp)
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error=$tmp;
			$this->errorcode=10129;
			return false;
		}
		
				// Create hash if pin has been set.
		if(!trim($this->pin))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error='Source key must have pin assigned to run transaction';
			$this->errorcode=999;
			return false;
		}
		
		// generate random seed value
		$seed = microtime(true) . rand();
		
		// assemble prehash data
		$prehash = $this->key . $seed . trim($this->pin);
		
		// hash the data
		$hash = sha1($prehash);
		
		$data = '<?xml version="1.0" encoding="UTF-8"?>' .
				'<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="urn:usaepay" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">' . 
				'<SOAP-ENV:Body>' .
				'<ns1:runQuickCredit>' .
				'<Token xsi:type="ns1:ueSecurityToken">' .
				'<ClientIP xsi:type="xsd:string">' . $this->ip . '</ClientIP>' .
				'<PinHash xsi:type="ns1:ueHash">' .
				'<HashValue xsi:type="xsd:string">' . $hash . '</HashValue>' .
				'<Seed xsi:type="xsd:string">' . $seed . '</Seed>' . 
				'<Type xsi:type="xsd:string">sha1</Type>' .
				'</PinHash>' .
				'<SourceKey xsi:type="xsd:string">' . $this->key . '</SourceKey>' .
				'</Token>' .
				'<RefNum xsi:type="xsd:integer">' . preg_replace('/[^0-9]/','',$this->refnum) . '</RefNum>' .
				'<Details xsi:type="ns1:TransactionDetail">' . 
				'<Amount xsi:type="xsd:double">' . $this->xmlentities($this->amount) . '</Amount>' . 
				'<Description xsi:type="xsd:string">' . $this->xmlentities($this->description) . '</Description>'.
				'<Discount xsi:type="xsd:double">' . $this->xmlentities($this->discount) . '</Discount>' .
				'<Invoice xsi:type="xsd:string">' . $this->xmlentities($this->invoice) . '</Invoice>' .
				'<NonTax xsi:type="xsd:boolean">' . ($this->nontaxable?'true':'false') . '</NonTax>' .
				'<OrderID xsi:type="xsd:string">' . $this->xmlentities($this->orderid) . '</OrderID>' .
				'<PONum xsi:type="xsd:string">' . $this->xmlentities($this->ponum) . '</PONum>' .
				'<Shipping xsi:type="xsd:double">' . $this->xmlentities($this->shipping) . '</Shipping>' .
				'<Subtotal xsi:type="xsd:double">' . $this->xmlentities($this->subtotal) . '</Subtotal>' .
				'<Tax xsi:type="xsd:double">' . $this->xmlentities($this->tax) . '</Tax>' .
				'<Tip xsi:type="xsd:double">' . $this->xmlentities($this->tip) . '</Tip>' .
				'</Details>' .
				'<AuthOnly xsi:type="xsd:boolean">false</AuthOnly>' .
				'</ns1:runQuickCredit>' .
				'</SOAP-ENV:Body>' .
				'</SOAP-ENV:Envelope>';
		
		// Figure out URL
		$url = ($this->gatewayurl?$this->gatewayurl:"https://www.usaepay.com/soap/gate/15E7FB61");
		if($this->usesandbox) $url = "https://sandbox.usaepay.com/soap/gate/15E7FB61";
		
		// Post data to Gateway
		$result=$this->httpPost($url, array('xml'=>$data));
		if($result===false) return false;
		
		if(preg_match('~<AuthCode[^>]*>(.*)</AuthCode>~', $result, $m)) $this->authcode=$m[1];
		if(preg_match('~<AvsResult[^>]*>(.*)</AvsResult>~', $result, $m)) $this->avs_result=$m[1];
		if(preg_match('~<AvsResultCode[^>]*>(.*)</AvsResultCode>~', $result, $m)) $this->avs_result_code=$m[1];
		if(preg_match('~<BatchRefNum[^>]*>(.*)</BatchRefNum>~', $result, $m)) $this->batch=$m[1];
		if(preg_match('~<CardCodeResult[^>]*>(.*)</CardCodeResult>~', $result, $m)) $this->cvv2_result=$m[1];
		if(preg_match('~<CardCodeResultCode[^>]*>(.*)</CardCodeResultCode>~', $result, $m)) $this->cvv2_result_code=$m[1];
		//if(preg_match('~<CardLevelResult[^>]*>(.*)</CardLevelResult>~', $result, $m)) $this->cardlevel_result=$m[1];
		//if(preg_match('~<CardLevelResultCode[^>]*>(.*)</CardLevelResultCode>~', $result, $m)) $this->cardlevel_result_code=$m[1];
		if(preg_match('~<ConversionRate[^>]*>(.*)</ConversionRate>~', $result, $m)) $this->conversionrate=$m[1];
		if(preg_match('~<ConvertedAmount[^>]*>(.*)</ConvertedAmount>~', $result, $m)) $this->convertedamount=$m[1];
		if(preg_match('~<ConvertedAmountCurrency[^>]*>(.*)</ConvertedAmountCurrency>~', $result, $m)) $this->convertedamountcurrency=$m[1];
		if(preg_match('~<Error[^>]*>(.*)</Error>~', $result, $m)) $this->error=$m[1];
		if(preg_match('~<ErrorCode[^>]*>(.*)</ErrorCode>~', $result, $m)) $this->errorcode=$m[1];
		//if(preg_match('~<isDuplicate[^>]*>(.*)</isDuplicate>~', $result, $m)) $this->isduplicate=$m[1];
		if(preg_match('~<RefNum[^>]*>(.*)</RefNum>~', $result, $m)) $this->refnum=$m[1];
		if(preg_match('~<Result[^>]*>(.*)</Result>~', $result, $m)) $this->result=$m[1];
		if(preg_match('~<ResultCode[^>]*>(.*)</ResultCode>~', $result, $m)) $this->resultcode=$m[1];
		if(preg_match('~<VpasResultCode[^>]*>(.*)</VpasResultCode>~', $result, $m)) $this->vpas_result_code=$m[1];
		
		
		// Store results		
		if($this->resultcode == "A") return true;
		return false;
	}
	
	
	/**
	 * Verify Proper Installation of USAePay class and required PHP Support
	 *
	 */
	function Test()
	{
		$curl_version=false;
		?>
		<table border=1>
		<tr><th><b>Test</b></th><th><b>Result</b></th></tr>
		<tr><td valign="Top">Checking PHP Version</td>
		<td valign="top"><?php
			if(version_compare(phpversion(),"4.3.0")) {
				?><font color="green">Ok</font><br>
				PHP version <?php echo phpversion()?> on <?php echo PHP_OS?> detected. 
				<?php				
			} else {
				?><font color="red">Warning</font><br>
				PHP version <?php echo phpversion()?> detected. It is recommended that you
				upgrade to the most recent release of PHP.
				<?php				
			}
		?></td></tr>
		<tr><td valign="Top">Checking CURL Extension</td>
		<td valign="top"><?php
			if(function_exists("curl_version")) {	
				$tmp=curl_version();
				// PHP 5 returns an array,  version 4 returns a string
				if(is_array($tmp)) { 
					$curl_version=$tmp["version"];
					$curl_ssl_version=$tmp["ssl_version"];					
				} else {
					$tmp=explode(" ", $tmp);
					foreach($tmp as $piece)
					{
						list($lib,$version)=explode("/",$piece);
						if(stristr($lib,"curl")) $curl_version=$version;
						elseif(stristr($lib,"ssl")) $curl_ssl_version=$version;
					}
					
				}
				?><font color="green">Ok</font><br>
				Curl Extension (<?php echo $curl_version?>) detected.
				<?php
			} else {
				?><font color="red">Error</font><br>
				Your PHP installation does not include the Curl Extension. You must either enable the curl
				extension in your php.ini file by removing the semi-colon from the line ";extension=php_curl.dll" or recompile
				php with the configuration flag: "--with-curl"
				<?php
			}
		?>
		</td></tr>
		<tr><td valign="Top">Checking CURL SSL Support</td>
		<td valign="top"><?php
			if($curl_ssl_version) {	
				?><font color="green">Ok</font><br>
				SSL Version (<?php echo $curl_ssl_version?>) detected.
				<?php
			} else {
				?><font color="red">Error</font><br>
				It appears that your curl installation does not include support for the HTTPS (ssl) protocal
				Proper SSL installation is required to communicate with the USAePay gateway securely.  Please recompile curl
				with SSL support.
				<?php
			}
		?>					
		</td></tr>
		<tr><td valign="Top">Checking Communication with the Gateway</td>
		<td valign="top"><?php
			$ssl_failed=true;
			if(!$curl_version) {
				?><font color="red">Error</font><br>
				No curl support
				<?php				
			} else {
				$ch = curl_init(($this->gatewayurl?$this->gatewayurl:"https://www.usaepay.com/secure/gate.php") . "?VersionCheck=1&UMsoftware=" . rawurlencode($this->software));
				if(!$ch) {
					?><font color="red">Error</font><br>
					Curl failed to connect to server:  (NULL $ch returned)
					<?php
				} else {
					
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					if($this->cabundle) curl_setopt($ch, CURLOPT_CAINFO, $this->cabundle);				

					$result=curl_exec($ch);
					parse_str($result, $return);
					if($return["UMversion"]) {
						$ssl_failed=false;
						?><font color="green">Ok</font><br>
						Successfully connected to the gateway.  Detected version (<?php echo $return["UMversion"]?>) of the USAePay gateway API.
						<?php						
					} else {
						$ch = curl_init(($this->gatewayurl?$this->gatewayurl:"https://www.usaepay.com/secure/gate.php") . "?VersionCheck=1&UMsoftware=" . rawurlencode($this->software));
						if($this->cabundle) curl_setopt($ch, CURLOPT_CAINFO, $this->cabundle);				
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);				
						$result=curl_exec($ch);
						parse_str($result, $return);
						if($return["UMversion"]) {
							?><font color="red">Warning</font><br>
							
							Successfully connected to the gateway but could not verify SSL certificate.  This usually indicates that you
							do not have curl set up with the proper root CA certificate.  It is recommended that you install an up to date 
							root ca bundle.  As a <b>temporary</b> work around you may disable the ssl cert check by adding the following to your script:<br><br><tt>
							$tran->ignoresslcerterrors=true;<br>
							<br></tt>
							<?php						
							$ch = curl_init("https://www.verisign.com");
							if($this->cabundle) curl_setopt($ch, CURLOPT_CAINFO, $this->cabundle);				
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							$result=curl_exec($ch);
							if(strlen($result)) {
								?>
								SSL certificate for VeriSign was validated sucessfully. This would indicate that you have a root CA bundle installed but that it
								is outdated. See below.
								<?php
							} else {
								?>
								Unable to verify SSL certificate for VeriSign.  This would indicate that you do not have a root CA bundle installed. See below.
								<?php
							}
												
							
							
						} else {
							$ch = curl_init("https://216.133.244.70/secure/gate.php?VersionCheck=1&UMsoftware=" . rawurlencode($this->software));
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
							curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
							$result=curl_exec($ch);
							parse_str($result, $return);
							if($return["UMversion"]) {
								?><font color="red">Warning</font><br>
								
								Successfully connected to the gateway using a static IP but could not connect using hostname. This would indicate
								that you do not have DNS setup correctly.  Please correct your server configuration to include a valid
								DNS server. As a temporary work around you may add the following to your script:<br><br><tt>
								$tran->ignoresslcerterrors=true;<br>
								$tran->gatewayurl="https://216.133.244.70/gate.php";<br>
								<br></tt>
								<?php
							} else {
								?><font color="red">Failed</font><br>
								Unable to establish connection to the USAePay Gateway.  It is possible that you are firewalling
								outbound traffic from your server.  To correct this problem,  you must add an allow entry in
								your firewall for ip <?php echo gethostbyname("www.usaepay.com")?> on port 443;
								<?php								
								
							}
							
						}
						
					}
				}				
			}
		?>					
		</td></tr>
		<?php if($ssl_failed) {?>
		
		<tr><td valign="Top">Looking for root CA Bundle</td>
		<td valign="top"><?php
			if(strstr(PHP_OS,"WIN")!==false) {
				foreach(array('c:\windows\ca-bundle.crt','c:\windows\curl-ca-bundle.crt') as $certpath)
				{
					if(is_file($certpath)) break;
					else unset($certpath);
				}
				
				if(!$certpath) $certpath="c:\windows\curl-ca-bundle.crt";
			} else {
				
				foreach(array("/usr/share/curl/curl-ca-bundle.crt","/usr/local/share/curl/curl-ca-bundle.crt","/etc/curl/curl-ca-bundle.crt") as $certpath)
				{
					if(is_file($certpath)) break;
					else unset($certpath);
				}
				
				if(!$certpath) $certpath="/usr/share/curl/curl-ca-bundle.crt";
			}
		
		
			if(is_readable($certpath)) {	
				?><font color="green">Ok</font><br>
				A root CA bundle was found in "<?php echo $certpath?>".  Since the above test failed,  its possible that curl is not
				correctly configured to use this bundle.  You can correct the problem by add the following code to your script:<br><br><tt>
				$tran->cabundle='<?php echo $certpath?>';
				</tt>
				<br><br>
				It is also possible that your root CA bundle is out dated. Your root CA bundle was last updated <?php echo date("m/d/y", filemtime($certpath))?>.
				You can download a new file from:
				<a href="https://www.usaepay.com/topics/curl-ca-bundle.crt">https://www.usaepay.com/topics/curl-ca-bundle.crt</a>				
				</tt>  
				<?php
			} else {
				?><font color="red">Error</font><br>
				Unable to locate your root CA bundle file. You can correct this by downloading a bundle file from: 
				<a href="https://www.usaepay.com/topics/curl-ca-bundle.crt">https://www.usaepay.com/topics/curl-ca-bundle.crt</a>
				Once downloaded save this file to: <?php echo $certpath?> You may also need to add the following code:<br><br><tt>
				$tran->cabundle='<?php echo $certpath?>';
				</tt> 
				<br><br>
				<?php
			}
		?>					
		</td></tr>
		<?php } ?>

		</table>
		<?php
	}
	
	function getFieldMap()
	{
		return array("UMkey" => 'key', 
			"UMcommand" => 'command',
			"UMauthCode" => 'origauthcode',
			"UMcard" => 'card',
			"UMexpir" => 'exp',
			"UMbillamount" => 'billamount', 
			"UMamount" => 'amount', 
			"UMinvoice" => 'invoice', 
			"UMorderid" => 'orderid', 
			"UMponum" => 'ponum', 
			"UMtax" => 'tax', 
			"UMnontaxable" => 'nontaxable',
			"UMtip" => 'tip', 
			"UMshipping" => 'shipping', 
			"UMdiscount" => 'discount', 
			"UMsubtotal" => 'subtotal', 
			"UMcurrency" => 'currency', 
			"UMname" => 'cardholder',
			"UMstreet" => 'street', 
			"UMzip" => 'zip',
			"UMdescription" => 'description',
			"UMcomments" => 'comments',
			"UMcvv2" => 'cvv2',
			"UMip" => 'ip',
			"UMtestmode" => 'testmode',
			"UMcustemail" => 'custemail',
			"UMcustreceipt" => 'custreceipt',
			"UMrouting" => 'routing',
			"UMaccount" => 'account',
			"UMssn" => 'ssn',
			"UMdlstate" => 'dlstate',
			"UMdlnum" => 'dlnum',
			"UMchecknum" => 'checknum',
			"UMaccounttype" => 'accounttype',
			"UMcheckformat" => 'checkformat',
			"UMcheckimagefront" => 'checkimage_front',
			"UMcheckimageback" => 'checkimage_back',
			"UMaddcustomer" => 'addcustomer',
			"UMrecurring" => 'recurring',
			"UMbillamount" => 'billamount',
			"UMbilltax" => 'billtax',
			"UMschedule" => 'schedule',
			"UMnumleft" => 'numleft',
			"UMstart" => 'start',
			"UMexpire" => 'end',
			"UMbillsourcekey" => 'billsourcekey',
			"UMbillfname" => 'billfname',
			"UMbilllname" => 'billlname',
			"UMbillcompany" => 'billcompany',
			"UMbillstreet" => 'billstreet',
			"UMbillstreet2" => 'billstreet2',
			"UMbillcity" => 'billcity',
			"UMbillstate" => 'billstate',
			"UMbillzip" => 'billzip',
			"UMbillcountry" => 'billcountry',
			"UMbillphone" => 'billphone',
			"UMemail" => 'email',
			"UMfax" => 'fax',
			"UMwebsite" => 'website',
			"UMshipfname" => 'shipfname',
			"UMshiplname" => 'shiplname',
			"UMshipcompany" => 'shipcompany',
			"UMshipstreet" => 'shipstreet',
			"UMshipstreet2" => 'shipstreet2',
			"UMshipcity" => 'shipcity',
			"UMshipstate" => 'shipstate',
			"UMshipzip" => 'shipzip',
			"UMshipcountry" => 'shipcountry',
			"UMshipphone" => 'shipphone', 
			"UMcardauth" => 'cardauth', 
			"UMpares" => 'pares', 
			"UMxid" => 'xid', 
			"UMcavv" => 'cavv', 
			"UMeci" => 'eci', 
			"UMcustid" => 'custid', 
			"UMcardpresent" => 'cardpresent', 
			"UMmagstripe" => 'magstripe', 
			"UMdukpt" => 'dukpt', 
			"UMtermtype" => 'termtype', 
			"UMmagsupport" => 'magsupport', 
			"UMcontactless" => 'contactless', 
			"UMsignature" => 'signature', 
			"UMsoftware" => 'software', 
			"UMignoreDuplicate" => 'ignoreduplicate', 
			"UMrefNum" => 'refnum',
			'UMauxonus' => 'auxonus',
			'UMepcCode' => 'epccode',
			'UMcustreceiptname' => 'custreceiptname',
			'UMallowPartialAuth' => 'allowpartialauth',
			);
	}
	function buildQuery($data)
	{
		if(function_exists('http_build_query') && ini_get('arg_separator.output')=='&') return http_build_query($data);
		
		$tmp=array();
		foreach($data as $key=>$val) $tmp[] = rawurlencode($key) . '=' . rawurlencode($val);
		
		return implode('&', $tmp);
		
	}
	
	function httpPost($url, $data)
	{				
		// if transport was not specified,  auto select transport
		if(!$this->transport)
		{
			if(function_exists("curl_version")) {
				$this->transport='curl';
			} else if(function_exists('stream_get_wrappers'))  {
				if(in_array('https',stream_get_wrappers())){
					$this->transport='stream';
				}
			}
		}
		
		
		// Use selected transport to post request to the gateway
		switch($this->transport)
		{
			case 'curl': return $this->httpPostCurl($url, $data);
			case 'stream': return $this->httpPostPHP($url, $data);
		}
						
		// No HTTPs libraries found,  return error		
		$this->result="Error";
		$this->resultcode="E";
		$this->error="Libary Error: SSL HTTPS support not found";
		$this->errorcode=10130;
		return false;					
	}
	
	function httpPostCurl($url, $data)
	{
		
		//init the connection
		$ch = curl_init($url);
		if(!is_resource($ch))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Libary Error: Unable to initialize CURL ($ch)";
			$this->errorcode=10131;
			return false;			
		}
		
		// set some options for the connection
		curl_setopt($ch,CURLOPT_HEADER, 1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_TIMEOUT, ($this->timeout>0?$this->timeout:45));
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		
		// Bypass ssl errors - A VERY BAD IDEA
		if($this->ignoresslcerterrors)
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		}
		
		// apply custom ca bundle location
		if($this->cabundle)
		{	
			curl_setopt($ch, CURLOPT_CAINFO, $this->cabundle);				
		}
		
		// set proxy
		if($this->proxyurl)
		{
			curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt ($ch, CURLOPT_PROXY, $this->proxyurl);
		}		
		
		$soapcall=false;
		if(is_array($data))
		{
			if(array_key_exists('xml',$data)) $soapcall=true;
		}
		
		if($soapcall)
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Content-type: text/xml;charset=UTF-8",
				"SoapAction: urn:ueSoapServerAction"
			));
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data['xml']);
			
		} else {
			// rawurlencode data 
			$data = $this->buildQuery($data);		
			
			// attach the data
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		}
		
		// run the transfer
		$result=curl_exec ($ch);
		
		//get the result and parse it for the response line.
		if(!strlen($result))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Error reading from card processing gateway.";
			$this->errorcode=10132;
			$this->blank=1;
			$this->transporterror=$this->curlerror=curl_error($ch);
			curl_close ($ch);
			return false;			
		}
		curl_close ($ch);
		$this->rawresult=$result;
		
		if($soapcall) {
			return $result;	
		}
		
		if(!$result) {
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Blank response from card processing gateway.";
			$this->errorcode=10132;
			return false;			
		}
		
		// result will be on the last line of the return
		$tmp=explode("\n",$result);
		$result=$tmp[count($tmp)-1];		
		
		return $result;
	}
	
	function httpPostPHP($url, $data)
	{

		
		// rawurlencode data 
		$data = $this->buildQuery($data);		
		
		// set stream http options		
		$options = array(
			'http'=> array(
				'method'=>'POST',
	            'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
	                . "Content-Length: " . strlen($data) . "\r\n",
	            'content' => $data,
	            'timeout' => ($this->timeout>0?$this->timeout:45),
	            'user_agent' => 'uePHPLibary v' . USAEPAY_VERSION . ($this->software?'/' . $this->software:'')
	        ),
	        'ssl' => array(
	            'verify_peer' => ($this->ignoresslcerterrors?false:true),
	            'allow_self_signed' => ($this->ignoresslcerterrors?true:false)	            
			)
		);
		
		if($this->cabundle) $options['ssl']['cafile'] = $this->cabundle;
		
		if(trim($this->proxyurl)) $options['http']['proxy'] = $this->proxyurl;
		
		
		// create stream context
		$context = stream_context_create($options);
		
		// post data to gateway
		$fd = fopen($url, 'r', null, $context);
		if(!$fd)
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Unable to open connection to gateway";
			$this->errorcode=10132;
			$this->blank=1;
			if(function_exists('error_get_last')) {
				$err=error_get_last();
				$this->transporterror=$err['message'];
			} else if(isset($GLOBALS['php_errormsg'])) {
				$this->transporterror=$GLOBALS['php_errormsg'];
			}
			//curl_close ($ch);
			return false;			
		}		
		
		// pull result
		$result = stream_get_contents($fd);

		// check for a blank response
		if(!strlen($result))
		{
			$this->result="Error";
			$this->resultcode="E";
			$this->error="Error reading from card processing gateway.";
			$this->errorcode=10132;
			$this->blank=1;
			fclose($fd);
			//$this->curlerror=curl_error($ch);
			//curl_close ($ch);
			return false;			
		}		
		
		fclose($fd);
		return $result;
		
	}
	
	function xmlentities($string)
	{
		$string = preg_replace('/[^a-zA-Z0-9 _\-\.\'\r\n]/e', '_uePhpLibPrivateXMLEntities("$0")', $string);
		return $string;
	}
	
}





//  umVerifyCreditCardNumber
//  Validates a credit card and returns the type of card.
//
//	Card Types:
//		 1	Mastercard
//		 2	Visa
//		 3	American Express
//		 4	Diners Club/Carte Blanche
//		10	Discover
//		20	enRoute	
//		28	JCB	


/**
 * Evaluates a creditcard number and if valid, returns the card type code
 *
 * @param ccnum string
 * @return int
 */
function umVerifyCreditCardNumber($ccnum)
{
	global $umErrStr;
	
	
	//okay lets do the stupid
	$ccnum=str_replace("-","",$ccnum);
	$ccnum=str_replace(" ","",$ccnum);
	$ccnum=str_replace("/","",$ccnum);

	if(!ereg("^[[:digit:]]{1,200}$", $ccnum)) {$umErrStr="Cardnumber contains characters that are not numbers";  return 0;}
	if(!ereg("^[[:digit:]]{13,16}$", $ccnum)) {$umErrStr="Cardnumber is not between 13 and 16 digits long";  return 0;}


	// Run Luhn Mod-10 to ensure proper check digit
	$total=0;
	$y=0;
	for($i=strlen($ccnum)-1; $i >= 0; $i--)
	{
		if($y==1) $y=2; else $y=1;         //multiply every other digit by 2
		$tmp=substr($ccnum,$i,1)*$y;
		if($tmp >9) $tmp=substr($tmp,0,1) + substr($tmp,1,1);
		$total+=$tmp;
	}
	if($total%10) {$umErrStr="Cardnumber fails Luhn Mod-10 check digit";  return 0;}


	switch(substr($ccnum,0,1))
	{
		case 2: //enRoute - First four digits must be 2014 or 2149. Only valid length is 15 digits
			if((substr($ccnum,0,4) == "2014" || substr($ccnum,0,4) == "2149") && strlen($ccnum) == 15) return 20;
			break;
		case 3: //JCB - Um yuck, read the if statement below, and oh by the way 300 through 309 overlaps with diners club.  bummer.
			if((substr($ccnum,0,4) == "3088" ||	substr($ccnum,0,4) == "3096" || substr($ccnum,0,4) == "3112" || substr($ccnum,0,4) == "3158" ||	substr($ccnum,0,4) == "3337" ||
				(substr($ccnum,0,8) >= "35280000" ||substr($ccnum,0,8) <= "358999999")) && strlen($ccnum)==16)  
			{
				return 28;
			} else { 
				switch(substr($ccnum,1,1))
				{
					case 4:
					case 7: // American Express - First digit must be 3 and second digit 4 or 7. Only Valid length is 15
						if(strlen($ccnum) == 15) return 3;
						break;
		     			case 0:
					case 6:
					case 8: //Diners Club/Carte Blanche - First digit must be 3 and second digit 0, 6 or 8. Only valid length is 14
						if(strlen($ccnum) == 14) return 4;
						break;
				}
			}
			break;
		case 4: // Visa - First digit must be a 4 and length must be either 13 or 16 digits.
			if(strlen($ccnum) == 13 || strlen($ccnum) == 16)
			{
				 return 2;
			}
			break;

		case 5: // Mastercard - First digit must be a 5 and second digit must be int the range 1 to 5 inclusive. Only valid length is 16
			if((substr($ccnum,1,1) >=1 && substr($ccnum,1,1) <=5) && strlen($ccnum) == 16)
			{
				return 1;
			}
			break;
   	case 6: // Discover - First four digits must be 6011. Only valid length is 16 digits.
			if(substr($ccnum,0,4) == "6011" && strlen($ccnum) == 16) return 10;
	}


	// couldn't match a card profile. time to call it quits and go home.  this goose is cooked.
	$umErrStr="Cardnumber did not match any known creditcard profiles";
	return 0;
}


function _uePhpLibPrivateXMLEntities($num)
{
	$chars = array(128 => '&#8364;',130 => '&#8218;',131 => '&#402;', 132 => '&#8222;', 133 => '&#8230;', 134 => '&#8224;', 135 => '&#8225;', 136 => '&#710;', 137 => '&#8240;', 138 => '&#352;', 139 => '&#8249;', 140 => '&#338;', 142 => '&#381;', 145 => '&#8216;',146 => '&#8217;',147 => '&#8220;',148 => '&#8221;',149 => '&#8226;',150 => '&#8211;',151 => '&#8212;',152 => '&#732;', 153 => '&#8482;',154 => '&#353;', 155 => '&#8250;',156 => '&#339;', 158 => '&#382;', 159 => '&#376;');
	$num = ord($num);
	return (($num > 127 && $num < 160) ? $chars[$num] : "&#".$num.";" );
}


?>