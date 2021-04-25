<?php
/**
 * @package    classificados
 *
 * @author     jorge <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\SearchHelper;

//SearchHelper

/**
 * Classificados controller.
 *
 * @package  classificados
 * @since    1.0.0
 */
class ClassificadosControllerEmpresa extends BaseController
{
	const TB_PRODUTO = '`#__produto`';
	const TB_EMPRESA = '`#__empresa`';
	const TB_TIPO_EMPRESA = '`#__tipo_empresa`';
	const TB_TIPO_PRODUTO = '`#__tipo_produto`';
	const TB_FOTO_PRODUTO = '`#__foto_produto`';
	const TB_FOTO_EMPRESA = '`#__foto_empresa`';

	const ITENS_POR_PAGINA = 20;
	const STATUS_ATIVO = 'A';

	public function cadastro(){
		$db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$input = $app->input;

		

		$input->set( 'view', 'empresa' );
		$input->set('layout', 'default' );
		parent::display (true);
	}



	/**
	 * Busca empresas pelo filtro.
	 */
	private function _salvarAcesso(){
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);
		
		// Insert columns.
		$columns = array('url', 'ip_criador', 'ip_criador_proxiado', 'status', 'data_criado');
		
		// Insert values.
		$values = array($db->quote($_SERVER['REQUEST_URI']), 
						$db->quote($_SERVER['REMOTE_ADDR']), 
						$db->quote($_SERVER['HTTP_X_FORWARDED_FOR']), 
						ClassificadosControllerBusca::STATUS_ATIVO,
						'NOW()');
		
		// Prepare the insert query.
		$query
			->insert($db->quoteName('#__url_busca'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));
		
		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		$db->execute();


	}




	/**
	 * Verifica se o usuário está logado.
	 *
	 * @param $task Tarefa que deve ser acionada do componente após o login.
	 * @return bool Retorna False se não estiver logado e true caso esteja.
	 * @throws Exception Não prvisto.
	 */
	private function _isLogad($task){
		$user = JFactory::getUser();
		$app = Factory::getApplication();
		$urlRetorno = urlencode(base64_encode( 'index.php?option=com_socialblade&task='.$task.'&Itemid='.
			JRequest::getVar('Itemid') ));
		$login =  JRoute::_ ( 'index.php?option=com_users&view=login&Itemid=' . JRequest::getVar('Itemid') .
			'&return='.$urlRetorno, false );
		if ($user == null || $user->id == null || $user->id == 0) {
			$app->redirect ($login, "" );
			$app->enqueueMessage(JText::sprintf('COM_SOCIALBLADES_NAO_LOGADO'), 'error');
			return false;
		}
		return true;
	}

}
