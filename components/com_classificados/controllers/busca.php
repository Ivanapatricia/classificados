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
class ClassificadosControllerBusca extends BaseController
{
	const TB_PRODUTO = '`#__produto`';
	const TB_EMPRESA = '`#__empresa`';
	const TB_TIPO_EMPRESA = '`#__tipo_empresa`';
	const TB_TIPO_PRODUTO = '`#__tipo_produto`';
	const TB_FOTO_PRODUTO = '`#__foto_produto`';
	const TB_FOTO_EMPRESA = '`#__foto_empresa`';

	const ITENS_POR_PAGINA = 20;
	const STATUS_ATIVO = 'A';

	public function busca(){
		$db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$input = $app->input;

		$nome = $input->get->get('q', null, 'string');
		$estaticoTipoEmpresa = $input->get->get('ste', null, 'int');
		$tipoEmpresa = $input->get->get('te', $estaticoTipoEmpresa, 'int');
		$estaticoTipoProduto = $input->get->get('stp', null, 'int');
		$tipoProduto = $input->get->get('tp', $estaticoTipoProduto, 'int');
		$pagina = $input->get->get('pag', 0, 'int');


		
		$itensEmpresa = $this->_getBuscaEmpresas($nome, $tipoEmpresa, $tipoProduto, $pagina);
		$itensProduto = $this->_getBuscaProdutos($nome, $tipoEmpresa, $tipoProduto, $pagina);
		$input->set( 'empresas', $itensEmpresa);
		$input->set( 'produtos', $itensProduto);



		if($tipoProduto != null && $tipoProduto != 0 && $tipoProduto != ''){
			$query = $db->getQuery ( true );
			$query->select("`nome`")
				->from(ClassificadosControllerBusca::TB_TIPO_PRODUTO)
				->where('`status`  = ' . $db->quote(ClassificadosControllerBusca::STATUS_ATIVO), 'AND')
				->where('`id`  = ' . $db->quote($tipoProduto));
			$db->setQuery ($query);
			$item = $db->loadObject();
			if($item != null){
				$input->set( 'tipoProduto', $item->nome );
			}
		}

		if($tipoEmpresa != null && $tipoEmpresa != 0 && $tipoEmpresa != ''){
			$query = $db->getQuery ( true );
			$query->select("`nome`")
				->from(ClassificadosControllerBusca::TB_TIPO_EMPRESA)
				->where('`status`  = ' . $db->quote(ClassificadosControllerBusca::STATUS_ATIVO), 'AND')
				->where('`id`  = ' . $db->quote($tipoEmpresa));
			$db->setQuery ($query);
			$item = $db->loadObject();
			if($item != null){
				$input->set( 'tipoEmpresa', $item->nome );
			}
		}

		$query = $db->getQuery ( true );
		$query->select("`nome`,`id`")
			->from(ClassificadosControllerBusca::TB_TIPO_EMPRESA)
			->where('`status`  = ' . $db->quote(ClassificadosControllerBusca::STATUS_ATIVO), 'AND')
			->order('`nome`');
		$db->setQuery ($query);
		$itens = $db->loadObjectList();
		$input->set( 'tiposEmpresa', $itens);

		$query = $db->getQuery ( true );
		$query->select("`nome`,`id`")
			->from(ClassificadosControllerBusca::TB_TIPO_PRODUTO)
			->where('`status`  = ' . $db->quote(ClassificadosControllerBusca::STATUS_ATIVO), 'AND')
			->order('`nome`');
		$db->setQuery ($query);
		$itens = $db->loadObjectList();
		$input->set( 'tiposProduto', $itens);


		if($nome != null && trim($nome) != '' && (
			($itensEmpresa  != null && sizeof($itensEmpresa->itens) > 0 )
			($itensProduto != null && sizeof($itensProduto->itens) > 0 ))){
			$this->_salvarAcesso();
			SearchHelper::logSearch($nome, 'com_classificados');
		}


		$input->set( 'view', 'busca' );
		$input->set('layout', 'default' );
		parent::display (true);
	}



