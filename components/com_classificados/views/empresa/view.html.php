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

use Joomla\CMS\MVC\View\HtmlView;

/**
 * Classificados view.
 *
 * @package   classificados
 * @since     1.0.0
 */
class ClassificadosViewEmpresa extends HtmlView
{

	public function display($tpl = null)
	{
		$this->metadados();
		return parent::display($tpl);
	}

	/**
	 * Carrega os metadados.
	 *
	 * @throws Exception
	 */
	protected function metadados()
	{



		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$pathway = $app->getPathway();
		$input = $app->input;
		$busca = $input->get('nome', null, 'string');
		$tipoEmpresa = $input->get('tipoEmpresa', null, 'string');
		$tipoProduto = $input->get('tipoProduto', null, 'string');
		$empresaUUID = $input->get('empresa', null, 'string');
		$descricao = null;
		$keywords = null;
		$titulo = null;

		$tela = $input->get('layout', 'default', 'string' );


		//Carregando a tela de empresa
		switch($tela ){
			case 'default': //Cadastro & edição dados básicos
				$titulo = $document->getTitle()  . ' : ' . $descricao;
				$descricao = JText::_('COM_CLASSIFICADOS_BUSCA_POR') . ($busca != null ? ' - ' . $busca : '' ) 
					. ($tipoEmpresa != null ? ' [ ' . ucfirst($tipoEmpresa)  . ' ]': '' ) 
					. ($tipoProduto != null ? ' [ ' . ucfirst($tipoProduto)   . ' ]': '' ) ;
				
				break;
			case 'imagens': //Cadastro de imagens do estabelecimento
				break;
			case 'telefones': //Cadastro de telefones de contato
				break;
			case 'emails': //Cadastro de e-mail de contatos
				break;
			case 'convites': //Convidar outros usuários

				
				break;
			case 'empresa': //Consulta



				
		}
	

		$document->setMetadata('APPLICATION-NAME','Classidicados');

		$pathway->addItem($descricao,'');
		$document->setTitle($titulo);
		$document->setDescription($descricao);
		$document->setMetadata('Keywords', $keywords);
	}
}
