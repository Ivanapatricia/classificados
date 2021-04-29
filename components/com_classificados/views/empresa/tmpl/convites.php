<?php

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) || die ( 'Restricted access' );



$app = JFactory::getApplication();
$input = $app->input;
$itemid = $input->get( 'Itemid', null, 'string' );




$url =  JRoute::_( 'index.php?option=com_classificados&task=convite.carregar&Itemid='.$itemid , false );
$urlGravar =  JRoute::_( 'index.php?option=com_classificados&task=convite.gravar&Itemid='.$itemid , false );
if (JRequest::getVar( 'task' ) == null || JRequest::getVar ( 'task' ) == '') {
	$app->redirect ($url, "" );
	exit();
}

$itens = $input->get('itens', null, 'array');


$convidado = $input->get( 'convidado', null, 'string');

?>

<?php 
	include_once 'incs/convidado/form.php';
	include_once 'incs/convidado/itens.php';
?>
