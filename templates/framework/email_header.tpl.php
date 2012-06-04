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
   
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/**
 * Framework template HTML Email Header, called as part of header-template-footer 
 * aggregate calls in _xls_mail_body_from_template();
 * 
 *
 */

?><html>
	<head>


		<meta http-equiv="Content-Type" content="text/html; charset=<?php _xt(QApplication::$EncodingType); ?>" />
		<base href="<?= _xls_site_dir(false);?>/"/>
		<title><?= _xls_get_conf('STORE_NAME') ?> <?php _xt("Email") ?></title>

<style type="text/css">
<!--
body {
   font-family: "Lucida Grande", "Lucida Sans", Verdana, sans-serif;
   font-size: 13px;
   font-style: normal;
   line-height: 1.5em;
   color: #111;
}

table {
font-size: 12px;
border: 0px;
width: 750px;
margin: 0 auto; 

}

tbody {
background-color: #E9EBEA;
}

.graphicheader {
height: 100px; 
text-align: left;
width=750px;
background-color: #ffffff;
} 

#cartitems table {
	width: 730px;
	margin-top: 10px;
	margin-bottom: 20px;
	
}

#cartitems th {
background: none repeat scroll 0 0 #000000;
color: #FFFFFF;
font-weight: bold;
padding-left: 2px;
text-align: left;
}

#cartitems .summary {
text-align:right;
font-weight: bold;
}


#cartitems .rightprice {
text-align:right;
}

#cartitems .shipping {
vertical-align: top;
text-align: left;
}

#footer a {color: #fff;}

a img {border: none;}
-->
</style>


<table>
    <tr>
      <th class="graphicheader">
      <a href="/">
	      <img src="<?php
	     $img =  _xls_get_conf('HEADER_IMAGE' ,  false ); 
	     
	     if(!$img)
	      $img = templateNamed('images') . '/webstore_installation.png';
	     else{
	      $img = _xls_get_url_resource($img);
	     }
	     echo $img;
	     ?>" />
     </a>
      </th>
    </tr>
 </table>
<table>
  <tbody>
    <tr>
     <td style="padding:15px;" width="750px">
