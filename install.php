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

/** THIS SCRIPT RUNS THE WEB BASED INSTALLER FOR WEB STORE 2 **/


// make sure our error reporting is set to ignore notices here, as qcodo will die on any message logged (e.g. an overwrite of a previously defined constant)
error_reporting(E_ALL & ~E_NOTICE);


 
//////////////////////////////////////////////////////////////////
// Check PHP version
if( version_compare(PHP_VERSION,'5.2.4') < 0 )
	die('WebStore requires at least PHP version of 5.2.4. Sorry cannot continue installation.');


//////////////////////////////////////////////////////////////////
// Verify the config file exists, and if not, create it
if (!file_exists('includes/configuration.inc.php'))
{
   //We have to create the configuration.inc.php file here from our template
   if(!is_writable('includes'))
       die("Please make the \"includes\" folder writable temporarily so we can create your configuration files.");

   $confighandle=fopen("includes/configuration.inc.php","w+");
   $contents="<?php

	//////////////////////////////////////////////////////////////////
	// Simplified configuration template. See configuration_inc.php-full
	// for full descriptions of these items.
	
	define('SERVER_INSTANCE', 'prod');
	define('ALLOW_REMOTE_ADMIN', false);
	
    switch (SERVER_INSTANCE) {
	    case 'dev':
	        break;
	
	    case 'test':
	        break;
	        
	    case 'stage':
            break;
	
	    case 'prod':
	        
			//__XLSWS_DIR_CONF__
		
			//__XLSWS_DB_CONF___
	
			
         break;
    }
	
	if(!defined('__DOCROOT__'))
	{
		header('Location: install.php');
	}
		
	define ('__URL_REWRITE__', 'apache');
	
	if((function_exists('date_default_timezone_set')) && (!ini_get('date.timezone')))
		date_default_timezone_set('America/Los_Angeles');
    include(dirname(__FILE__) . '/configurationpaths.inc.php');
	

	?>";
   if (fwrite($confighandle,$contents)===false)
       die("There was an error creating the configuration.inc.php file. Check your directory permissions.");
   fclose($confighandle);

}


//////////////////////////////////////////////////////////////////
// Verify the state and soap folders exist and if not, create them
// These may fail if cache isn't yet writable, so we ignore errors
// and will get them again after fixing cache
if (!file_exists('includes/qcodo/cache/state'))
    @mkdir('includes/qcodo/cache/state');
if (!file_exists('includes/qcodo/cache/soap'))
    @mkdir('includes/qcodo/cache/soap');
 
 
 
//////////////////////////////////////////////////////////////////
// Set up initial pathing so install can continue
define ('__SUBDIRECTORY__', preg_replace('/\/?\w+\.php$/', '', $_SERVER['PHP_SELF']));
define ('__DOCROOT__', str_replace(__SUBDIRECTORY__,"",dirname(__FILE__)));
define ('__VIRTUAL_DIRECTORY__', '');

// read the config file
$content = file_get_contents("includes/configuration.inc.php");
if(stristr($content , "define ('__DOCROOT__'") && $_SERVER['REQUEST_URI']!=__SUBDIRECTORY__."/install.php?check"){
	// config is there.
	exit('Store has already been installed. <script type="text/javascript">document.location.href="index.php";</script>');
}


// include our prepend.inc which'll give us access to useful classes like QForm, used below
require_once('includes/prepend.inc.php');


//////////////////////////////////////////////////////////////////
// Are we installing in the root
if(!defined('__DOCROOT__'))
{
	
	if(dirname($_SERVER["REQUEST_URI"])  == '/'){
		define ('__DOCROOT__', (dirname($_SERVER['SCRIPT_FILENAME'])));
		define ('__VIRTUAL_DIRECTORY__', '');
		define ('__SUBDIRECTORY__', '');
	}else{
		
		define ('__DOCROOT__', dirname(dirname($_SERVER['SCRIPT_FILENAME'])));
		// is there a ~?
		if($tilda = stristr($_SERVER['REQUEST_URI'] , '~')){
			$tilda = substr($tilda , 0 , strpos($tilda , '/') );
			define ('__VIRTUAL_DIRECTORY__', '/' . $tilda);
		}else{
			define ('__VIRTUAL_DIRECTORY__', '');
		}
		define ('__SUBDIRECTORY__', '/' . basename(dirname($_SERVER['SCRIPT_FILENAME'])));
	}

}




