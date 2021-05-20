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
class ClassificadosViewPessoa extends HtmlView
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

		$descricao = null;
		$keywords = null;
		$titulo = null;

		$tela = $input->get('layout', 'default', 'string' );


		$document->setMetadata('APPLICATION-NAME','Classidicados');

		switch($tela ){
			case 'default': //Cadastro & edição dados básicos
				$titulo = $document->getTitle()  . ' : ' . $descricao;
				$descricao = JText::_('COM_CLASSIFICADOS_PESSOA_MEUSDADOS') ;
				
				break;
			case 'form': //Cadastro de imagens do estabelecimento
				$titulo = $document->getTitle()  . ' : ' . $descricao;
				$descricao = JText::_('COM_CLASSIFICADOS_PESSOA_EDITAR_MEUSDADOS') ;
				$pathway->addItem($descricao,'');

				break;

				
		}
	


		
		$document->setTitle($titulo);
		$document->setDescription($descricao);
		$document->setMetadata('Keywords', $keywords);
	}


	function formatarDataHora($data){
		if($data != null && trim($data) != '' && $data != '0000-00-00 00:00:00'){
			return (new JDate($data))->format('d/m/y h:i');
		}
		return '';
	}
	
	function formatarData($data){

		if($data != null && trim($data) != '' && $data != '0000-00-00 00:00:00'){
			return (new JDate($data))->format('d/m/Y');
		}
		return '';
	}

	function umOuOutro($um, $outro){
		return $um == null || trim($um) == '' ?  $outro : $um;
	}

	function selecionado($um, $outro){
		return $um != null && trim($um) != '' && $um==$outro?  ' SELECTED' : '';
	}

	function checked($um){
		return $um != null && trim($um) != '' ?  ' CHECKED' : '';
	}
}
