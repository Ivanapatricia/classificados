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
	const TB_PESSOA = '`#__pessoa`';
	const TB_TIPO_EMPRESA = '`#__tipo_empresa`';
	const TB_TIPO_PRODUTO = '`#__tipo_produto`';
	const TB_FOTO_PRODUTO = '`#__foto_produto`';
	const TB_FOTO_EMPRESA = '`#__foto_empresa`';

	const ITENS_POR_PAGINA = 20;
	const STATUS_ATIVO = 'A';

	public function empresa(){
		//Se não estiver logado.
		$this->getModel('classificados')->isLogado('empresa.empresa') || exit();
		$db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$input = $app->input;
		$itemid = $input->get('Itemid', null, 'string');




		
		$query = $db->getQuery ( true );
		$query->select("`id_empresa` AS `id`")
			->from (ClassificadosControllerEmpresa::TB_PESSOA)
			->where( $db->quoteName('status') . ' = ' . $db->quote(ClassificadosControllerEmpresa::STATUS_ATIVO), 'AND')
			->where( $db->quoteName('id') . ' = ' . $db->quote($user->id))
			->setLimit(1);

		$db->setQuery ( $query );
		$empresa = $db->loadObject();
		if($empresa == null || $empresa == '' || $empresa->id == null || $empresa->id == ''){
			//Caso não tenha pessoa cadastrada.
			$app->redirect(JRoute::_( 'index.php?option=com_classificados&task=pessoa.meusdados&p=1&Itemid='.$itemid , false ), "" );
			exit();
			return;
		}

		$empresaId = $empresa->id;
		$query = $db->getQuery ( true );
		$query->select("`uuid`,`exibir`,`nome_fantasia`,`razao_social`,`cnpj`,`descricao`,`id_tipo_empresa`,
		`id_tipo_destaque_ativo`,`id_user_criador`,`ip_criador`,`ip_criador_proxiado`,`ip_alterador`,
		`ip_alterador_proxiado`,`id_user_alterador`,`data_criado`,`data_alterado`")
			->from (ClassificadosControllerEmpresa::TB_EMPRESA)
			->where( $db->quoteName('status') . ' = ' . $db->quote(ClassificadosControllerEmpresa::STATUS_ATIVO), 'AND')
			->where( $db->quoteName('id') . ' = ' . $db->quote($empresaId))
			->setLimit(1);

		$db->setQuery ( $query );
		$empresa = $db->loadObject();
		$input->set( 'item', $empresa );
		$isCadastrada = !($empresa == null || $empresa == '' || $empresa->id == null || $empresa->id == '' );
		
		$input->set( 'isCadastrada', $isCadastrada);
		



		

		$input->set( 'view', 'empresa'  );
		//Se estiver cadastrada abre a tela normal, se não redireciona para cadastro.
		$input->set('layout', $isCadastrada ? 'default' :  'empresa');
		parent::display (true);
	}









}
