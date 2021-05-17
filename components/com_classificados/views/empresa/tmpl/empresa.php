<?php




/*<!--
1- Formulário com os campos


    `nome_fantasia` VARCHAR(250) NOT NULL, 
    `razao_social` VARCHAR(250) NOT NULL,
    `id_tipo_empresa` BIGINT NULL, ->Combobox (SELECT/Option)
    `cnpj` VARCHAR(14) NULL,
    `exibir` TINYINT(1) NULL DEFAULT 1, -> Checkbox
    `descricao` TEXT NULL, -> Textearea


    -->
*/



defined ( '_JEXEC' ) || die ( 'Restricted access' );


$app = JFactory::getApplication();
$input = $app->input;
$itemid = $input->get( 'Itemid', null, 'string' );
$task =  $input->get( 'task', null, 'string' );


$url =  JRoute::_( 'index.php?option=com_classificados&task=empresa.empresa&Itemid='.$itemid , false );
$urlGravar =  JRoute::_( 'index.php?option=com_classificados&task=empresa.gravar&Itemid='.$itemid , false );

if ($task == null || $task == '') {
	$app->redirect ($url, "" );
	exit();
}
?>
<form action="<?= $urlGravar ?>" method="post" enctype="application/x-www-form-urlencoded" class="form-validate form-horizontal">
	<input type="hidden" name="option" value="com_classificados"/>
	<input type="hidden" name="task" value="empresa.gravar"/>
	<input type="hidden" name="Itemid" value="<?= $itemid ?>"/>
    <?= $this->getModel('classificados')->gerarToken($token) ?>


</form>