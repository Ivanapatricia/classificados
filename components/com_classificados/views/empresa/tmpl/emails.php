<?php

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


$email = $input->get( 'email', null, 'string');

$token = JSession::getFormToken();



	include_once 'incs/email/form.php';
	include_once 'incs/email/itens.php';
