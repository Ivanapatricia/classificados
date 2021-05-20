<?php






defined ( '_JEXEC' ) || die ( 'Restricted access' );


$app = JFactory::getApplication();
$input = $app->input;
$itemid = $input->get( 'Itemid', null, 'string' );
$task =  $input->get( 'task', null, 'string' );

//$token = JSession::getFormToken();




$url =  JRoute::_( 'index.php?option=com_classificados&task=pessoaemail.email&Itemid='.$itemid , false );
$urlGravar =  JRoute::_( 'index.php?option=com_classificados&task=pessoaemail.salvar&Itemid='.$itemid , false );
$voltar = JRoute::_( 'index.php?option=com_classificados&task=pessoa.meusdados&t=email&Itemid='.$itemid , false );
if ($task == null || $task == '') {
	$app->redirect ($url, "" );
	exit();
}

$email = $input->get('email',null,'string');
$exibir =$input->get('exibir',false,'string');
$contato = $input->get('contato',false,'string');
$validado = $input->get('validado',false,'string');
$id = $input->get('id', null,'int');


$principalAtual = JFactory::getUser()->email == $email;

$doc = JFactory::getDocument();
if(!$principalAtual && $validado!=null && $validado!=''){
    $doc->addScriptDeclaration('
    jQuery(document).ready(function(){
        jQuery("#email").change(function(){
            var mudou = !(jQuery("#email").val() == "'.$email .'");
            if(mudou){ 
                jQuery("#principal").prop("checked", false);
            }
            jQuery("#principal").prop("disabled", mudou );
        });
    });');
}
?>
<form action="<?= $urlGravar ?>" method="post" enctype="application/x-www-form-urlencoded" class="form-validate form-horizontal">
	<input type="hidden" name="option" value="com_classificados"/>
	<input type="hidden" name="task" value="pessoaemail.salvar"/>
	<input type="hidden" name="id" value="<?= $id ?>"/>
    <input type="hidden" name="Itemid" value="<?= $itemid ?>"/>
   
    <?php echo JHtml::_('form.token'); ?>


    <fieldset>
        <legend><?= JText::_('COM_CLASSIFICADOS_PESSOA_DADOS_PESSOAIS') ?></legend>

        <div class="control-group">
            <label class="control-label" for="email"><?= JText::_('COM_CLASSIFICADOS_PESSOA_EMAIL') ?></label>
            <div class="controls">
                <input type="email" id="email" name="email" value="<?= $email ?>"
                    required="required" maxlength="200" minlength="3"
                    placeholder="<?= JText::_('COM_CLASSIFICADOS_PESSOA_EMAIL_PLACEHOLDER') ?>"/>
            </div>
        </div>



        <div class="control-group">
            <label class="control-label" for="principal"><?= JText::_('COM_CLASSIFICADOS_PESSOA_EMAIL_TORNAR_PRINCIPAL') ?></label>
            <div class="controls">
                <input type="checkbox" name="principal" id="principal" value="1" class="btn-check" <?php
                if($principalAtual){
                    echo ' CHECKED disabled';
                }
                else if($validado==null || $validado==''){
                    echo ' disabled';
                }
                
                ?>/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="exibir"><?= JText::_('COM_CLASSIFICADOS_PESSOA_EXIBIR_EMAIL') ?></label>
            <div class="controls">
                <input type="checkbox" name="exibir" id="exibir" value="1" <?= $this->checked($input->get('exibir'), 'S' )?>/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="controle"><?= JText::_('COM_CLASSIFICADOS_PESSOA_CONTROLE_EMAIL') ?></label>
            <div class="controls">
                <input type="checkbox" name="controle" id="controle" value="1" class="btn-check" <?= $this->checked($input->get('controle'), 'S' )?>/>
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