	/**
	 * Busca empresas pelo filtro.
	 */
	private function _salvarAcesso(){
		$db = JFactory::getDbo();


		$query = $db->getQuery ( true );
		$query->select("`nome`,`id`")
			->from('#__url_busca')
			->where('`status`  = ' . $db->quote(ClassificadosControllerBusca::STATUS_ATIVO), 'AND')
			->order('`nome`');
		$db->setQuery ($query);
		$itens = $db->loadObjectList();


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
	 * Busca empresas pelo filtro.
	 */
	private function _getBuscaProdutos($nome, $tipoEmpresa, $tipoProduto, $pagina = 0){
		$retorno = new stdClass();
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select("`emp`.`id`, `pro`.`uuid`, `emp`.`nome_fantasia` AS nomeEmpresa, `emp`.`razao_social` as `razaoSocial`, 
		`emp`.`id_tipo_empresa`, `tip`.`nome` AS `tipoEmpresa`, `tipp`.`nome` AS `tipoProduto`, `pro`.`nome` AS produto")
			->from(ClassificadosControllerBusca::TB_PRODUTO . ' as `pro`')
			->join('INNER', ClassificadosControllerBusca::TB_EMPRESA . ' as `emp` ON `pro`.`id_empresa` = `emp`.`id`')
			->join('INNER', ClassificadosControllerBusca::TB_TIPO_EMPRESA . ' as `tip` ON `emp`.`id_tipo_empresa` = `tip`.`id` ')
			->join('INNER', ClassificadosControllerBusca::TB_TIPO_PRODUTO . ' as `tipp` ON `pro`.`id_tipo_produto` = `tipp`.`id` ')
			->join('LEFT', ClassificadosControllerBusca::TB_FOTO_PRODUTO . ' as `fot` ON `pro`.`id` = `fot`.`id_produto` ');

        if($tipoEmpresa!=null && trim($tipoEmpresa) != ''){
            $query->where('`emp`.`id_tipo_empresa` = ' . $db->quote($tipoEmpresa), 'AND');
        }
		if($tipoProduto!=null && trim($tipoProduto) != ''){
            $query->where('`pro`.`id_tipo_produto` = ' . $db->quote($tipoProduto), 'AND');
        }
        if($nome!=null && trim($nome) != ''){
			$nomeVal = $db->quote(strtoupper($db->escape($nome,true)).'%');
            $query->where("( upper(`emp`.`nome_fantasia`) like $nomeVal OR 
            upper(`emp`.`razao_social`) like  $nomeVal  OR 
			upper(`emp`.`descricao`) like  $nomeVal )", 'AND');
        }
        $query->where('`emp`.`exibir`  = 1 ')
			->where('`fot`.`status`  = ' . $db->quote(ClassificadosControllerBusca::STATUS_ATIVO), 'AND')
            ->where('`pro`.`status`  = ' . $db->quote(ClassificadosControllerBusca::STATUS_ATIVO), 'AND')
			->where('`emp`.`status`  = ' . $db->quote(ClassificadosControllerBusca::STATUS_ATIVO), 'AND')
			->where('`fot`.`ordem`  = 1 ')
			->order('`emp`.`id_tipo_empresa` DESC, `emp`.`nome_fantasia`, `emp`.`razao_social` ')
			->setLimit($pagina * ClassificadosControllerBusca::ITENS_POR_PAGINA, 
				ClassificadosControllerBusca::ITENS_POR_PAGINA);

		$db->setQuery ( $query);
		
		$retorno->itens = $db->loadObjectList();
		$retorno->total = $db->getNumRows();//$db->setQuery ($query->sele(' count(1) as total' )->order(null) )->loadObject()->total;
		$retorno->totalPagina =  $retorno->total / ClassificadosControllerBusca::ITENS_POR_PAGINA; 


		return $retorno;
	}


		/**
	 * Busca empresas pelo filtro.
	 */
	private function _getBuscaEmpresas($nome, $tipoEmpresa, $tipoProduto,  $pagina = 0){
		$retorno = new stdClass();
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select("`emp`.`id`, `emp`.`uuid`, `emp`.`nome_fantasia`, `emp`.`razao_social` as `razaoSocial`, 
			`emp`.`id_tipo_empresa`, `tip`.`nome` AS `tipoEmpresa`, `emp`.`id_tipo_destaque_ativo`")
			->from (ClassificadosControllerBusca::TB_EMPRESA . ' as `emp`')
			->join('INNER', ClassificadosControllerBusca::TB_TIPO_EMPRESA . ' as `tip` ON `emp`.`id_tipo_empresa` = `tip`.`id` ')
			->join('LEFT', ClassificadosControllerBusca::TB_FOTO_EMPRESA . ' as `fot` ON `emp`.`id` = `fot`.`id_empresa` ');

        if($tipoProduto!=null && trim($tipoProduto) != ''){
            $query->where('`emp`.`id` IN (SELECT id_empresa AS id FROM '  . ClassificadosControllerBusca::TB_PRODUTO 
			. '  WHERE id_tipo_produto = ' . $db->quote($tipoProduto) .')', 'AND');
        }

        if($tipoEmpresa!=null && trim($tipoEmpresa) != ''){
            $query->where('`emp`.`id_tipo_empresa` = ' . $db->quote($tipoEmpresa), 'AND');
        }
        if($nome!=null && trim($nome) != ''){
			$nomeVal = $db->quote(strtoupper($db->escape($nome,true)).'%');
            $query->where("( upper(`emp`.`nome_fantasia`) like $nomeVal OR 
            upper(`emp`.`razao_social`) like  $nomeVal  OR 
			upper(`emp`.`descricao`) like  $nomeVal )", 'AND');
        }
        $query->where('`emp`.`exibir`  = 1 ')
            ->where('`emp`.`status`  = ' . $db->quote(ClassificadosControllerBusca::STATUS_ATIVO))
			->order('`emp`.`id_tipo_empresa` DESC, `emp`.`nome_fantasia`, `emp`.`razao_social` ')
			->setLimit($pagina * ClassificadosControllerBusca::ITENS_POR_PAGINA, 
				ClassificadosControllerBusca::ITENS_POR_PAGINA);

		$db->setQuery ( $query);


		$retorno->itens = $db->loadObjectList();
		$retorno->total = $db->getNumRows();//$db->setQuery ($query->select(' count(1) as total' )->order(null) )->loadObject()->total;
		$retorno->totalPagina =  $retorno->total / ClassificadosControllerBusca::ITENS_POR_PAGINA; 


		return $retorno;
	}




}
