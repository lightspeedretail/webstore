<?php $this->layout='//layouts/column1'; ?>

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
