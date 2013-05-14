<style type="text/css">
	.install_agreement {
		background-color: #FFFFFF;
		border: 1px solid #666666;
		display: block;
		font-size: 0.8em;
		height: 300px;
		line-height: 12pt;
		margin: 5px 0 10px;
		overflow-y: scroll;
		padding: 5px 2px 2px 10px;
		width: 725px;
	}
	legend { font-size: 0.8em; line-height: 13pt; }
	label[for=InstallForm_iagree] { display: inline; margin-left: 10px; margin-right: 5px; padding-top: 5px; }
	input[type="checkbox"] { margin-left: 20px; height: 20px; width: 20px; }
</style>
<script>
	function setupMail(id)
	{
		if (id=="smtp") {
			var fulldomain = window.location.hostname;
			fulldomain =  fulldomain.split('').reverse().join('');

			var firstDot = fulldomain.indexOf('.');
			var secondDot = fulldomain.indexOf(".",firstDot+1);
			var domain = fulldomain.substr(0,secondDot);
			domain =  "mail."+domain.split('').reverse().join('');

			$("#InstallForm_EMAIL_SMTP_SERVER").val(domain);
			$("#InstallForm_EMAIL_SMTP_PORT").val("465");
			$("#InstallForm_EMAIL_SMTP_SECURITY_MODE").val(0);
		}
		if (id=="gmail") {
			$("#InstallForm_EMAIL_SMTP_SERVER").val("smtp.gmail.com");
			$("#InstallForm_EMAIL_SMTP_PORT").val("465");
			$("#InstallForm_EMAIL_SMTP_SECURITY_MODE").val(0);
		}
		if (id=="godaddy") {
			$("#InstallForm_EMAIL_SMTP_SERVER").val("smtpout.secureserver.net");
			$("#InstallForm_EMAIL_SMTP_PORT").val("80");
			$("#InstallForm_EMAIL_SMTP_SECURITY_MODE").val(0);
		}

	}
</script>
<div class="span10">
		<h3>Configuration  Page <?=$model->page?></h3>
	<div class="hero-unit">
		<div class="editinstructions"><?php echo $this->editSectionInstructions; ?></div>
		<?php echo $form->renderBegin(); ?>
		<?php if ($model->page == 1): ?>
		<div id="agreement" class="install_agreement">
			<?php echo $this->license; ?>
		</div>
		<?php endif; ?>
		<?php echo $form->renderBody();?>
	</div>
		<p class="pull-right">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'htmlOptions'=>array('name'=>'buttonSubmit'),
				'buttonType'=>'submit',
				'label'=>'Next',
				'type'=>'primary',
				'size'=>'large',
			)); ?>
			<?php if ($model->page==4)
				$this->widget('bootstrap.widgets.TbButton', array(
					'htmlOptions'=>array('name'=>'buttonSkip'),
					'buttonType'=>'submit',
					'label'=>'Skip',
					'type'=>'secondary',
					'size'=>'medium',
				)); ?>
		</p>
	<?php $form->renderEnd(); ?>
</div>