// since these are not defined



	if (!isset($this)) {
		
		
		class WSInstall extends QForm {
			protected $pnlInstall;
			protected $btnNext;
			protected $btnPrev;
			
			protected $arrSteps;
			
			protected $intStep;
			
			protected $pnlStep;
			
			
			protected function Form_Create(){
				$this->pnlInstall = new QPanel($this);
				$this->pnlInstall->AutoRenderChildren = true;
				
				
				$this->btnPrev = new QButton($this);
				$this->btnNext = new QButton($this);
				
				$this->btnPrev->Text = ('Previous');
				$this->btnNext->Text = ('Next');
				
				$this->btnPrev->AddAction(new QClickEvent() , new QServerAction('btnPrev_Click'));
				$this->btnNext->AddAction(new QClickEvent() , new QServerAction('btnNext_Click'));
				//$this->btnNext->CausesValidation = true;
				
				$this->btnPrev->Enabled = false;
				$this->btnNext->Enabled = false;
				
				$this->pnlStep = new QPanel($this);
				$this->pnlStep->HtmlEntities = false;
				$this->pnlStep->CssClass = "steps";
				$this->pnlStep->Visible = true;
				$this->pnlStep->DisplayStyle = QDisplayStyle::Block;
				
				$this->arrSteps = array( array('license_agreement' => 'License agreement'),
								//'Checking requirements',
								array('host_settings' => 'Host settings'),
							//	array('check_permissions' => 'Check Permissions'),
								array('db_settings' => 'Database settings'),
								array('store_password' => 'Password'),
								array('install_db' => 'Installing database')
								);

								
				$this->intStep = 0;
				
				//die("stopping here");
				$checkenv = $this->xls_check_server_environment();
				if ((in_array("fail",$checkenv) && $_SERVER['REQUEST_URI']!=__SUBDIRECTORY__."/install.php?ignore")
					|| $_SERVER['REQUEST_URI']==__SUBDIRECTORY__."/install.php?check")
					$this->environment_not_acceptable($checkenv);
				else
					$this->license_agreement();
				
			}
			
			protected function Form_PreRender(){
				$this->btnPrev->Enabled = true;
				$this->btnNext->Enabled = true;

				
				if($this->intStep == 0)
					$this->btnPrev->Display = false;

				if($this->intStep == (count($this->arrSteps) -1))
					$this->btnNext->Display = false;
					
			}
			
			
			protected function iControl($strControlId , $controlType){
				$c = $this->GetControl($strControlId);
				if(!$c)
					$c = new $controlType($this->pnlInstall, $strControlId);

				$c->Display = true;
				
				return $c;
			}
			
			protected function hideControls(){
				//$this->pnlInstall->RemoveChildControls(true);
				
				$ctls = $this->GetAllControls();
				foreach($ctls as $ctl)
					$ctl->Display = false;
				
				$this->pnlInstall->Display = true;
				$this->pnlInstall->AutoRenderChildren = true;
				$this->btnPrev->Display = true;
				$this->btnNext->Display = true;
				
				$this->pnlStep->Display = true;
				
			}
			
			
			protected function environment_not_acceptable($checkenv){
				
				
				$warning_text="<table>";
				if ($_SERVER['REQUEST_URI']==__SUBDIRECTORY__."/install.php?check")
				{
					$warning_text .= "<tr><td colspan='2'><b>SYSTEM CHECK for "._xls_version()."</b></td></tr>";
					$warning_text .= "<tr><td colspan='2'>The chart below shows the results of the system check and if upgrades have been performed.</td></td>";
					
					//For 2.1.x upgrade, have the upgrades been run?			
					if ($_SERVER['REQUEST_URI']==__SUBDIRECTORY__."/install.php?check") {
						  $checkenv = array_merge($checkenv,$this->xls_check_upgrades());
						  $checkenv = array_merge($checkenv,$this->xls_check_file_signatures());
				    }
					
				}
				else
				{
					$warning_text .= "<tr><td colspan='2'><b>CANNOT INSTALL</b></td></tr>";
					$warning_text .= "<tr><td colspan='2'>There are issues with your PHP environment which need to be fixed before you can install WebStore. Please check the chart below for missing libraries on your PHP installation which must be installed/compiled into PHP, and subdirectories which you need to make writeable. Remember to restart Apache if you change any php.ini settings.</td></td>";
				}
				$warning_text .= "<tr><td colspan='2'><hr></td></tr>";
				$curver=_xls_version();
				foreach ($checkenv as $key=>$value)
				$warning_text .= "<tr><td>$key</td><td>".(($value=="pass" || $value==$curver) ? "$value" : "<font color='#cc0000'><b>$value</b></font>" )."</td>";
				
				
					
					
				$warning_text .= "</table>";
				
				$this->hideControls();
				
				$this->pnlStep->Text = "<img src=\"templates/install/step_01.png\" />";
				$this->btnNext->Display = false;
				$lbox = $this->iControl('agreement' , 'QPanel');
				//$lbox->TextMode = QTextMode::MultiLine;
				$lbox->Width = 500;
				$lbox->Height = 400;
				//$lbox->ReadOnly = true;
				$lbox->DisplayStyle = QDisplayStyle::Block;
				$lbox->CssClass = "install_agreement";
				$lbox->Text = $warning_text;	
				$lbox->HtmlEntities = false;
				
				
								
				$this->pnlInstall->CssClass = '';
				
			}
			
			
			protected function license_agreement(){
				$this->hideControls();
				
				$this->pnlStep->Text = "<img src=\"templates/install/step_01.png\" />";
				
				$lbox = $this->iControl('agreement' , 'QPanel');
				//$lbox->TextMode = QTextMode::MultiLine;
				$lbox->Width = 500;
				$lbox->Height = 400;
				//$lbox->ReadOnly = true;
				$lbox->DisplayStyle = QDisplayStyle::Block;
				$lbox->CssClass = "install_agreement";
				$lbox->Text = <<<EOT
				
<p class=MsoNormal>Xsilva Systems Inc. (&quot;XSILVA&#0153;&quot;)</p>

<p class=MsoNormal><span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p>

<p class=MsoNormal>END USER LICENCE AGREEMENT FOR Xsilva LightSpeed&#0153; and/or
Xsilva LightSpeed Web Store&#0153; SOFTWARE PRODUCTS (&quot;SOFTWARE PRODUCT&quot;)</p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>IMPORTANT</p>

<p class=MsoNormal>PLEASE READ CAREFULLY BEFORE OPENING ANY XSILVA&#0153; SOFTWARE
PRODUCT</p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>This XSILVA&#0153; End-User License Agreement (&quot;EULA&quot;)
is a legal agreement between you (either an individual or a single entity) and
XSILVA&#0153;<span style="mso-spacerun: yes">&nbsp; </span>for XSILVA&#0153;'s SOFTWARE
PRODUCTS identified above, which include the User's Guide, any associated
SOFTWARE components, any media, any printed materials other than the User's
Guide, and any &quot;online&quot; or electronic documentation
(&quot;SOFTWARE&quot;). By installing, copying, or otherwise using either
SOFTWARE PRODUCT, you agree to be bound by the terms of this EULA with relation
to such SOFTWARE PRODUCT. If you do not agree to the terms of this EULA, do not
install or use the SOFTWARE PRODUCT.</p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>SOFTWARE PRODUCT LICENSE</p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal><span style="mso-spacerun: yes">&nbsp;</span>We agree and
confirm that this Agreement is entered into by both parties for good and
valuable consideration and that the preamble hereinabove forms part of this
License Agreement. </p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>1.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><span
style="mso-spacerun: yes">&nbsp; </span>LICENSE.<span style="mso-spacerun:
yes">&nbsp; </span>All SOFTWARE is licensed, not sold.<span
style="mso-spacerun: yes">&nbsp; </span>XSILVA&#0153; grants you a personal and
non-exclusive limited license only for the use of the SOFTWARE PRODUCT, for
which the proper fees have been paid.<span style="mso-spacerun: yes">&nbsp;
</span></p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>2.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><span
style="mso-spacerun: yes">&nbsp; </span>PROPERTY OF XSILVA&#0153;.<span
style="mso-spacerun: yes">&nbsp; </span>The SOFTWARE is the confidential
property of XSILVA&#0153;.<span style="mso-spacerun: yes">&nbsp; </span>All right,
title and interest, including without limitation, copyright, in and to the
SOFTWARE is the sole and exclusive property of XSILVA&#0153;. For greater certainty,
the SOFTWARE shall include all parts and aspects of the SOFTWARE, including
without limitation any images, graphic user interface, design elements, order
of operation, so-called &quot;look and feel&quot;, data organization, ideas, concepts,
photographs, animations, text and &quot;applets&quot; that are incorporated
into the SOFTWARE, as well as all accompanying printed material of the SOFTWARE
PRODUCT. </p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>3.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
GRANT OF LICENSE.<span style="mso-spacerun: yes">&nbsp;&nbsp; </span>Each
SOFTWARE PRODUCT is licensed as a single product. Its component parts may not
be separated for use on more than one server. You may use each respective
SOFTWARE PRODUCT on one single server and as many client workstations for which
you have purchased licenses.<span style="mso-spacerun: yes">&nbsp; </span>Under
no other circumstances may any SOFTWARE PRODUCT be operated at the same time on
more than the number of computers for which you have paid a separate license
fee. After you have purchased the license for SOFTWARE, and have received the
file enabling the registered copy, you are licensed to copy the SOFTWARE only
into the memory of the number of computers corresponding to the number of
licenses purchased, regardless of the computer network architecture on which
the Software is stored and operated. XSILVA&#0153; shall have the right, at any time,
to audit and verify your compliance with this Agreement, including entry upon
your premises to inspect your information technology and related records.<span
style="mso-spacerun: yes">&nbsp; </span></p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>4.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>TERMINATION
AND TRANSFER.<span style="mso-spacerun: yes">&nbsp; </span>You may terminate
this license at any time by destroying the original and all copies of the
SOFTWARE in whatever form.<span style="mso-spacerun: yes">&nbsp; </span>You may
not transfer the SOFTWARE or the rights under this EULA.<span
style="mso-spacerun: yes">&nbsp; </span></p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>Without prejudice to any other rights, XSILVA&#0153; may terminate
this EULA if you fail to comply with the terms and conditions of this EULA. In
such event, you must destroy all copies of the SOFTWARE, and certify such
destruction to XSILVA&#0153; in writing within five (5) days of notice of termination
by XSILVA&#0153;.</p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>5.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><span
style="mso-spacerun: yes">&nbsp;&nbsp; </span>RESTRICTIONS.<span
style="mso-spacerun: yes">&nbsp; </span>You may not modify, enhance, revise,
alter, reverse engineer, de-compile, or disassemble any SOFTWARE, save where
this prohibition is contrary to the law of any particular jurisdiction. You may
not sublicence, rent, lease, or lend either the SOFTWARE or your rights under
this EULA. You may not use the SOFTWARE to perform any unauthorized transfer of
information, including without limitation, any transfer of files in violation
of a copyright, or for any illegal purpose.<span style="mso-spacerun:
yes">&nbsp; </span>You shall have the right to make a single copy of the
SOFTWARE for storage on a single computer provided that such copy is only used
for backup or archival purposes, except that you may not reproduce the printed
materials included in the SOFTWARE PRODUCT.<span style="mso-spacerun:
yes">&nbsp; </span>No proprietary or intellectual right, title or interest in
or to any trademark, logo or trade name of XSILVA&#0153; or its licensors is granted
under this EULA.</p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>6.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
UPGRADES.<span style="mso-spacerun: yes">&nbsp; </span>Future SOFTWARE that
XSILVA&#0153;<span style="mso-spacerun: yes">&nbsp;&nbsp; </span>labels as an upgrade
shall replace and / or supplement the SOFTWARE that constitutes the basis of
this EULA.<span style="mso-spacerun: yes">&nbsp; </span>You may use the
resulting upgraded SOFTWARE only in accordance with the terms and conditions of
this EULA unless amended by the terms of that upgrade.<span
style="mso-spacerun: yes">&nbsp; </span></p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>7.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>SUPPORT
SERVICES. XSILVA&#0153; may provide you with support services related to the
SOFTWARE. Use of support services is governed by XSILVA&#0153;<span
style="mso-spacerun: yes">&nbsp; </span>polices and programs described in the
user manual, in online documentation, and/or other XSILVA&#0153; -provided materials,
as they may be modified from time to time. Any supplemental SOFTWARE code
provided to you as part of the support services shall be considered part of the
SOFTWARE and subject to the terms and conditions of this EULA. With respect to
technical information you provide to XSILVA&#0153; as part of the support services,
XSILVA&#0153; may use such information for its business purposes, including for
product support and development. XSILVA&#0153; will not utilize such technical information
in a form that personally identifies you.<span style="mso-spacerun: yes">&nbsp;
</span></p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>8.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>INTELLECTUAL
PROPERTY. The SOFTWARE is protected by Canadian and United States Intellectual
property law and international treaty provisions governing intellectual
property. You acknowledge that no title to the intellectual property in the
SOFTWARE is transferred to you. You further acknowledge that title and full
ownership rights to the SOFTWARE will remain the exclusive property of
XSILVA&#0153;<span style="mso-spacerun: yes">&nbsp; </span>and you will not acquire
any rights to the SOFTWARE except as expressly set forth in this license. You
agree that any copies of the SOFTWARE will contain the same proprietary notices
which appear on and in the SOFTWARE, and that you shall not remove same.</p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>9.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><span
style="mso-spacerun: yes">&nbsp;&nbsp; </span>EXPORT RESTRICTIONS. You agree
that you will not export or re-export the SOFTWARE to any country, person,
entity, or end user subject to Canadian or U.S.A. export restrictions.
Restricted countries currently include, without limitation, Cuba, Iran, Iraq,
Libya, North Korea, Sudan, and Syria. You warrant and represent that neither
the U.S.A. Bureau of Export Administration nor any other federal agency has
suspended, revoked or denied your export privileges.</p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>10.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>LIMITED
WARRANTY.<span style="mso-spacerun: yes">&nbsp; </span>XSILVA&#0153; warrants to you
that for a period of thirty (30) days from the date that you licensed, and paid
for, the SOFTWARE (as evidenced by a copy of such payment receipt), the
SOFTWARE will materially perform in accordance with the written materials that
are enclosed with the SOFTWARE.<span style="mso-spacerun: yes">&nbsp; </span>Except
for the foregoing, the SOFTWARE PRODUCT is provided &quot;AS IS&quot;.<span
style="mso-spacerun: yes">&nbsp; </span></p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>11.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>CUSTOMER
REMEDIES.<span style="mso-spacerun: yes">&nbsp; </span>XSILVA&#0153; and its
suppliers' sole and absolute liability, and your exclusive remedy, under this
EULA shall be, at XSILVA&#0153; 's sole discretion, to either repair or to replace
the SOFTWARE that does not meet XSILVA&#0153; 's limited warranty stated in section
10, or refund the fee paid by you for such SOFTWARE upon receipt by
XSILVA&#0153;<span style="mso-spacerun: yes">&nbsp; </span>of the SOFTWARE with a
copy of your payment receipt.<span style="mso-spacerun: yes">&nbsp; </span>The
limited warranty in section 10 is void if any failure of the SOFTWARE to
operate has resulted from misuse, breach of this EULA, accident, abuse or
misapplication by any person other than XSILVA&#0153;, or use of same in conjunction
with other software.<span style="mso-spacerun: yes">&nbsp; </span>Any
replacement of the SOFTWARE will be warranted, on the same terms and conditions
as stipulated herein, and for the same period as stipulated under Section 10.</p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>12.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>DISCLAIMER
OF WARRANTY.<span style="mso-spacerun: yes">&nbsp; </span>UNLESS SPECIFIED IN
THIS EULA, ALL EXPRESS OR IMPLIED CONDITIONS, REPRESENTATIONS AND WARRANTIES,
INCLUDING ANY IMPLIED WARRANTY OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
PURPOSE, OR NON-INFRINGEMENT, ARE DISCLAIMED, EXCEPT TO THE EXTENT THAT THESE
DISCLAIMERS ARE HELD TO BE LEGALLY INVALID.</p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>13.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>LIMITATION
OF LIABILITY.<span style="mso-spacerun: yes">&nbsp; </span>IN NO EVENT SHALL
XSILVA&#0153;, ITS DIRECTORS, OFFICERS, EMPLOYEES, XSILVA&#0153; �S SUPPLIERS OR ITS
LICENSORS BE LIABLE FOR ANY LOST REVENUE, PROFIT OR DATA, OR FOR ANY SPECIAL,
INDIRECT, CONSEQUENTIAL,INCIDENTAL DAMAGES, ARISING OUT OF, OR IN ANY WAY
RELATED TO THE USE OF OR INABILITY TO USE THE SOFTWARE, EVEN IF XSILVA&#0153;<span
style="mso-spacerun: yes">&nbsp; </span>HAS BEEN ADVISED OF THE POSSIBILITY OF
SUCH DAMAGES.<span style="mso-spacerun: yes">&nbsp; </span>In no event shall
XSILVA&#0153;'s liability to you, whether in contract, tort (including negligence),
or otherwise, exceed the greater of CDN$1.00 or the amount paid by you for the
SOFTWARE under this EULA. You agree and confirm that: you shall not apply for
or seek any punitive or exemplary damages against XSILVA&#0153; in any connection
with this EULA, the Software or the conduct of XSILVA&#0153; ; and, the provisions of
sections 10, 11, 12 and 13 have induced XSILVA&#0153;<span style="mso-spacerun:
yes">&nbsp; </span>to enter into this EULA and that XSILVA&#0153;<span
style="mso-spacerun: yes">&nbsp; </span>would not have entered into this EULA
but for such provisions. Sections 10, 11, 12 and 13 shall survive any
termination of this EULA for any reason whatsoever.</p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>14.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>U.S.
GOVERNMENT RESTRICTED RIGHTS. The SOFTWARE is provided with RESTRICTED RIGHTS.
Use, duplication, or disclosure by the Government is subject to restrictions as
set forth in subparagraph (c)(1)(ii) of The Rights in Technical Data and
Computer SOFTWARE clause of DFARS 252.227-7013 or subparagraphs (c)(i) and (2)
of the Commercial Computer SOFTWARE-Restricted Rights at 48 CFR 52.227-19, as
applicable.<span style="mso-spacerun: yes">&nbsp; </span>Manufacturer is Xsilva
Systems Inc / 1583 St-Hubert, Montreal, QC, Canada. H2L 3Z1. </p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>15.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>GOVERNING
LAW.<span style="mso-spacerun: yes">&nbsp; </span>This EULA shall be governed
and interpreted in accordance with the laws of the Province of Quebec, Canada.
The parties submit, attorn and consent to the non-exclusive jurisdiction of the
courts in Montreal, Quebec, Canada, save that in the event that XSILVA&#0153; deems
it to be in its interest to enforce this EULA in any other jurisdiction, it
shall have the right to do so before such courts.</p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>16.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>SEVERABILITY.<span
style="mso-spacerun: yes">&nbsp; </span>If any term, clause, paragraph or
article of this EULA is held to be invalid or unenforceable, for any reason, it
shall not affect, impair, invalidate or modify the remainder of this EULA, but
the effect shall be confined to the term, clause, sentence or article of this
EULA judged to be invalid or unenforceable.</p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>17.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>ENFORCEABILITY.<span
style="mso-spacerun: yes">&nbsp; </span>The failure or delay of any party to
enforce at any time or any period of time any of the provisions of this EULA
shall not constitute a present or future waiver of such provisions nor the right
of either party to enforce each and every provision.</p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>18.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>LANGUAGE.<span
style="mso-spacerun: yes">&nbsp; </span>The parties acknowledge that they
require that this EULA be drawn up in the English language only.<span
style="mso-spacerun: yes">&nbsp; </span>Les parties reconnaissent qu�ils ont
exig� que la pr�sente convention soit r�dig�e en langue anglaise
seulement.<span style="mso-spacerun: yes">&nbsp; </span></p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>19.<span style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>ENTIRE
AGREEMENT.<span style="mso-spacerun: yes">&nbsp; </span>This EULA is the entire
EULA between you and XSILVA&#0153; relating to its subject matter. It supersedes all
prior or contemporaneous oral or written communications, proposals,
representations and warranties and prevails over any conflicting or additional
terms of any quote, order, acknowledgement, or other communication between the
parties relating to its subject matter during the term of this EULA.<span
style="mso-spacerun: yes">&nbsp; </span>No modification of this EULA will be
binding, unless in writing and signed by an authorized representative of each
party.</p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal>Should you have any questions concerning this EULA, or if
you desire to contact XSILVA&#0153; for any reason, please contact Xsilva Systems Inc
by mail at: 1583 St-Hubert, Montreal, QC, Canada. H2L 3Z1, by telephone at:
(514) 907-1801 or by electronic mail at:<span style="mso-spacerun: yes">&nbsp;
</span>info@xsilva.com.</p>				
				
EOT;
				
				$lbox->Text = utf8_encode($lbox->Text);
				$lbox->HtmlEntities = false;
				
				
				$agree = $this->iControl('agree' , 'QCheckBox');
				$agreeLbl = $this->iControl('agreeLabel' , 'QLabel');
				$agreeLbl->Text = "I agree to the terms and conditions";
				$agreeLbl->CssClass = "checkbox_agreement";
				
				$this->pnlInstall->CssClass = '';
				
			}
			
			protected function host_settings(){
				$this->hideControls();
				
				$this->pnlStep->Text = "<img src=\"templates/install/step_02.png\" /></div>";
				
				$hostadvise = $this->iControl('hostadvise' , 'QLabel');
				$hostadvise->Text = "We have calculated the following directories for your installation. It should be correct unless you want to install in a seperate location.";
				$hostadvise->CssClass = "advice";
				$hostadvise->DisplayStyle = QDisplayStyle::Block;
				
				
				$docrootname = $this->iControl('docrootname' , 'QLabel');
				$docrootname->Text = "Document Root";
				$docrootname->CssClass = "label";
				
				$docroot = $this->iControl('docroot' , 'QTextBox');
				$docroot->Required = true;
				$docroot->DisplayStyle = QDisplayStyle::Block;
				
				
				
				$virtualdirname = $this->iControl('virdirectoryname' , 'QLabel');
				$virtualdirname->Text = "Virtual Directory";
				$virtualdirname->CssClass = "label";
				
				//die(print_r($_SERVER , true));
				
				$virtualdir = $this->iControl('virdirectory' , 'QTextBox');
				$virtualdir->DisplayStyle = QDisplayStyle::Block;				
				
				
				
				$subdirname = $this->iControl('subdirname' , 'QLabel');
				$subdirname->Text = "Sub Directory";
				$subdirname->CssClass = "label";
				
				$subdir = $this->iControl('subdir' , 'QTextBox');
				$subdir->DisplayStyle = QDisplayStyle::Block;
				
				
				if((dirname($_SERVER["REQUEST_URI"])  == '/') || (dirname($_SERVER["REQUEST_URI"])  == '')){
					
					if(trim($docroot->Text) == '')
						$docroot->Text = dirname($_SERVER['SCRIPT_FILENAME']);
					if(trim($virtualdir->Text) == '')
						$virtualdir->Text = '';
						
					if(trim($subdir->Text) == '')
						$subdir->Text = '';
				}else{

					if(trim($docroot->Text) == '')
						$docroot->Text = dirname(dirname($_SERVER['SCRIPT_FILENAME']));
						
					if(trim($virtualdir->Text) == ''){
							
						// is there a ~?
						if($tilda = stristr($_SERVER['REQUEST_URI'] , '~')){
							$tilda = substr($tilda , 0 , strpos($tilda , '/') );
							$virtualdir->Text = '/' . $tilda;
						}
							
					}

					if(trim($subdir->Text) == '')
						$subdir->Text = '/' .  basename(dirname($_SERVER['SCRIPT_FILENAME']));
						
					if(basename(dirname($_SERVER['SCRIPT_FILENAME'])) == '' || basename(dirname($_SERVER['SCRIPT_FILENAME'])) == '/')
						$subdir->Text = '';
				
						
						
				}
				
				
				

				$storetzconfname = $this->iControl('storetzname' , 'QLabel');
				$storetzconfname->Text = "Store Timezone";	
				$storetzconfname->CssClass = "label";			
				
				$storetz = $this->iControl('storetz' , 'QListBox');
                if($storetz->ItemCount == 0){
                    $arr = _xls_timezones();
					
					foreach($arr as $t)
						$storetz->AddItem($t , $t , date_default_timezone_get() == $t);
					
					
					
					
				}
				$storetz->DisplayStyle = QDisplayStyle::Block;		
								
								
				
				
				
				
				 $this->pnlInstall->CssClass = "host_settings";
			}
			
			
			protected function db_settings(){
				$this->hideControls();
				
				$this->pnlStep->Text = "<img src=\"templates/install/step_03.png\" /></div>";
				
				$dbhostname = $this->iControl('dbhostname' , 'QLabel');
				$dbhostname->Text = "MySQL Database Host (Server name or IP)";
				$dbhostname->CssClass = "label";
				
				$dbhost = $this->iControl('dbhost' , 'QTextBox');
				$dbhost->Text = ini_get('mysql.default_host');
				if($dbhost->Text == '')
					$dbhost->Text = 'localhost';
				$dbhost->Required = true;
				$dbhost->DisplayStyle = QDisplayStyle::Block;


				
				$dbportname = $this->iControl('dbportname' , 'QLabel');
				$dbportname->Text = "Port";
				$dbportname->CssClass = "label";
				
				$dbport = $this->iControl('dbport' , 'QTextBox');
				$dbport->Text = '';
				$dbport->DisplayStyle = QDisplayStyle::Block;
				
				
				
				
				$dbusername = $this->iControl('dbusername' , 'QLabel');
				$dbusername->Text = "Username";
				$dbusername->CssClass = "label";
				
				$dbuser = $this->iControl('dbuser' , 'QTextBox');
				$dbuser->DisplayStyle = QDisplayStyle::Block;				
				$dbuser->Text = ini_get('mysql.default_user');
				
				
				$dbpassname = $this->iControl('dbpassname' , 'QLabel');
				$dbpassname->Text = "Password";
				$dbpassname->CssClass = "label";
								
				$dbpass = $this->iControl('dbpass' , 'QTextBox');
				$dbpass->DisplayStyle = QDisplayStyle::Block;				
				$dbpass->TextMode = QTextMode::Password;
				
				
				
				$dbname = $this->iControl('dbname' , 'QLabel');
				$dbname->Text = "Database Name";
				$dbname->CssClass = "label";				
								
				$db = $this->iControl('db' , 'QTextBox');
				$db->Required = true;
				$db->DisplayStyle = QDisplayStyle::Block;				
								

				
				$dbencodingname = $this->iControl('dbencodingname' , 'QLabel');
				$dbencodingname->Text = "Database Encoding";
				$dbencodingname->CssClass = "label";
								
				$dbencoding = $this->iControl('dbencoding' , 'QListBox');
				if($dbencoding->ItemCount==0){
					$dbencoding->Width = "350";
					$dbencoding->AddItem('UTF8', 'utf8');
				}
				$dbencoding->Required = true;
				$dbencoding->DisplayStyle = QDisplayStyle::Block;				
				
				
				
				
				$this->pnlInstall->CssClass = "db_settings";
				
			}
			
			
			
			protected function store_password(){
				$this->hideControls();
				
				$this->pnlStep->Text = "<img src=\"templates/install/step_04.png\" /></div>";
				
				
				$storepassname = $this->iControl('storepassname' , 'QLabel');
				$storepassname->Text = "Store Password";
				$storepassname->CssClass = "label";
								
				$storepass = $this->iControl('storepass' , 'QTextBox');
				$storepass->DisplayStyle = QDisplayStyle::Block;				
				$storepass->TextMode = QTextMode::Password;
				

				$storepassconfname = $this->iControl('storepassconfname' , 'QLabel');
				$storepassconfname->Text = "Confirm Store Password";	
				$storepassconfname->CssClass = "label";			
				
				$storepassconf = $this->iControl('storepassconf' , 'QTextBox');
				$storepassconf->DisplayStyle = QDisplayStyle::Block;				
				$storepassconf->TextMode = QTextMode::Password;
				
				
				
				$this->pnlInstall->CssClass = "store_password";
			}
			
			
			
			protected function password_obs($text){
				$len = strlen($text);
				
				if($len <= 2)
					return "*******";
					
				return substr($text , 0 , 1) . str_repeat("*" , $len - 2) .  substr($text , $len -1 , 1);
				
			}
			
			protected function install_db(){
				
				$this->hideControls();
				
				$this->pnlStep->Text = "<img src=\"templates/install/step_05.png\" /></div>";

					$dbhost = $this->GetControl('dbhost');
					$dbuser = $this->GetControl('dbuser');
					$dbpass = $this->GetControl('dbpass');
					$dbport = $this->GetControl('dbport');
					$db = $this->GetControl('db');							
					$dbencoding = $this->GetControl('dbencoding');							
					
					$pass = $this->GetControl('storepass');
					$tz = $this->GetControl('storetz');
					
					$docroot = $this->GetControl('docroot');
					$virtualdir = $this->GetControl('virdirectory');
					$subdir = $this->GetControl('subdir');					
				
				
				
				
				$installdb = $this->iControl('installdb' , 'QLabel');
				$installdb->Text = sprintf("<h1>Your store will be installed with the following configuration.</h1>
						<strong>Root directory</strong> : %s <br/>
						<strong>Virtual directory</strong> : %s <br/>
						<strong>Sub directory</strong> : %s <br/>
						<br/>
						<br/>
						<br/>
						<strong>Database Host</strong> : %s %s <br/>
						<strong>Database Username</strong> : %s <br/>
						<strong>Database Password</strong> : %s <br/>
						<strong>Database</strong> : %s <br/>
						<strong>Database Encoding</strong> : %s <br/>
						<br/>
						<br/>
						<strong>Timezone</strong> : %s <br/>
						<br/>
						<br/>
						<strong>Store Admin Password</strong> : %s <br/>
						<br/>
						<br/>
						Click install store to perform installation of the store.<br/>" 
						,   $docroot->Text
						,   $virtualdir->Text
						,   $subdir->Text
						,   $dbhost->Text
						,	$dbport->Text
						,	$dbuser->Text
						,	$this->password_obs($dbpass->Text)
						,   $db->Text
						,   $dbencoding->SelectedValue
						,	$tz->SelectedValue
						,	$this->password_obs($pass->Text)
						);
				$installdb->HtmlEntities = false;
				
				$installdb->DisplayStyle = QDisplayStyle::Block;	
				$installdb->CssClass = "textbold";
				
				$dbbut = $this->iControl('dbbut' , 'QButton');
				$dbbut->Text = "Install Store";
				$dbbut->CssClass = "button center rounded";
				$dbbut->AddAction(new QClickEvent()  , new QServerAction('performDBInstall'));
				
				$this->pnlInstall->CssClass = "install_center";
			}
			
			
			
			
			
			protected function btnPrev_Click($strFormId, $strControlId, $strParameter){
				
				$this->intStep--;
				$step = $this->arrSteps[$this->intStep];
				$func = key($step);
				$this->$func();
				
			}
			
			
			
			protected function btnNext_Click($strFormId, $strControlId, $strParameter){
				if($this->intStep == 0){
					$agree = $this->GetControl('agree');
					if($agree && !$agree->Checked){
						QApplication::ExecuteJavaScript('alert(\'You must agree to Licence Agreement to continue installation.\');');
						return;
					}elseif(!$agree){
						$this->license_agreement();
						return;
					}
				}
				
				
				
				if($this->intStep == 1){
					
					// Check that folder exists
					$docroot = $this->GetControl('docroot');
					if(!file_exists($docroot->Text)){
						QApplication::ExecuteJavaScript('alert(\'The Doc root you have given does not exist!.\');');
						return;
					}
					
				}
				
				
				if($this->intStep == 2){
					
					$link = $this->connect_db();
					if(!$link)
						return;
					
					
				}				
				
				
				

				if($this->intStep == 3){
					$pass = $this->GetControl('storepass');
					$passconf = $this->GetControl('storepassconf');
					if(strlen(trim($pass->Text)) < 6){
						QApplication::ExecuteJavaScript('alert(\'Please give at least 6 characters for your store password. We recommend choosing a strong password!\');');
						return;
					}
					
					if($pass->Text != $passconf->Text){
						QApplication::ExecuteJavaScript('alert(\'Given password do not match\');');
						return;
					}
					
				}				
				
				
				
				
				
				
				
				

				$this->intStep++;
				$step = $this->arrSteps[$this->intStep];
				$func = key($step);
				$this->$func();
				
			}
			protected function parse_php_info()
			{
				if ($_SERVER['REQUEST_URI']=="/install.php?phpinfo"){
				echo phpinfo();
				die();
				}
			      ob_start();
			      phpinfo();
			      $phpinfotemp = array('phpinfotemp' => array());
			      
			      if (preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr>(.*?)\s*(?:(.*?)\s*(?:(.*?)\s*)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
			          {
			          foreach ($matches as $match)
			              {
			              if (strlen($match[1]))
			                  $phpinfotemp[$match[1]] = array();
			              elseif (isset($match[3]))
			                  $phpinfotemp[end(array_keys($phpinfotemp))][] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
			              else
			                  $phpinfotemp[end(array_keys($phpinfotemp))][] = $match[2];
			              }
			          }
			      $phpinfo = array();
			      foreach ($phpinfotemp as $name => $section)
			          {
			          if ($name=="PHP Core") $name="Core"; //name change between 5.2 and 5.3
			          foreach ($section as $key => $val)
			                  {
			                  preg_match_all('|<td.*>(.*)</td>|U', $val[1], $output);
			                  $phpinfo[$name][trim(strip_tags($output[0][0]))] = trim(strip_tags($output[0][1]));
			                  }
			          }
			    ob_end_flush();
				return $phpinfo;
			}
			

			protected function xls_check_server_environment()
			{ 
				$phpinfo = $this->parse_php_info();
				//We check all the elements we need for a successful install and pass back the report
				$checked=array();
				$checked['MySQLi']= isset($phpinfo['mysqli']) ? "pass" : "fail";
				$checked['PHP Session']= ($phpinfo['session']['Session Support']=="enabled" ? "pass" : "fail");
				$checked['cURL Support']= isset($phpinfo['curl']) ? "pass" : "fail";
				if ($checked['cURL Support']=="pass")
						$checked['cURL SSL Support']= (
							(stripos($phpinfo['curl']['cURL Information'],"OpenSSL") !== false ||
							$phpinfo['curl']['SSL']=="Yes") ? "pass" : "fail");
				$checked['Multi-Byte String Library']= ($phpinfo['mbstring']['Multibyte Support']=="enabled" ? "pass" : "fail");
				$checked['GD Library']= ($phpinfo['gd']['GD Support']=="enabled" ? "pass" : "fail");
				$checked['GD Library GIF']= ($phpinfo['gd']['GIF Create Support']=="enabled" ? "pass" : "fail");
				$checked['GD Library JPG']= ($phpinfo['gd']['JPG Support']=="enabled" ? "pass" : "fail");
				if ($checked['GD Library JPG']=="fail")
					$checked['GD Library JPG']= ($phpinfo['gd']['JPEG Support']=="enabled" ? "pass" : "fail");
				$checked['GD Library PNG']= ($phpinfo['gd']['PNG Support']=="enabled" ? "pass" : "fail");
				$checked['GD Library Freetype Support']= ($phpinfo['gd']['FreeType Support']=="enabled" ? "pass" : "fail");
				$checked['MCrypt Encryption Library']= isset($phpinfo['mcrypt']) ? "pass" : "fail";
				$checked['Session use_only_cookies must be turned Off']= ($phpinfo['session']['session.use_only_cookies']=="Off" ? "pass" : "fail");
				$checked['Soap Library']= ($phpinfo['soap']['Soap Client']=="enabled" ? "pass" : "fail");
			
				//Check php.ini settings
				$checked['allow_call_time_pass_reference in Php.ini must be turned On']=($phpinfo['Core']['allow_call_time_pass_reference']=="On" ? "pass" : "fail");
				$checked['magic_quotes_gpc in Php.ini must be turned Off']=($phpinfo['Core']['magic_quotes_gpc']=="Off" ? "pass" : "fail");
				$checked['register_globals in Php.ini must be turned Off']=($phpinfo['Core']['register_globals']=="Off" ? "pass" : "fail");
				$checked['short_open_tag in Php.ini must be turned On']=($phpinfo['Core']['short_open_tag']=="On" ? "pass" : "fail");
			
				
				//Check folder permissions
				$checked['/photos folder must be writeable']= (is_writable(__DOCROOT__.__SUBDIRECTORY__.'/photos') ? "pass" : "fail");
				$checked['/includes/qcodo/cache folder must be writeable']= (is_writable(__DOCROOT__.__SUBDIRECTORY__.'/includes/qcodo/cache') ? "pass" : "fail");
				if(is_writable(__DOCROOT__.__SUBDIRECTORY__.'/includes/qcodo/cache'))
				{
					//Because we create these, don't bother checking unless the parent is writable
					$checked['/includes/qcodo/cache/soap folder must be writeable']= (is_writable(__DOCROOT__.__SUBDIRECTORY__.'/includes/qcodo/cache/soap') ? "pass" : "fail");
					$checked['/includes/qcodo/cache/state folder must be writeable']= (is_writable(__DOCROOT__.__SUBDIRECTORY__.'/includes/qcodo/cache/state') ? "pass" : "fail");
				}
			
				return $checked;
			}	
			protected function xls_check_upgrades()
			{ 
				$checked=array();
				$checked['<b>--Upgrade Check RESULTS BELOW--</b>']= "pass";
				
				//Have we run the Upgrade Database to add new fields to the database?				
				$result = _dbx_first_cell("select `key` from xlsws_configuration where `key`='SESSION_HANDLER'");
				$checked['Upgrade Database command has been run from Admin Panel'] = ($result=="SESSION_HANDLER" ? "pass" : "fail");								
				//Have new 2.1 templates been added
				$template = _dbx_first_cell("select `value` from xlsws_configuration where `key`='DEFAULT_TEMPLATE'");
				$checked['search_advanced.tpl.php added to your templates'] = file_exists("templates/".$template."/search_advanced.tpl.php") ? "pass" : "fail";
				$checked['slider.tpl.php added to your templates'] = file_exists("templates/".$template."/slider.tpl.php") ? "pass" : "fail";				
				$checked['promo_code.tpl.php added to your templates'] = file_exists("templates/".$template."/promo_code.tpl.php") ? "pass" : "fail";
				$checked['adv_search.png added to your templates css/images'] = file_exists("templates/".$template."/css/images/adv_search.png") ? "pass" : "fail";

				//Has CSS been updated
				$filename = "templates/".$template."/css/webstore.css";
                $handle = fopen($filename, "r");
                $contents = fread($handle, filesize($filename));
                fclose($handle);               
                $checked['products_sliber_theme_bg removed from your templates/css'] = !preg_match('/products_sliber_theme_bg/', $contents) ? "pass" : "fail";
				
				//Has configuration_inc.php either been replaced or modified correctly
				$filename = "includes/configuration.inc.php";
                $handle = fopen($filename, "r");
                $contents = fread($handle, filesize($filename));
                fclose($handle);               
                $checked['configuration.inc.php removed DEVTOOLS_CLI line'] = !preg_match('/__DEVTOOLS_CLI__/', $contents) ? "pass" : "fail";
                $checked['configuration.inc.php removed ERROR_PAGE_PATH line'] = !preg_match('/ERROR_PAGE_PATH/', $contents) ? "pass" : "fail";
                $checked['configuration.inc.php has utf8 on db connect'] = preg_match('/\'encoding\' => \'utf8\',/', $contents) ? "pass" : "fail";
             
				$checked['<b>Note: Specific template code changes are not checked.</b>']= "pass";
             
                return $checked;
			}
			protected function xls_check_file_signatures($complete=false)
			{ 
				$checked=array();
				$checked['<b>--File Signatures Check--</b>']= "pass";
				
				include("includes/signatures.php");


				$fn=unserialize($signatures);
				if(!isset($signatures)) $checked['Signature File in /includes']="fail";
				foreach($fn as $key=>$value) {
					if(!file_exists($key))
						$checked[$key] = "MISSING";
					else {
				    $hashes=array_reverse(explode(",",$value));
				    $hashfile=md5_file($key);
				    if (!in_array($hashfile,$hashes))
				        $checked[$key] = "modified";
				    elseif(_xls_version() != $versions[array_search($hashfile,$hashes)] || $complete)
				        $checked[$key] = $versions[array_search($hashfile,$hashes)];
				   } 
				}         
                return $checked;
			}

			protected function connect_db(){
				// Check that you can connect to db
					$dbhost = $this->GetControl('dbhost');
					$dbuser = $this->GetControl('dbuser');
					$dbpass = $this->GetControl('dbpass');
					$dbport = $this->GetControl('dbport');
					$db = $this->GetControl('db');
					
					
					$host = $dbhost->Text;
					
					if($dbport->Text != '')
						$host .= ":" . $dbport->Text;
					
					$link = false;
					try{
						$link = @mysql_connect( $host, $dbuser->Text , $dbpass->Text);
					}catch(Exception $e){
						//
					}
					if(!$link){
						QApplication::ExecuteJavaScript('alert(\'Cannot connect to database server with given host/user/password! Please check and try again.\');');
						return false;
					}
					
					if(!mysql_select_db($db->Text , $link)){
						QApplication::ExecuteJavaScript('alert(\'Connected to server but could not locate database called ' . $db->Text . '! Please check and try again.\');');
						return false;
					}
					
					return $link;
					
			}
			
			
			protected function mysql_query($str){
				
				if(!mysql_query($str)){
						echo '<font color=red>MySQL error : ' . mysql_error() . "in query $str </font><br/>\n";
						return false;
				}
				
				return true;
				
			}
			
			
			
			
			protected function performDBInstall($strFormId, $strControlId, $strParameter){
				
				
					$link = $this->connect_db();
					
					if(!$link){
						return;
					}
					
					$db_ok = true;
					
					$old_config_file = $content = file_get_contents("includes/configuration.inc.php");
					
					
					$docroot = $this->GetControl('docroot');
					$virtualdir = $this->GetControl('virdirectory');
					$subdir = $this->GetControl('subdir');
					$content = str_replace('//__XLSWS_DIR_CONF__'  , sprintf("define ('__DOCROOT__', '%s');\n\tdefine ('__VIRTUAL_DIRECTORY__', '%s');\n\tdefine ('__SUBDIRECTORY__', '%s');" 
							, $docroot->Text , $virtualdir->Text , $subdir->Text ) , $content);
					
					
							
							
					$dbhost = $this->GetControl('dbhost');
					$dbuser = $this->GetControl('dbuser');
					$dbpass = $this->GetControl('dbpass');
					$dbport = $this->GetControl('dbport');
					$db = $this->GetControl('db');							
					$dbencoding = $this->GetControl('dbencoding');							
					

					$tz = $this->GetControl('storetz');							
					
					$content = str_replace('//__XLSWS_DB_CONF___' , sprintf("\t\tdefine('DB_CONNECTION_1', serialize(array( 
				'adapter' => 'MySqli5',
				'server' => '%s',
				'port' => %s,
				'database' => '%s',
				'username' => '%s',
				'password' => '%s',
				'encoding' => '%s',
				'profiling' => false)));"  ,  $dbhost->Text , ($dbport->Text!='')?$dbport->Text:'null' , $db->Text , $dbuser->Text , $dbpass->Text, 'utf8') , $content);
							
					
					if(!file_put_contents("includes/configuration.inc.php" , $content)){
						QApplication::ExecuteJavaScript('alert(\'Cannot write to includes/configuration.inc.php. Please check permission.\');');
						return;
					}
					
				$charset = "utf8";
				
				
				echo "<html>";
				echo "<body>";
				echo "<pre>";
				
					
				
				
				
				
				$sql = "CREATE TABLE `xlsws_cart` (
  `rowid` int(11) NOT NULL auto_increment,
  `id_str` varchar(64) default NULL,
  `address_bill` varchar(255) default NULL,
  `address_ship` varchar(255) default NULL,
  `ship_firstname` varchar(64) default NULL,
  `ship_lastname` varchar(64) default NULL,
  `ship_company` varchar(255) default NULL,
  `ship_address1` varchar(255) default NULL,
  `ship_address2` varchar(255) default NULL,
  `ship_city` varchar(64) default NULL,
  `ship_zip` varchar(10) default NULL,
  `ship_state` varchar(16) default NULL,
  `ship_country` varchar(16) default NULL,
  `ship_phone` varchar(32) default NULL,
  `zipcode` varchar(10) default NULL,
  `contact` varchar(255) default NULL,
  `discount` double default NULL,
  `firstname` varchar(64) default NULL,
  `lastname` varchar(64) default NULL,
  `company` varchar(255) default NULL,
  `name` varchar(255) default NULL,
  `phone` varchar(64) default NULL,
  `po` varchar(64) default NULL,
  `type` mediumint(9) default NULL,
  `status` varchar(32) default NULL,
  `cost_total` double default NULL,
  `currency` varchar(3) default NULL,
  `currency_rate` double default NULL,
  `datetime_cre` datetime default NULL,
  `datetime_due` datetime default NULL,
  `datetime_posted` datetime default NULL,
  `email` varchar(255) default NULL,
  `sell_total` double default NULL,
  `printed_notes` varchar(255) default NULL,
  `shipping_method` varchar(255) default NULL,
  `shipping_module` varchar(64) default NULL,
  `shipping_data` varchar(255) default NULL,
  `shipping_cost` double default NULL,
  `shipping_sell` double default NULL,
  `payment_method` varchar(255) default NULL,
  `payment_module` varchar(64) default NULL,
  `payment_data` varchar(255) default NULL,
  `payment_amount` double default NULL,
  `fk_tax_code_id` bigint(20) default '0',
  `tax_inclusive` tinyint(1) default NULL,
  `subtotal` double default NULL,
  `tax1` double default '0',
  `tax2` double default '0',
  `tax3` double default '0',
  `tax4` double default '0',
  `tax5` double default '0',
  `total` double default NULL,
  `count` int(11) default '0',
  `downloaded` tinyint(1) default '0',
  `user` varchar(32) default NULL,
  `ip_host` varchar(255) default NULL,
  `customer_id` int(11) default NULL,
  `gift_registry` bigint(20) default NULL,
  `send_to` varchar(255) default NULL,
  `submitted` datetime default NULL,
  `modified` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `linkid` varchar(32) default NULL,
  `fk_promo_id` int(5) default NULL,  
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `id_str` (`id_str`),
  KEY `customer` (`customer_id`),
  KEY `type` (`type`),
  KEY `linkid` (`linkid`),
  KEY `fk_tax_code_id` (`fk_tax_code_id`),
  KEY `submitted` (`submitted`),
  KEY `gift_registry` (`gift_registry`),
  KEY `downloaded` (`downloaded`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				
				echo "Creating table Cart.<br/>";
				$this->mysql_query("SET NAMES utf8");
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_cart`;");
				$db_ok = $db_ok && $this->mysql_query($sql);
				
				
$sql = "CREATE TABLE `xlsws_cart_item` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `cart_id` bigint(20) NOT NULL,
  `cart_type` int(11) default '1',
  `product_id` bigint(20) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `discount` varchar(16) default NULL,
  `qty` float NOT NULL,
  `sell` double NOT NULL,
  `sell_base` double NOT NULL,
  `sell_discount` double NOT NULL,
  `sell_total` double NOT NULL,
  `serial_numbers` varchar(255) default NULL,
  `gift_registry_item` bigint(20) default NULL,
  `datetime_added` datetime NOT NULL,
  `datetime_mod` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rowid`),
  KEY `cart_id` (`cart_id`),
  KEY `code` (`code`),
  KEY `product_id` (`product_id`),
  KEY `gift_registry_item` (`gift_registry_item`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				
				echo "Creating table Cart Items.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_cart_item`;");
				$db_ok = $db_ok && $this->mysql_query($sql);
				
				
				
				$sql = "CREATE TABLE `xlsws_category` (
  `rowid` int(11) NOT NULL auto_increment,
  `name` varchar(64) default NULL,
  `parent` int(11) default NULL,
  `position` int(11) NOT NULL,
  `child_count` int(11) default '1',
  `custom_page` varchar(64) default NULL,
  `image_id` bigint(20) default NULL,
  `meta_keywords` varchar(255) default NULL,
  `meta_description` varchar(255) default NULL,
  `created` datetime default NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rowid`),
  KEY `name` (`name`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				
				echo "Creating table Category.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_category`;");
				$db_ok = $db_ok && $this->mysql_query($sql);
				
				

				
				
				
				
				
				
				
				
				
				
				
				
				
				
				$sql = "CREATE TABLE `xlsws_configuration` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `title` varchar(64) NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` mediumtext NOT NULL,
  `helper_text` varchar(255) NOT NULL,
  `configuration_type_id` int(11) NOT NULL default '0',
  `sort_order` int(5) default NULL,
  `modified` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created` datetime default NULL,
  `options` varchar(255) default NULL,
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `key` (`key`),
  KEY `configuration_type_id` (`configuration_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				
				echo "Creating table Configration.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_configuration`;");
				$db_ok = $db_ok && $this->mysql_query($sql);

				$sql = array();

$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Image Store Location', 'IMAGE_STORE', 'FS', 'Where images are stored? Database or FileSystem on web server?', 17, 8, NOW(), NOW(), 'STORE_IMAGE_LOCATION');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Authorized IPs For Web Store Admin (USE WITH CAUTION)', 'LSAUTH_IPS', '', 'List of IP Addresses (comma seperated) which are allowed to administer this server. NOTE: DO NOT USE THIS OPTION IF YOU DO NOT HAVE A STATIC IP ADDRESS', 16, 4, NOW(), NOW(), '');";
//$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Moderate Customer Registration', 'MODERATE_REGISTRATION', '', 'If enabled, customer registrations will need to be moderated before they are approved', 3, 1, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Disable Cart', 'DISABLE_CART', '', 'If selected, products will only be shown but not sold', 4, 4, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Default Language', 'LANGUAGE_DEFAULT', 'EN', '', 15, 1, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Default Currency', 'CURRENCY_DEFAULT', 'USD', '', 15, 7, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Languages', 'LANGUAGES', 'EN,ES,FR', '', 3, 3, NOW(), NOW(), NULL);";
//$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Newsletter', 'NEWSLETTER_DEFAULT', '1', 'Subscribe new customer to newsletter by default', 3, 6, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Phone Types', 'PHONE_TYPES', 'work,home,mobile,work fax,home fax', 'Options phone types in Additional Contact Info', 3, 5, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'SMTP Server', 'EMAIL_SMTP_SERVER', 'localhost', 'SMTP Server to send emails', 5, 4, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Minimum Password Length', 'MIN_PASSWORD_LEN', '6', 'Minimum password length', 3, 3, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Store Email', 'EMAIL_FROM', 'you@yourdomain.com', 'From which address emails will be sent', 2, 3, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Store Name', 'STORE_NAME', 'LightSpeed Web Store', 'Name of your store store', 2, 1, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'BCC Address', 'EMAIL_BCC', ' ', 'Enter an email address here if you would like to get BCCed on all emails sent by the webstore.', 5, 2, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Email Signature', 'EMAIL_SIGNATURE', 'Thank you, LightSpeed Web Store', 'Email signature for all outgoing emails', 5, 3, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Enable Wish List', 'ENABLE_GIFT_REGISTRY', '1', '', 7, 1, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Enable SRO', 'ENABLE_SRO', '0', '', 6, 4, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Date Format', 'DATE_FORMAT', 'm/d/Y', 'The date format to be used in store. Please see http://www.php.net/date for more information', 15, 3, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'SRO Additional Items', 'SRO_ADDITIONAL_ITEMS', 'Mouse,Keyboard,Cables', 'Options in Additional Items field. Enter values separated by comma', 6, 5, NOW(), NOW(), '');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'SRO Warranty options', 'SRO_WARRANTY_OPTIONS', 'None,6 Months,12 Months,24 Months,36 Months', 'Options in Warranty field. Enter values separated by comma.', 6, 5, NOW(), NOW(), '');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Default Expiry Days', 'DEFAULT_EXPIRY_GIFT_REGISTRY', '30', 'Default number of days for gift registry expiry', 7, 2, NOW(), NOW(), '');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Admin Email', 'ADMIN_EMAIL', 'you@yourdomain.com', 'The administrator email address used for administrative access', 1, 5, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Enable Families?', 'ENABLE_FAMILIES', '', '', 8, 5, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Products Per Page', 'PRODUCTS_PER_PAGE', '8', 'Number of products per page to display in product listing or search', 8, 3, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Products Sorting', 'PRODUCT_SORT_FIELD', 'Code', 'By which field products will sorted in result', 8, 4, NOW(), NOW(), 'PRODUCT_SORT');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Order From', 'ORDER_FROM', 'you@yourdomain.com', 'Order email address from which order notification is sent. This email address also gets the notification of the order', 5, 1, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Allow Guest Checkout', 'ALLOW_GUEST_CHECKOUT', '1', 'Allow customer to checkout as guest?', 3, 2, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Low Inventory Threshold', 'INVENTORY_LOW_THRESHOLD', '3', 'If inventory of a product is below this quantity, Low inventory threshold title will be displayed in place of inventory value.', 11, 7, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Available Inventory Message', 'INVENTORY_AVAILABLE', 'Available', 'This text will be shown when product is available for shipping. This value will only be shown if you choose Display Inventory Level in place of actual inventory value', 11, 5, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Zero or Negative Inventory Message', 'INVENTORY_ZERO_NEG_TITLE', 'This item is not currently available', 'This text will be shown in place of showing 0 or negative inventory when you choose Display Inventory Level', 11, 4, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Display Empty Categories?', 'DISPLAY_EMPTY_CATEGORY', '', 'Show categories that have no child category or images?', 8, 6, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Display Inventory Level', 'INVENTORY_DISPLAY_LEVEL', '1', 'Display inventory messages (see below) in place of actual inventory amounts', 11, 2, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Display Inventory', 'INVENTORY_DISPLAY', '1', 'Display the total number of items in inventory?', 11, 1, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Low Inventory Message', 'INVENTORY_LOW_TITLE', 'There is low inventory for this item', 'If inventory of a product is below the low threshold, this text will be shown.', 11, 6, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Use Total Inventory Count', 'INVENTORY_FIELD_TOTAL', '1', 'If selected yes, the inventory figure shown will be that of  available, reserved and inventory in warehouses. If no, only that of available in store will be shown', 11, 3, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Non Stocked Item Display Message', 'INVENTORY_NON_TITLE', 'Available on request', 'Title to be shown for products that are not normally stocked', 11, 8, NOW(), NOW(), '');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Only Ship To Defined Destinations', 'SHIP_RESTRICT_DESTINATION', '0', 'If selected yes, web shopper can only choose addresses in defined Destinations. See Destinations for more information', 9, 2, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Listing Image Width', 'LISTING_IMAGE_WIDTH', '90', 'Product Listing Image Width. Comes in search or category listing page', 17, 1, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Listing Image Height', 'LISTING_IMAGE_HEIGHT', '90', 'Product Listing Image Height. Comes in search or category listing page', 17, 2, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Detail Image Width', 'DETAIL_IMAGE_WIDTH', '256', 'Product Detail Page Image Width. When the product is being viewed in the product detail page.', 17, 5, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Detail Image Height', 'DETAIL_IMAGE_HEIGHT', '256', 'Product Detail Page Image Height. When the product is being viewed in the product detail page.', 17, 6, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Product Size Label', 'PRODUCT_SIZE_LABEL', 'Size', 'Rename Size Option of LightSpeed to this', 8, 2, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Product Color Label', 'PRODUCT_COLOR_LABEL', 'Color', 'Rename Color Option of LightSpeed to this', 8, 1, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Mini Image Width', 'MINI_IMAGE_WIDTH', '30', 'Mini Cart Image Width. For images in the mini cart for every page.', 17, 3, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Mini Image Height', 'MINI_IMAGE_HEIGHT', '30', 'Mini Cart Image Height. For images in the mini cart for every page.', 17, 4, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Tax Inclusive Pricing', 'TAX_INCLUSIVE_PRICING', '', 'If selected yes, all prices will be shown tax inclusive in webstore.', 15, 6, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Browser Encoding', 'ENCODING', 'UTF-8', 'What character encoding would you like to use for your visitors?  UTF-8 should be normal for all users.', 15, 10, NOW(), NOW(), 'ENCODING');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Web Store Time Zone', 'TIMEZONE', 'Canada/Eastern', 'The timezone in which your Web Store should display and store time.', 15, 4, NOW(), NOW(), 'TIMEZONE');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Enable SSL', 'ENABLE_SSL', '', 'You must have SSL/https enabled on your site to use SSL.', 16, 2, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Number Of Hours Before Purchase Status Is Reset', 'RESET_GIFT_REGISTRY_PURCHASE_STATUS', '6', 'A visitor may add an item to cart from gift registry but may never order it. The option will reset the status to available for purchase after the specified number of hours since it was added to cart.', 7, 3, NOW(), NOW(), '');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Currency Printing Format', 'CURRENCY_FORMAT', '%n', 'Currency will be printed in this format. Please see http://www.php.net/money_format for more details.', 15, 8, NOW(), NOW(), '');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Locale', 'LOCALE', 'en_US', 'Locale for your web store. See http://www.php.net/money_format for more information', 15, 1, NOW(), NOW(), '');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Show Lightbox In Product Detail Page', 'PRODUCT_ENLARGE_SHOW_LIGHTBOX', '', 'Show lightbox for image enlarge in product detail page?', 17, 7, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Store Phone', 'STORE_PHONE', 'YOUR PHONE', 'Phone number displayed in email footer.', 2, 2, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Default Country', 'DEFAULT_COUNTRY', 'CA', 'Default country for shipping or customer registration', 15, 2, NOW(), NOW(), 'COUNTRY');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Template', 'DEFAULT_TEMPLATE', 'deluxe', 'The default template from templates directory to be used for Web Store', 0, 0, NOW(), NOW(), 'TEMPLATE');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Quote Expiry Days', 'QUOTE_EXPIRY', '30', 'Number of days before discount in quote will expire.', 4, 5, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Cart Expiry Days', 'CART_LIFE', '30', 'Number of days before ordered/process carts are deleted from the system', 4, 6, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Use SEO-Friendly URL', 'ENABLE_SEO_URL', '', 'Make your URLs search engine friendly (www.example.com/category.html instead of www.example.com/index.php?id=123)', 1, 10, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Weight Unit', 'WEIGHT_UNIT', 'lb', 'What is the weight unit used in Web Store?', 9, 2, NOW(), NOW(), 'WEIGHT');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Forward To Non-SSL When Not Required', 'SSL_NO_NEED_FORWARD', '', 'Usually SSL browsing is slow due to added encrypted data overhead. Enable this option if want the user to go to non-ssl site when ssl not required. If you always want SSL, set this option to No.', 16, 3, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Database Backup Folder', 'DB_BACKUP_FOLDER', 'db_backup/', 'The folder where database backup will be done. Please make sure this folder is not visible from the internet', 1, 15, NOW(), NOW(), '');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Allow Out-Of-Stock Products To Be Added To Cart', 'INVENTORY_OUT_ALLOW_ADD', '1', 'Whether out of stock product should be allowed to add to the cart.', 11, 9, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Dimension Unit', 'DIMENSION_UNIT', 'in', 'What is the dimension unit used in Web Store?', 9, 3, NOW(), NOW(), 'DIMENSION');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'LightSpeed Secure Key', 'LSKEY', '5f4dcc3b5aa765d61d8327deb882cf99', 'The secure key or password for administrative access to your lightspeed web store', 0, 1, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Debug Template Usage', 'DEBUG_TEMPLATE', '', 'If selected, WS will print template files it used for generating the pages in HTML raw source and in system logs.', 1, 17, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Header Image', 'HEADER_IMAGE', '/photos/defaultheader.png', 'Enter the location (relative to you Web Store install directory OR a full URL) to the header or logo image for your Web Store.', 0, 1, NOW(), NOW(), 'HEADERIMAGE');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Take Store Offline', 'STORE_OFFLINE', '', 'If selected, store will be offline.', 0, 16, NOW(), NOW(), '');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Enable HTML Email', 'HTML_EMAIL', '1', 'Enable HTML Email. If disabled, it will send text-only email.', 5, 2, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'SMTP Server Port', 'EMAIL_SMTP_PORT', '25', 'SMTP Server Port', 5, 5, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'SMTP Server Username', 'EMAIL_SMTP_USERNAME', '', 'If your SMTP server requires a username, please enter it here', 5, 6, NOW(), NOW(), '');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'SMTP Server Password', 'EMAIL_SMTP_PASSWORD', '', 'If your SMTP server requires a password, please enter it here.', 5, 7, NOW(), NOW(), NULL);";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Number of decimal places used in tax calculation', 'TAX_DECIMAL', '2', 'Please specify the number of decimal places to be used in tax calculation. This should be the same as the number of decimal places your currency format is shown as. ', 0, 9, NOW(), NOW(), NULL);";				
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Allow Qty-purchase in fraction', 'QTY_FRACTION_PURCHASE', '0', 'If enabled, customers will be able to purchase items in fractions. E.g. 0.5 of an item can ordered by a customer.', 0, 10, NOW(), NOW(), 'BOOL');";				
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Cache category', 'CACHE_CATEGORY', '0', 'If you have a large category tree and large product database, you may gain performance by caching the category tree parsing. ', 8, 6, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Show products in Sitemap', 'SITEMAP_SHOW_PRODUCTS', '0', 'Enable this option if you want to show products in your sitemap page. If you have a very large product database, we recommend you turn off this option', 8, 7, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Next Order Id',  'NEXT_ORDER_ID',  '12000',  'What is the next order id webstore will use? This value will incremented at every order submission.',  '15',  '11', NOW( ) , NOW( ), 'PINT');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Add taxes for shipping fees', 'SHIPPING_TAXABLE', '0', 'Enable this option if you want taxes to be calculated for shipping fees and applied to the total.', 9, 7, NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Ignore line breaks in long description', 'HTML_DESCRIPTION', '0', 'If you are utilizing HTML primarily within your web long descriptions, you may want this option on', 8,8 , NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Hide price of matrix master product', 'MATRIX_PRICE', '0', 'If you do not want to show the price of your master product in a size/color matrix, turn this option on', 8,9 , NOW(), NOW(), 'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Session storage', 'SESSION_HANDLER', 'DB', 'Store sessions in the database or file system?', 1, 6, NOW(), NOW(), 'STORE_IMAGE_LOCATION');";
$sql[]= "INSERT into `xlsws_configuration` VALUES (NULL,'Show child products in search results', 'CHILD_SEARCH', '','If you want child products from a size color matrix to show up in search results, enable this option',8,10,NOW(),NOW(),'BOOL');";
$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Security mode for outbound SMTP',  'EMAIL_SMTP_SECURITY_MODE',  '0',  'Automatic based on SMTP Port, or force security.',  '5',  '8', NOW() , NOW(), 'EMAIL_SMTP_SECURITY_MODE');";

//$sql[]= "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Debug LightSpeed Soap Call', 'DEBUG_LS_SOAP_CALL', '1', 'If selected, all soap calls will be logged in the database. It is advised that you do not enable this unless advised by XSilva', 1, 16, NOW(), NOW(), 'BOOL');";
				
				echo "Entering Configuration values<br/>";

				foreach($sql as $s)
					$db_ok = $db_ok && $this->mysql_query($s);
					
				
					
				$sql ="CREATE TABLE `xlsws_country` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `code` char(2) NOT NULL default '',
  `code_A3` char(3) NOT NULL default '',
  `region` char(2) NOT NULL default '',
  `avail` char(1) NOT NULL default 'Y',
  `sort_order` int(11) default '10',
  `country` varchar(255) NOT NULL,
  `zip_validate_preg` varchar(255) NULL default '',
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `code` (`code`),
  KEY `avail` (`avail`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				
				echo "Creating country Table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_country`;");
				$db_ok = $db_ok && $this->mysql_query($sql);			

				

				$sql = array();
				
				
$sql[] = "INSERT INTO `xlsws_country` VALUES (1, 'AF', 'AFG', 'AS', 'Y', 100, 'Afghanistan', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (2, 'AL', 'ALB', 'EU', 'N', 10, 'Albania', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (3, 'DZ', 'DZA', 'AF', 'Y', 10, 'Algeria', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (4, 'AS', 'ASM', 'AU', 'Y', 10, 'American Samoa', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (6, 'AO', 'AGO', 'AF', 'Y', 10, 'Angola', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (7, 'AI', 'AIA', 'LA', 'Y', 10, 'Anguilla', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (8, 'AQ', 'ATA', 'AN', 'Y', 10, 'Antarctica', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (9, 'AG', 'ATG', 'LA', 'Y', 10, 'Antigua and Barbuda', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (10, 'AR', 'ARG', 'LA', 'Y', 10, 'Argentina', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (11, 'AM', 'ARM', 'AS', 'Y', 10, 'Armenia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (12, 'AW', 'ABW', 'LA', 'Y', 10, 'Aruba', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (13, 'AU', 'AUS', 'AU', 'Y', 4, 'Australia', '/\\\\d{4}/');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (14, 'AT', 'AUT', 'EU', 'Y', 10, 'Austria', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (15, 'AZ', 'AZE', 'AS', 'Y', 10, 'Azerbaijan', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (16, 'BS', 'BHS', 'LA', 'Y', 10, 'Bahamas', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (17, 'BH', 'BHR', 'AS', 'Y', 10, 'Bahrain', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (18, 'BD', 'BGD', 'AS', 'Y', 10, 'Bangladesh', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (19, 'BB', 'BRB', 'LA', 'Y', 10, 'Barbados', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (20, 'BY', 'BLR', 'EU', 'Y', 10, 'Belarus', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (21, 'BE', 'BEL', 'EU', 'Y', 10, 'Belgium', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (22, 'BZ', 'BLZ', 'LA', 'Y', 10, 'Belize', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (23, 'BJ', 'BEN', 'AF', 'Y', 10, 'Benin', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (24, 'BM', 'BMU', 'LA', 'Y', 10, 'Bermuda', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (25, 'BT', 'BTN', 'AS', 'Y', 10, 'Bhutan', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (26, 'BO', 'BOL', 'LA', 'Y', 10, 'Bolivia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (27, 'BA', 'BIH', 'EU', 'Y', 10, 'Bosnia and Herzegowina', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (28, 'BW', 'BWA', 'AF', 'Y', 10, 'Botswana', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (29, 'BV', 'BVT', 'AN', 'Y', 10, 'Bouvet Island', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (30, 'BR', 'BRA', 'LA', 'Y', 10, 'Brazil', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (31, 'IO', 'IOT', 'AS', 'Y', 10, 'British Indian Ocean Territory', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (32, 'VG', 'VGB', 'LA', 'Y', 10, 'British Virgin Islands', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (33, 'BN', 'BRN', 'AS', 'Y', 10, 'Brunei Darussalam', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (34, 'BG', 'BGR', 'EU', 'Y', 10, 'Bulgaria', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (35, 'BF', 'BFA', 'AF', 'Y', 10, 'Burkina Faso', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (36, 'BI', 'BDI', 'AF', 'Y', 10, 'Burundi', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (37, 'KH', 'KHM', 'AS', 'Y', 10, 'Cambodia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (38, 'CM', 'CMR', 'AF', 'Y', 10, 'Cameroon', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (39, 'CA', 'CAN', 'NA', 'Y', 2, 'Canada', '/^[ABCEGHJKLMNPRSTVXY]\\\\d[A-Z]( )?\\\\d[A-Z]\\\\d$/');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (40, 'CV', 'CPV', 'AF', 'Y', 10, 'Cape Verde', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (41, 'KY', 'CYM', 'LA', 'Y', 10, 'Cayman Islands', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (42, 'CF', 'CAF', 'AF', 'Y', 10, 'Central African Republic', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (43, 'TD', 'TCD', 'AF', 'Y', 10, 'Chad', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (44, 'CL', 'CHL', 'LA', 'Y', 10, 'Chile', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (45, 'CN', 'CHN', 'AS', 'Y', 10, 'China', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (46, 'CX', 'CXR', 'AU', 'Y', 10, 'Christmas Island', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (47, 'CC', 'CCK', 'AU', 'Y', 10, 'Cocos (Keeling) Islands', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (48, 'CO', 'COL', 'LA', 'Y', 10, 'Colombia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (49, 'KM', 'COM', 'AF', 'Y', 10, 'Comoros', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (50, 'CG', 'COG', 'AF', 'Y', 10, 'Congo', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (51, 'CK', 'COK', 'AU', 'Y', 10, 'Cook Islands', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (52, 'CR', 'CRI', 'LA', 'Y', 10, 'Costa Rica', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (53, 'CI', 'CIV', 'AF', 'Y', 10, 'Cote D''ivoire', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (54, 'HR', 'HRV', 'EU', 'Y', 10, 'Croatia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (55, 'CU', 'CUB', 'LA', 'Y', 10, 'Cuba', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (56, 'CY', 'CYP', 'EU', 'Y', 10, 'Cyprus', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (57, 'CZ', 'CZE', 'EU', 'Y', 10, 'Czech Republic', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (58, 'DK', 'DNK', 'EU', 'Y', 10, 'Denmark', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (59, 'DJ', 'DJI', 'AF', 'Y', 10, 'Djibouti', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (60, 'DM', 'DMA', 'LA', 'Y', 10, 'Dominica', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (61, 'DO', 'DOM', 'LA', 'Y', 10, 'Dominican Republic', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (62, 'TP', '', 'AS', 'Y', 10, 'East Timor', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (63, 'EC', 'ECU', 'LA', 'Y', 10, 'Ecuador', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (64, 'EG', 'EGY', 'AF', 'Y', 10, 'Egypt', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (65, 'SV', 'SLV', 'LA', 'Y', 10, 'El Salvador', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (66, 'GQ', 'GNQ', 'AF', 'Y', 10, 'Equatorial Guinea', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (67, 'ER', 'ERI', 'AF', 'Y', 10, 'Eritrea', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (68, 'EE', 'EST', 'EU', 'Y', 10, 'Estonia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (69, 'ET', 'ETH', 'AF', 'Y', 10, 'Ethiopia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (70, 'FK', 'FLK', 'LA', 'Y', 10, 'Falkland Islands (Malvinas)', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (71, 'FO', 'FRO', 'EU', 'Y', 10, 'Faroe Islands', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (72, 'FJ', 'FJI', 'AU', 'Y', 10, 'Fiji', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (73, 'FI', 'FIN', 'EU', 'Y', 10, 'Finland', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (74, 'FR', 'FRA', 'EU', 'Y', 10, 'France', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (75, 'FX', 'FXX', 'EU', 'Y', 10, 'France, Metropolitan', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (76, 'GF', 'GUF', 'LA', 'Y', 10, 'French Guiana', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (77, 'PF', 'PYF', 'AU', 'Y', 10, 'French Polynesia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (78, 'TF', 'ATF', 'AN', 'Y', 10, 'French Southern Territories', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (79, 'GA', 'GAB', 'AF', 'Y', 10, 'Gabon', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (80, 'GE', 'GEO', 'AS', 'Y', 10, 'Georgia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (81, 'GM', 'GMB', 'AF', 'Y', 10, 'Gambia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (82, 'PS', 'PSE', 'AS', 'Y', 10, 'Palestine Authority', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (83, 'DE', 'DEU', 'EU', 'Y', 10, 'Germany', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (84, 'GH', 'GHA', 'AF', 'Y', 10, 'Ghana', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (85, 'GI', 'GIB', 'EU', 'Y', 10, 'Gibraltar', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (86, 'GR', 'GRC', 'EU', 'Y', 10, 'Greece', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (87, 'GL', 'GRL', 'NA', 'Y', 10, 'Greenland', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (88, 'GD', 'GRD', 'LA', 'Y', 10, 'Grenada', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (89, 'GP', 'GLP', 'LA', 'Y', 10, 'Guadeloupe', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (90, 'GU', 'GUM', 'AU', 'Y', 10, 'Guam', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (91, 'GT', 'GTM', 'LA', 'Y', 10, 'Guatemala', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (92, 'GN', 'GIN', 'AF', 'Y', 10, 'Guinea', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (93, 'GW', 'GNB', 'AF', 'Y', 10, 'Guinea-Bissau', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (94, 'GY', 'GUY', 'LA', 'Y', 10, 'Guyana', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (95, 'HT', 'HTI', 'LA', 'Y', 10, 'Haiti', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (96, 'HM', 'HMD', 'AU', 'Y', 10, 'Heard and McDonald Islands', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (97, 'HN', 'HND', 'LA', 'Y', 10, 'Honduras', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (98, 'HK', 'HKG', 'AS', 'Y', 10, 'Hong Kong', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (99, 'HU', 'HUN', 'EU', 'Y', 10, 'Hungary', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (100, 'IS', 'ISL', 'EU', 'Y', 10, 'Iceland', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (101, 'IN', 'IND', 'AS', 'Y', 10, 'India', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (102, 'ID', 'IDN', 'AS', 'Y', 10, 'Indonesia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (103, 'IQ', 'IRQ', 'AS', 'Y', 10, 'Iraq', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (104, 'IE', 'IRL', 'EU', 'Y', 10, 'Ireland', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (105, 'IR', 'IRN', 'AS', 'Y', 10, 'Iran', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (106, 'IL', 'ISR', 'AS', 'Y', 10, 'Israel', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (107, 'IT', 'ITA', 'EU', 'Y', 10, 'Italy', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (108, 'JM', 'JAM', 'LA', 'Y', 10, 'Jamaica', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (109, 'JP', 'JPN', 'AS', 'Y', 10, 'Japan', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (110, 'JO', 'JOR', 'AS', 'Y', 10, 'Jordan', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (111, 'KZ', 'KAZ', 'AS', 'Y', 10, 'Kazakhstan', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (112, 'KE', 'KEN', 'AF', 'Y', 10, 'Kenya', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (113, 'KI', 'KIR', 'AU', 'Y', 10, 'Kiribati', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (114, 'KP', 'PRK', 'AS', 'Y', 10, 'Korea', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (115, 'KR', 'KOR', 'AS', 'Y', 10, 'Korea, Republic of', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (116, 'KW', 'KWT', 'AS', 'Y', 10, 'Kuwait', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (117, 'KG', 'KGZ', 'AS', 'Y', 10, 'Kyrgyzstan', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (118, 'LA', 'LAO', 'AS', 'Y', 10, 'Laos', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (119, 'LV', 'LVA', 'EU', 'Y', 10, 'Latvia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (120, 'LB', 'LBN', 'AS', 'Y', 10, 'Lebanon', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (121, 'LS', 'LSO', 'AF', 'Y', 10, 'Lesotho', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (122, 'LR', 'LBR', 'AF', 'Y', 10, 'Liberia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (123, 'LY', 'LBY', 'AF', 'Y', 10, 'Libyan Arab Jamahiriya', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (124, 'LI', 'LIE', 'EU', 'Y', 10, 'Liechtenstein', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (125, 'LT', 'LTU', 'EU', 'Y', 10, 'Lithuania', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (126, 'LU', 'LUX', 'EU', 'Y', 10, 'Luxembourg', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (127, 'MO', 'MAC', 'AS', 'Y', 10, 'Macau', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (128, 'MK', 'MKD', 'EU', 'Y', 10, 'Macedonia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (129, 'MG', 'MDG', 'AF', 'Y', 10, 'Madagascar', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (130, 'MW', 'MWI', 'AF', 'Y', 10, 'Malawi', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (131, 'MY', 'MYS', 'AS', 'Y', 10, 'Malaysia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (132, 'MV', 'MDV', 'AS', 'Y', 10, 'Maldives', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (133, 'ML', 'MLI', 'AF', 'Y', 10, 'Mali', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (134, 'MT', 'MLT', 'EU', 'Y', 10, 'Malta', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (135, 'MH', 'MHL', 'AU', 'Y', 10, 'Marshall Islands', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (136, 'MQ', 'MTQ', 'LA', 'Y', 10, 'Martinique', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (137, 'MR', 'MRT', 'AF', 'Y', 10, 'Mauritania', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (138, 'MU', 'MUS', 'AF', 'Y', 10, 'Mauritius', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (139, 'YT', 'MYT', 'AF', 'Y', 10, 'Mayotte', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (140, 'MX', 'MEX', 'LA', 'Y', 10, 'Mexico', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (141, 'FM', 'FSM', 'AU', 'Y', 10, 'Micronesia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (142, 'MD', 'MDA', 'EU', 'Y', 10, 'Moldova, Republic of', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (143, 'MC', 'MCO', 'EU', 'Y', 10, 'Monaco', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (144, 'MN', 'MNG', 'AS', 'Y', 10, 'Mongolia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (145, 'MS', 'MSR', 'LA', 'Y', 10, 'Montserrat', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (146, 'MA', 'MAR', 'AF', 'Y', 10, 'Morocco', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (147, 'MZ', 'MOZ', 'AF', 'Y', 10, 'Mozambique', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (148, 'MM', 'MMR', 'AS', 'Y', 10, 'Myanmar', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (149, 'NA', 'NAM', 'AF', 'Y', 10, 'Namibia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (150, 'NR', 'NRU', 'AU', 'Y', 10, 'Nauru', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (151, 'NP', 'NPL', 'AS', 'Y', 10, 'Nepal', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (152, 'NL', 'NLD', 'EU', 'Y', 10, 'Netherlands', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (153, 'AN', 'ANT', 'LA', 'Y', 10, 'Netherlands Antilles', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (154, 'NC', 'NCL', 'AU', 'Y', 10, 'New Caledonia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (155, 'NZ', 'NZL', 'AU', 'Y', 10, 'New Zealand', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (156, 'NI', 'NIC', 'LA', 'Y', 10, 'Nicaragua', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (157, 'NE', 'NER', 'AF', 'Y', 10, 'Niger', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (158, 'NG', 'NGA', 'AF', 'Y', 10, 'Nigeria', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (159, 'NU', 'NIU', 'AU', 'Y', 10, 'Niue', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (160, 'NF', 'NFK', 'AU', 'Y', 10, 'Norfolk Island', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (161, 'MP', 'MNP', 'AU', 'Y', 10, 'Northern Mariana Islands', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (162, 'NO', 'NOR', 'EU', 'Y', 10, 'Norway', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (163, 'OM', 'OMN', 'AS', 'Y', 10, 'Oman', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (164, 'PK', 'PAK', 'AS', 'Y', 10, 'Pakistan', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (165, 'PW', 'PLW', 'AU', 'Y', 10, 'Palau', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (166, 'PA', 'PAN', 'LA', 'Y', 10, 'Panama', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (167, 'PG', 'PNG', 'AS', 'Y', 10, 'Papua New Guinea', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (168, 'PY', 'PRY', 'LA', 'Y', 10, 'Paraguay', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (169, 'PE', 'PER', 'LA', 'Y', 10, 'Peru', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (170, 'PH', 'PHL', 'AS', 'Y', 10, 'Philippines', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (171, 'PN', 'PCN', 'AU', 'Y', 10, 'Pitcairn', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (172, 'PL', 'POL', 'EU', 'Y', 10, 'Poland', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (173, 'PT', 'PRT', 'EU', 'Y', 10, 'Portugal', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (174, 'PR', 'PRI', 'LA', 'Y', 10, 'Puerto Rico', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (175, 'QA', 'QAT', 'AS', 'Y', 10, 'Qatar', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (176, 'RE', 'REU', 'AF', 'Y', 10, 'Reunion', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (177, 'RO', 'ROU', 'EU', 'Y', 10, 'Romania', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (178, 'RU', 'RUS', 'EU', 'Y', 10, 'Russian Federation', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (179, 'RW', 'RWA', 'AF', 'Y', 10, 'Rwanda', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (180, 'LC', 'LCA', 'LA', 'Y', 10, 'Saint Lucia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (181, 'WS', 'WSM', 'AU', 'Y', 10, 'Samoa', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (182, 'SM', 'SMR', 'EU', 'Y', 10, 'San Marino', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (183, 'ST', 'STP', 'AF', 'Y', 10, 'Sao Tome and Principe', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (184, 'SA', 'SAU', 'AS', 'Y', 10, 'Saudi Arabia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (185, 'SN', 'SEN', 'AF', 'Y', 10, 'Senegal', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (186, 'SC', 'SYC', 'AF', 'Y', 10, 'Seychelles', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (187, 'SL', 'SLE', 'AF', 'Y', 10, 'Sierra Leone', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (188, 'SG', 'SGP', 'AS', 'Y', 10, 'Singapore', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (189, 'SK', 'SVK', 'EU', 'Y', 10, 'Slovakia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (190, 'SI', 'SVN', 'EU', 'Y', 10, 'Slovenia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (191, 'SB', 'SLB', 'AU', 'Y', 10, 'Solomon Islands', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (192, 'SO', 'SOM', 'AF', 'Y', 10, 'Somalia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (193, 'ZA', 'ZAF', 'AF', 'Y', 10, 'South Africa', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (194, 'ES', 'ESP', 'EU', 'Y', 10, 'Spain', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (195, 'LK', 'LKA', 'AS', 'Y', 10, 'Sri Lanka', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (196, 'SH', 'SHN', 'AF', 'Y', 10, 'St. Helena', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (197, 'KN', 'KNA', 'LA', 'Y', 10, 'St. Kitts and Nevis', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (198, 'PM', 'SPM', 'NA', 'Y', 10, 'St. Pierre and Miquelon', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (199, 'VC', 'VCT', 'LA', 'Y', 10, 'St. Vincent and the Grenadines', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (200, 'SD', 'SDN', 'AF', 'Y', 10, 'Sudan', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (201, 'SR', 'SUR', 'LA', 'Y', 10, 'Suriname', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (202, 'SJ', 'SJM', 'EU', 'Y', 10, 'Svalbard and Jan Mayen Islands', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (203, 'SZ', 'SWZ', 'AF', 'Y', 10, 'Swaziland', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (204, 'SE', 'SWE', 'EU', 'Y', 10, 'Sweden', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (205, 'CH', 'CHE', 'EU', 'Y', 10, 'Switzerland', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (206, 'SY', 'SYR', 'AS', 'Y', 10, 'Syrian Arab Republic', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (207, 'TW', 'TWN', 'AS', 'Y', 10, 'Taiwan', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (208, 'TJ', 'TJK', 'AS', 'Y', 10, 'Tajikistan', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (209, 'TZ', 'TZA', 'AF', 'Y', 10, 'Tanzania, United Republic of', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (210, 'TH', 'THA', 'AS', 'Y', 10, 'Thailand', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (211, 'TG', 'TGO', 'AF', 'Y', 10, 'Togo', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (212, 'TK', 'TKL', 'AU', 'Y', 10, 'Tokelau', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (213, 'TO', 'TON', 'AU', 'Y', 10, 'Tonga', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (214, 'TT', 'TTO', 'LA', 'Y', 10, 'Trinidad and Tobago', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (215, 'TN', 'TUN', 'AF', 'Y', 10, 'Tunisia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (216, 'TR', 'TUR', 'EU', 'Y', 10, 'Turkey', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (217, 'TM', 'TKM', 'AS', 'Y', 10, 'Turkmenistan', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (218, 'TC', 'TCA', 'LA', 'Y', 10, 'Turks and Caicos Islands', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (219, 'TV', 'TUV', 'AU', 'Y', 10, 'Tuvalu', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (220, 'UG', 'UGA', 'AF', 'Y', 10, 'Uganda', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (221, 'UA', 'UKR', 'EU', 'Y', 10, 'Ukraine', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (222, 'AE', 'ARE', 'AS', 'Y', 10, 'United Arab Emirates', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (223, 'GB', 'GBR', 'EU', 'Y', 3, 'United Kingdom (Great Britain)', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (224, 'US', 'USA', 'NA', 'Y', 1, 'United States', '/^\\\\d{5}(-\\\\d{4})?$/');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (225, 'VI', 'VIR', 'LA', 'Y', 10, 'United States Virgin Islands', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (226, 'UY', 'URY', 'LA', 'Y', 10, 'Uruguay', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (227, 'UZ', 'UZB', 'AS', 'Y', 10, 'Uzbekistan', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (228, 'VU', 'VUT', 'AU', 'Y', 10, 'Vanuatu', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (229, 'VA', 'VAT', 'EU', 'Y', 10, 'Vatican City State', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (230, 'VE', 'VEN', 'LA', 'Y', 10, 'Venezuela', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (231, 'VN', 'VNM', 'AS', 'Y', 10, 'Viet Nam', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (232, 'WF', 'WLF', 'AU', 'Y', 10, 'Wallis And Futuna Islands', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (233, 'EH', 'ESH', 'AF', 'Y', 10, 'Western Sahara', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (234, 'YE', 'YEM', 'AS', 'Y', 10, 'Yemen', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (235, 'CS', 'SCG', 'EU', 'Y', 10, 'Serbia and Montenegro', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (236, 'ZR', 'ZAR', 'AF', 'Y', 10, 'Zaire', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (237, 'ZM', 'ZMB', 'AF', 'Y', 10, 'Zambia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (238, 'ZW', 'ZWE', 'AF', 'Y', 10, 'Zimbabwe', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (239, 'AP', '', '', 'Y', 10, 'Asia-Pacific', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (240, 'RS', '', '', 'Y', 10, 'Republic of Serbia', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (241, 'AX', '', '', 'Y', 10, 'Aland Islands', '');";
$sql[] = "INSERT INTO `xlsws_country` VALUES (242, 'EU', '', '', 'Y', 10, 'Europe', '');";				
				
				


				echo "Installing Countries<br/>";

				foreach($sql as $s)
					$db_ok = $db_ok && $this->mysql_query($s);


				
					
				$sql = "CREATE TABLE `xlsws_credit_card` (
  `rowid` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `length` varchar(16) NOT NULL,
  `prefix` varchar(64) NOT NULL,
  `sort_order` int(11) NOT NULL default '0',
  `enabled` tinyint(1) NOT NULL,
  `validFunc` varchar(32) default NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				
					
				echo "Creating credit card types Table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_credit_card`;");
				$db_ok = $db_ok && $this->mysql_query($sql);			
				
					
				
				
				
				$sql = array();
				
$sql[] = "INSERT INTO `xlsws_credit_card` VALUES (1, 'American Express', '15', '34,37', 3, 1, '', '2009-01-21 23:44:42');";
$sql[] = "INSERT INTO `xlsws_credit_card` VALUES (2, 'Carte Blanche', '14', '300,301,302,303,304,305,36,38', 0, 1, '', '2008-10-27 16:37:31');";
$sql[] = "INSERT INTO `xlsws_credit_card` VALUES (3, 'Diners Club', '14', '300,301,302,303,304,305,36,38', 0, 1, '', '2008-10-27 16:37:31');";
$sql[] = "INSERT INTO `xlsws_credit_card` VALUES (4, 'Discover', '16', '6011', 0, 1, '', '2008-10-27 16:37:31');";
$sql[] = "INSERT INTO `xlsws_credit_card` VALUES (5, 'Enroute', '15', '2014,2149', 0, 1, '', '2008-10-27 16:37:31');";
$sql[] = "INSERT INTO `xlsws_credit_card` VALUES (6, 'JCB', '15,16', '3,1800,2131', 0, 1, '', '2008-10-27 16:37:31');";
$sql[] = "INSERT INTO `xlsws_credit_card` VALUES (7, 'Maestro', '16,18', '5020,6', 1, 1, '', '2008-11-08 04:15:38');";
$sql[] = "INSERT INTO `xlsws_credit_card` VALUES (8, 'MasterCard', '16', '51,52,53,54,55', 0, 1, '', '2008-10-27 16:37:31');";
$sql[] = "INSERT INTO `xlsws_credit_card` VALUES (9, 'Solo', '16,18,19', '6334,6767', 0, 1, '', '2008-10-27 16:37:31');";
$sql[] = "INSERT INTO `xlsws_credit_card` VALUES (10, 'Switch', '16,18,19', '4903,4905,4911,4936,564182,633110,6333,6759', 0, 1, '', '2008-10-27 16:37:31');";
$sql[] = "INSERT INTO `xlsws_credit_card` VALUES (11, 'Visa', '13,16', '4', 0, 1, '', '2008-10-27 16:37:31');";
$sql[] = "INSERT INTO `xlsws_credit_card` VALUES (12, 'Visa Electron', '16', '417500,4917,4913', 0, 1, '', '2008-10-27 16:37:31');";



				echo "Installing Credit Card Types<br/>";

				foreach($sql as $s)
					$db_ok = $db_ok && $this->mysql_query($s);
				
				
						
				$sql = "CREATE TABLE `xlsws_customer` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `address1_1` varchar(255) default NULL,
  `address1_2` varchar(255) default NULL,
  `address2_1` varchar(255) default NULL,
  `address_2_2` varchar(255) default NULL,
  `city1` varchar(64) default NULL,
  `city2` varchar(64) default NULL,
  `company` varchar(255) default NULL,
  `country1` varchar(32) default NULL,
  `country2` varchar(32) default NULL,
  `currency` varchar(3) default NULL,
  `email` varchar(255) default NULL,
  `firstname` varchar(64) default NULL,
  `pricing_level` int(11) default NULL,
  `homepage` varchar(255) default NULL,
  `id_customer` varchar(32) default NULL,
  `language` varchar(8) default NULL,
  `lastname` varchar(64) default NULL,
  `mainname` varchar(255) default NULL,
  `mainphone` varchar(32) default NULL,
  `mainephonetype` varchar(8) default NULL,
  `phone1` varchar(32) default NULL,
  `phonetype1` varchar(8) default NULL,
  `phone2` varchar(32) default NULL,
  `phonetype2` varchar(8) default NULL,
  `phone3` varchar(32) default NULL,
  `phonetype3` varchar(8) default NULL,
  `phone4` varchar(32) default NULL,
  `phonetype4` varchar(8) default NULL,
  `state1` varchar(32) default NULL,
  `state2` varchar(32) default NULL,
  `type` varchar(1) default NULL,
  `user` varchar(32) default NULL,
  `zip1` varchar(16) default NULL,
  `zip2` varchar(16) default NULL,
  `newsletter_subscribe` tinyint(1) default NULL,
  `html_email` tinyint(1) default '1',
  `password` varchar(32) default NULL,
  `temp_password` varchar(32) default NULL,
  `allow_login` tinyint(1) default '1',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				
					
				echo "Creating Customer Table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_customer`;");
				$db_ok = $db_ok && $this->mysql_query($sql);			
				
								
				
				
				
				
				
				$sql = "CREATE TABLE `xlsws_custom_page` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `key` varchar(32) NOT NULL,
  `title` varchar(64) NOT NULL,
  `page` mediumtext,
  `meta_keywords` varchar(255) default NULL,
  `meta_description` varchar(255) default NULL,
  `modified` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created` datetime default NULL,
  `product_tag` varchar(255) default NULL,
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				echo "Creating Custom pages Table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_custom_page`;");
				$db_ok = $db_ok && $this->mysql_query($sql);			

				
				

				
				$sql = array();
				
				
