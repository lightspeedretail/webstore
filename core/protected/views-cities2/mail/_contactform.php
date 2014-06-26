<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
<head>
    <title><?php echo CHtml::encode(_xls_get_conf('STORE_NAME')); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />

    <style type="text/css">
        <!--
        body { font-family: "Lucida Grande", "Lucida Sans", Verdana, sans-serif; font-size: 13px;font-style: normal;line-height: 1.5em;color: #111;}
        table { font-size: 12px; border: 0px;width: 750px; margin: 0 auto;}
        tbody {background-color: #E9EBEA;}
        .graphicheader {height: 100px;text-align: left; width=750px;background-color: #ffffff;}
        #cartitems table { width: 730px; margin-top: 10px;margin-bottom: 20px; }
        #cartitems th {background: none repeat scroll 0 0 #000000;color: #FFFFFF;font-weight: bold;padding-left: 2px;text-align: left;}
        #cartitems .summary {text-align:right;font-weight: bold;}
        #cartitems .rightprice { text-align:right;}
        #cartitems .shipping {vertical-align: top;text-align: left;}
        #footer a {color: #fff;}
        a img {border: none;}
        -->
    </style>
	<body>
	<table>
	    <tr>
	        <th class="graphicheader">
		        <?php echo CHtml::link(CHtml::image(CController::createAbsoluteUrl(_xls_get_conf('HEADER_IMAGE'))), Yii::app()->baseUrl."/"); ?>
	        </th>
	    </tr>
	</table>
	<table>
	    <tbody>
	        <td style="padding:15px;" width="750px">


		        <h1>Contact Us question for <?php echo _xls_get_conf('STORE_NAME') ?></h1>

				<b>From:</b> <?= $model->fromName ?><br/>
				<b>Email:</b> <?= $model->fromEmail ?><br/>
				<b>Subject:</b> <?= $model->contactSubject ?><br/>

				<div id="cartnotes">
					<table>
		                <tr>
		                    <td><?=$model->contactBody ?></td>
		                </tr>
		            </table>
				</div>

	    </tbody>
	</table>
  </body>
</html>
