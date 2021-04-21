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


		$document->setMetadata('APPLICATION-NAME','Social Blade');
		$descricao = 'Cadastro '.$document->getTitle();
		$pathway->addItem($descricao,'');
		$document->setTitle($document->getTitle()  . ' : ' .
			$input->get('tipo_empresa') != '' && $input->get('tipo_empresa') !=  null ?
				JText::_('Busca de clasificado') : JText::_('Busca de clasificado')

		);
		$document->setDescription($descricao);
		$document->setMetadata('Keywords', 'cadastro');
	}
}
