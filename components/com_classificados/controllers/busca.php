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

/**
 * Classificados controller.
 *
 * @package  classificados
 * @since    1.0.0
 */
class ClassificadosControllerClassificados extends BaseController
{
	const TB_PRODUTO = '#__produto';
	const TB_EMPRESA = '#__empresa';

	public function busca(){
		$this->_isLogad('busca') || exit();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();

		$user = JFactory::getUser();
		$input = $app->input;

		$nome = $input->post->get('nome', null, 'string');
		$tipo = $input->post->get('tipo', $input->post->get('pagina', null, 'string'), 'string');


		$this->_isLogad('parceiros') || exit();

		$id = JRequest::getVar('id', null, 'int', 'GET');
		$query = $db->getQuery ( true );
		$query->select("`id`, `nome`, `tipo_rede`,`rede`, `descricao` ")
			->from (SocialbladeController::TB_PARCEIRO)
			->where( $db->quoteName('status') . ' = ' . $db->quote(SocialbladeController::STATUS_ATIVO))
			->where( $db->quoteName('id_user_criador') . ' = ' . $db->quote($user->id))
			->setLimit(50000);
		$db->setQuery ( $query );

		JRequest::setVar( 'itens',$db->loadObjectList());


	}


	private function _getEmpresas($ipo, $nome, $user){
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select("`id`, `nome`, `tipo_rede`,`rede`, `descricao` ")
			->from (ClassificadosControllerClassificados::TB_PARCEIRO)
			->where( $db->quoteName('status') . ' = ' . $db->quote(SocialbladeController::STATUS_ATIVO))
			->where( $db->quoteName('id_user_criador') . ' = ' . $db->quote($user->id))
			->setLimit(50000);
		$db->setQuery ( $query );

		JRequest::setVar( 'itens',$db->loadObjectList());
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
