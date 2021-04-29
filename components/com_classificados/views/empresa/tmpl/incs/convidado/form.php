
<form action="<?= $urlGravar ?>" method="post" enctype="application/x-www-form-urlencoded" class="form-validate form-horizontal well">
	<input type="hidden" name="option" value="com_classificados"/>
	<input type="hidden" name="task" value="convite.gravar"/>
	<input type="hidden" name="Itemid" value="<?= $itemid ?>"/>
    <?php echo JHTML::_('form.token'); ?>
	<div class="control-group">
		<label class="control-label" for="convidado"><?= JText::_('COM_CLASSIFICADOS_CONVITE_EMAIL') ?></label>
		<div class="controls">
			<input type="email" id="convidado" name="convidado" required="required" 
				maxlength="250" minlength="3" max="250" min="3" 
				placeholder="<?= JText::_('COM_CLASSIFICADOS_CONVITE_EMAIL_PLACEHOLDER') ?>"/>
		</div>
	</div>
	<div class="btn-toolbar text-right">
		<div class="btn-group">
			<button type="submit" id="btnBuscar" class="btn"><em class="icon-ok"></em><?php
				echo JText::sprintf('COM_CLASSIFICADOS_BTN_GRAVAR'); ?></button>
		</div>
	</div>
</form>