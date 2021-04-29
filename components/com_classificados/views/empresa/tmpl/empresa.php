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


$url =  JRoute::_( 'index.php?option=com_classificados&task=emailempresa.carregar&Itemid='.$itemid , false );
$urlGravar =  JRoute::_( 'index.php?option=com_classificados&task=emailempresa.gravar&Itemid='.$itemid , false );

if (JRequest::getVar( 'task' ) == null || JRequest::getVar ( 'task' ) == '') {
	$app->redirect ($url, "" );
	exit();
}
