<?php

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