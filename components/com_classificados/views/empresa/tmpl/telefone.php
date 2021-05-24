<?php






defined ( '_JEXEC' ) || die ( 'Restricted access' );


$app = JFactory::getApplication();
$input = $app->input;
$itemid = $input->get( 'Itemid', null, 'string' );
$task =  $input->get( 'task', null, 'string' );

//$token = JSession::getFormToken();




$url =  JRoute::_( 'index.php?option=com_classificados&task=empresatelefone.telefone&Itemid='.$itemid , false );
$urlGravar =  JRoute::_( 'index.php?option=com_classificados&task=empresatelefone.salvar&Itemid='.$itemid , false );
$voltar = JRoute::_( 'index.php?option=com_classificados&task=empresa.meusdados&t=telefone&Itemid='.$itemid , false );
if ($task == null || $task == '') {
	$app->redirect ($url, "" );
	exit();
}
$ddd = $input->get('ddd',null,'ddd');
$telefone = $input->get('telefone',null,'string');
$exibir =$input->get('exibir',false,'boolean');
$tipo = $input->get('tipo',false,'string');
$id = $input->get('id', null,'int');


$doc = JFactory::getDocument();

$doc->addScriptDeclaration('
jQuery(document).ready(function(){
    jQuery("#telefone").mask("999999999");
    jQuery("#ddd").mask("999")
});');
?>
<form action="<?= $urlGravar ?>" method="post" enctype="application/x-www-form-urlencoded" class="form-validate form-horizontal">
	<input type="hidden" name="option" value="com_classificados"/>
	<input type="hidden" name="task" value="empresatelefone.salvar"/>
	<input type="hidden" name="id" value="<?= $id ?>"/>
    <input type="hidden" name="Itemid" value="<?= $itemid ?>"/>
   
    <?php echo JHtml::_('form.token'); ?>


    <fieldset>
        <legend><?= JText::_('COM_CLASSIFICADOS_EMPRESA_DADOS_EMPRESAIS') ?></legend>
        <div class="control-group">
            <label class="control-label" for="ddd"><?= JText::_('COM_CLASSIFICADOS_EMPRESA_DDD') ?></label>
            <div class="controls">
                <input type="text" id="ddd" name="ddd" value="<?= $ddd ?>"
                    required="required" maxlength="3" minlength="2" pattern="[1-9]{1}[0-9]{1}[0-9]{0,1}"
                    placeholder="<?= JText::_('COM_CLASSIFICADOS_EMPRESA_DDD_PLACEHOLDER') ?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="telefone"><?= JText::_('COM_CLASSIFICADOS_EMPRESA_TELEFONE') ?></label>
            <div class="controls">
                <input type="text" id="telefone" name="telefone" value="<?= $telefone ?>"
                    required="required" maxlength="10" minlength="8" pattern="(9[0-9]{4}[\-\s]{0,1}[0-9]{4})|([2-8]{1}[0-9]{3}[\-\s]{0,1}[0-9]{4})"
                    placeholder="<?= JText::_('COM_CLASSIFICADOS_EMPRESA_TELEFONE_PLACEHOLDER') ?>"/>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="exibir"><?= JText::_('COM_CLASSIFICADOS_EMPRESA_EXIBIR_TELEFONE') ?></label>
            <div class="controls">
                <input type="checkbox" name="exibir" id="exibir" value="1" <?= $this->checked($input->get('exibir'), 'S' )?>/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="controle"><?= JText::_('COM_CLASSIFICADOS_EMPRESA_TIPO_TELEFONE') ?></label>
            <div class="controls">

                <select name="tipo" id="tipo" required="required">
                    <option></option>
                    <option value="CASA"<?= $this->selecionado($tipo,'CASA' )?>><?= JText::_('CASA') ?></option>
                    <option value="CELU"<?= $this->selecionado($tipo,'CELU' )?>><?= JText::_('CELULAR') ?></option>
                    <option value="RECA"<?= $this->selecionado($tipo,'RECA' )?>><?= JText::_('RECADO') ?></option>
                    <option value="COME"<?= $this->selecionado($tipo,'COME' )?>><?= JText::_('COMERCIAL') ?></option>
                    <option value="OUTR"<?= $this->selecionado($tipo,'OUTR' )?>><?= JText::_('OUTRO') ?></option>

                </select>
            </div>
        </div>



        <div class="btn-toolbar text-right">
            <div class="btn-group">
                <a type="button" id="btnCancelar" class="btn btn-danger" href="<?= $voltar ?>"><?=
                JText::_('COM_CLASSIFICADOS_BTN_CANCELAR') . JText::_('COM_CLASSIFICADOS_BTN_CANCELAR_TEXT') ?></a>

                <button type="submit" id="btnBuscar" class="btn btn-success"><?=
                JText::_('COM_CLASSIFICADOS_BTN_GRAVAR') . JText::_('COM_CLASSIFICADOS_BTN_GRAVAR_TEXT') ?></button>
            </div>

        </div>




        
    </fieldset>



</form>