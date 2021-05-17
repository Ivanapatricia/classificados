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

use Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * Classificados model.
 *
 * @package   classificados
 * @since     1.0.0
 */
class ClassificadosModelEmpresa extends BaseDatabaseModel
{

    const TB_EMPRESA = '#__empresa';
    const STATUS_ATIVO = 'A';

	public function buscaEmpresas($tipo, $nome){
        $query = $db->getQuery ( true );
		$query->select("`id`, `uuid`, `nome_fantasia`, `razao_social`, `cnpj`, `descricao`, `id_tipo_empresa`, `id_tipo_destaque_ativo`")
			->from (ClassificadosModelClassificados::TB_EMPRESA);

        if($tipo!=null && trim($tipo) != ''){
            $query->where('`id_tipo_empresa` = ' . $db->quote($tipo), 'AND');
        }
        if($tipo!=null && trim($nome) != ''){
            $query->where('( upper(`nome_fantasia`) like ' . $db->quote(strtoupper($nome).'%') . '  OR ' .
            'upper(`razao_social`) like ' . $db->quote(strtoupper($nome).'%')  . ')', 'AND');
        }
        $query->where('`exibir`  = 1 ')
            ->where('`status`  = ' . $db->quote(ClassificadosModelClassificados::STATUS_ATIVO), 'AND')
		    ->setLimit(50000);
		$db->setQuery ( $query );

		return $db->loadObjectList();
	}
}
