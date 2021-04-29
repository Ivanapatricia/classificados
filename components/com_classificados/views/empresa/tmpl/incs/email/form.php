<form action="<?= $urlGravar ?>" method="post" enctype="application/x-www-form-urlencoded" class="form-validate form-horizontal">
	<input type="hidden" name="option" value="com_classificados"/>
	<input type="hidden" name="task" value="emailempresa.gravar"/>
	<input type="hidden" name="Itemid" value="<?= $itemid ?>"/>
<?php 
	echo $this->getModel('classificados')->gerarToken($token);
	
?>


	<div class="control-group">
		<label class="control-label" for="email"><?= JText::_('COM_CLASSIFICADOS_EMAILEMPRESA_EMAIL') ?></label>
		<div class="controls">
			<input type="email" id="email" name="email" 
				required="required" maxlength="250" minlength="3" max="250" min="3" 
				placeholder="<?= JText::_('COM_CLASSIFICADOS_EMAILEMPRESA_EMAIL_PLACEHOLDER') ?>"/>
		</div>
	</div>
	<div class="btn-toolbar text-right">
		<div class="btn-group">
			<button type="submit" id="btnBuscar" class="btn"><?php
				echo JText::sprintf('COM_CLASSIFICADOS_BTN_GRAVAR'); ?></button>
		</div>
	</div>
</form>