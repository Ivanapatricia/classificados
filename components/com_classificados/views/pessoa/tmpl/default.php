<?php

defined ( '_JEXEC' ) || die ( 'Restricted access' );


$app = JFactory::getApplication();
$input = $app->input;
$itemid = $input->get( 'Itemid', null, 'string' );
$task =  $input->get( 'task', null, 'string' );
$item = $input->get('item', null,'object');
$token = JSession::getFormToken();


$url =  JRoute::_( 'index.php?option=com_classificados&task=pessoa.meusdados&Itemid='.$itemid , false );
$cadastro =  JRoute::_( 'index.php?option=com_classificados&task=pessoa.editar&Itemid='.$itemid , false );

if ($task == null || $task == '') {
	$app->redirect ($url, "" );
	exit();
}

$acao = $input->get( 't', null, 'string' );

$url_trocasenha =  JRoute::_( 'index.php?option=com_users&view=reset&Itemid='.$itemid . '&return=' .urlencode(base64_encode( 'index.php?option=com_classificados&task=pessoa.meusdados&p=1&Itemid=' . $itemid)) , false );

$liAtivo = ' class="active" ';
$divAtivo = ' active';
?>

<div class="tabbable tabs-left">
    <ul class="nav nav-tabs">
        <li<?= $acao == null || $acao == '' ? $liAtivo : ''?>><a href="#usuario" data-toggle="tab" title="<?= JText::_('COM_CLASSIFICADOS_PESSOA_MENU_MEUSDADOS') ?>"> <em class="icon-home" alt="<?= JText::_('COM_CLASSIFICADOS_PESSOA_MENU_MEUSDADOS') ?>" title="<?= JText::_('COM_CLASSIFICADOS_PESSOA_MENU_MEUSDADOS') ?>"></em> </a></li>
        <li<?= $acao == 'email' ? $liAtivo : ''?>><a href="#email" data-toggle="tab" title="<?= JText::_('COM_CLASSIFICADOS_PESSOA_MENU_EMAIL') ?>"> <em class="icon-envelope" alt="<?= JText::_('COM_CLASSIFICADOS_PESSOA_MENU_EMAIL') ?>" title="<?= JText::_('COM_CLASSIFICADOS_PESSOA_MENU_EMAIL') ?>"></em> </a></li>
        <li<?= $acao == 'telefone' ? $liAtivo : ''?>><a href="#telefone" data-toggle="tab" title="<?= JText::_('COM_CLASSIFICADOS_PESSOA_MENU_TELEFONE') ?>"> <em class="icon-phone" alt="<?= JText::_('COM_CLASSIFICADOS_PESSOA_MENU_TELEFONE') ?>" title="<?= JText::_('COM_CLASSIFICADOS_PESSOA_MENU_TELEFONE') ?>"></em> </a></li>
        <li<?= $acao == 'endereco' ? $liAtivo : ''?>><a href="#endereco" data-toggle="tab" title="<?= JText::_('COM_CLASSIFICADOS_PESSOA_MENU_ENDERECO') ?>"> <em class="icon-address" alt="<?= JText::_('COM_CLASSIFICADOS_PESSOA_MENU_ENDERECO') ?>" title="<?= JText::_('COM_CLASSIFICADOS_PESSOA_MENU_ENDERECO') ?>"></em> </a></li>
        <li<?= $acao == 'configuracao' ? $liAtivo : ''?>><a href="#configuracao" data-toggle="tab" title="<?= JText::_('COM_CLASSIFICADOS_PESSOA_MENU_CONFIGURACAO') ?>"> <em class="icon-wrench" alt="<?= JText::_('COM_CLASSIFICADOS_PESSOA_MENU_CONFIGURACAO') ?>" title="<?= JText::_('COM_CLASSIFICADOS_PESSOA_MENU_CONFIGURACAO') ?>"></em> </a></li>
 
        <li><a href="<?= $url_trocasenha ?>" title="<?= JText::_('COM_CLASSIFICADOS_PESSOA_TROCAR_SENHA') ?>"> <em class="icon-locked" alt="<?= JText::_('COM_CLASSIFICADOS_PESSOA_TROCAR_SENHA') ?>" title="<?= JText::_('COM_CLASSIFICADOS_PESSOA_TROCAR_SENHA') ?>"></em> </a></li>
 
    </ul>
    <div class="tab-content">
        <div class="tab-pane<?= $acao == null || $acao == '' ? $divAtivo : ''?>" id="usuario">
<?php
        require_once 'incs/sobremim/meusdados.php'
?>
        </div>
        <div class="tab-pane<?= $acao == 'email' ? $divAtivo : ''?>" id="email">
<?php
        require_once 'incs/sobremim/emails.php'
?>
        </div>
        <div class="tab-pane<?= $acao == 'telefone' ? $divAtivo : ''?>" id="telefone">
        <?php
        require_once 'incs/sobremim/telefones.php'
?>
        </div>
        <div class="tab-pane<?= $acao == 'endereco' ? $divAtivo : ''?>" id="endereco">
        <?php
        require_once 'incs/sobremim/enderecos.php'
?>
        </div>
        <div class="tab-pane<?= $acao == 'configuracao' ? $divAtivo : ''?>" id="configuracao">
        <?php
       require_once 'incs/sobremim/configuracao.php'
?>
        </div>



    </div>
</div>
