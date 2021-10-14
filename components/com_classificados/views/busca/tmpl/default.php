<?php

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) || die ( 'Restricted access' );
use Joomla\CMS\Factory;
$document = Factory::getDocument();
$app = JFactory::getApplication();
$input = $app->input;
$itemid = $input->get( 'Itemid', null, 'string' );
$params = $app->getParams('com_classificados');

$ste 			= $params->get('ste');
$stp 			= $params->get('stp') ;
$tipoEmpresa 	= $input->get('tipoEmpresa', null, 'string');
$tipoProduto 	= $input->get('tipoProduto', null, 'string');



$url_busca =  JRoute::_( 'index.php?option=com_classificados&task=busca.busca' . 
	($ste != null && !empty($ste) ?  '&ste=' . $ste : '') . 
	($stp != null && !empty($stp) ?  '&stp=' . $stp : '') .'&Itemid='.$itemid , false );
if ($input->get( 'task' ) == null || $input->get( 'task' ) == '') {
	//$app->redirect($url_busca, "Carregando a busca" );
	//$app->redirect($url_busca, "Carregando a busca" );
	$controller = JControllerLegacy::getInstance("busca");
	$controller->setRedirect($url_busca,JText::_(''),$type);
	$controller->redirect();
	exit ();
}

$itens = $input->get('itens', null, 'array');
$tiposEmpresa = $input->get('tiposEmpresa', null, 'array');
$tiposProduto = $input->get('tiposProduto', null, 'array');


$empresas = $input->get( 'empresas', null);
$produtos = $input->get( 'produtos', null);
?>

<?php if($ste != null && !empty($ste)) : ?><h2><?= ucfirst($tipoEmpresa) ?></h2><?php endif; ?>
<?php if($stp != null && !empty($stp)) : ?><h3><?= ucfirst($tipoProduto) ?></h3><?php endif; ?>
<?php 
	include_once 'incs/form.php';

	include_once 'incs/resultados.php';
?>
