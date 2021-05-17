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
class ClassificadosViewBusca extends HtmlView
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

	

		$document->setMetadata('APPLICATION-NAME','Classidicados');
		$descricao = JText::_('COM_CLASSIFICADOS_BUSCA_POR') . ($busca != null ? ' - ' . $busca : '' ) 
		. ($tipoEmpresa != null ? ' [ ' . ucfirst($tipoEmpresa)  . ' ]': '' ) 
		. ($tipoProduto != null ? ' [ ' . ucfirst($tipoProduto)   . ' ]': '' ) ;

		$pathway->addItem($descricao,'');
		$document->setTitle($document->getTitle()  . ' : ' .$descricao );
		$document->setDescription($descricao);
		$document->setMetadata('Keywords', ' busca' 
			. ($busca != null ? ', ' . $busca : '' ) 
			. ($tipoEmpresa != null ? ', ' . $tipoEmpresa : '' ) 
			. ($tipoProduto != null ? ', ' . $tipoProduto : '' ) );
	}
}