$sql[] = "INSERT INTO `xlsws_custom_page` VALUES (1, 'intro', 'Welcome to our store', '<p>Welcome to our store. It is powered by LightSpeed.</p>', NULL, NULL, '2008-10-02 11:16:57', '2008-10-02 19:00:51', '01');";
$sql[] = "INSERT INTO `xlsws_custom_page` VALUES (3, 'top', 'Top Products', ' ', 'top products', 'top product page', '2009-01-23 01:11:50', NULL, 'a');";
$sql[] = "INSERT INTO `xlsws_custom_page` VALUES (4, 'new', 'New Products', ' ', NULL, NULL, '2009-01-02 18:48:51', NULL, 'a');";
$sql[] = "INSERT INTO `xlsws_custom_page` VALUES (5, 'promo', 'Promotions', ' ', NULL, NULL, '2009-01-02 18:50:35', NULL, 'a');";
$sql[] = "INSERT INTO `xlsws_custom_page` VALUES (6, 'about', 'About Us', 'Page coming soon.....', '', '', '2009-02-06 00:17:50', NULL, '');";
$sql[] = "INSERT INTO `xlsws_custom_page` VALUES (7, 'privacy', 'Privacy Policy', 'Page coming soon.....', NULL, NULL, '2009-01-02 18:06:01', NULL, '');";
$sql[] = "INSERT INTO `xlsws_custom_page` VALUES (8, 'tc', 'Terms and Conditions', 'Page coming soon.....', NULL, NULL, '2009-01-02 18:03:00', NULL, '');";
$sql[] = "INSERT INTO `xlsws_custom_page` VALUES (9, 'contactus', 'Contact Us', 'Page coming soon.....', NULL, NULL, '2009-01-04 10:55:04', NULL, '');";
				
		
				
				echo "Entering Custom pages<br/>";

				foreach($sql as $s)
					$db_ok = $db_ok && $this->mysql_query($s);
					
								
								
				
				
				
				
				
				
				
				$sql = "CREATE TABLE `xlsws_destination` (
  `rowid` int(11) NOT NULL auto_increment,
  `country` varchar(5) default NULL,
  `state` varchar(5) default NULL,
  `zipcode1` varchar(10) default NULL,
  `zipcode2` varchar(10) default NULL,
  `taxcode` int(11) default NULL,
  `name` varchar(32) default NULL,
  `base_charge` float default '0',
  `ship_free` float default NULL,
  `ship_rate` float default NULL,
  `modified` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rowid`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				echo "Creating Destinations Table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_destination`;");
				$db_ok = $db_ok && $this->mysql_query($sql);					
				
				
				
				$sql = "CREATE TABLE `xlsws_family` (
  `rowid` int(11) NOT NULL auto_increment,
  `family` varchar(32) NOT NULL,
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `Family` (`family`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				echo "Creating Families Table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_family`;");
				$db_ok = $db_ok && $this->mysql_query($sql);	

				
				$sql = "CREATE TABLE `xlsws_gift_registry` (
  `rowid` int(11) NOT NULL auto_increment,
  `registry_name` varchar(100) NOT NULL,
  `registry_password` varchar(100) NOT NULL,
  `registry_description` text,
  `event_date` date NOT NULL,
  `html_content` text NOT NULL,
  `ship_option` varchar(100) default NULL,
  `customer_id` int(11) NOT NULL,
  `gift_code` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `gift_code` (`gift_code`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				echo "Creating Gift Registry Table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_gift_registry`;");
				$db_ok = $db_ok && $this->mysql_query($sql);				
				
				
				
				
				$sql = "CREATE TABLE `xlsws_gift_registry_items` (
  `rowid` int(11) NOT NULL auto_increment,
  `registry_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` double NOT NULL default '1',
  `registry_status` varchar(50) default '0',
  `purchase_status` bigint(20) default '0',
  `purchased_by` varchar(100) default NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `rowid` (`rowid`,`registry_id`),
  KEY `registry_id` (`registry_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				
				echo "Creating Gift Registry Items table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_gift_registry_items`;");
				$db_ok = $db_ok && $this->mysql_query($sql);				

				
				
				$sql = "CREATE TABLE `xlsws_gift_registry_receipents` (
  `rowid` int(11) NOT NULL auto_increment,
  `registry_id` int(11) NOT NULL,
  `customer_id` int(11) default NULL,
  `receipent_name` varchar(100) NOT NULL,
  `receipent_email` varchar(100) NOT NULL,
  `email_sent` tinyint(1) default '0',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rowid`),
  KEY `registry_id` (`registry_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				
				echo "Creating Gift Registry Receipients table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_gift_registry_receipents`;");
				$db_ok = $db_ok && $this->mysql_query($sql);				

				
				
				
				$sql = "CREATE TABLE `xlsws_images` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `image_path` varchar(255) default NULL,
  `image_data` mediumblob,
  `width` mediumint(9) default NULL,
  `height` mediumint(9) default NULL,
  `checksum` varchar(32) default NULL,
  `parent` bigint(20) default NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `width` (`width`,`height`,`parent`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM";
				
				
				
				echo "Creating images table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_images`;");
				$db_ok = $db_ok && $this->mysql_query($sql);					
				
				
				
				$sql = "CREATE TABLE `xlsws_log` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `visitor_id` bigint(20) default NULL,
  `log` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rowid`),
  KEY `visitor_id` (`visitor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				
				echo "Creating System Logs table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_log`;");
				$db_ok = $db_ok && $this->mysql_query($sql);					
				
				
				
				$sql = "CREATE TABLE `xlsws_modules` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `file` varchar(64) NOT NULL,
  `type` varchar(16) NOT NULL,
  `sort_order` int(5) default NULL,
  `configuration` mediumtext,
  `modified` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created` datetime default NULL,
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `file` (`file`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				
				echo "Creating Modules table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_modules`;");
				$db_ok = $db_ok && $this->mysql_query($sql);					
							
				$sql = array();
				
