<?php






defined ( '_JEXEC' ) || die ( 'Restricted access' );


$app = JFactory::getApplication();
$input = $app->input;
$itemid = $input->get( 'Itemid', null, 'string' );
$task =  $input->get( 'task', null, 'string' );

//$token = JSession::getFormToken();




$url =  JRoute::_( 'index.php?option=com_classificados&task=empresaendereco.endereco&Itemid='.$itemid , false );
$urlGravar =  JRoute::_( 'index.php?option=com_classificados&task=empresaendereco.salvar&Itemid='.$itemid , false );
$voltar = JRoute::_( 'index.php?option=com_classificados&task=empresa.meusdados&t=endereco&Itemid='.$itemid , false );
$urlCIdade = JRoute::_('http://localhost/joomla/index.php?option=com_classificados&task=empresaendereco.cidade', false);

if(strpos($urlCIdade, "?")>0){
    if(substr($urlCIdade, strlen($urlCIdade)-1)=="?"){
        $urlCIdade.='uf=';
    }else{
        $urlCIdade.='&uf=';
    }
}
else{
    $urlCIdade.='?uf=';
}

if ($task == null || $task == '') {
	$app->redirect ($url, "" );
	exit();
}

$endereco= JRequest::getVar('endereco', null,'string');
$numero= JRequest::getVar('numero', null,'string');
$complemento= JRequest::getVar('complemento', null,'string');
$bairro= JRequest::getVar('bairro', null,'string'); 
$cep= JRequest::getVar('cep', null,'string');
$cidade= JRequest::getVar('cidade', null,'int');
$logradouro= JRequest::getVar('logradouro', null,'int');
$uf= JRequest::getVar('uf', null,'string');
$id = JRequest::getVar('id', null,'int');

$logradouros= JRequest::getVar('logradouros', null,'array');
$cidades= JRequest::getVar('cidades', null,'array');
$ufs= JRequest::getVar('ufs', null,'array');



JFactory::getDocument()->addScriptDeclaration('
var urlCidade = "'.$urlCIdade.'";
jQuery(document).ready(function(){
    jQuery("#cep").mask("#####-###");
    jQuery("#uf").change(function(){
        if(jQuery("#uf").val()!=""){
            jQuery.get(urlCidade+jQuery("#uf").val()).done(function(resposta){
                if(resposta && resposta.length>0){
                    jQuery("#cidade option").remove();
                    jQuery("#cidade").append(
                        jQuery("<option>"));
                    for(var i=0; i < resposta.length ; i++){
                        jQuery("#cidade").append(
                            jQuery("<option>", {
                                value: resposta[i].id,
                                text: resposta[i].nome
                            })
                        );
                    }
                }
            });
        }
    });
});');
?>
<form action="<?= $urlGravar ?>" method="post" enctype="application/x-www-form-urlencoded" class="form-validate form-horizontal">
	<input type="hidden" name="option" value="com_classificados"/>
	<input type="hidden" name="task" value="empresaendereco.salvar"/>
	<input type="hidden" name="id" value="<?= $id ?>"/>
    <input type="hidden" name="Itemid" value="<?= $itemid ?>"/>
   
    <?php echo JHtml::_('form.token'); ?>


    <fieldset>
        <legend><?= JText::_('COM_CLASSIFICADOS_EMPRESA_DADOS_EMPRESAIS') ?></legend>
        <div class="control-group">
            <label class="control-label" for="cep"><?= JText::_('COM_CLASSIFICADOS_EMPRESA_CEP') ?></label>
            <div class="controls">
                <input type="text" id="cep" name="cep" value="<?= $cep ?>"
                    required="required" maxlength="9" minlength="9" pattern="[0-9]{5}-[0-9]{3}"
                    placeholder="<?= JText::_('COM_CLASSIFICADOS_EMPRESA_CEP_PLACEHOLDER') ?>"/>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="logradouro"><?= JText::_('COM_CLASSIFICADOS_EMPRESA_LOGRADOURO_ENDERECO') ?></label>
            <div class="controls">

                <select name="logradouro" id="logradouro" required="required">
                    <option></option>
<?php 
if($logradouros!=null):
    foreach($logradouros as $item) :
?>
                    <option value="<?= $item->id ?>"<?= $this->selecionado($item->id,$logradouro)?>><?= $item->nome ?></option>
<?php
    endforeach;
endif;
?>  
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="endereco"><?= JText::_('COM_CLASSIFICADOS_EMPRESA_ENDERECO') ?></label>
            <div class="controls">
                <input type="text" id="endereco" name="endereco" value="<?= $endereco ?>"
                    required="required" maxlength="250" minlength="8"  
                    placeholder="<?= JText::_('COM_CLASSIFICADOS_EMPRESA_ENDERECO_PLACEHOLDER') ?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="numero"><?= JText::_('COM_CLASSIFICADOS_EMPRESA_NUMERO_ENDERECO') ?></label>
            <div class="controls">
                <input type="text" id="numero" name="numero" value="<?= $numero ?>"
                    required="required" maxlength="20" minlength="1"  
                    placeholder="<?= JText::_('COM_CLASSIFICADOS_EMPRESA_NUMERO_ENDERECO_PLACEHOLDER') ?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="complemento"><?= JText::_('COM_CLASSIFICADOS_EMPRESA_COMPLEMENTO_ENDERECO') ?></label>
            <div class="controls">
                <input type="text" id="complemento" name="complemento" value="<?= $complemento ?>"
                    maxlength="250"   
                    placeholder="<?= JText::_('COM_CLASSIFICADOS_EMPRESA_COMPLEMENTO_ENDERECO_PLACEHOLDER') ?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="bairro"><?= JText::_('COM_CLASSIFICADOS_EMPRESA_BAIRRO_ENDERECO') ?></label>
            <div class="controls">
                <input type="text" id="bairro" name="bairro" value="<?= $bairro ?>"
                    required="required" maxlength="250" minlength="3"  
                    placeholder="<?= JText::_('COM_CLASSIFICADOS_EMPRESA_BAIRRO_ENDERECO_PLACEHOLDER') ?>"/>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="uf"><?= JText::_('COM_CLASSIFICADOS_EMPRESA_UF_ENDERECO') ?></label>
            <div class="controls">

                <select name="uf" id="uf" required="required">
                    <option></option>
<?php 
if($ufs!=null):
    foreach($ufs as $item) :
?>
                    <option value="<?= $item->uf ?>"<?= $this->selecionado($item->uf,$uf)?>><?= $item->nome ?></option>
<?php
    endforeach;
endif;
?>  
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="cidade"><?= JText::_('COM_CLASSIFICADOS_EMPRESA_CIDADE_ENDERECO') ?></label>
            <div class="controls">

                <select name="cidade" id="cidade" required="required">
                    <option></option>
<?php 
if($cidades!=null):
    foreach($cidades as $item) :
?>
                    <option value="<?= $item->id ?>"<?= $this->selecionado($item->id,$cidade)?>><?= $item->nome ?></option>
<?php
    endforeach;
endif;
?>  
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