$sql[] = "INSERT INTO `xlsws_modules` VALUES (14, 'store_pickup.php', 'shipping', 1, 'a:4:{s:5:\"label\";s:27:\"Store Pickup from our store\";s:3:\"msg\";s:73:\"Please present order ID %s with photo ID at the reception for collection.\";s:7:\"product\";s:8:\"SHIPPING\";s:6:\"markup\";s:1:\"2\";}', NOW(), NULL);";
$sql[] = "INSERT INTO `xlsws_modules` VALUES (42, 'sidebar_order_lookup.php', 'sidebar', 2, NULL, NOW(), NULL);";
$sql[] = "INSERT INTO `xlsws_modules` VALUES (49, 'xlsws_class_payment.php', 'payment', 9, 'a:1:{s:5:\"label\";s:16:\"Cash On Delivery\";}', NOW(), NULL);";
$sql[] = "INSERT INTO `xlsws_modules` VALUES (53, 'sidebar_wishlist.php', 'sidebar', 3, NULL, NOW(), NULL);";

				
				echo "Entering Sample Modules<br/>";

				foreach($sql as $s)
					$db_ok = $db_ok && $this->mysql_query($s);
				
				
				
				
				
				
				$sql = "CREATE TABLE `xlsws_product` (
  `rowid` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `image_id` bigint(20) default NULL,
  `class_name` varchar(32) default NULL,
  `code` varchar(255) NOT NULL,
  `current` tinyint(1) default NULL,
  `description` mediumtext,
  `description_short` mediumtext,
  `family` varchar(32) default NULL,
  `gift_card` tinyint(1) default NULL,
  `inventoried` tinyint(1) default NULL,
  `inventory` float default NULL,
  `inventory_total` float default NULL,
  `master_model` tinyint(1) default NULL,
  `fk_product_master_id` bigint(20) default '0',
  `product_size` varchar(32) default NULL,
  `product_color` varchar(32) default NULL,
  `product_height` float default NULL,
  `product_length` float default NULL,
  `product_width` float default NULL,
  `product_weight` float default '0',
  `fk_tax_status_id` bigint(20) default '0',
  `sell` float default NULL,
  `sell_tax_inclusive` float default NULL,
  `sell_web` float default NULL,
  `upc` varchar(12) default NULL,
  `web` tinyint(1) default NULL,
  `web_keyword1` varchar(255) default NULL,
  `web_keyword2` varchar(255) default NULL,
  `web_keyword3` varchar(255) default NULL,
  `meta_desc` varchar(255) default NULL,
  `meta_keyword` varchar(255) default NULL,
  `featured` tinyint(1) NOT NULL default '0',
  `created` datetime default NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `code` (`code`),
  KEY `web` (`web`),
  KEY `name` (`name`),
  KEY `fk_product_master_id` (`fk_product_master_id`),
  KEY `master_model` (`master_model`),
  KEY `fk_tax_status_id` (`fk_tax_status_id`),
  FULLTEXT KEY `description` (`description`),
  FULLTEXT KEY `name_2` (`name`),
  FULLTEXT KEY `code_2` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				
				echo "Creating Products table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_product`;");
				$db_ok = $db_ok && $this->mysql_query($sql);
				
				
				
				
				
				$sql = "CREATE TABLE `xlsws_product_category_assn` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset;";
				
				
				echo "Creating Product-Category Relation table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_product_category_assn`;");
				$db_ok = $db_ok && $this->mysql_query($sql);


				
				
				$sql = "CREATE TABLE `xlsws_product_image_assn` (
  `product_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  KEY `product_id` (`product_id`,`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset;";
				
				
				echo "Creating Product-Image Relation table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_product_image_assn`;");
				$db_ok = $db_ok && $this->mysql_query($sql);
				
				
				
				$sql = "CREATE TABLE xlsws_product_qty_pricing (
  rowid bigint(20) NOT NULL auto_increment,
  product_id int(11) NOT NULL,
  pricing_level int(11) default NULL,
  qty float default NULL,
  price float default NULL,
  PRIMARY KEY  (rowid),
  KEY product_id (product_id),
  KEY product_id_2 (product_id,pricing_level)
) ENGINE=MyISAM DEFAULT CHARSET=$charset;";
				
				echo "Creating Qty Pricing table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_product_qty_pricing`;");
				$db_ok = $db_ok && $this->mysql_query($sql);			
				
				
									
				
				
				
				$sql = "CREATE TABLE `xlsws_product_related` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `product_id` bigint(20) NOT NULL,
  `related_id` bigint(20) NOT NULL,
  `autoadd` tinyint(1) default NULL,
  `qty` float default NULL,
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `product_id` (`product_id`,`related_id`),
  KEY `product_id_2` (`product_id`),
  KEY `related_id` (`related_id`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				
				echo "Creating Related Product table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_product_related`;");
				$db_ok = $db_ok && $this->mysql_query($sql);					
				
				
				
				$sql = "CREATE TABLE `xlsws_sro` (
  `rowid` int(11) NOT NULL auto_increment,
  `ls_id` varchar(20) default NULL,
  `customer_name` varchar(255) default NULL,
  `customer_email_phone` varchar(255) NOT NULL,
  `zipcode` varchar(10) default NULL,
  `problem_description` mediumtext,
  `printed_notes` mediumtext,
  `work_performed` mediumtext,
  `additional_items` mediumtext,
  `warranty` mediumtext,
  `warranty_info` mediumtext,
  `status` varchar(32) default NULL,
  `cart_id` bigint(20) default NULL,
  `datetime_cre` datetime default NULL,
  `datetime_mod` timestamp NULL default NULL,
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `ls_id` (`ls_id`),
  KEY `cart_id` (`cart_id`),
  KEY `customer_email_phone` (`customer_email_phone`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				
				echo "Creating SRO table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_sro`;");
				$db_ok = $db_ok && $this->mysql_query($sql);					
				
				
				
				$sql = "CREATE TABLE `xlsws_sro_repair` (
  `rowid` int(11) NOT NULL auto_increment,
  `sro_id` varchar(20) default NULL,
  `family` varchar(255) default NULL,
  `description` varchar(255) default NULL,
  `purchase_date` varchar(32) default NULL,
  `serial_number` varchar(255) default NULL,
  `datetime_cre` datetime default NULL,
  `datetime_mod` timestamp NULL default NULL,
  PRIMARY KEY  (`rowid`),
  KEY `sro_id` (`sro_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=$charset;";
				
				
				echo "Creating SRO Repair table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_sro_repair`;");
				$db_ok = $db_ok && $this->mysql_query($sql);					
				
				
				
				
				$sql ="CREATE TABLE `xlsws_state` (
  `rowid` bigint(20) unsigned NOT NULL auto_increment,
  `country_code` char(2) NOT NULL default '',
  `code` varchar(32) NOT NULL default '',
  `avail` char(1) NOT NULL default 'Y',
  `sort_order` int(11) default '10',
  `state` varchar(255) NOT NULL,
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `cs` (`country_code`,`code`),
  KEY `code` (`code`),
  KEY `country_code` (`country_code`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
				echo "Creating State/Region table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_state`;");
				$db_ok = $db_ok && $this->mysql_query($sql);	
				
				$sql = array();
				
$sql[] = "INSERT INTO `xlsws_state` VALUES (15, 'US', 'CA', 'Y', 10, 'California')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (13, 'US', 'AR', 'Y', 10, 'Arkansas')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (12, 'US', 'AZ', 'Y', 10, 'Arizona')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (11, 'US', 'AK', 'Y', 10, 'Alaska')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (10, 'US', 'AL', 'Y', 10, 'Alabama')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (16, 'US', 'CO', 'Y', 10, 'Colorado')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (17, 'US', 'CT', 'Y', 10, 'Connecticut')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (18, 'US', 'DE', 'Y', 10, 'Delaware')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (19, 'US', 'DC', 'Y', 10, 'District of Columbia')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (20, 'US', 'FL', 'Y', 10, 'Florida')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (21, 'US', 'GA', 'Y', 10, 'Georgia')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (22, 'US', 'GU', 'Y', 10, 'Guam')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (23, 'US', 'HI', 'Y', 10, 'Hawaii')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (24, 'US', 'ID', 'Y', 10, 'Idaho')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (25, 'US', 'IL', 'Y', 10, 'Illinois')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (26, 'US', 'IN', 'Y', 10, 'Indiana')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (27, 'US', 'IA', 'Y', 10, 'Iowa')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (28, 'US', 'KS', 'Y', 10, 'Kansas')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (29, 'US', 'KY', 'Y', 10, 'Kentucky')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (30, 'US', 'LA', 'Y', 10, 'Louisiana')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (31, 'US', 'ME', 'Y', 10, 'Maine')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (32, 'US', 'MD', 'Y', 10, 'Maryland')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (33, 'US', 'MA', 'Y', 10, 'Massachusetts')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (34, 'US', 'MI', 'Y', 10, 'Michigan')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (35, 'US', 'MN', 'Y', 10, 'Minnesota')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (36, 'US', 'MS', 'Y', 10, 'Mississippi')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (37, 'US', 'MO', 'Y', 10, 'Missouri')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (38, 'US', 'MT', 'Y', 10, 'Montana')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (39, 'US', 'NE', 'Y', 10, 'Nebraska')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (40, 'US', 'NV', 'Y', 10, 'Nevada')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (41, 'US', 'NH', 'Y', 10, 'New Hampshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (42, 'US', 'NJ', 'Y', 10, 'New Jersey')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (43, 'US', 'NM', 'Y', 10, 'New Mexico')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (44, 'US', 'NY', 'Y', 10, 'New York')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (45, 'US', 'NC', 'Y', 10, 'North Carolina')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (46, 'US', 'ND', 'Y', 10, 'North Dakota')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (47, 'US', 'OH', 'Y', 10, 'Ohio')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (48, 'US', 'OK', 'Y', 10, 'Oklahoma')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (49, 'US', 'OR', 'Y', 10, 'Oregon')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (50, 'US', 'PA', 'Y', 10, 'Pennsylvania')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (51, 'US', 'PR', 'Y', 10, 'Puerto Rico')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (52, 'US', 'RI', 'Y', 10, 'Rhode Island')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (53, 'US', 'SC', 'Y', 10, 'South Carolina')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (54, 'US', 'SD', 'Y', 10, 'South Dakota')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (55, 'US', 'TN', 'Y', 10, 'Tennessee')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (56, 'US', 'TX', 'Y', 10, 'Texas')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (57, 'US', 'UT', 'Y', 10, 'Utah')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (58, 'US', 'VT', 'Y', 10, 'Vermont')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (59, 'US', 'VI', 'Y', 10, 'Virgin Islands')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (60, 'US', 'VA', 'Y', 10, 'Virginia')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (61, 'US', 'WA', 'Y', 10, 'Washington')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (62, 'US', 'WV', 'Y', 10, 'West Virginia')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (63, 'US', 'WI', 'Y', 10, 'Wisconsin')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (64, 'US', 'WY', 'Y', 10, 'Wyoming')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (74, 'FR', '01', 'Y', 10, 'Ain')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (66, 'FR', '02', 'Y', 10, 'Aisne')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (67, 'FR', '03', 'Y', 10, 'Allier')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (68, 'FR', '04', 'Y', 10, 'Alpes-de-Haute-Provence')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (69, 'FR', '06', 'Y', 10, 'Alpes-Maritimes')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (70, 'FR', '07', 'Y', 10, 'Ardèche')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (71, 'FR', '08', 'Y', 10, 'Ardennes')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (72, 'FR', '09', 'Y', 10, 'Ariège')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (73, 'FR', '10', 'Y', 10, 'Aube')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (75, 'FR', '11', 'Y', 10, 'Aude')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (76, 'FR', '12', 'Y', 10, 'Aveyron')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (77, 'FR', '13', 'Y', 10, 'Bouches-du-Rhône')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (78, 'FR', '14', 'Y', 10, 'Calvados')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (79, 'FR', '15', 'Y', 10, 'Cantal')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (80, 'FR', '16', 'Y', 10, 'Charente')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (81, 'FR', '17', 'Y', 10, 'Charente-Maritime')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (82, 'FR', '18', 'Y', 10, 'Cher')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (83, 'FR', '19', 'Y', 10, 'Corrèze')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (84, 'FR', '2A', 'Y', 10, 'Corse-du-Sud')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (85, 'FR', '21', 'Y', 10, 'Côte-d''Or')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (86, 'FR', '22', 'Y', 10, 'Côtes-d''Armor')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (87, 'FR', '23', 'Y', 10, 'Creuse')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (88, 'FR', '24', 'Y', 10, 'Dordogne')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (89, 'FR', '25', 'Y', 10, 'Doubs')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (90, 'FR', '26', 'Y', 10, 'Drôme')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (91, 'FR', '91', 'Y', 10, 'Essonne')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (92, 'FR', '27', 'Y', 10, 'Eure')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (93, 'FR', '28', 'Y', 10, 'Eure-et-Loir')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (94, 'FR', '29', 'Y', 10, 'Finistére')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (95, 'FR', '30', 'Y', 10, 'Gard')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (96, 'FR', '32', 'Y', 10, 'Gers')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (97, 'FR', '33', 'Y', 10, 'Gironde')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (98, 'FR', '2B', 'Y', 10, 'Haute-Corse')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (99, 'FR', '31', 'Y', 10, 'Haute-Garonne')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (100, 'FR', '43', 'Y', 10, 'Haute-Loire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (101, 'FR', '52', 'Y', 10, 'Haute-Marne')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (102, 'FR', '87', 'Y', 10, 'Haute-Vienne')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (103, 'FR', '05', 'Y', 10, 'Haute-Vienne')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (104, 'FR', '92', 'Y', 10, 'Hauts-de-Seine')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (105, 'FR', '34', 'Y', 10, 'Hérault')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (106, 'FR', '35', 'Y', 10, 'Ille-et-Vilaine')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (107, 'FR', '36', 'Y', 10, 'Indre')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (108, 'FR', '37', 'Y', 10, 'Indre-et-Loire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (109, 'FR', '38', 'Y', 10, 'Isère')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (110, 'FR', '39', 'Y', 10, 'Jura')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (111, 'FR', '40', 'Y', 10, 'Landes')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (112, 'FR', '41', 'Y', 10, 'Loir-et-Cher')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (113, 'FR', '42', 'Y', 10, 'Loire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (114, 'FR', '44', 'Y', 10, 'Loire-Atlantique')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (115, 'FR', '45', 'Y', 10, 'Loiret')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (116, 'FR', '46', 'Y', 10, 'Lot')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (117, 'FR', '47', 'Y', 10, 'Lot-et-Garonne')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (118, 'FR', '48', 'Y', 10, 'Lozère')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (119, 'FR', '49', 'Y', 10, 'Maine-et-Loire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (120, 'FR', '50', 'Y', 10, 'Manche')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (121, 'FR', '51', 'Y', 10, 'Marne')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (122, 'FR', '75', 'Y', 10, 'Paris')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (123, 'FR', '93', 'Y', 10, 'Seine-Saint-Denis')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (124, 'FR', '80', 'Y', 10, 'Somme')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (125, 'FR', '81', 'Y', 10, 'Tarn')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (126, 'FR', '82', 'Y', 10, 'Tarn-et-Garonne')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (127, 'FR', '90', 'Y', 10, 'Territoire de Belfort')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (128, 'FR', '95', 'Y', 10, 'Val-d''Oise')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (129, 'FR', '94', 'Y', 10, 'Val-de-Marne')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (130, 'FR', '83', 'Y', 10, 'Var')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (131, 'FR', '84', 'Y', 10, 'Vaucluse')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (132, 'FR', '85', 'Y', 10, 'Vendée')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (133, 'FR', '86', 'Y', 10, 'Vienne')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (134, 'FR', '88', 'Y', 10, 'Vosges')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (135, 'FR', '89', 'Y', 10, 'Yonne')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (136, 'CA', 'AB', 'Y', 10, 'Alberta')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (137, 'CA', 'BC', 'Y', 10, 'British Columbia')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (138, 'CA', 'MB', 'Y', 10, 'Manitoba')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (139, 'CA', 'NB', 'Y', 10, 'New Brunswick')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (140, 'CA', 'NL', 'Y', 10, 'Newfoundland and Labrador')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (141, 'CA', 'NT', 'Y', 10, 'Northwest Territories')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (142, 'CA', 'NS', 'Y', 10, 'Nova Scotia')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (143, 'CA', 'NU', 'Y', 10, 'Nunavut')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (144, 'CA', 'ON', 'Y', 10, 'Ontario')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (145, 'CA', 'PE', 'Y', 10, 'Prince Edward Island')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (146, 'CA', 'QC', 'Y', 10, 'Québec')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (147, 'CA', 'SK', 'Y', 10, 'Saskatchewan')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (148, 'CA', 'YT', 'Y', 10, 'Yukon Territory')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (149, 'AU', 'ACT', 'Y', 10, 'Australian Capital Territory')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (150, 'AU', 'NSW', 'Y', 10, 'New South Wales')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (151, 'AU', 'NT', 'Y', 10, 'Northern Territory')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (152, 'AU', 'QLD', 'Y', 10, 'Queensland')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (153, 'AU', 'SA', 'Y', 10, 'South Australia')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (154, 'AU', 'TAS', 'Y', 10, 'Tasmania')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (155, 'AU', 'VIC', 'Y', 10, 'Victoria')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (156, 'AU', 'WA', 'Y', 10, 'Western Australia')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (157, 'NL', 'DR', 'Y', 10, 'Drenthe')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (158, 'NL', 'FL', 'Y', 10, 'Flevoland')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (159, 'NL', 'FR', 'Y', 10, 'Friesland')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (160, 'NL', 'GE', 'Y', 10, 'Gelderland')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (161, 'NL', 'GR', 'Y', 10, 'Groningen')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (162, 'NL', 'LI', 'Y', 10, 'Limburg')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (163, 'NL', 'NB', 'Y', 10, 'Noord Brabant')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (164, 'NL', 'NH', 'Y', 10, 'Noord Holland')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (165, 'NL', 'OV', 'Y', 10, 'Overijssel')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (166, 'NL', 'UT', 'Y', 10, 'Utrecht')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (167, 'NL', 'ZE', 'Y', 10, 'Zeeland')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (168, 'NL', 'ZH', 'Y', 10, 'Zuid Holland')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (169, 'DE', 'BAW', 'Y', 10, CONCAT('Baden-Württemberg'))";
$sql[] = "INSERT INTO `xlsws_state` VALUES (170, 'DE', 'BAY', 'Y', 10, 'Bayern')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (171, 'DE', 'BER', 'Y', 10, 'Berlin')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (172, 'DE', 'BRG', 'Y', 10, 'Branderburg')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (173, 'DE', 'BRE', 'Y', 10, 'Bremen')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (174, 'DE', 'HAM', 'Y', 10, 'Hamburg')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (175, 'DE', 'HES', 'Y', 10, 'Hessen')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (176, 'DE', 'MEC', 'Y', 10, 'Mecklenburg-Vorpommern')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (177, 'DE', 'NDS', 'Y', 10, 'Niedersachsen')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (178, 'DE', 'NRW', 'Y', 10, 'Nordrhein-Westfalen')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (179, 'DE', 'RHE', 'Y', 10, 'Rheinland-Pfalz')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (180, 'DE', 'SAR', 'Y', 10, 'Saarland')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (181, 'DE', 'SAS', 'Y', 10, 'Sachsen')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (182, 'DE', 'SAC', 'Y', 10, 'Sachsen-Anhalt')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (183, 'DE', 'SCN', 'Y', 10, 'Schleswig-Holstein')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (184, 'DE', 'THE', 'Y', 10, CONCAT('Thüringen'))";
$sql[] = "INSERT INTO `xlsws_state` VALUES (185, 'GB', 'ABN', 'Y', 10, 'Aberdeen')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (186, 'GB', 'ABNS', 'Y', 10, 'Aberdeenshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (187, 'GB', 'ANG', 'Y', 10, 'Anglesey')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (188, 'GB', 'AGS', 'Y', 10, 'Angus')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (189, 'GB', 'ARY', 'Y', 10, 'Argyll and Bute')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (190, 'GB', 'BEDS', 'Y', 10, 'Bedfordshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (191, 'GB', 'BERKS', 'Y', 10, 'Berkshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (192, 'GB', 'BLA', 'Y', 10, 'Blaenau Gwent')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (193, 'GB', 'BRI', 'Y', 10, 'Bridgend')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (194, 'GB', 'BSTL', 'Y', 10, 'Bristol')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (195, 'GB', 'BUCKS', 'Y', 10, 'Buckinghamshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (196, 'GB', 'CAE', 'Y', 10, 'Caerphilly')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (197, 'GB', 'CAMBS', 'Y', 10, 'Cambridgeshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (198, 'GB', 'CDF', 'Y', 10, 'Cardiff')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (199, 'GB', 'CARM', 'Y', 10, 'Carmarthenshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (200, 'GB', 'CDGN', 'Y', 10, 'Ceredigion')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (201, 'GB', 'CHES', 'Y', 10, 'Cheshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (202, 'GB', 'CLACK', 'Y', 10, 'Clackmannanshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (203, 'GB', 'CON', 'Y', 10, 'Conwy')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (204, 'GB', 'CORN', 'Y', 10, 'Cornwall')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (205, 'GB', 'DNBG', 'Y', 10, 'Denbighshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (206, 'GB', 'DERBY', 'Y', 10, 'Derbyshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (207, 'GB', 'DVN', 'Y', 10, 'Devon')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (208, 'GB', 'DOR', 'Y', 10, 'Dorset')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (209, 'GB', 'DGL', 'Y', 10, 'Dumfries and Galloway')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (210, 'GB', 'DUND', 'Y', 10, 'Dundee')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (211, 'GB', 'DHM', 'Y', 10, 'Durham')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (212, 'GB', 'ARYE', 'Y', 10, 'East Ayrshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (213, 'GB', 'DUNBE', 'Y', 10, 'East Dunbartonshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (214, 'GB', 'LOTE', 'Y', 10, 'East Lothian')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (215, 'GB', 'RENE', 'Y', 10, 'East Renfrewshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (216, 'GB', 'ERYS', 'Y', 10, 'East Riding of Yorkshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (217, 'GB', 'SXE', 'Y', 10, 'East Sussex')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (218, 'GB', 'EDIN', 'Y', 10, 'Edinburgh')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (219, 'GB', 'ESX', 'Y', 10, 'Essex')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (220, 'GB', 'FALK', 'Y', 10, 'Falkirk')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (221, 'GB', 'FFE', 'Y', 10, 'Fife')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (222, 'GB', 'FLINT', 'Y', 10, 'Flintshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (223, 'GB', 'GLAS', 'Y', 10, 'Glasgow')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (224, 'GB', 'GLOS', 'Y', 10, 'Gloucestershire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (225, 'GB', 'LDN', 'Y', 10, 'Greater London')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (226, 'GB', 'MCH', 'Y', 10, 'Greater Manchester')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (227, 'GB', 'GDD', 'Y', 10, 'Gwynedd')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (228, 'GB', 'HANTS', 'Y', 10, 'Hampshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (229, 'GB', 'HWR', 'Y', 10, 'Herefordshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (230, 'GB', 'HERTS', 'Y', 10, 'Hertfordshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (231, 'GB', 'HLD', 'Y', 10, 'Highlands')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (232, 'GB', 'IVER', 'Y', 10, 'Inverclyde')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (233, 'GB', 'IOW', 'Y', 10, 'Isle of Wight')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (234, 'GB', 'KNT', 'Y', 10, 'Kent')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (235, 'GB', 'LANCS', 'Y', 10, 'Lancashire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (236, 'GB', 'LEICS', 'Y', 10, 'Leicestershire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (237, 'GB', 'LINCS', 'Y', 10, 'Lincolnshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (238, 'GB', 'MSY', 'Y', 10, 'Merseyside')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (239, 'GB', 'MERT', 'Y', 10, 'Merthyr Tydfil')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (240, 'GB', 'MLOT', 'Y', 10, 'Midlothian')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (241, 'GB', 'MMOUTH', 'Y', 10, 'Monmouthshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (242, 'GB', 'MORAY', 'Y', 10, 'Moray')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (243, 'GB', 'NPRTAL', 'Y', 10, 'Neath Port Talbot')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (244, 'GB', 'NEWPT', 'Y', 10, 'Newport')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (245, 'GB', 'NOR', 'Y', 10, 'Norfolk')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (246, 'GB', 'ARYN', 'Y', 10, 'North Ayrshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (247, 'GB', 'LANN', 'Y', 10, 'North Lanarkshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (248, 'GB', 'YSN', 'Y', 10, 'North Yorkshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (249, 'GB', 'NHM', 'Y', 10, 'Northamptonshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (250, 'GB', 'NLD', 'Y', 10, 'Northumberland')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (251, 'GB', 'NOT', 'Y', 10, 'Nottinghamshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (252, 'GB', 'ORK', 'Y', 10, 'Orkney Islands')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (253, 'GB', 'OFE', 'Y', 10, 'Oxfordshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (254, 'GB', 'PEM', 'Y', 10, 'Pembrokeshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (255, 'GB', 'PERTH', 'Y', 10, 'Perth and Kinross')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (256, 'GB', 'PWS', 'Y', 10, 'Powys')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (257, 'GB', 'REN', 'Y', 10, 'Renfrewshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (258, 'GB', 'RHON', 'Y', 10, 'Rhondda Cynon Taff')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (259, 'GB', 'RUT', 'Y', 10, 'Rutland')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (260, 'GB', 'BOR', 'Y', 10, 'Scottish Borders')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (261, 'GB', 'SHET', 'Y', 10, 'Shetland Islands')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (262, 'GB', 'SPE', 'Y', 10, 'Shropshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (263, 'GB', 'SOM', 'Y', 10, 'Somerset')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (264, 'GB', 'ARYS', 'Y', 10, 'South Ayrshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (265, 'GB', 'LANS', 'Y', 10, 'South Lanarkshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (266, 'GB', 'YSS', 'Y', 10, 'South Yorkshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (267, 'GB', 'SFD', 'Y', 10, 'Staffordshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (268, 'GB', 'STIR', 'Y', 10, 'Stirling')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (269, 'GB', 'SFK', 'Y', 10, 'Suffolk')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (270, 'GB', 'SRY', 'Y', 10, 'Surrey')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (271, 'GB', 'SWAN', 'Y', 10, 'Swansea')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (272, 'GB', 'TORF', 'Y', 10, 'Torfaen')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (273, 'GB', 'TWR', 'Y', 10, 'Tyne and Wear')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (274, 'GB', 'VGLAM', 'Y', 10, 'Vale of Glamorgan')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (275, 'GB', 'WARKS', 'Y', 10, 'Warwickshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (276, 'GB', 'WDUN', 'Y', 10, 'West Dunbartonshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (277, 'GB', 'WLOT', 'Y', 10, 'West Lothian')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (278, 'GB', 'WMD', 'Y', 10, 'West Midlands')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (279, 'GB', 'SXW', 'Y', 10, 'West Sussex')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (280, 'GB', 'YSW', 'Y', 10, 'West Yorkshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (281, 'GB', 'WIL', 'Y', 10, 'Western Isles')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (282, 'GB', 'WLT', 'Y', 10, 'Wiltshire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (283, 'GB', 'WORCS', 'Y', 10, 'Worcestershire')";
$sql[] = "INSERT INTO `xlsws_state` VALUES (284, 'GB', 'WRX', 'Y', 10, 'Wrexham');";
				
				
				echo "Installing States/Regions<br/>";

				foreach($sql as $s)
					$db_ok = $db_ok && $this->mysql_query($s);				
				
					

					
				$sql = "CREATE TABLE `xlsws_tax` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `tax` char(32) NOT NULL,
  `max` double default '0',
  `compounded` tinyint(1) default '0',
  PRIMARY KEY  (`rowid`),
  KEY `tax` (`tax`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
					
				echo "Creating tax table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_tax`;");
				$db_ok = $db_ok && $this->mysql_query($sql);						
					
					

				$sql = "CREATE TABLE `xlsws_tax_code` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `code` char(32) NOT NULL,
  `list_order` int(11) NOT NULL default '0',
  `tax1_rate` double NOT NULL default '0',
  `tax2_rate` double NOT NULL default '0',
  `tax3_rate` double NOT NULL default '0',
  `tax4_rate` double NOT NULL default '0',
  `tax5_rate` double NOT NULL default '0',
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
					
				echo "Creating tax code.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_tax_code`;");
				$db_ok = $db_ok && $this->mysql_query($sql);					
				
				

				$sql = "CREATE TABLE `xlsws_tax_status` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `status` char(32) NOT NULL,
  `tax1_status` tinyint(1) NOT NULL default '1',
  `tax2_status` tinyint(1) NOT NULL default '1',
  `tax3_status` tinyint(1) NOT NULL default '1',
  `tax4_status` tinyint(1) NOT NULL default '1',
  `tax5_status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`rowid`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
					
				echo "Creating tax status.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_tax_status`;");
				$db_ok = $db_ok && $this->mysql_query($sql);				
				
				
				
				
				
				
				$sql = "CREATE TABLE `xlsws_view_log` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `resource_id` bigint(20) default NULL,
  `log_type_id` int(11) NOT NULL,
  `visitor_id` bigint(20) default NULL,
  `page` varchar(255) default NULL,
  `vars` varchar(32) default NULL COMMENT 'Additional data for the view log',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rowid`),
  KEY `visitor_id` (`visitor_id`),
  KEY `log_type_id` (`log_type_id`),
  KEY `resource_id` (`resource_id`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
					
				echo "Creating View Log.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_view_log`;");
				$db_ok = $db_ok && $this->mysql_query($sql);				
				
				$sql = "CREATE TABLE `xlsws_promo_code` (
  `rowid` int(11) NOT NULL auto_increment,
  `code` varchar(255) default NULL,
  `type` int(11) default '0',
  `amount` double NOT NULL,
  `valid_from` tinytext NOT NULL,
  `qty_remaining` int(11) NOT NULL default '-1',
  `valid_until` tinytext,
  `lscodes` longtext NOT NULL,
  `threshold` double NOT NULL,
  PRIMARY KEY  (`rowid`)
) ENGINE=MyISAM  DEFAULT CHARSET=$charset";
				
					
				echo "Creating Promo Codes.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_promo_code`;");
				$db_ok = $db_ok && $this->mysql_query($sql);
				
				$sql = "CREATE TABLE `xlsws_shipping_tiers` (
  `rowid` int(11) NOT NULL auto_increment,
  `start_price` double default '0',
  `end_price` double default '0',
  `rate` double default '0',
  PRIMARY KEY  (`rowid`)
) ENGINE=MyISAM  DEFAULT CHARSET=$charset";
				
					
				echo "Creating Shipping Tiers.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_shipping_tiers`;");
				$db_ok = $db_ok && $this->mysql_query($sql);	
				

				$sql = "CREATE TABLE IF NOT EXISTS `xlsws_sessions` (
  `intSessionId` int(10) NOT NULL auto_increment,
  `vchName` varchar(255) NOT NULL default '',
  `uxtExpires` int(10) unsigned NOT NULL default '0',
  `txtData` longtext,
  PRIMARY KEY  (`intSessionId`),
  KEY `idxName` (`vchName`),
  KEY `idxExpires` (`uxtExpires`)
) ENGINE=MyISAM  DEFAULT CHARSET=$charset";
				
					
				echo "Creating Sessions Table.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_sessions`;");
				$db_ok = $db_ok && $this->mysql_query($sql);	

	
				$sql = "CREATE TABLE `xlsws_view_log_type` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
					
				echo "Creating View Log Types.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_view_log_type`;");
				$db_ok = $db_ok && $this->mysql_query($sql);				
				
				
				
	$sql = array();
				
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (1, 'index')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (2, 'categoryview')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (3, 'productview')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (4, 'pageview')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (5, 'productcartadd')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (6, 'search')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (7, 'registration')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (8, 'giftregistryview')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (9, 'giftregistryadd')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (10, 'customerlogin')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (11, 'customerlogout')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (12, 'checkoutcustomer')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (13, 'checkoutshipping')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (14, 'checkoutpayment')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (15, 'checkoutfinal')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (16, 'unknown')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (17, 'invalidcreditcard')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (18, 'failcreditcard')";
$sql[] = "INSERT INTO `xlsws_view_log_type` VALUES (19, 'familyview')";

				
				
				echo "Installing View Log Types<br/>";

				foreach($sql as $s)
					$db_ok = $db_ok && $this->mysql_query($s);				
				
								
				
				
				$sql = "CREATE TABLE `xlsws_visitor` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `customer_id` bigint(20) default NULL,
  `host` varchar(255) default NULL,
  `ip` varchar(32) default NULL,
  `browser` varchar(255) default NULL,
  `screen_res` varchar(12) default NULL,
  `created` datetime default NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rowid`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=$charset";
				
					
				echo "Creating Visitor.<br/>";
				$db_ok = $db_ok && $this->mysql_query("DROP TABLE IF EXISTS `xlsws_visitor`;");
				$db_ok = $db_ok && $this->mysql_query($sql);					
				
				
				// update store password
				echo "Updating store password.<br/>";
				$pass = $this->GetControl('storepass');
				$sql = "UPDATE xlsws_configuration SET value='" .  strtolower(md5($pass->Text))  . "' WHERE `key`='LSKEY'";
				$db_ok = $db_ok && $this->mysql_query($sql);

				
				// update store timezone
				echo "Updating store timezone.<br/>";
				$tz = $this->GetControl('storetz');
				$sql = "UPDATE xlsws_configuration SET value='" . $tz->SelectedValue  . "' WHERE `key`='TIMEZONE'";
				$db_ok = $db_ok && $this->mysql_query($sql);
				
				
				if($db_ok){
					echo "<BR/><b>Don't forget to change permissions on your /includes folder back to read only, and specifically set the file includes/configuration.inc.php to 644 or world readable only! Your /includes/qcodo/cache folders need to remain writable.</b><BR/><BR/>";
					
					echo "Done!<BR/><BR/>";
					
					echo "<a href=\"index.php\">Click here</a> to see your store.";
				}else{
					file_put_contents("includes/configuration.inc.php" , $old_config_file);	
				}
				
				echo "</pre>";
				echo "</body>";
				echo "</html>";
				
				exit();
				
				
				
			}
			
			
			
			
			
		}
		WSInstall::Run('WSInstall' , __FILE__);
	}



?>
<?php if(isset($this)): ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Web Store 2.0 Installation Wizard</title>
<style type="text/css">
body {
	color: #222;
	font-size: 14px;
}

.install_content {
	width:520px;
	height: 25px;
	margin: 0 auto;
}

.install_content option {
margin: 0;
}

#content { 
	padding: 15px; 
	text-align: center;
	min-width: 512px;
	width: 512px;
	max-width: 512px;
	vertical-align: middle;
}

.header	{
	margin: 5px 0 45px -70px;
}

.install_agreement{
	height: 400px;
	overflow-y: scroll;
	background-color: #ffffff;
	border: 1px solid #666;
	padding: 5px 2px 2px 10px;
	margin: 20px 0 10px 0;
}

.install_agreement input {
border:1px solid #acacac;
color:#444;
height:20px;
padding:4px 4px;
margin: 5px 0 15px 0;
width: 200px;
}

.prev_button{
	display: inline;
	float: left;
	position: relative;
	top: 452px;
	
}
.next_button{
	float: right;
	position: relative;
	top: 452px;
	left: 0px;
}

.prev_button input, .next_button input {height: 30px;}

.checkbox_agreement {
	font-weight: bold;
	font-size: 14px;
	color: #222;
	margin: 5px 0 0 10px;;
	float: left;
	display: block;
}

#agree_ctl input {
	display: block;
	float: left;
	width: 10px;
}

.host_settings {margin: 90px 0 0 155px;}

.db_settings {margin: 55px 0 0 155px}

.store_password {margin: 155px 0 0 155px}

.install {margin: 175px 0 0 0; text-align: center;}

.install_center {margin: 10px 0 0 0; text-align: center;}

.label {
	font-size: 14px;
	color: #222;
	margin: 15px 0 5px 0;
}

.advice {
	font-size: 12px;
	color: #000;
	margin: -25px 0 35px -75px;
	width: 375px;
}


.textbold {
	font-weight: bold;
	color:#222;
	margin: 0 0 25px 0;
}

.steps {position: relative; top: 200px; left: 10px; margin: 0 auto; width: 563px;}

</style>


<link href="templates/deluxe/css/webstore.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="assets/js/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/webstore.js"></script>


</head>
<body>

<noscript>
<h1>
Please enable javascript to install Web Store!
</h1>
</noscript>

<?php $this->RenderBegin(); ?>

<?php $this->pnlStep->Render(); ?>

<div class="install_content">

<img src="templates/install/webstore_installation.png" class="header"/>

<div id="content" class="rounded {3px transparent}">
<div class="prev_button">
<?php $this->btnPrev->Render('CssClass=button rounded {3px transparent}'); ?>
</div>
<div class="next_button">
<?php $this->btnNext->Render('CssClass=button rounded {3px transparent}'); ?>
</div>
<?php $this->pnlInstall->Render(); ?>
<div>

</div>

<?php $this->RenderEnd(); ?>
</div>
</body>
</html>
<?php endif; ?>
